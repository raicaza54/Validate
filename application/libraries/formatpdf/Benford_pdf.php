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

class Benford_pdf extends TCPDF {

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
    private $backGroundColor = array('r' => 246, 'g' => 245, 'b' => 245);
    
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
        $this->CI->load->library('jpgraph/Graph');
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
        $this->MultiCell(NULL, NULL, 'Análisis: Ley de Benford', 0, 'R', FALSE, 1, 50, 5);
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
        $y = 270;
        $w = 204;
        $style = array(
            'color' => $this->lineColor,
            'width' => $this->lineWidth
        );        
        $this->Line(10, $y, 206, $y, $style);
        $this->MultiCell(100, NULL, 'Pág ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 'L', FALSE, 0, NULL, $y+1);
        $this->MultiCell(NULL, NULL, 'Consecutivo: '.$this->codigo, 0, 'R', FALSE, 1, NULL);
        //$this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, FALSE, 'C', 0, '', 0, FALSE, 'T', 'M');
    }

    private function digito($d, $dn) {
        if(is_array($d) && array_key_exists('d'.$dn, $d) && array_key_exists('mad', $d['d'.$dn]) && array_key_exists('madDescribe', $d['d'.$dn])){
            $this->MultiCell(196, NULL, 'DESVIACIÓN ABSOLUTA MEDIA', 0, 'L', FALSE, 1);
            $this->MultiCell(196, NULL, 'EN ESTE CASO NOS DA '.strip_tags($d['d'.$dn]['mad']).' QUE VIÉNDOLO EN LA TABLA ES '.mb_strtoupper(strip_tags($d['d'.$dn]['madDescribe']), 'UTF-8'), 0, 'L', FALSE, 1);
        }
        if(is_array($d) && array_key_exists('d'.$dn, $d) && array_key_exists('mad_d'.$dn, $d['d'.$dn]) && (count($d['d'.$dn]['mad_d'.$dn]) > 0)){
            if($this->GetY() > 260){
                $this->AddPage();
            }            
            $this->Ln(1);
            $this->MultiCell(41, NULL, 'Mínimo', 1, 'C', TRUE, 0);
            $this->MultiCell(41, NULL, 'Máximo', 1, 'C', TRUE, 0);
            $this->MultiCell(114, NULL, 'Descripción', 1, 'C', TRUE, 1);
            foreach ($d['d'.$dn]['mad_d'.$dn] as $filas) {
                $this->MultiCell(41, NULL, $filas['min'], 1, 'R', FALSE, 0);
                $this->MultiCell(41, NULL, (($filas['max'] >= 10000) ? '∞':$filas['max']), 1, 'R', FALSE, 0);
                $this->MultiCell(114, NULL, $filas['descripcion'], 1, 'L', FALSE, 1);
                if($this->GetY() > 260){
                    $this->AddPage();
                }
            }
        }
        if(is_array($d) && array_key_exists('d'.$dn, $d) && array_key_exists('tabla', $d['d'.$dn]) && (count($d['d'.$dn]['tabla']) > 0)){
            $this->Ln(5);
            $this->MultiCell(30, NULL, 'Número', 1, 'C', TRUE, 0);
            $this->MultiCell(41, NULL, 'Frecuencia', 1, 'C', TRUE, 0);
            $this->MultiCell(41, NULL, 'Observado', 1, 'C', TRUE, 0);
            $this->MultiCell(43, NULL, 'Ley de Benford', 1, 'C', TRUE, 0);
            $this->MultiCell(41, NULL, 'Variación', 1, 'C', TRUE, 1);            
            foreach ($d['d'.$dn]['tabla'] as $filas) {
                $this->MultiCell(30, NULL, $filas['numero'], 1, 'R', FALSE, 0);
                $this->MultiCell(41, NULL, $filas['frecuencia'], 1, 'R', FALSE, 0);
                $this->MultiCell(41, NULL, $filas['observado'], 1, 'R', FALSE, 0);
                $this->MultiCell(43, NULL, $filas['benford'], 1, 'R', FALSE, 0);
                $this->MultiCell(41, NULL, $filas['variacion'], 1, 'R', FALSE, 1);            
                if($this->GetY() > 260){
                    $this->AddPage();
                }
            }
            $this->Ln(5);
        }
    }
    
    private function grafica($d, $dn) {
        if(is_array($d) && array_key_exists('d'.$dn, $d) && array_key_exists('grafica', $d['d'.$dn]) && (count($d['d'.$dn]['grafica']) > 0) && array_key_exists('data1', $d['d'.$dn]['grafica'])){
            $ydataBar = [];
            foreach ($d['d'.$dn]['grafica']['data1'] as $value) {
                if(is_numeric($value)){
                    $ydataBar[] = $value;
                }
            }
        }
        if(is_array($d) && array_key_exists('d'.$dn, $d) && array_key_exists('grafica', $d['d'.$dn]) && (count($d['d'.$dn]['grafica']) > 0) && array_key_exists('data2', $d['d'.$dn]['grafica'])){
            $ydataLine = [];
            foreach ($d['d'.$dn]['grafica']['data2'] as $value) {
                if(is_numeric($value)){
                    $ydataLine[] = $value;
                }
            }
        }
        $graph = new Graph(1250, 400);
        $graph->SetScale("textlin");
        $graph->img->SetAntiAliasing(false);
        $graph->img->SetMargin(27, 1, 5, 80);
        $graph->SetBox(false);
        $bplot = new BarPlot($ydataBar);
        $graph->Add($bplot);
        $bplot->SetLegend('Recuento');
        $bplot->SetColor("#1f77b4");
        $bplot->SetFillColor("#1f77b4");
        $lplot = new LinePlot($ydataLine);
        $graph->Add($lplot);
        $lplot->SetBarCenter();
        $lplot->mark->SetType(MARK_FILLEDCIRCLE,'',1.0);
        $lplot->mark->SetColor('#ff7f0e');
        $lplot->mark->SetFillColor('#ff7f0e');        
        $lplot->SetLegend('Ley de Benford');
        $lplot->SetColor("#ff7f0e");
        $fileName = md5($dn.$this->CI->session->userdata('clientes_id').$this->CI->session->userdata('users_id'));
        $filePath = $this->CI->config->item('path_clie').'grafica/'.$fileName.'.png';
        $graph->Stroke($filePath);
        $this->Image($filePath, 10, '', 196, 60, 'PNG', '', 'N', FALSE, 300);
    }
    
    public function run($data, $dataPdf) {
        require_once (APPPATH.'/libraries/jpgraph/jpgraph_bar.php');
        require_once (APPPATH.'/libraries/jpgraph/jpgraph_line.php');
        extract($dataPdf);
        extract($this->backGroundColor);
        try{
            $this->SetFont($this->family, '', 7);
            $this->SetProtection(array('print', 'copy'), '', NULL, 0, NULL);
            $this->SetTitle('Ley de Benford');
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
            $this->MultiCell(67, NULL, 'Consecutivo: '.$this->codigo, TRUE, 'L', FALSE, 0);
            $this->MultiCell(86, NULL, 'Empresa: '.$this->empresa['nombre'], TRUE, 'L', FALSE, 0);
            $this->MultiCell(43, NULL, 'NIT: '.$this->empresa['identificacion'], TRUE, 'L', FALSE, 1);
            $this->Ln(5);
            $this->MultiCell(196, NULL, 'PRIMER DÍGITO', FALSE, 'C', FALSE, 1);
            $this->grafica($d1, '1');
            $this->digito($d1, '1');
            $this->MultiCell(196, NULL, 'SEGUNDO DÍGITO', FALSE, 'C', FALSE, 1);
            $this->grafica($d2, '2');
            $this->digito($d2, '2');
            $this->MultiCell(196, NULL, 'PRIMERO Y SEGUNDO DÍGITO', FALSE, 'C', FALSE, 1);
            $this->grafica($d12, '12');
            $this->digito($d12, '12');
            //$this->Output('archivo.pdf', 'I');
            $filePath = $this->CI->config->item('path_clie').'resultados/BEN'.$this->namePdf.'.pdf';
            $this->Output($filePath, 'F');
        } catch(Exception $ex){
            return FALSE;
        }
        return file_exists($filePath);        
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
