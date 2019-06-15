<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * PDF
 * Invoca la libreria TCPDF generadora de todos los archivos
 * pdf que se crean en proyecto por medio de plantillas
 *
 * Requires PHP v.7.x or higher.
 *
 * @package		Usertracking for CodeIgniter
 * @author		Natty Narwhal
 * @since		 Version 1.0
 * 
 * Output
 * I  : send the file inline to the browser (default). The plug-in is used if 
 *      available. The name given by name is used when one selects the "Save as" 
 *      option on the link generating the PDF.
 * D  : send to the browser and force a file download with the name given by name.
 * F  : save to a local server file with the name given by name.
 * S  : return the document as a string (name is ignored).
 * FI : equivalent to F + I option
 * FD : equivalent to F + D option
 * E  : return the document as base64 mime multi-part email attachment (RFC 2045)
 * 
 * Ancho de hoja: 196
 * 
 */

require_once APPPATH . 'libraries/tcpdf/tcpdf.php';

class Spider_pdf extends TCPDF {

    /**
     * Variable para mostrar el nombre de la empresa
     *
     * @var String 
     */
    private $empresa;

    /**
     * Variable para mostrar codigo debida diligencia al pie
     *
     * @var String
     */
    private $codigo;
    
    /**
     * Variable para nombrar el archivo
     *
     * @var String
     */    
    private $namePdf;    
    
    /**
     * Color de las lineas del reporte
     *
     * @var Array 
     */
    private $lineColor = array(7, 0, 0);
    
    /**
     * Color de las lineas sub tipo
     *
     * @var Array 
     */
    private $lineBackColor = array(66, 66, 66);
    
    /**
     * Color de fondo de campos
     *
     * @var Array 
     */
    private $backGroundColor = array('r' => 226, 'g' => 226, 'b' => 226);
    
    /**
     * Ancho de lineas de tablas del reporte
     *
     * @var Double 
     */
    private $lineWidth = 0.1;

    /**
     * Fuente del reporte
     *
     * @var string
     */
    private $family = 'dejavusans';
    
    private $CI;
    
    function __construct($param) {
        extract($param);
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache);
        $this->CI = & get_instance();
        $this->empresa = $empresa;
        $this->codigo = $codigo;
        $this->namePdf = $namePdf;
        $this->setCellPaddings(1, 1, 1, 1);
        $this->SetFont($this->family, '', 7);
    }

    //Page header
    public function Header() {
        // Logo
        $image_file = base_url('assets/images/pdf/logo.jpg');
        $this->Image($image_file, 9, 4.6, 6, '', 'JPG', '', 'T', FALSE, 300, '', FALSE, FALSE, 0, FALSE, FALSE, FALSE);
        // Logo Vertical
        $image_pdf = base_url('assets/images/pdf/logopdf.jpg');
        $this->Image($image_pdf, 6, 90, 2.5, '', 'JPG', '', 'T', FALSE, 300, '', FALSE, FALSE, 0, FALSE, FALSE, FALSE);
        // Set font
        $this->SetFont($this->family, '', 8);
        // Title
        $this->MultiCell(NULL, NULL, '    ALIDATE', 0, 'L', FALSE, 0, 10, 5.5);
        $this->SetFont($this->family, '', 8);
        $this->MultiCell(NULL, NULL, 'Análisis: La Araña', 0, 'R', FALSE, 1, 50, 5);
        $y = 10;
        $style = array(
            'color' => $this->lineColor,
            'width' => $this->lineWidth
        );
        $this->Line(10, $y, 206, $y, $style);
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont($this->family, '', 7);
        // Page number
        $y = -10;
        $w = 204;
        $this->setCellPaddings(0.3, 1, 0.3, 1);
        $style = array(
            'color' => $this->lineColor,
            'width' => $this->lineWidth
        );
        $this->MultiCell(100, NULL, 'Pág ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 'T', 'L', FALSE, 0, NULL, $y+1);
        $this->MultiCell(NULL, NULL, 'Consecutivo: '.$this->codigo, 'T', 'R', FALSE, 1, NULL);
        //$this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, FALSE, 'C', 0, '', 0, FALSE, 'T', 'M');
    }

    private function spiderCentral($html, $class, $h) {
        $left = 10;
        foreach($html->find('div.'.$class) as $div){
            preg_match_all('!\d+\.*\d*!', $div->style, $top);
            $top = $this->px_to_mm($top[0][0]);
            $this->MultiCell(20, 4, $div->plaintext, TRUE, 'C', FALSE, 1, 98, ($top + round(($h-7),2)), TRUE, 0, TRUE, TRUE, 0, 'M');
        }
    }
    
    private function spiderBox($html, $class, $h) {
        $left = 10;
        if($class == 'spd-credito'){
            $left = 146;
        }
        foreach ($html->find('div.'.$class) as $div) {
            preg_match_all('!\d+\.*\d*!', $div->style, $top);
            $top = $this->px_to_mm($top[0][0]);
            foreach ($div->find('div') as $divChild) {
                $txtCell[$divChild->class] = $divChild->plaintext;
            }
            $this->MultiCell(20, 4, $txtCell['ispd-cuenta'], TRUE, 'R', FALSE, 0, ($left), ($top + round(($h-7),2)), TRUE, 0, TRUE, TRUE, 0, 'M');
            $this->MultiCell(20, 4, $txtCell['ispd-dinero'], TRUE, 'R', FALSE, 0, ($left+20), '', TRUE, 0, TRUE, TRUE, 0, 'M');
            $this->MultiCell(20, 4, $txtCell['ispd-porcentaje'], TRUE, 'R', FALSE, 1, ($left+40), '', TRUE, 0, TRUE, TRUE, 0, 'M');
        }        
    }
    
    private function spiderLine($html, $h) {
        $left = [
            'x1' => 3.2,
            'x2' => 13
        ];
        foreach($html->find('line') as $line){
            if($line->class == 'lin-credito'){
                $left = [
                    'x1' => -4,
                    'x2' => 5.7
                ];                
            }
            $this->Line(
                $this->px_to_mm($line->x1) + $left['x1'], 
                $this->px_to_mm($line->y1) + ($top + round(($h-7),2)), 
                $this->px_to_mm($line->x2) + $left['x2'], 
                $this->px_to_mm($line->y2) + ($top + round(($h-7),2))
            );
        }
    }
    
    public function run($data, $dataPdf) {
        require_once (APPPATH.'/libraries/Simple_html_dom.php');
        extract($dataPdf);
        extract($this->backGroundColor);
        try{
            $this->SetFont($this->family, '', 7);
            $this->SetProtection(array('modify', 'copy'), '');
            $this->SetTitle('La Araña');
            $this->SetLineStyle(array(
                'color' => $this->lineBackColor,
                'width' => $this->lineWidth
            ));
            $this->SetMargins(10, 15, 10);
            $this->SetAutoPageBreak(TRUE, 15);
            $this->SetAuthor('GEO Informatic Solutions S.A.');
            $this->SetDisplayMode('real', 'default');
            $this->AddPage();
            $this->SetFillColor($r, $g, $b);
            $this->MultiCell(67, NULL, 'Autor: '.$this->CI->session->first_name . ' ' . $this->CI->session->last_name, TRUE, 'L', FALSE, 0);
            $this->MultiCell(43, NULL, 'Fecha: '.date('d/m/Y'), TRUE, 'L', FALSE, 0);
            $this->MultiCell(43, NULL, 'Hora: '.date('h:i:s A'), TRUE, 'L', FALSE, 0);
            $this->MultiCell(43, NULL, 'IP: '.$this->CI->input->ip_address(), TRUE, 'L', FALSE, 1);
            $h = $this->_height(array(
                'txt' => 'Empresa: '.$this->empresa['nombre'],
                'w' => 86
            ));
            $this->MultiCell(67, $h, 'Consecutivo: '.$this->codigo, TRUE, 'L', FALSE, 0);
            $this->MultiCell(86, $h, 'Empresa: '.$this->empresa['nombre'], TRUE, 'L', FALSE, 0);
            $this->MultiCell(43, $h, 'NIT: '.$this->empresa['identificacion'], TRUE, 'L', FALSE, 1);
            $this->Ln(5);
            if(is_array($dataPdf)){
                if(array_key_exists(0, $dataPdf) && array_key_exists('body', $dataPdf[0])){
                    $this->SetFont($this->family, '', 5);
                    $this->setCellMargins(0, 0, 0, 0);
                    $this->setCellPaddings(0.3, 1, 0.3, 1);
                    $dataPdf = $dataPdf[0];
                    $pider = $dataPdf['body'];
                    $html = str_get_html($pider);
                    $this->spiderBox($html, 'spd-debito', $h);
                    $this->spiderBox($html, 'spd-credito', $h);
                    $this->spiderCentral($html, 'spd-spider', $h);
                    $this->spiderLine($html, $h);
                }else{
                    $this->MultiCell(196, NULL, '---TENEMOS UN PROBLEMA CON EL REPORTE ---', FALSE, 'C', FALSE, 0, '', '', TRUE, 0, TRUE);
                }                
            }else{
                $this->MultiCell(196, NULL, '---TENEMOS UN PROBLEMA CON EL REPORTE ---', FALSE, 'C', FALSE, 0, '', '', TRUE, 0, TRUE);
            }
            //$this->Output('archivo.pdf', 'I');
            $filePath = $this->CI->config->item('path_clie').'resultados/SPI'.$this->namePdf.'.pdf';
            $this->Output($filePath, 'F');
        } catch(Exception $ex){
            return FALSE;
        }
        return file_exists($filePath);
    }

    private function px_to_mm($pixels, $dpi = 180){
        $mTop = 30;
        $mm = (($pixels * 26.458) / $dpi) + $mTop;
        return $mm;
    }
    
    private function _height($param) {
        extract($param);
        // store current object
        $this->startTransaction();
        // store starting values
        $start_y = $this->GetY();
        $start_page = $this->getPage();
        // call your printing functions with your parameters
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        $this->MultiCell($w, NULL, $txt, 1, 'L', false, 1, '', '', true, 0, true);
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // get the new Y
        $end_y = $this->GetY();
        $end_page = $this->getPage();
        // calculate height
        $height = 0;
        if ($end_page == $start_page) {
            $height = $end_y - $start_y;
        } else {
            for ($page=$start_page; $page <= $end_page; ++$page) {
                $this->setPage($page);
                if ($page == $start_page) {
                    // first page
                    $height = $this->h - $start_y - $this->bMargin;
                } elseif ($page == $end_page) {
                    // last page
                    $height = $end_y - $this->tMargin;
                } else {
                    $height = $this->h - $this->tMargin - $this->bMargin;
                }
            }
        }
        // restore previous object
        $this->rollbackTransaction(true);
        return $height;
    }
}

/* application/libraries/Pdf.php */
