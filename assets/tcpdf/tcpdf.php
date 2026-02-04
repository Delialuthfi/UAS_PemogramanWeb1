<?php
/**
 * TCPDF - Simple PDF Generation
 */

class TCPDF {
    private $pdf;
    private $x = 10;
    private $y = 10;
    private $fontsize = 10;
    private $fontname = 'Helvetica';
    private $page = 0;
    private $pages = [];
    private $current_page;
    
    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4') {
        $this->pdf = '%PDF-1.4' . "\n" . '%' . chr(226) . chr(227) . chr(207) . chr(211) . "\n";
        $this->AddPage();
    }
    
    public function AddPage($orientation = '') {
        $this->page++;
        $this->current_page = '';
        $this->x = 10;
        $this->y = 10;
    }
    
    public function SetFont($family = '', $style = '', $size = 0) {
        if ($size > 0) {
            $this->fontsize = $size;
        }
        $family = strtolower(str_replace('arial', 'helvetica', $family));
        $this->fontname = ucfirst($family);
    }
    
    public function SetFillColor($r, $g = null, $b = null) {
        // Dummy - not used in this simple version
    }
    
    public function SetTextColor($r, $g = null, $b = null) {
        // Dummy - not used in this simple version
    }
    
    public function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = 'L', $fill = false) {
        if ($h == 0) {
            $h = 5;
        }
        
        // Add text to current page
        $this->current_page .= '<text x="' . $this->x . '" y="' . $this->y . '">' . htmlspecialchars($txt) . '</text>';
        
        if ($ln > 0) {
            $this->y += $h + 1;
            $this->x = 10;
        } else {
            $this->x += $w;
        }
    }
    
    public function Ln($h = null) {
        if ($h === null) $h = 5;
        $this->y += $h;
        $this->x = 10;
    }
    
    public function Output($dest = '', $name = '') {
        // Create a simple HTML table output since PDF is complex
        // We'll send it as HTML that can be saved as PDF from browser
        
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $name . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        
        // Create basic PDF structure
        $pdf = "%PDF-1.4\n";
        $pdf .= "%â€¢â€¢â€¢â€¢\n";
        
        $objects = [];
        $offsets = [];
        
        // Catalog object
        $offsets[1] = strlen($pdf);
        $objects[1] = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
        
        // Pages object
        $offsets[2] = strlen($pdf) + strlen($objects[1]);
        $objects[2] = "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";
        
        // Page object
        $offsets[3] = strlen($pdf) + strlen($objects[1]) + strlen($objects[2]);
        $objects[3] = "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> >> >> >>\nendobj\n";
        
        // Content object
        $content = "BT\n/F1 12 Tf\n50 750 Td\n(Report) Tj\nET\n";
        $offsets[4] = strlen($pdf) + strlen($objects[1]) + strlen($objects[2]) + strlen($objects[3]);
        $objects[4] = "4 0 obj\n<< /Length " . strlen($content) . " >>\nstream\n" . $content . "\nendstream\nendobj\n";
        
        // Build final PDF
        $pdf .= $objects[1] . $objects[2] . $objects[3] . $objects[4];
        
        // XRef table
        $xref_pos = strlen($pdf);
        $pdf .= "xref\n";
        $pdf .= "0 5\n";
        $pdf .= "0000000000 65535 f\n";
        for ($i = 1; $i <= 4; $i++) {
            $pdf .= str_pad($offsets[$i], 10, '0', STR_PAD_LEFT) . " 00000 n\n";
        }
        
        // Trailer
        $pdf .= "trailer\n<< /Size 5 /Root 1 0 R >>\n";
        $pdf .= "startxref\n" . $xref_pos . "\n";
        $pdf .= "%%EOF";
        
        echo $pdf;
        exit;
    }
}

?>
