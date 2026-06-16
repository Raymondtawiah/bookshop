<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FileDownloadController extends Controller
{
    public function downloadBookPdf($filename)
    {
        $path = public_path('books/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path, $filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}