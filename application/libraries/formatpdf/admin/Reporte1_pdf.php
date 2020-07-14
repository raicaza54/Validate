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

class Reporte1_pdf extends TCPDF {

    
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
        $this->CI->load->library('formatpdf/Formato', NULL, 'Formato');
        $this->setCellPaddings(1, 1, 1, 1);
        $this->SetFont($this->family, '', 7);
    }

    //Page header
    public function Header() {
        // Logo
        $image_file = $this->CI->config->item('path_pdf').'logo.jpg';
        $this->Image($image_file, 9, 4.6, 20, '', 'JPG', '', 'T', FALSE, 300, '', FALSE, FALSE, 0, FALSE, FALSE, FALSE);
        // Logo Vertical
        $image_pdf = $this->CI->config->item('path_pdf').'logopdf.jpg';
        $this->Image($image_pdf, 6, 90, 2.5, '', 'JPG', '', 'T', FALSE, 300, '', FALSE, FALSE, 0, FALSE, FALSE, FALSE);
        // Set font
        $this->SetFont($this->family, '', 8);
        // Title
        //$this->MultiCell(NULL, NULL, '    ALIDATE', 0, 'L', FALSE, 0, 10, 5.5);
        $this->SetFont($this->family, '', 8);
        $this->MultiCell(NULL, NULL, 'Reporte: Inicios de Sesión', 0, 'R', FALSE, 1, 50, 5);
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
        $this->MultiCell(NULL, NULL, '', 'T', 'R', FALSE, 1, NULL);
        //$this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, FALSE, 'C', 0, '', 0, FALSE, 'T', 'M');
    }
    
    public function run($data) {
        extract($this->backGroundColor);
        try{
            $this->SetFont($this->family, '', 7);
            $this->SetProtection(array('modify', 'copy'), '');
            $this->SetTitle('Manipulación');
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
            $this->Ln(5);
            
            $this->MultiCell(98, NULL, 'Usuario', TRUE, 'L', FALSE, 0);
            $this->MultiCell(98, NULL, 'Inicios de Sesión', TRUE, 'C', FALSE, 1);
            foreach ($data['usuario'] as $value) {
                $this->MultiCell(98, NULL, $value['nombre'], TRUE, 'L', FALSE, 0);
                $this->MultiCell(98, NULL, $value['cantidad'], TRUE, 'R', FALSE, 1);                
            }
            $this->Ln(5);
            
            $this->MultiCell(98, NULL, 'Día de la Semana', TRUE, 'L', FALSE, 0);
            $this->MultiCell(98, NULL, 'Inicios de Sesión', TRUE, 'C', FALSE, 1);
            $dia = '';
            foreach ($data['dia'] as $value) {
                switch ($value['dia']) {
                    case 'Monday': $dia = 'Lunes'; break;
                    case 'Tuesday': $dia = 'Martes'; break;
                    case 'Wednesday': $dia = 'Miércoles'; break;
                    case 'Thursday': $dia = 'Jueves'; break;
                    case 'Friday': $dia = 'Viernes'; break;
                    case 'Saturday': $dia = 'Sábado'; break;
                    case 'Sunday': $dia = 'Domingo'; break;
                }                
                
                $this->MultiCell(98, NULL, $dia, TRUE, 'L', FALSE, 0);
                $this->MultiCell(98, NULL, $value['cantidad'], TRUE, 'R', FALSE, 1);                
            }
            $this->Ln(5);
            
            $this->MultiCell(98, NULL, 'Hora del día', TRUE, 'L', FALSE, 0);
            $this->MultiCell(98, NULL, 'Inicios de Sesión', TRUE, 'C', FALSE, 1);
            $hora = '';
            foreach ($data['hora'] as $value) {
                switch ($value['hora']) {
                    case 0: $hora = '12 am'; break;
                    case 13: $hora = '1 pm'; break;
                    case 14: $hora = '2 pm'; break;
                    case 15: $hora = '3 pm'; break;
                    case 16: $hora = '4 pm'; break;
                    case 17: $hora = '5 pm'; break;
                    case 18: $hora = '6 pm'; break;
                    case 19: $hora = '7 pm'; break;
                    case 20: $hora = '8 pm'; break;
                    case 21: $hora = '9 pm'; break;
                    case 22: $hora = '10 pm'; break;
                    case 23: $hora = '11 pm'; break;
                    default: $hora = $value['hora'].' am'; break;
                }                
                $this->MultiCell(98, NULL, $hora, TRUE, 'L', FALSE, 0);
                $this->MultiCell(98, NULL, $value['cantidad'], TRUE, 'R', FALSE, 1);                
            }
            $this->Ln(5);
            //$this->Output('archivo.pdf', 'I');
            return $this->Output('Reporte1.pdf', 'S');
        } catch(Exception $ex){
            return FALSE;
        }
    }

}

/* application/libraries/Pdf.php */
