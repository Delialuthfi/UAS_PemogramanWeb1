<?php
/**
 * Simple PDF Generator
 * Creates basic PDF documents without external dependencies
 */

class SimplePDF {
    private $buffer = '';
    private $objectOffsets = [];
    private $objectCount = 0;
    private $pageCount = 0;
    private $pages = [];
    private $currentPage = null;
    private $x = 10;
    private $y = 10;
    private $pageWidth = 210;
    private $pageHeight = 297;
    private $fontSize = 12;
    private $fontName = 'Helvetica';
    
    public function __construct() {
        $this->addPage();
    }
    
    public function addPage() {
        if ($this->currentPage !== null) {
            $this->pages[] = $this->currentPage;
        }
        $this->currentPage = '';
        $this->pageCount++;
        $this->y = 10;
    }
    
    public function setFont($font = 'Helvetica', $style = '', $size = 12) {
        $this->fontName = $font;
        $this->fontSize = $size;
    }
    
    public function cell($w, $h, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false) {
        if ($this->y > $this->pageHeight - 10) {
            $this->addPage();
        }
        
        $txtWidth = (strlen($txt) * $this->fontSize * 0.5) / 72 * 25.4;
        
        $x = sprintf('%.2f', $this->x * 2.83);
        $y = sprintf('%.2f', (297 - $this->y) * 2.83);
        $w = sprintf('%.2f', $w * 2.83);
        $h = sprintf('%.2f', $h * 2.83);
        
        // Add text to page
        $this->currentPage .= "BT\n";
        $this->currentPage .= "/$this->fontName 0 Tf\n";
        $this->currentPage .= "$x $y Td\n";
        $this->currentPage .= "({$txt}) Tj\n";
        $this->currentPage .= "ET\n";
        
        $this->y += $h / 2.83;
        if ($ln > 0) {
            $this->x = 10;
            $this->y += 5;
        }
    }
    
    public function ln($height = 10) {
        $this->y += $height;
        $this->x = 10;
    }
    
    public function multiCell($w, $h, $txt = '', $border = 0, $align = '', $fill = false) {
        $this->cell($w, $h, $txt, $border, 1, $align, $fill);
    }
    
    public function output($name = '', $dest = '') {
        if ($this->currentPage !== null) {
            $this->pages[] = $this->currentPage;
        }
        
        // Build PDF
        $pdf = "%PDF-1.4\n";
        $pdf .= "%âãÏÓ\n";
        
        $objects = [];
        $objectOffsets = [];
        
        // Object 1: Catalog
        $offset = strlen($pdf);
        $objectOffsets[1] = $offset;
        $objects[1] = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
        $pdf .= $objects[1];
        
        // Object 2: Pages
        $offset = strlen($pdf);
        $objectOffsets[2] = $offset;
        $pageRefs = '';
        for ($i = 1; $i <= count($this->pages); $i++) {
            $pageRefs .= ($i + 2) . " 0 R ";
        }
        $objects[2] = "2 0 obj\n<< /Type /Pages /Kids [$pageRefs] /Count " . count($this->pages) . " >>\nendobj\n";
        $pdf .= $objects[2];
        
        // Objects 3+: Pages content
        $pageNum = 3;
        $contentObjs = [];
        foreach ($this->pages as $idx => $content) {
            // Page object
            $offset = strlen($pdf);
            $objectOffsets[$pageNum] = $offset;
            $contentObj = $pageNum + count($this->pages);
            $objects[$pageNum] = "$pageNum 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595.28 841.89] /Contents $contentObj 0 R /Resources << /Font << /F1 << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> >> >> >>\nendobj\n";
            $pdf .= $objects[$pageNum];
            $contentObjs[$idx] = $contentObj;
            $pageNum++;
        }
        
        // Content objects
        foreach ($this->pages as $idx => $content) {
            $objNum = $contentObjs[$idx];
            $offset = strlen($pdf);
            $objectOffsets[$objNum] = $offset;
            $contentStream = "BT\n/F1 12 Tf\n50 750 Td\n({$content}) Tj\nET\n";
            $objects[$objNum] = "$objNum 0 obj\n<< /Length " . strlen($content) . " >>\nstream\n$content\nendstream\nendobj\n";
            $pdf .= $objects[$objNum];
        }
        
        // XRef
        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n";
        $pdf .= "0 " . (count($objectOffsets) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";
        
        foreach ($objectOffsets as $num => $offset) {
            $pdf .= str_pad($offset, 10, '0', STR_PAD_LEFT) . " 00000 n \n";
        }
        
        // Trailer
        $pdf .= "trailer\n";
        $pdf .= "<< /Size " . (count($objectOffsets) + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n";
        $pdf .= "$xrefOffset\n";
        $pdf .= "%%EOF\n";
        
        if ($dest == 'D') {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $name . '"');
            echo $pdf;
        } else {
            return $pdf;
        }
    }
}
?>
