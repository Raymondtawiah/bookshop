<?php

use App\Models\Order;
use App\Services\OrderPdfService;
use App\Services\PdfGeneratorService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Test PDF Generation with Email
|--------------------------------------------------------------------------
*/

Route::get('/test-pdf-email/{orderId}', function ($orderId) {
    try {
        $order = Order::findOrFail($orderId);
        
        // Test passage content
        $passagePath = resource_path('passages/visa-application-guide.txt');
        
        if (!file_exists($passagePath)) {
            return response()->json([
                'error' => 'Passage file not found',
                'path' => $passagePath
            ]);
        }
        
        $content = file_get_contents($passagePath);
        
        // Try to generate PDF
        $pdfService = new PdfGeneratorService();
        $pdfPath = $pdfService->generateFromText($content, $order, 'Visa Application Guide');
        
        return response()->json([
            'success' => true,
            'order' => $order->order_number,
            'pdf_path' => $pdfPath,
            'message' => 'PDF generated successfully! Now trying to send email...'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
