<?php

namespace App\Services;

use App\Contracts\WordToPdfInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\Element\Paragraph;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;

class WordToPdfService implements WordToPdfInterface
{
    protected string $storagePath = 'books';

    public function convertToPdf($wordFile, string $title = 'document'): ?string
    {
        try {
            $filename = time().'_'.uniqid().'_'.preg_replace('/[^a-zA-Z0-9_-]/', '_', $title).'.pdf';
            $fullPath = public_path("storage/{$this->storagePath}");

            if (! is_dir($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            $outputPath = "{$fullPath}/{$filename}";

            $this->convertWordToPdfUsingLibreOffice($wordFile, $outputPath);

            if (file_exists($outputPath)) {
                Log::info("Word converted to PDF successfully: {$outputPath}");

                return $filename;
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Word to PDF conversion failed: '.$e->getMessage());

            return null;
        }
    }

    public function convertToPdfUsingPhpWord(string $wordFilePath, string $outputPath, string $title = 'Document', ?string $customerName = null, bool $addFooter = true): bool
    {
        try {
            if (! file_exists($wordFilePath)) {
                throw new \Exception("Word file not found: {$wordFilePath}");
            }

            $validDate = date('Y-m-d');
            $extension = strtolower(pathinfo($wordFilePath, PATHINFO_EXTENSION));

            Log::info("Converting Word file: {$wordFilePath} with extension: {$extension}");

            // Try PHPWord native PDF writer (preserves more formatting)
            $result = $this->convertWithPhpWord($wordFilePath, $outputPath);
            
            if ($result) {
                Log::info("Word converted to PDF using PHPWord: {$outputPath}");
                return true;
            }

            // Try LibreOffice conversion (preserves formatting)
            $result = $this->convertWithLibreOffice($wordFilePath, $outputPath);
            
            if ($result) {
                Log::info("Word converted to PDF using LibreOffice: {$outputPath}");
                return true;
            }

            // Fallback: If both fail, use raw text extraction
            Log::warning('All conversion methods failed, using raw text extraction');

            $pdf = new \TCPDF;
            $pdf->SetFont('helvetica', '', 12);
            $pdf->SetCreator('Bookshop');
            $pdf->SetAuthor('Bookshop');
            $pdf->SetTitle($title);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetMargins(20, 20, 20);
            $pdf->SetAutoPageBreak(true, 30);

            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 11);

            $rawText = $this->extractRawText($wordFilePath);
            if ($rawText) {
                Log::info('Using raw text extraction, length: '.strlen($rawText));
                $pdf->MultiCell(0, 6, $rawText);
            } else {
                $pdf->Ln(10);
                $pdf->SetFont('helvetica', '', 12);
                $pdf->MultiCell(0, 10, 'This document was converted from Word. Some formatting may have been lost.');
                $pdf->Ln(5);
            }

            if ($addFooter && $customerName) {
                $this->addCustomerFooter($pdf, $customerName, $validDate);
            }

            $pdf->Output($outputPath, 'F');

            Log::info("Word converted to PDF using fallback: {$outputPath}");

            return true;

        } catch (\Exception $e) {
            Log::error('Word PDF conversion failed: '.$e->getMessage().' Stack: '.$e->getTraceAsString());
            throw new \Exception('Failed to convert Word to PDF: '.$e->getMessage());
        }
    }

    protected function convertWithPhpWord(string $wordFilePath, string $outputPath): bool
    {
        try {
            $extension = strtolower(pathinfo($wordFilePath, PATHINFO_EXTENSION));
            $format = $extension === 'docx' ? 'Word2007' : 'Word2003';

            $phpWord = IOFactory::load($wordFilePath, $format);

            $sections = $phpWord->getSections();
            if (count($sections) === 0) {
                Log::warning('No sections found in Word document');
                return false;
            }

            $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
            
            $tempHtml = sys_get_temp_dir().'/'.uniqid().'.html';
            $htmlWriter->save($tempHtml);

            if (! file_exists($tempHtml)) {
                Log::warning('Failed to create HTML from Word document');
                return false;
            }

            $htmlContent = file_get_contents($tempHtml);
            unlink($tempHtml);

            if (! $htmlContent) {
                Log::warning('Empty HTML content from Word document');
                return false;
            }

            Log::info('Generated HTML length: '.strlen($htmlContent));

            // Clean HTML before passing to Dompdf
            $htmlContent = $this->cleanHtmlForPdf($htmlContent);

            // Wrap in basic HTML structure if needed
            if (! preg_match('/<html/i', $htmlContent)) {
                $htmlContent = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>'.$htmlContent.'</body></html>';
            }

            // Use Dompdf for better HTML rendering
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', false);
            $options->set('defaultFont', 'Arial');
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($htmlContent);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $pdfContent = $dompdf->output();
            file_put_contents($outputPath, $pdfContent);

            return file_exists($outputPath);

        } catch (\Exception $e) {
            Log::error('PHPWord Dompdf conversion error: '.$e->getMessage());
            return false;
        }
    }

    protected function cleanHtmlForPdf(string $html): string
    {
        // Remove Word-specific comments and styles
        $html = preg_replace('/<!--[\s\S]*?-->/', '', $html);
        $html = preg_replace('/<![CDATA[\s\S]*?]]>/', '', $html);
        
        // Fix Word's abnormal hyphenation and special characters
        $html = str_replace('­', '', $html); // Soft hyphen
        $html = str_replace('&shy;', '', $html);
        
        // Decode HTML entities that might be double-encoded
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Remove empty style tags
        $html = preg_replace('/<style[^>]*>\s*<\/style>/', '', $html);
        
        // Clean up Word-generated class names
        $html = preg_replace('/class="Mso[a-zA-Z0-9_-]*"/', '', $html);
        $html = preg_replace('/class="[^"]*"/', '', $html);
        
        // Fix list spacing that Word creates
        $html = str_replace('margin-top:0pt;', '', $html);
        $html = str_replace('margin-bottom:0pt;', '', $html);
        
        // Remove Word namespaces
        $html = preg_replace('/xmlns:[a-z-]+="[^"]*"/', '', $html);
        $html = preg_replace('/xml:space="[^"]*"/', '', $html);
        
        // Remove style attributes that might cause issues
        $html = preg_replace('/style="[^"]*"/', '', $html);
        
        // Fix tab characters
        $html = str_replace("\t", ' ', $html);
        
        // Ensure proper UTF-8 encoding
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        
        // Ensure body content is properly formatted
        if (preg_match('/<body[^>]*>([\s\S]*)<\/body>/i', $html, $matches)) {
            $html = $matches[1];
        }
        
        return $html;
    }

    protected function simpleCleanHtml(string $html): string
    {
        // Just basic cleaning - let TCPDF handle most of it
        $html = preg_replace('/<!--[\s\S]*?-->/', '', $html);
        
        if (preg_match('/<body[^>]*>([\s\S]*)<\/body>/i', $html, $matches)) {
            return $matches[1];
        }
        
        return $html;
    }

    protected function convertWithLibreOffice(string $wordFilePath, string $outputPath): bool
    {
        try {
            $tempDir = sys_get_temp_dir();
            $tempFile = $tempDir.'/'.uniqid().'.'.pathinfo($wordFilePath, PATHINFO_EXTENSION);
            
            copy($wordFilePath, $tempFile);

            $outputDir = dirname($outputPath);
            
            // Use LibreOffice to convert
            $command = sprintf(
                'libreoffice --headless --convert-to pdf --outdir "%s" "%s" 2>&1',
                $outputDir,
                $tempFile
            );
            
            exec($command, $output, $returnCode);
            
            // Clean up temp file
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
            
            if ($returnCode === 0) {
                // LibreOffice creates PDF with same name as input
                $expectedPdf = $outputDir.'/'.pathinfo($tempFile, PATHINFO_FILENAME).'.pdf';
                
                if (file_exists($expectedPdf)) {
                    // Rename to our desired output path
                    if (file_exists($outputPath)) {
                        unlink($outputPath);
                    }
                    rename($expectedPdf, $outputPath);
                    return true;
                }
            }
            
            Log::warning('LibreOffice conversion returned code: '.$returnCode);
            return false;
            
        } catch (\Exception $e) {
            Log::error('LibreOffice conversion error: '.$e->getMessage());
            return false;
        }
    }

    protected function extractWordContent($phpWord, $pdf): bool
    {
        $contentExtracted = false;
        $sections = $phpWord->getSections();

        if (count($sections) === 0) {
            Log::warning('No sections found in Word document');

            return false;
        }

        foreach ($phpWord->getSections() as $section) {
            $elements = $section->getElements();
            Log::info('Processing '.count($elements).' elements');

            foreach ($elements as $element) {
                if ($element instanceof Text) {
                    $text = $element->getText();
                    if ($text) {
                        $fontStyle = $element->getFontStyle();
                        $fontSize = $fontStyle && $fontStyle->getSize() ? $fontStyle->getSize() : 11;
                        $bold = $fontStyle && $fontStyle->isBold();
                        $italic = $fontStyle && $fontStyle->isItalic();

                        $pdf->SetFont('helvetica', ($bold ? 'B' : '').($italic ? 'I' : ''), $fontSize);
                        $pdf->MultiCell(0, 6, $text);
                        $pdf->Ln(2);
                        $contentExtracted = true;
                    }
                } elseif ($element instanceof TextRun) {
                    $text = '';
                    foreach ($element->getElements() as $childElement) {
                        if ($childElement instanceof Text) {
                            $text .= $childElement->getText();
                        }
                    }
                    if ($text) {
                        $fontStyle = $element->getFontStyle();
                        $fontSize = $fontStyle && $fontStyle->getSize() ? $fontStyle->getSize() : 11;
                        $bold = $fontStyle && $fontStyle->isBold();
                        $italic = $fontStyle && $fontStyle->isItalic();

                        $pdf->SetFont('helvetica', ($bold ? 'B' : '').($italic ? 'I' : ''), $fontSize);
                        $pdf->MultiCell(0, 6, $text);
                        $pdf->Ln(2);
                        $contentExtracted = true;
                    }
                } elseif ($element instanceof Paragraph) {
                    $text = $element->getText();
                    if ($text) {
                        $pdf->MultiCell(0, 6, $text);
                        $pdf->Ln(2);
                        $contentExtracted = true;
                    }
                } elseif ($element instanceof Table) {
                    $rows = $element->getRows();
                    foreach ($rows as $row) {
                        $cells = $row->getCells();
                        foreach ($cells as $cell) {
                            $cellElements = $cell->getElements();
                            foreach ($cellElements as $cellElement) {
                                if ($cellElement instanceof Text) {
                                    $pdf->MultiCell(60, 5, $cellElement->getText());
                                }
                            }
                        }
                        $pdf->Ln();
                    }
                    $contentExtracted = true;
                }
            }
        }

        return $contentExtracted;
    }

    protected function extractRawText(string $wordFilePath): string
    {
        try {
            $extension = strtolower(pathinfo($wordFilePath, PATHINFO_EXTENSION));

            if ($extension === 'docx') {
                $zip = new \ZipArchive;
                if ($zip->open($wordFilePath) === true) {
                    $content = $zip->getFromName('word/document.xml');
                    $zip->close();

                    if ($content) {
                        $xml = simplexml_load_string($content);
                        $xml->registerXPathNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
                        
                        // Get all paragraphs with their text
                        $paragraphs = $xml->xpath('//w:p');
                        
                        $text = '';
                        foreach ($paragraphs as $p) {
                            $ps = $p->xpath('.//w:t');
                            $paraText = '';
                            foreach ($ps as $t) {
                                $paraText .= (string) $t;
                            }
                            if (trim($paraText)) {
                                $text .= $paraText."\n";
                            }
                        }
                        
                        // If no paragraphs, try getting all text nodes
                        if (trim($text) === '') {
                            $textNodes = $xml->xpath('//w:t');
                            foreach ($textNodes as $node) {
                                $text .= (string) $node."\n";
                            }
                        }

                        return trim($text);
                    }
                }
            } elseif ($extension === 'doc') {
                // For .doc files, try using antiword or catdoc if available
                $text = shell_exec('antiword "'.$wordFilePath.'" 2>/dev/null');
                if ($text) {
                    return trim($text);
                }
                $text = shell_exec('catdoc "'.$wordFilePath.'" 2>/dev/null');
                if ($text) {
                    return trim($text);
                }
            }

            return '';
        } catch (\Exception $e) {
            Log::error('Raw text extraction failed: '.$e->getMessage());

            return '';
        }
    }

    protected function addCustomerFooter(\TCPDF $pdf, string $customerName, string $validDate): void
    {
        $pageWidth = 210;
        $pageHeight = 297;

        try {
            if (method_exists($pdf, 'getPageWidth') && $pdf->getPageWidth()) {
                $pageWidth = $pdf->getPageWidth();
            }
            if (method_exists($pdf, 'getPageHeight') && $pdf->getPageHeight()) {
                $pageHeight = $pdf->getPageHeight();
            }
        } catch (\Exception $e) {
        }

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetTextColor(0, 0, 128);
        $pdf->SetXY(0, $pageHeight - 25);
        $pdf->Cell($pageWidth, 8, "This book is personally customized for {$customerName}", 0, 1, 'C', false);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, $pageHeight - 15);
        $footerText = "Licensed to: {$customerName} | Valid: {$validDate}";
        $pdf->Cell($pageWidth, 8, $footerText, 0, 1, 'C', false);

        $pdf->SetTextColor(0, 0, 0);
    }

    protected function convertWordToPdfUsingLibreOffice($wordFile, string $outputPath): void
    {
        $tempDir = sys_get_temp_dir();
        $tempDoc = $tempDir.'/'.uniqid().'.'.$wordFile->getClientOriginalExtension();

        copy($wordFile->getPathname(), $tempDoc);

        $command = sprintf(
            'libreoffice --headless --convert-to pdf --outdir "%s" "%s" 2>&1',
            dirname($outputPath),
            $tempDoc
        );

        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new \Exception('LibreOffice conversion failed with code: '.$returnCode);
        }

        $expectedPdf = dirname($outputPath).'/'.pathinfo($tempDoc, PATHINFO_FILENAME).'.pdf';

        if (file_exists($expectedPdf)) {
            rename($expectedPdf, $outputPath);
        }

        if (file_exists($tempDoc)) {
            unlink($tempDoc);
        }
    }

    public function hasWordSupport(): bool
    {
        return class_exists('PhpOffice\PhpWord\IOFactory');
    }
}
