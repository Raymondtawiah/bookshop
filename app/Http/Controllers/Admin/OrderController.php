<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\EmailOfferTrackerInterface;
use App\Contracts\EmailSenderInterface;
use App\Http\Controllers\Controller;
use App\Mail\BookOfferMail;
use App\Mail\PaymentReminder;
use App\Mail\SendPdfToCustomer;
use App\Models\Order;
use App\Services\PassageService;
use App\Services\Payments\PaymentRouterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    protected EmailOfferTrackerInterface $bookOfferTracker;

    protected EmailSenderInterface $emailSender;

    protected PaymentRouterService $paymentRouter;

    public function __construct(EmailOfferTrackerInterface $bookOfferTracker, EmailSenderInterface $emailSender, PaymentRouterService $paymentRouter)
    {
        $this->bookOfferTracker = $bookOfferTracker;
        $this->emailSender = $emailSender;
        $this->paymentRouter = $paymentRouter;
    }

    /**
     * Display all orders with optional filtering
     */
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($request->has('search') && ! empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('contact', 'like', "%{$search}%");
            });
        }

        if ($request->has('payment_status') && ! empty($request->input('payment_status'))) {
            $paymentStatus = $request->input('payment_status');
            if (in_array($paymentStatus, ['paid', 'pending', 'failed'])) {
                $query->where('payment_status', $paymentStatus);
            }
        }

        if ($request->has('status') && ! empty($request->input('status'))) {
            $status = $request->input('status');
            if (in_array($status, ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])) {
                $query->where('status', $status);
            }
        }

        if ($request->has('start_date') && ! empty($request->input('start_date'))) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }

        if ($request->has('end_date') && ! empty($request->input('end_date'))) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        $orders = $query->latest()->paginate(15)->appends($request->except('page'));

        $totalOrders = $query->count();
        $totalPaid = (clone $query)->where('payment_status', 'paid')->count();
        $totalPending = (clone $query)->where('payment_status', 'pending')->count();
        $totalRevenue = (clone $query)->where('payment_status', 'paid')->sum('total_amount_usd');

        return view('admin.orders.index', compact('orders', 'totalOrders', 'totalPaid', 'totalPending', 'totalRevenue'));
    }

    /**
     * Display order details
     */
    public function show($id)
    {
        $order = Order::with('user')->findOrFail($id);

        $orderItems = $order->order_items;

        return view('admin.orders.show', compact('order', 'orderItems'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    /**
     * Send uploaded PDF to customer
     */
    public function sendBookPdf(Request $request, $id)
    {
        $request->validate([
            'pdf_file' => 'required|file|mimes:pdf|max:51200',
            'override_email' => 'nullable|email|max:255',
        ]);

        $order = Order::with('user')->findOrFail($id);

        $pdfFile = $request->file('pdf_file');
        $filename = 'order-'.($order->order_number ?? $order->id).'_'.time().'.pdf';
        $pdfPath = $pdfFile->storeAs('books/pdfs', $filename, 'public');

        $recipientEmail = $request->input('override_email') ?? $order->user?->email ?? $order->email;

        if (! $recipientEmail) {
            return redirect()->back()->with('error', 'No email address found for this order.');
        }

        $fullPath = Storage::disk('public')->path($pdfPath);

        if (! file_exists($fullPath)) {
            return redirect()->back()->with('error', 'PDF file could not be stored. Please try again.');
        }

        $recipient = (object) [
            'id' => $order->id,
            'name' => $order->customer_name ?? 'Customer',
            'email' => $recipientEmail,
        ];

        $mail = new SendPdfToCustomer(
            $recipient,
            [['path' => $fullPath, 'filename' => $pdfFile->getClientOriginalName()]],
            $order->id
        );

        $emailSent = $this->emailSender->send($recipient, $mail);

        if ($emailSent) {
            $currentPaymentStatus = $order->payment_status;
            $isAlreadyPaid = in_array($currentPaymentStatus, ['paid', 'completed']);

            $order->update([
                'status' => 'confirmed',
                'payment_status' => $isAlreadyPaid ? $currentPaymentStatus : 'paid',
                'paid_at' => $order->paid_at ?? now(),
                'pdf_sent' => true,
                'pdf_sent_at' => now(),
            ]);

            return redirect()->back()->with('success', 'PDF sent to customer successfully!');
        }

        Log::error('Failed to send PDF email for order', [
            'order_id' => $order->id,
            'email' => $recipientEmail,
        ]);

        return redirect()->back()->with('error', 'Failed to send PDF email. Please try again.');
    }

    /**
     * Send book offer notification to customer
     */
    public function sendBookOffer(Request $request, $id)
    {
        $request->validate([
            'pdf_file' => 'nullable|file|mimes:pdf|max:51200',
            'override_email' => 'nullable|email|max:255',
            'note' => 'nullable|string|max:500',
        ]);

        $order = Order::with('user')->findOrFail($id);

        if ($order->book_offered) {
            return redirect()->back()->with('info', 'Book has already been offered for this order.');
        }

        $recipientEmail = $request->input('override_email');
        if (empty($recipientEmail)) {
            $recipientEmail = $order->user?->email ?? $order->email;
        }

        if (! $recipientEmail) {
            return redirect()->back()->with('error', 'No email address found for this order.');
        }

        $recipient = (object) [
            'email' => $recipientEmail,
            'name' => $order->customer_name ?? 'Customer',
        ];

        $uploadedPdfPath = null;
        $uploadedPdfName = null;

        if ($request->hasFile('pdf_file')) {
            $pdfFile = $request->file('pdf_file');
            $filename = 'order-'.($order->order_number ?? $order->id).'_'.time().'.pdf';
            $pdfPath = $pdfFile->storeAs('books/pdfs', $filename, 'public');
            $uploadedPdfPath = Storage::disk('public')->path($pdfPath);
            $uploadedPdfName = $pdfFile->getClientOriginalName();
        }

        $mail = new BookOfferMail($order, $uploadedPdfPath, $uploadedPdfName);

        $emailSent = $this->emailSender->send($recipient, $mail);

        if (! $emailSent) {
            Log::error('Failed to send book offer email for order', [
                'order_id' => $order->id,
                'email' => $recipientEmail,
                'has_pdf_upload' => $request->hasFile('pdf_file'),
            ]);
        }

        if ($emailSent) {
            $this->bookOfferTracker->markAsOffered($order->id, $request->input('note'));

            return redirect()->back()->with('success', 'Book offer sent to customer successfully!');
        }

        return redirect()->back()->with('error', 'Failed to send book offer email. Please try again.');
    }

    /**
     * Send payment reminder to customer for pending order
     */
    public function sendPaymentReminder(Request $request, $id)
    {
        $order = Order::with('user')->findOrFail($id);

        if ($order->payment_status !== 'pending') {
            return redirect()->back()->with('error', 'This order is not pending payment.');
        }

        $recipientEmail = $order->user?->email ?? $order->email;

        if (! $recipientEmail) {
            return redirect()->back()->with('error', 'No email address found for this order.');
        }

        $provider = $order->payment_provider ?? 'stripe';
        $amount = $order->total_amount_usd ?? $order->total_amount;

        $reference = $order->order_number;

        if ($provider === 'paystack') {
            $reference = 'REM-'.$order->order_number.'-'.time();
            $order->update(['transaction_reference' => $reference]);
        }

        $paymentResult = $this->paymentRouter->createCheckout(
            $recipientEmail,
            $amount,
            $provider,
            $reference
        );

        if (! $paymentResult['success']) {
            Log::error('Payment reminder: failed to create checkout', [
                'order_id' => $order->id,
                'provider' => $provider,
                'error' => $paymentResult['message'] ?? 'Unknown error',
            ]);

            return redirect()->back()->with('error', 'Failed to generate payment link. Please try again.');
        }

        $paymentLink = $paymentResult['url'];

        Mail::to($recipientEmail)->send(new PaymentReminder($order, $paymentLink));

        $order->update([
            'reminder_sent' => true,
            'reminder_sent_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Payment reminder sent successfully!');
    }

    /**
     * Preview passage content (API endpoint)
     */
    public function previewPassage(Request $request)
    {
        $passage = $request->query('passage');

        if (empty($passage)) {
            return response()->json([
                'success' => false,
                'message' => 'No passage selected',
            ]);
        }

        $passageService = app(PassageService::class);
        $content = $passageService->getPassage($passage);

        if ($content === null) {
            return response()->json([
                'success' => false,
                'message' => 'Passage not found',
            ]);
        }

        return response()->json([
            'success' => true,
            'content' => $content,
            'name' => $passageService->getPassageName($passage),
        ]);
    }
}
