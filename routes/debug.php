<?php

use App\Models\Order;
use App\Services\PassageService;
use App\Services\PdfGeneratorService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Debug Routes - These routes help identify errors
|--------------------------------------------------------------------------
|
| Access these routes to test PDF generation and see detailed error messages
|
*/

Route::get('/debug-pdf/{orderId}', function ($orderId) {
    $order = Order::findOrFail($orderId);
    
    $results = [
        'order' => [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'customer_name' => $order->customer_name,
            'email' => $order->email,
        ],
        'tests' => []
    ];
    
    // Test 1: Check if TCPDF class exists
    $results['tests']['tcpdf_class'] = [
        'name' => 'TCPDF Class Exists',
        'passed' => class_exists('\TCPDF'),
        'message' => class_exists('\TCPDF') ? 'TCPDF class found' : 'TCPDF class NOT found'
    ];
    
    // Test 2: Check if FPDI class exists
    $results['tests']['fpdi_class'] = [
        'name' => 'FPDI Class Exists',
        'passed' => class_exists('setasign\Fpdi\Tcpdf\Fpdi'),
        'message' => class_exists('setasign\Fpdi\Tcpdf\Fpdi') ? 'FPDI class found' : 'FPDI class NOT found'
    ];
    
    // Test 3: Check passages
    try {
        $passageService = new PassageService();
        $passages = $passageService->getAllPassages();
        $passageNames = $passageService->getPassageNames();
        
        $results['tests']['passages'] = [
            'name' => 'Passages Available',
            'passed' => count($passages) > 0,
            'message' => 'Found ' . count($passages) . ' passages: ' . implode(', ', array_keys($passages)),
            'passages' => $passageNames
        ];
    } catch (\Exception $e) {
        $results['tests']['passages'] = [
            'name' => 'Passages Available',
            'passed' => false,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
    
    // Test 4: Try to get a passage
    try {
        $passageService = new PassageService();
        $content = $passageService->getPassage('visa-application-guide');
        
        $results['tests']['passage_content'] = [
            'name' => 'Passage Content',
            'passed' => !empty($content),
            'message' => !empty($content) ? 'Content length: ' . strlen($content) . ' chars' : 'No content found',
            'content_preview' => !empty($content) ? substr($content, 0, 100) . '...' : null
        ];
    } catch (\Exception $e) {
        $results['tests']['passage_content'] = [
            'name' => 'Passage Content',
            'passed' => false,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
    
    // Test 5: Try to create PDF
    try {
        $passageService = new PassageService();
        $content = $passageService->getPassage('visa-application-guide');
        
        if (empty($content)) {
            throw new \Exception('No passage content available');
        }
        
        // Try to initialize TCPDF
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        
        $results['tests']['tcpdf_init'] = [
            'name' => 'TCPDF Initialization',
            'passed' => true,
            'message' => 'TCPDF initialized successfully'
        ];
        
        // Try to add a page
        $pdf->AddPage();
        
        $results['tests']['tcpdf_page'] = [
            'name' => 'TCPDF Add Page',
            'passed' => true,
            'message' => 'Page added successfully'
        ];
        
        // Try to add content
        $pdf->SetFont('helvetica', '', 12);
        $pdf->MultiCell(0, 10, $content);
        
        $results['tests']['tcpdf_content'] = [
            'name' => 'TCPDF Add Content',
            'passed' => true,
            'message' => 'Content added successfully'
        ];
        
        // Try to save
        $outputPath = public_path('storage/test_debug.pdf');
        $pdf->Output($outputPath, 'F');
        
        $results['tests']['tcpdf_save'] = [
            'name' => 'TCPDF Save',
            'passed' => file_exists($outputPath),
            'message' => file_exists($outputPath) ? 'PDF saved successfully at: ' . $outputPath : 'Failed to save PDF'
        ];
        
    } catch (\Exception $e) {
        $results['tests']['tcpdf_init'] = [
            'name' => 'TCPDF Test',
            'passed' => false,
            'message' => 'Error: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
    }
    
    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
});

// Simple route to test passage reading
Route::get('/debug-passages', function () {
    try {
        $passageService = new PassageService();
        $passages = $passageService->getAllPassages();
        
        $output = "=== PASSAGES DEBUG ===\n\n";
        $output .= "Number of passages: " . count($passages) . "\n\n";
        
        foreach ($passages as $key => $passage) {
            $output .= "Passage: {$key}\n";
            $output .= "  Name: {$passage['name']}\n";
            $output .= "  Path: {$passage['path']}\n";
            $output .= "  Content length: " . strlen($passage['content']) . " chars\n";
            $output .= "  Content preview: " . substr($passage['content'], 0, 100) . "...\n\n";
        }
        
        return response($output, 200, ['Content-Type' => 'text/plain']);
    } catch (\Exception $e) {
        return response("Error: " . $e->getMessage() . "\n" . $e->getTraceAsString(), 500, ['Content-Type' => 'text/plain']);
    }
});
