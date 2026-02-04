<?php
/**
 * Simple PDF Generator - Create valid PDF files without external dependencies
 */

class SimplePDFGenerator {
    private $pages = [];
    private $currentPage = '';
    private $y = 0;
    private $x = 15; // Adjusted margin
    private $pageHeight = 277;
    private $pageWidth = 190;
    private $fontSize = 10;
    private $fontHeight = 4;
    
    public function __construct() {
        $this->addNewPage();
    }
    
    private function addNewPage() {
        if (!empty($this->currentPage)) {
            $this->pages[] = $this->currentPage;
        }
        $this->currentPage = "";
        $this->y = 20;
    }
    
    public function addCompanyHeader($name, $address) {
        // Logo simulated with a colored box
        $this->currentPage .= "0.145 0.388 0.921 rg\n"; // Branding Blue
        $x = $this->x * 2.83465;
        $y = (297 - 28) * 2.83465; 
        $this->currentPage .= "$x $y 30 30 re f\n"; 
        
        // Initial "RS"
        $this->currentPage .= "BT\n/F1 16 Tf\n1 1 1 rg\n";
        $textX = $x + 5;
        $textY = $y + 8;
        $this->currentPage .= "1 0 0 1 $textX $textY Tm\n(RS) Tj\nET\n";
        
        // Brand Name (Dark Blue)
        $this->currentPage .= "BT\n/F1 18 Tf\n0.05 0.1 0.2 rg\n"; 
        $textX = $x + 40; 
        $textY = $y + 15;
        $this->currentPage .= "1 0 0 1 $textX $textY Tm\n";
        $this->currentPage .= "(" . addslashes($name) . ") Tj\nET\n";
        
        // Address (Gray)
        $this->currentPage .= "BT\n/F1 9 Tf\n0.5 0.55 0.6 rg\n"; 
        $textY -= 14;
        $this->currentPage .= "1 0 0 1 $textX $textY Tm\n";
        
        // Handle long address - simplistic wrap
        if (strlen($address) > 80) {
             $addr1 = substr($address, 0, 80);
             $addr2 = substr($address, 80);
             $this->currentPage .= "(" . addslashes($addr1) . "-) Tj\n";
             $textY -= 10;
             $this->currentPage .= "ET\nBT\n/F1 9 Tf\n0.5 0.55 0.6 rg\n";
             $this->currentPage .= "1 0 0 1 $textX $textY Tm\n";
             $this->currentPage .= "(" . addslashes($addr2) . ") Tj\nET\n";
        } else {
             $this->currentPage .= "(" . addslashes($address) . ") Tj\nET\n";
        }
        
        // Line
        $lineY = (297 - 38) * 2.83465;
        $this->currentPage .= "0.9 0.9 0.9 RG\n1 w\n";
        $startX = $this->x * 2.83465;
        $endX = (210 - $this->x) * 2.83465; 
        $this->currentPage .= "$startX $lineY $endX $lineY m l S\n";
        
        $this->y = 50;
    }

    public function addTitle($text) {
        $x = $this->x * 2.83465;
        $y = (297 - $this->y) * 2.83465;
        
        $this->currentPage .= "BT\n/F1 14 Tf\n0 0 0 rg\n";
        $this->currentPage .= "1 0 0 1 $x $y Tm\n";
        $this->currentPage .= "(" . addslashes($text) . ") Tj\nET\n";
        
        $this->y += 8;
    }
    
    public function addSubtitle($text) {
        $x = $this->x * 2.83465;
        $y = (297 - $this->y) * 2.83465;
        
        $this->currentPage .= "BT\n/F1 10 Tf\n0.4 0.4 0.4 rg\n";
        $this->currentPage .= "1 0 0 1 $x $y Tm\n";
        $this->currentPage .= "(" . addslashes($text) . ") Tj\nET\n";
        
        $this->y += 10;
    }
    
    public function addText($text, $size = 10, $bold = false) {
        $this->startTextBlock($size);
        $this->addTextContent($text);
        $this->endTextBlock();
        $this->y += ($size / 2.5);
    }
    
    private function startTextBlock($size = 10, $color = '0 0 0') {
        if ($this->y > $this->pageHeight) {
            $this->addNewPage();
        }
        $x = $this->x * 2.83465;
        $y = (297 - $this->y) * 2.83465;
        
        $this->currentPage .= "BT\n";
        $this->currentPage .= "/F1 $size Tf\n";
        $this->currentPage .= "$color rg\n";
        $this->currentPage .= "$x $y Td\n";
    }
    
    private function addTextContent($text) {
        $this->currentPage .= "(" . addslashes($text) . ") Tj\n";
    }
    
    private function endTextBlock() {
        $this->currentPage .= "ET\n";
    }
    
    public function addTable($headers, $rows, $customWidths = null) {
        $cellHeight = 10;
        $pageWidthMm = 210 - ($this->x * 2);
        
        $colWidths = [];
        if ($customWidths && count($customWidths) === count($headers)) {
            // Normalize custom widths to page width
            $totalRatio = array_sum($customWidths);
            foreach ($customWidths as $w) {
                $colWidths[] = ($w / $totalRatio) * $pageWidthMm;
            }
        } else {
            // Default equal width
            $colWidths = array_fill(0, count($headers), $pageWidthMm / count($headers));
        }
        
        // Header
        if ($this->y > $this->pageHeight - 20) {
            $this->addNewPage();
        }
        
        $y = (297 - $this->y) * 2.83465;
        
        // Draw header background
        $this->currentPage .= "0.1 0.25 0.6 rg\n";
        $x = $this->x * 2.83465;
        $w = $pageWidthMm * 2.83465;
        $h = $cellHeight * 2.83465;
        $this->currentPage .= "$x $y $w -$h re f\n"; 
        
        // Header text
        $colX = $this->x;
        $y_text = (297 - $this->y - ($cellHeight * 0.65)) * 2.83465;
        
        foreach ($headers as $i => $header) {
            $x_text = ($colX + 3) * 2.83465; 
            
            // Text clipping logic to prevent overlap
            $maxTextW = ($colWidths[$i] - 6) * 2.83465; // available width in points
             
            $this->currentPage .= "BT\n/F1 9 Tf\n1 1 1 rg\n"; 
            $this->currentPage .= "1 0 0 1 $x_text $y_text Tm\n"; 
            
            // Simple truncation
            // Assuming avg character width ~4 points for 9pt font caps
            $maxChars = floor($maxTextW / 5); 
            $text = strtoupper($header);
            if (strlen($text) > $maxChars) $text = substr($text, 0, $maxChars) . '.';
            
            $this->currentPage .= "(" . $text . ") Tj\nET\n";
            
            $colX += $colWidths[$i];
        }
        
        $this->y += $cellHeight;
        
        // Data rows
        $rowIndex = 0;
        foreach ($rows as $row) {
            if ($this->y > $this->pageHeight - 10) {
                $this->addNewPage();
                $y = (297 - $this->y) * 2.83465;
            } else {
                $y = (297 - $this->y) * 2.83465;
            }
            
            // Row Border
            $this->currentPage .= "0.85 0.85 0.85 RG\n"; 
            $rectY = $y - ($cellHeight * 2.83465);
            $rectH = $cellHeight * 2.83465;
            $this->currentPage .= "$x $rectY $w $rectH re S\n";
            
            // Zebra Striping
            if ($rowIndex % 2 != 0) {
                $this->currentPage .= "0.96 0.97 0.99 rg\n"; 
                $this->currentPage .= "$x $y $w -$rectH re f\n";
            }
            
            // Data Text
            $colX = $this->x;
            $y_text = (297 - $this->y - ($cellHeight * 0.7)) * 2.83465;
            
            foreach ($row as $i => $cell) {
                $x_text = ($colX + 3) * 2.83465; 
                
                $this->currentPage .= "BT\n/F1 9 Tf\n";
                if ($rowIndex % 2 != 0) {
                     $this->currentPage .= "0 0 0 rg\n";
                } else {
                     $this->currentPage .= "0.25 0.25 0.25 rg\n"; 
                }
                
                $this->currentPage .= "1 0 0 1 $x_text $y_text Tm\n"; 
                
                // Truncate based on Col Width
                $maxTextW = ($colWidths[$i] - 6) * 2.83465;
                $maxChars = floor($maxTextW / 4); // Avg char width 4
                
                $cell_text = $cell;
                if (strlen($cell_text) > $maxChars) $cell_text = substr($cell_text, 0, $maxChars) . '...';
                
                $this->currentPage .= "(" . addslashes($cell_text) . ") Tj\nET\n";
                
                $colX += $colWidths[$i];
            }
            
            // Vertical Dividers
            $this->currentPage .= "0.9 0.9 0.9 RG\n"; 
            $currX = $this->x;
            for($i=1; $i < count($headers); $i++) {
                $currX += $colWidths[$i-1];
                $lineX = $currX * 2.83465;
                $this->currentPage .= "$lineX $y $lineX $rectY m l S\n";
            }
            
            $this->y += $cellHeight;
            $rowIndex++;
        }
    }
    
    public function generatePDF() {
        if (!empty($this->currentPage)) {
            $this->pages[] = $this->currentPage;
        }
        
        $pdf = "%PDF-1.4\n%âãÏÓ\n";
        
        // Create objects
        $objectOffsets = [];
        $objectNum = 1;
        
        // Object 1: Catalog
        $objectOffsets[$objectNum] = strlen($pdf);
        $pdf .= "$objectNum 0 obj\n<</Type /Catalog /Pages 2 0 R>>\nendobj\n";
        $objectNum++;
        
        // Object 2: Pages
        $objectOffsets[$objectNum] = strlen($pdf);
        $pageRefs = '';
        for ($i = 0; $i < count($this->pages); $i++) {
            $pageRefs .= ($objectNum + 1 + $i) . " 0 R ";
        }
        $pdf .= "$objectNum 0 obj\n<</Type /Pages /Kids [$pageRefs] /Count " . count($this->pages) . ">>\nendobj\n";
        $objectNum++;
        
        // Objects: Pages and content streams
        $pageObjStart = $objectNum;
        foreach ($this->pages as $idx => $content) {
            // Page object
            $objectOffsets[$objectNum] = strlen($pdf);
            $contentObj = $objectNum + count($this->pages);
            $pdf .= "$objectNum 0 obj\n<</Type /Page /Parent 2 0 R /MediaBox [0 0 595.28 841.89] /Contents $contentObj 0 R /Resources <</Font <</F1 <</Type /Font /Subtype /Type1 /BaseFont /Helvetica>>>>>>\nendobj\n";
            $objectNum++;
        }
        
        // Content streams
        foreach ($this->pages as $idx => $content) {
            $objectOffsets[$objectNum] = strlen($pdf);
            $pdf .= "$objectNum 0 obj\n<</Length " . strlen($content) . ">>\nstream\n$content\nendstream\nendobj\n";
            $objectNum++;
        }
        
        // Xref
        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 " . ($objectNum) . "\n";
        $pdf .= "0000000000 65535 f \n";
        
        for ($i = 1; $i < $objectNum; $i++) {
            if (isset($objectOffsets[$i])) {
                $pdf .= str_pad($objectOffsets[$i], 10, '0', STR_PAD_LEFT) . " 00000 n \n";
            }
        }
        
        // Trailer
        $pdf .= "trailer\n<</Size $objectNum /Root 1 0 R>>\n";
        $pdf .= "startxref\n$xrefOffset\n%%EOF";
        
        return $pdf;
    }
}

?>
