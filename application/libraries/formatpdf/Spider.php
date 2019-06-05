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

class Spider extends TCPDF {

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
    
    function __construct($param) {
        extract($param);
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache);
        $this->empresa = $empresa;
        $this->codigo = $codigo;
        $this->setCellPaddings(1, 1, 1, 1);
        $this->SetFont($this->family, '', 7);
    }

    //Page header
    public function Header() {
        // Logo
        $image_file = img_path() . 'logo.jpg';
        $this->Image($image_file, 9, 4.6, 5, '', 'JPG', '', 'T', FALSE, 300, '', FALSE, FALSE, 0, FALSE, FALSE, FALSE);
        // Logo Vertical
        $image_pdf = img_path() . 'logopdf.jpg';
        $this->Image($image_pdf, 6, 90, 2.5, '', 'JPG', '', 'T', FALSE, 300, '', FALSE, FALSE, 0, FALSE, FALSE, FALSE);
        // Set font
        $this->SetFont($this->family, '', 10);
        // Title
        //$this->Cell(0, 5, 'HOLA MUNDO', 1, FALSE, 'L', 0, '', 0, FALSE, 'M', 'M');
        $this->MultiCell(NULL, NULL, '    Natty Narwhal', 0, 'L', FALSE, 0, NULL, 5);
        $this->MultiCell(NULL, NULL, 'Empresa: '.$this->empresa, 0, 'R', FALSE, 1, 50, 5);
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
        $this->MultiCell(NULL, NULL, 'Reporte: '.$this->codigo, 0, 'R', FALSE, 1, NULL);

        //$this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, FALSE, 'C', 0, '', 0, FALSE, 'T', 'M');
    }

    public function lista_individual($data, $dataPdf) {
        extract($dataPdf);
        extract($this->backGroundColor);
        $this->SetFont($this->family, '', 7);
        $this->SetProtection(array('print', 'copy'), '', NULL, 0, NULL);
        $this->SetTitle('Listas de Sanciones');
        $this->SetLineStyle(array(
            'color' => $this->lineBackColor,
            'width' => $this->lineWidth
        ));
        $this->SetMargins(10, 15, 10);
        $this->SetAutoPageBreak(TRUE, 15);
        $this->SetAuthor('Natty Narwhal');
        $this->SetDisplayMode('real', 'default');
        $this->AddPage();
        $this->SetFillColor($r, $g, $b);
        
        /**
         * Impresion de la cabecera y parametros
         */

        $this->MultiCell(18, NULL, 'Nombre', 1, 'L', TRUE, 0);
        $this->MultiCell(70, NULL, $cabecera['user_nombre'], 1, 'L', FALSE, 0);
        $this->MultiCell(13, NULL, '', 0, 'L', FALSE, 0);
        $this->MultiCell(38, NULL, 'Fecha', 1, 'C', TRUE, 0);
        $this->MultiCell(40, NULL, 'Reporte', 1, 'C', TRUE, 0);
        $this->MultiCell(17, NULL, 'Cantidad', 1, 'C', TRUE, 1);

        $this->MultiCell(18, NULL, 'Usuario', 1, 'L', TRUE, 0);
        $this->MultiCell(70, NULL, $cabecera['user_username'], 1, 'L', FALSE, 0);
        $this->MultiCell(13, NULL, '', 0, 'C', FALSE, 0);
        $this->MultiCell(38, NULL, date('d/m/Y - h:i:sA', strtotime($cabecera['fecha'])), 1, 'C', FALSE, 0);
        $this->MultiCell(40, NULL, $cabecera['ddil_codigo'], 1, 'C', FALSE, 0);
        $this->MultiCell(17, NULL, str_pad($cabecera['result_count'],2,"0",STR_PAD_LEFT), 1, 'C', FALSE, 1);

        $this->Ln(5);
        $this->MultiCell(98, NULL, 'Nombre(s)', 1, 'C', TRUE, 0);
        $this->MultiCell(98, NULL, 'Apellido(s)', 1, 'C', TRUE, 1);
        
        $this->MultiCell(98, NULL, $this->_vacio($cabecera['nombre']), 1, 'L', FALSE, 0,'','',TRUE,0,TRUE);
        $this->MultiCell(98, NULL, $this->_vacio($cabecera['apellido']), 1, 'L', FALSE, 1,'','',TRUE,0,TRUE);
        
        $this->MultiCell(98, NULL, 'Identificación', 1, 'C', TRUE, 0);
        $this->MultiCell(58, NULL, 'Tipo de identificación', 1, 'C', TRUE, 0);
        $this->MultiCell(40, NULL, 'Mínimo Score', 1, 'C', TRUE, 1);
        
        $this->MultiCell(98, NULL, $this->_vacio($cabecera['identificacion']), 1, 'C', FALSE, 0,'','',TRUE,0,TRUE);
        $this->MultiCell(58, NULL, identificacion_tipo($cabecera['identificacion_tipo']), 1, 'C', FALSE, 0);
        $this->MultiCell(40, NULL, $cabecera['nivel'].'%', 1, 'C', FALSE, 1);
        
        /**
         * Impresion de resultados
         */
        $this->Ln(5);
        if(is_array($resultado) && (count($resultado) > 0)){
            $this->MultiCell(43, NULL, 'Nombre', 1, 'C', TRUE, 0);
            $this->MultiCell(40, NULL, 'Alia(s)', 1, 'C', TRUE, 0);
            $this->MultiCell(35, NULL, 'identificación(es)', 1, 'C', TRUE, 0);
            $this->MultiCell(30, NULL, 'Nacionalidad(es)', 1, 'C', TRUE, 0);
            $this->MultiCell(30, NULL, 'Lista', 1, 'C', TRUE, 0);
            $this->MultiCell(18, NULL, 'Score', 1, 'C', TRUE, 1);
            $wth = array(
                'name'                     => 43,
                'aliases_name'             => 40,
                'indetifiers_number'       => 35,
                'indetifiers_country_name' => 30,
                'lista_id'                 => 30,
                'similar'                  => 18,
            );
            $resultado = array_column_multi($resultado, array_keys($wth));
            foreach ($resultado as $value) {
                $hgWth = array();
                foreach ($wth as $keyWth => $columWth){
                    $hgWth[] = $this->_height(array(
                        'txt' => $this->_highlight($value[$keyWth]),
                        'w' => $columWth
                    ));
                }
                $hg = max($hgWth);
                unset($hgWth);
                $this->MultiCell(43, $hg, $this->_highlight($value['name']), 1, 'L', FALSE, 0, '', '', TRUE, 0, TRUE);
                $this->MultiCell(40, $hg, $this->_highlight($value['aliases_name']), 1, 'L', FALSE, 0, '', '', TRUE, 0, TRUE);
                $this->MultiCell(35, $hg, $this->_highlight($value['indetifiers_number']), 1, 'L', FALSE, 0, '', '', TRUE, 0, TRUE);
                $this->MultiCell(30, $hg, $this->_highlight($value['indetifiers_country_name']), 1, 'L', FALSE, 0, '', '', TRUE, 0, TRUE);
                $this->MultiCell(30, $hg, $this->_highlight($value['lista_id']), 1, 'L', FALSE, 0, '', '', TRUE, 0, TRUE);
                $this->MultiCell(18, $hg, $this->_highlight($value['similar']), 1, 'L', FALSE, 1, '', '', TRUE, 0, TRUE);
                if($this->GetY() > 252){
                    $this->AddPage();
                }
            }
        }else{
            $this->MultiCell(196, NULL, 'La consulta no trajo resultados, Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'."\n", 1, 'J', FALSE, 1);
        }

        $this->Ln(5);
        // set style for barcode
        $style = array(
            'border' => 0,
            'vpadding' => 2,
            'hpadding' => 2,
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $yQR = $this->GetY();
        // QRCODE,L : QR-CODE Low error correction
        $this->write2DBarcode('nattynarwhal.com.co', 'QRCODE,L', 186, $yQR, 20, 20, $style, 'N');
        $this->setCellPaddings(1, 1, 10, 1);
        $this->MultiCell(NULL, 20, "Con este codigo se puede comprobar si este documento es original <a href='http://nattynarwhal.com.co' target='_blank'>http://nattynarwhal.com.co</a>, se debe tener una cuenta registrada Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat", 1, 'J', FALSE, 1, '', $yQR,TRUE, 0, TRUE);
        $this->setCellPaddings(1, 1, 1, 1);
        /**
         * Impresion de las listas consultadas
         */
        $this->Ln(5);
        $this->SetFont($this->family, '', 5);
        if(isSerialized($cabecera['parametros'])){
            $parametros = unserialize($cabecera['parametros']);
            if(is_array($parametros) && (count($parametros) > 0)){
                foreach ($parametros as $key => $value) {
                    $h = $this->_height(array(
                        'txt' => $value['descripcion'],
                        'w' => 161
                    ));
                    $this->MultiCell(35, $h, $value['nombre'], 1, 'L', TRUE, 0);
                    $this->MultiCell(161, $h, $value['descripcion'], 1, 'L', FALSE, 1);
                }
            }
        }
        $this->MultiCell(NULL, NULL, 'A continuación se describen algunos términos A: Alias, N: Nombre, I: Identificación', 1, 'L', FALSE, 1);
        $this->SetFont($this->family, '', 7);
        $this->Output('archivo.pdf', 'I');
    }

    private function _vacio($cadena){
        $r = $cadena;
        if(trim(strlen($cadena)) <= 0){
            $r = '<span style="text-align: center;">**Vacio**</span>';
        }
        return $r;
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
    
    private function _highlight($param) {
        $r = '';
        if(isSerialized($param)){
            $e = unserialize($param);
            if(count($e) > 0){
                foreach ($e as $value) {
                    $r .= $this->_high($value).br();
                }
            }
        }else{
            $r = $this->_high($param);
        }
        $r = preg_replace('/(<br \/>)+$/', '', $r);
        return $r;
    }
    
    private function _high($e) {
        return str_replace(
                array(
                    '<##~',
                    '~##>'
                ),
                array(
                    '<span style="font-weight: 300; background-color: #C4C4C4; padding: 1px 0px;">',
                    '</span>'
                ),$e);        
    }

}

/* application/libraries/Pdf.php */
