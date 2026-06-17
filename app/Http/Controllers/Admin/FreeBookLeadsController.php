<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\FreeBookLead;
use Illuminate\Http\Request;

class FreeBookLeadsController extends Controller
{
    public function index(Request $request)
    {
        $query = FreeBookLead::with('book');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('book_title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('book_id')) {
            $query->where('book_id', $request->input('book_id'));
        }

        if ($request->filled('downloaded')) {
            if ($request->input('downloaded') === 'yes') {
                $query->whereNotNull('downloaded_at');
            } else {
                $query->whereNull('downloaded_at');
            }
        }

        if ($request->filled('notified')) {
            if ($request->input('notified') === 'yes') {
                $query->whereNotNull('notified_at');
            } else {
                $query->whereNull('notified_at');
            }
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        $leads = $query->latest()->paginate(20)->appends($request->except('page'));

        $totalLeads = FreeBookLead::count();
        $totalDownloaded = FreeBookLead::whereNotNull('downloaded_at')->count();
        $totalNotified = FreeBookLead::whereNotNull('notified_at')->count();
        $totalBooks = Book::where('is_free', true)->get();

        return view('admin.free-books.index', compact(
            'leads', 'totalLeads', 'totalDownloaded', 'totalNotified', 'totalBooks'
        ));
    }

    public function download(FreeBookLead $lead)
    {
        $book = $lead->book;

        $filePath = storage_path('app/books/'.$book->book_pdf);
        if (! file_exists($filePath)) {
            $filePath = public_path('public/books/'.$book->book_pdf);
            if (! file_exists($filePath)) {
                $filePath = public_path('books/'.$book->book_pdf);
                if (! file_exists($filePath)) {
                    abort(404, 'PDF file not found.');
                }
            }
        }

        return response()->download($filePath, $book->book_pdf, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$book->book_pdf.'"',
        ]);
    }

    public function downloadAll(Request $request)
    {
        $query = FreeBookLead::with('book');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('book_title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('book_id')) {
            $query->where('book_id', $request->input('book_id'));
        }

        if ($request->filled('downloaded')) {
            if ($request->input('downloaded') === 'yes') {
                $query->whereNotNull('downloaded_at');
            } else {
                $query->whereNull('downloaded_at');
            }
        }

        if ($request->filled('notified')) {
            if ($request->input('notified') === 'yes') {
                $query->whereNotNull('notified_at');
            } else {
                $query->whereNull('notified_at');
            }
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        $leads = $query->get();

        $filename = 'free-book-leads-'.now()->format('Y-m-d-His').'.csv';

        $callback = function () use ($leads) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'Email']);

            foreach ($leads as $lead) {
                fputcsv($handle, [
                    $lead->full_name,
                    $lead->email,
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
