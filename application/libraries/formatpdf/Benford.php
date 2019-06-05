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

class Benford extends TCPDF {

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
        $this->SetFont($this->family, '', 10);
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
            $this->Ln(5);
            $this->MultiCell(196, NULL, 'DESVIACIÓN ABSOLUTA MEDIA', 0, 'L', FALSE, 1);
            $this->MultiCell(196, NULL, 'EN ESTE CASO NOS DA '.strip_tags($d['d'.$dn]['mad']).' QUE VIÉNDOLO EN LA TABLA ES '.mb_strtoupper(strip_tags($d['d'.$dn]['madDescribe']), 'UTF-8'), 0, 'L', FALSE, 1);
        }
        if(is_array($d) && array_key_exists('d'.$dn, $d) && array_key_exists('mad_d'.$dn, $d['d'.$dn]) && (count($d['d'.$dn]['mad_d'.$dn]) > 0)){
            $this->Ln(1);
            $this->MultiCell(41, NULL, 'Mínimo', 1, 'C', TRUE, 0);
            $this->MultiCell(41, NULL, 'Máximo', 1, 'C', TRUE, 0);
            $this->MultiCell(114, NULL, 'Descripción', 1, 'C', TRUE, 1);
            foreach ($d['d'.$dn]['mad_d'.$dn] as $filas) {
                $this->MultiCell(41, NULL, $filas['min'], 1, 'R', FALSE, 0);
                $this->MultiCell(41, NULL, (($filas['max'] >= 10000) ? '∞':$filas['max']), 1, 'R', FALSE, 0);
                $this->MultiCell(114, NULL, $filas['descripcion'], 1, 'L', FALSE, 1);
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
            }            
        }        
    }
    
    public function run($data, $dataPdf) {
        require_once (APPPATH.'/libraries/jpgraph/jpgraph_bar.php');
        extract($dataPdf);
        extract($this->backGroundColor);
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

        $this->MultiCell(67, NULL, 'Autor: Kevin G. Enriquez C.', TRUE, 'L', FALSE, 0);
        $this->MultiCell(43, NULL, 'Fecha: '.date('d/m/Y'), TRUE, 'L', FALSE, 0);
        $this->MultiCell(43, NULL, 'Hora: '.date('h:i:s A'), TRUE, 'L', FALSE, 0);
        $this->MultiCell(43, NULL, 'IP: 180.180.200.125', TRUE, 'L', FALSE, 1);
        
        $this->MultiCell(67, NULL, 'Consecutivo: 00012354', TRUE, 'L', FALSE, 0);
        $this->MultiCell(86, NULL, 'Empresa: AVICOLA S.A.', TRUE, 'L', FALSE, 0);
        $this->MultiCell(43, NULL, 'NIT: 3184568787', TRUE, 'L', FALSE, 1);
        
        // Example data (04/2015)
        $json = '[{"Hogwarts Academy":{"Yield":"19021 kWh","Yield specific":"127.01 kWh\/kWp","Target yield":"16069.23 kWh","Current-target yield %":"<span style=\"color: #3ab121\">118.37 %<span>"}},{"cols": [{"id":"","label":"Time","pattern":"","type":"string"},{"id":"","label":"Hogwarts Academy (AC)","pattern":"","type":"number"},{"id":"","label":"Target values","pattern":"","type":"number"}], "rows": [{"c":[{"v":"01/04","f":null}, {"v":615.8,"f":"615,80 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"02/04","f":null}, {"v":712.5,"f":"712,50 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"03/04","f":null}, {"v":171,"f":"171,00 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"04/04","f":null}, {"v":382.3,"f":"382,30 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"05/04","f":null}, {"v":606.3,"f":"606,30 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"06/04","f":null}, {"v":774.5,"f":"774,50 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"07/04","f":null}, {"v":570.6,"f":"570,60 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"08/04","f":null}, {"v":726.8,"f":"726,80 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"09/04","f":null}, {"v":789.2,"f":"789,20 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"10/04","f":null}, {"v":592.9,"f":"592,90 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"11/04","f":null}, {"v":677.1,"f":"677,10 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"12/04","f":null}, {"v":244.5,"f":"244,50 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"13/04","f":null}, {"v":457.4,"f":"457,40 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"14/04","f":null}, {"v":340.8,"f":"340,80 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"15/04","f":null}, {"v":425.3,"f":"425,30 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"16/04","f":null}, {"v":828.8,"f":"828,80 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"17/04","f":null}, {"v":616.8,"f":"616,80 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"18/04","f":null}, {"v":660.3,"f":"660,30 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"19/04","f":null}, {"v":453.2,"f":"453,20 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"20/04","f":null}, {"v":691.9,"f":"691,90 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"21/04","f":null}, {"v":904.4,"f":"904,40 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"22/04","f":null}, {"v":879.1,"f":"879,10 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"23/04","f":null}, {"v":824.8,"f":"824,80 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"24/04","f":null}, {"v":777.9,"f":"777,90 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"25/04","f":null}, {"v":413.8,"f":"413,80 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"26/04","f":null}, {"v":834.8,"f":"834,80 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"27/04","f":null}, {"v":920.8,"f":"920,80 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"28/04","f":null}, {"v":751,"f":"751,00 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"29/04","f":null}, {"v":737.7,"f":"737,70 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]},{"c":[{"v":"30/04","f":null}, {"v":638.7,"f":"638,70 kWh"}, {"v":535.640966432,"f":"535,64 kWh"}]}]}]';
        // Turn string into object
        $obj = json_decode($json);
        // Stores for graph data
        $xdata = array();
        $ydata = array();
        // Get coords data from object
        $obj_data = $obj[1]->rows;
        $counter = 1;
        // Add it to each of our storage arrays
        foreach ($obj_data as $data) {
            // only plot when there is a kW value
            if (isset($data->c[1]->v)) {
                $xdata[] = $data->c[0]->v; // date
                $ydata[] = $data->c[1]->v; // kw
            }
        }
        // Create the graph. 
        // One minute timeout for the cached image
        // INLINE_NO means don't stream it back to the browser.
        $graph = new Graph(1200, 350, 'auto');
        $graph->SetScale("textlin");
        $graph->img->SetMargin(60, 30, 20, 40);
        $graph->yaxis->SetTitleMargin(45);
        $graph->yaxis->scale->SetGrace(30);
        $graph->SetShadow();
        // Turn the tickmarks
        $graph->xaxis->SetTickSide(SIDE_DOWN);
        $graph->yaxis->SetTickSide(SIDE_LEFT);
        // Create a bar pot
        $bplot = new BarPlot($ydata);
        $bplot->SetFillColor("orange");
        // Use a shadow on the bar graphs (just use the default settings)
        $bplot->SetShadow();
        $bplot->value->SetFormat(" %2.1f kW", 70);
        $bplot->value->SetFont(FF_VERDANA, FS_NORMAL, 8);
        $bplot->value->SetColor("blue");
        $bplot->value->Show();
        $graph->Add($bplot);
        $graph->title->Set("Hogwarts Academy");
        $graph->xaxis->title->Set("Day");
        $graph->yaxis->title->Set("Yield in kilowatt hours");
        $graph->title->SetFont(FF_FONT1, FS_BOLD);
        $graph->yaxis->title->SetFont(FF_FONT1, FS_BOLD);
        $graph->xaxis->title->SetFont(FF_FONT1, FS_BOLD);
        
        // Send back the HTML page which will call this script again
        // to retrieve the image.
        $fileName = $this->CI->config->item('path_file').'/cli'.$this->CI->session->userdata('clientes_id').'/grafica/imagefile.png';
        $graph->Stroke($fileName);
        $this->Ln(5);
        $this->Image($fileName, 10, NULL, 196, NULL, 'PNG', '', 'N', FALSE, 300, '', FALSE, FALSE, 0, FALSE, FALSE, FALSE);
        $this->digito($d1, '1');
        $this->digito($d2, '2');
        $this->digito($d12, '12');
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
