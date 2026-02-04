<?php
/**
 * FPDF - Simple PDF Generator
 */

class FPDF {
    private $version = '1.3';
    private $page = 0;
    private $pages = [];
    private $state = 0;
    private $compress = true;
    private $DefPrintFooter = true;
    private $DefPrintHeader = true;
    private $curorientation = 'P';
    private $CurPageSize = ['w' => 210, 'h' => 297];
    private $w = 210;
    private $h = 297;
    private $wPt = 595.28;
    private $hPt = 841.89;
    private $lMargin = 10;
    private $tMargin = 10;
    private $rMargin = 10;
    private $bMargin = 10;
    private $cMargin = 0;
    private $x = 0;
    private $y = 0;
    private $lasth = 0;
    private $fontFamily = '';
    private $fontStyle = '';
    private $fontSizePt = 12;
    private $fontSize = 12;
    private $draw_color = '0 0 0';
    private $fill_color = '255 255 255';
    private $text_color = '0 0 0';
    private $ws = 0;
    private $core_fonts = ['courier' => 1, 'helvetica' => 1, 'times' => 1, 'symbol' => 1, 'zapfdingbats' => 1];
    private $fonts = [];
    private $font_files = [];
    private $diffs = [];
    private $images = [];
    private $links = [];
    private $n = 0;
    private $offsets = [];
    private $buffer = '';
    private $pages_alias_nb_pages = '';
    
    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4') {
        $this->state = 1;
        $this->page = 0;
        
        if (is_array($size)) {
            $this->w = $size[0] / $unit;
            $this->h = $size[1] / $unit;
        } else {
            $size = strtoupper($size);
            if ($size == 'A4') {
                $this->w = 210;
                $this->h = 297;
            }
        }
        
        $this->wPt = $this->w * 2.83465;
        $this->hPt = $this->h * 2.83465;
        $this->curorientation = $orientation[0];
        $this->x = $this->lMargin;
        $this->y = $this->tMargin;
        $this->fontFamily = '';
        $this->fontStyle = '';
        $this->fontSizePt = 12;
    }
    
    public function AddPage($orientation = '') {
        if ($this->state == 0) {
            $this->Open();
        }
        
        $bak_x = $this->x;
        $bak_y = $this->y;
        
        $this->page++;
        $this->pages[$this->page] = '';
        
        if ($this->state == 3) {
            $this->EndPage();
        }
        
        $this->BeginPage($orientation);
        $this->In_Footer = false;
        
        if ($this->DefPrintHeader) {
            $this->Header();
        }
        $this->footer_called = false;
    }
    
    private function BeginPage($orientation) {
        if (!$this->fontFamily) {
            $this->SetFont('Helvetica', '', 12);
        }
        $this->state = 2;
    }
    
    public function SetFont($family, $style = '', $size = 0) {
        $family = strtolower($family);
        
        if ($family == '') {
            $family = $this->fontFamily;
        }
        
        if ($family == 'arial') {
            $family = 'helvetica';
        }
        
        $style = strtoupper($style);
        
        if (strpos($style, 'U') !== false) {
            $this->underline = 1;
            $style = str_replace('U', '', $style);
        } else {
            $this->underline = 0;
        }
        
        if ($style == 'IB') {
            $style = 'BI';
        }
        
        if ($size == 0) {
            $size = $this->fontSizePt;
        }
        
        $this->fontFamily = $family;
        $this->fontStyle = $style;
        $this->fontSizePt = $size;
        $this->fontSize = $size / 25.4 * 72;
    }
    
    public function SetFillColor($r, $g = null, $b = null) {
        if (($r == 0 && $g == 0 && $b == 0) || $g === null) {
            $this->fill_color = sprintf('%.3f', $r / 255) . ' g';
        } else {
            $this->fill_color = sprintf('%.3f', $r / 255) . ' ' . sprintf('%.3f', $g / 255) . ' ' . sprintf('%.3f', $b / 255) . ' rg';
        }
    }
    
    public function SetTextColor($r, $g = null, $b = null) {
        if (($r == 0 && $g == 0 && $b == 0) || $g === null) {
            $this->text_color = sprintf('%.3f', $r / 255) . ' g';
        } else {
            $this->text_color = sprintf('%.3f', $r / 255) . ' ' . sprintf('%.3f', $g / 255) . ' ' . sprintf('%.3f', $b / 255) . ' rg';
        }
    }
    
    public function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '') {
        $k = 1;
        if ($this->y + $h > ($this->h - $this->bMargin)) {
            $this->AddPage($this->curorientation);
        }
        
        if ($fill) {
            $s = sprintf('%.2f', ($this->x) * $k) . ' ' . sprintf('%.2f', ($this->hPt - $this->y * $k)) . ' ' . sprintf('%.2f', $w * $k) . ' ' . sprintf('%.2f', -$h * $k) . ' re f ';
            $this->_out($s);
        }
        
        if ($border) {
            $s = sprintf('%.2f', ($this->x) * $k) . ' ' . sprintf('%.2f', ($this->hPt - $this->y * $k)) . ' ' . sprintf('%.2f', $w * $k) . ' ' . sprintf('%.2f', -$h * $k) . ' re S ';
            $this->_out($s);
        }
        
        $s = 'BT ' . $this->text_color . ' /' . $this->CurrentFont['name'] . ' ' . sprintf('%.2f', $this->fontSize) . ' Tf ' . sprintf('%.2f', ($this->x + ($w * 0.5 - strlen($txt) * $this->fontSize * 0.4)) * $k) . ' ' . sprintf('%.2f', ($this->hPt - $this->y - $h * 0.3) * $k) . ' Td (' . $this->_escape($txt) . ') Tj ET';
        $this->_out($s);
        
        $this->lasth = $h;
        if ($ln > 0) {
            $this->x = $this->lMargin;
            if ($ln == 1) {
                $this->y = $this->y + $h;
            }
        } else {
            $this->x = $this->x + $w;
        }
    }
    
    public function Ln($h = null) {
        $this->x = $this->lMargin;
        if (is_string($h)) {
            $this->y += $this->lasth;
        } else if ($h !== null) {
            $this->y += $h;
        } else {
            $this->y += $this->lasth;
        }
    }
    
    private function _out($s) {
        if ($this->state == 2) {
            $this->buffer .= $s . "\n";
        } else {
            $this->pages[$this->page] .= $s . "\n";
        }
    }
    
    private function _escape($s) {
        $s = str_replace('\\', '\\\\', $s);
        $s = str_replace('(', '\\(', $s);
        $s = str_replace(')', '\\)', $s);
        return $s;
    }
    
    public function Output($dest = '', $name = '') {
        if ($this->state < 3) {
            $this->Close();
        }
        
        if ($dest == 'D') {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $name . '"');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');
        } elseif ($dest == 'F') {
            file_put_contents($name, $this->buffer);
            return;
        } elseif ($dest == 'S') {
            return $this->buffer;
        } else {
            header('Content-Type: application/pdf');
            if (headers_sent()) {
                die('Some data has already been output, can\'t send PDF file');
            }
        }
        
        echo $this->buffer;
    }
    
    public function Close() {
        if ($this->state == 3) {
            return;
        }
        if ($this->page > 0) {
            $this->EndPage();
        }
        $this->state = 2; // Set state to terminating
        $this->_putpages();
        $this->_putresources();
        
        $this->_putinfo();
        $this->_putcatalog();
        
        $this->buffer = $this->_enddoc();
        $this->state = 3;
    }
    
    private function _putpages() {
        $nb = $this->page;
        $filter = $this->compress ? '/Filter /FlateDecode ' : '';
        
        for ($n = 1; $n <= $nb; $n++) {
            $this->pages[$n] = 'q' . "\n" . '1 0 0 1 0 0 cm' . "\n" . $this->pages[$n] . 'Q' . "\n";
            
            $p = $this->compress ? gzcompress($this->pages[$n]) : $this->pages[$n];
            
            $this->_newobj();
            $this->_out('<<' . $filter . '/Length ' . strlen($p) . '>>');
            $this->_putstream($p);
            $this->_out('endobj');
        }
        
        $this->n++;
        $this->offsets[$this->n] = strlen($this->buffer);
        $this->_out($this->n . ' 0 obj');
        $this->_out('<</Type /Pages');
        $kids = '/Kids [';
        for ($i = 0; $i < $nb; $i++) {
            $kids .= ($this->n - $nb + $i + 1) . ' 0 R ';
        }
        $this->_out($kids . ']');
        $this->_out('/Count ' . $nb);
        $this->_out('/MediaBox [0 0 ' . sprintf('%.2f', $this->wPt) . ' ' . sprintf('%.2f', $this->hPt) . ']');
        $this->_out('>>');
        $this->_out('endobj');
    }
    
    private function _putresources() {
        $this->_putfonts();
    }
    
    private function _putfonts() {
    }
    
    private function _putinfo() {
        $this->_newobj();
        $this->_out('<<');
        $this->_out('/Producer (FPDF)');
        $this->_out('/CreationDate (D:' . date('YmdHis') . ')');
        $this->_out('>>');
        $this->_out('endobj');
    }
    
    private function _putcatalog() {
        $this->_newobj();
        $this->_out('<<');
        $this->_out('/Type /Catalog');
        $this->_out('/Pages ' . ($this->n - 1) . ' 0 R');
        $this->_out('>>');
        $this->_out('endobj');
    }
    
    private function _newobj() {
        $this->n++;
        $this->offsets[$this->n] = strlen($this->buffer);
        $this->_out($this->n . ' 0 obj');
    }
    
    private function _putstream($s) {
        $this->_out('stream');
        $this->_out($s);
        $this->_out('endstream');
    }
    
    private function _enddoc() {
        $xref = strlen($this->buffer);
        $this->_out('xref');
        $this->_out('0 ' . ($this->n + 1));
        $this->_out('0000000000 65535 f ');
        
        for ($i = 1; $i <= $this->n; $i++) {
            $this->_out(str_pad($this->offsets[$i], 10, '0', STR_PAD_LEFT) . ' 00000 n ');
        }
        
        $this->_out('trailer');
        $this->_out('<<');
        $this->_out('/Size ' . ($this->n + 1));
        $this->_out('/Root ' . $this->n . ' 0 R');
        $this->_out('/Info ' . ($this->n - 1) . ' 0 R');
        $this->_out('>>');
        $this->_out('startxref');
        $this->_out($xref);
        $this->_out('%%EOF');
        
        return $this->buffer;
    }
    
    public function Header() {
    }
    
    public function Footer() {
    }
    
    private function EndPage() {
        $this->state = 1;
    }
    
    private $CurrentFont = ['type' => 'core', 'name' => 'Helvetica'];
}
?>
