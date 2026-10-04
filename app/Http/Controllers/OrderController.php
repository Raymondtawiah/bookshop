<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Book;
use App\Models\Nationality;
use App\Models\Order;
use App\Services\NotificationService;
use App\Services\PaymentRouter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use ZipArchive;

class OrderController extends Controller
{
    protected PaymentRouter $paymentRouter;

    public function __construct(PaymentRouter $paymentRouter)
    {
        $this->paymentRouter = $paymentRouter;
    }

    /**
     * Show direct checkout form for a single book.
     */
    public function directCheckout($book_id)
    {
        $book = Book::findOrFail($book_id);
        $nationalities = Nationality::select('name')->distinct()->orderBy('name')->get();

        return view('direct-checkout', [
            'book' => $book,
            'total' => $book->price,
            'nationalities' => $nationalities,
            'direct' => true,
        ]);
    }

    /**
     * Process direct checkout for a single book.
     */
    public function processDirectCheckout(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'confirm_email' => 'required|accepted',
            'nationality' => 'required|string|max:100',
            'contact' => 'required|string|max:20',
            'payment_method' => 'required|in:bank,card,paystack',
            'booking_date' => 'nullable|date|after_or_equal:tomorrow',
            'booking_time' => 'nullable|string|max:20',
            'booking_note' => 'nullable|string|max:1000',
        ]);

        $bookingDate = $request->booking_date;
        if ($bookingDate) {
            $date = \Carbon\Carbon::parse($bookingDate);
            if ($date->dayOfWeek !== \Carbon\Carbon::SATURDAY) {
                return back()->withInput()->with('error', 'Please select a Saturday for your booking.');
            }
        }

        $bookingTime = null;
        if ($request->filled('booking_time')) {
            $bookingTime = \Carbon\Carbon::createFromFormat('g:i A', $request->booking_time)->format('H:i:s');
        }

        $book = Book::findOrFail($request->book_id);

        $totalUsd = $book->price;
        $reference = 'ORD-'.time().rand(1000, 9999);
        $paymentMethod = $request->payment_method;

        $orderItems = [[
            'book_id' => $book->id,
            'product_name' => $book->title,
            'unit_price_usd' => $book->price,
            'quantity' => 1,
            'total_price_usd' => $book->price,
        ]];

        $order = Order::create([
            'user_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'email' => $request->email,
            'nationality' => $request->nationality,
            'contact' => $request->contact,
            'total_amount' => $totalUsd,
            'currency' => 'USD',
            'status' => 'pending',
            'order_number' => $reference,
            'order_items' => $orderItems,
            'booking_date' => $bookingDate,
            'booking_time' => $bookingTime,
            'booking_note' => $request->booking_note,
        ]);

        NotificationService::newOrder($order);

        if ($paymentMethod === 'bank') {
            $order->update([
                'payment_method' => 'bank',
                'payment_provider' => 'bank',
                'payment_status' => 'pending',
                'status' => 'pending_payment',
            ]);

            return view('direct-checkout', [
                'order' => $order,
                'total' => $totalUsd,
                'bankTransfer' => true,
                'bankDetails' => config('paystack.bankDetails'),
                'nationalities' => Nationality::select('name')->distinct()->orderBy('name')->get(),
            ]);
        }

        $provider = $paymentMethod === 'paystack' ? 'paystack' : 'stripe';

        $paymentResult = $this->paymentRouter->createCheckout(
            $request->email,
            $totalUsd,
            $provider,
            $reference
        );

        if (! $paymentResult['success']) {
            $order->delete();

            return back()->with('error', $paymentResult['message'] ?? 'Payment initialization failed');
        }

        $order->update([
            'payment_method' => $paymentMethod,
            'payment_provider' => $paymentResult['provider'],
            'currency' => $paymentResult['currency'],
            'total_amount' => $paymentResult['currency'] === 'GHS'
                ? ($paymentResult['amount_ghs'] ?? $totalUsd)
                : ($paymentResult['amount_usd'] ?? $totalUsd),
            'total_amount_usd' => $paymentResult['amount_usd'] ?? $totalUsd,
        ]);

        return redirect($paymentResult['url']);
    }

    /**
     * Detect mobile network from phone number
     */
    private function detectNetwork($phoneNumber)
    {
        // Remove any spaces or dashes
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Get the first 3 digits after country code (233) or 0
        if (strlen($phone) === 12 && substr($phone, 0, 3) === '233') {
            $prefix = substr($phone, 3, 3);
        } elseif (strlen($phone) === 10 && substr($phone, 0, 1) === '0') {
            $prefix = substr($phone, 1, 3);
        } else {
            $prefix = substr($phone, 0, 3);
        }

        // MTN Ghana
        if (in_array($prefix, ['540', '550', '560', '570', '580', '240', '250'])) {
            return 'MTN';
        }
        // Vodafone Ghana
        if (in_array($prefix, ['520', '530', '540', '200', '210'])) {
            return 'VODAFONE';
        }
        // AirtelTigo
        if (in_array($prefix, ['270', '280', '290', '570', '571'])) {
            return 'AIRTELTIGO';
        }

        return 'MTN'; // Default to MTN
    }

    /**
     * View user's orders.
     */
    public function myOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    /**
     * View user's order detail.
     */
    public function myOrderDetail(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        return view('customer.order-detail', compact('order'));
    }

    /**
     * Resume payment for a pending order.
     */
    public function resumePayment(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('my-order-detail', $order->id)
                ->with('info', 'This order has already been paid.');
        }

        // Create new checkout session for the same order
        $provider = 'stripe';

        $paymentResult = $this->paymentRouter->createCheckout(
            $order->email,
            $order->total_amount,
            $provider,
            $order->order_number
        );

        if (! $paymentResult['success']) {
            return back()->with('error', $paymentResult['message'] ?? 'Failed to resume payment.');
        }

        return redirect($paymentResult['url']);
    }

    /**
     * Download purchased books PDF(s) securely.
     * Only accessible if order is paid and belongs to authenticated user.
     */
    public function downloadPdf(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this download.');
        }

        if (! $order->isPaid()) {
            abort(403, 'Payment not confirmed. Please complete your payment to access the download.');
        }

        $orderItems = $order->order_items;
        if ($orderItems->isEmpty()) {
            abort(404, 'No items found in this order.');
        }

        $bookIds = $orderItems->pluck('book_id')->filter()->unique()->toArray();
        if (empty($bookIds)) {
            abort(404, 'No valid books found for this order.');
        }

        $books = Book::whereIn('id', $bookIds)->get();

        if ($books->isEmpty()) {
            abort(404, 'Books not found.');
        }

        // Handle single book download directly
        if ($books->count() === 1) {
            $book = $books->first();

            $filePath = storage_path('app/books/'.$book->book_pdf);
            if (! file_exists($filePath)) {
                // Fallback to public folder (for legacy)
                $filePath = public_path('public/books/'.$book->book_pdf);
                if (! file_exists($filePath)) {
                    abort(404, 'PDF file not found.');
                }
            }

            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$book->book_pdf.'"',
            ]);
        }

        // Multiple books: create a ZIP archive on the fly
        $tempFile = tempnam(sys_get_temp_dir(), 'order_').'.zip';
        $zip = new ZipArchive;

        if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Could not create zip file');
        }

        foreach ($books as $book) {
            $filePath = storage_path('app/books/'.$book->book_pdf);
            if (! file_exists($filePath)) {
                $filePath = public_path('public/books/'.$book->book_pdf);
                if (! file_exists($filePath)) {
                    Log::warning('Download: PDF not found for book', ['book_id' => $book->id, 'filename' => $book->book_pdf]);

                    continue;
                }
            }
            $zip->addFile($filePath, $book->book_pdf);
        }
        $zip->close();

        // Ensure temp file is deleted after response
        register_shutdown_function(function () use ($tempFile) {
            if (file_exists($tempFile)) {
                @unlink($tempFile);
            }
        });

        $zipFileName = 'Order-'.$order->order_number.'.zip';

        return response()->download($tempFile, $zipFileName, [
            'Content-Type' => 'application/zip',
        ]);
    }
}
