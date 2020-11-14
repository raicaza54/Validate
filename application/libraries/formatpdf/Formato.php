<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Formato {

    private $CI;

    public function __construct() {
        $this->CI = & get_instance();
    }    
    
    function datosCliente($pdf, $cliente, $orientacion = 'V', $encabezado = TRUE) {
        $y = 26;
        if(file_exists($cliente['empr_logotipo'])){
            if($encabezado == FALSE) $y = 15;
            if($orientacion == 'H'){
                $h = $this->height($pdf, 'Por: ' . $cliente['empr_nombre'], 217);
                $pdf->Image($cliente['empr_logotipo'], 240, $y, 15, '', '', '', 'T', FALSE, 300, '', FALSE, FALSE, 0, FALSE, FALSE, FALSE);
                $pdf->MultiCell(217, $h, 'Por: '.$cliente['empr_nombre'], TRUE, 'L', FALSE, 1, '', ($y + 3));
                $pdf->MultiCell(217, '', 'NIT: '.$cliente['empr_identificacion'], TRUE, 'L', FALSE, 1, '');
                $pdf->Ln(3);
            }elseif($orientacion == 'V'){
                $h = $this->height($pdf, 'Por: ' . $cliente['empr_nombre'], 153);
                $pdf->Image($cliente['empr_logotipo'], 180, $y, 15, '', '', '', 'T', FALSE, 300, '', FALSE, FALSE, 0, FALSE, FALSE, FALSE);
                $pdf->MultiCell(153, $h, 'Por: '.$cliente['empr_nombre'], TRUE, 'L', FALSE, 1, '', ($y + 3));
                $pdf->MultiCell(153, '', 'NIT: '.$cliente['empr_identificacion'], TRUE, 'L', FALSE, 1, '');
                $pdf->Ln(3);
            }            
        }
    }
    
    function height($pdf, $txt, $w) {
        // store current object
        $pdf->startTransaction();
        // store starting values
        $start_y = $pdf->GetY();
        $start_page = $pdf->getPage();
        // call your printing functions with your parameters
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        $pdf->MultiCell($w, NULL, $txt, 1, 'L', false, 1, '', '', true, 0, true);
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // get the new Y
        $end_y = $pdf->GetY();
        $end_page = $pdf->getPage();
        // calculate height
        $height = 0;
        if ($end_page == $start_page) {
            $height = $end_y - $start_y;
        } else {
            for ($page = $start_page; $page <= $end_page; ++$page) {
                $pdf->setPage($page);
                if ($page == $start_page) {
                    // first page
                    $height = $pdf->h - $start_y - $pdf->bMargin;
                } elseif ($page == $end_page) {
                    // last page
                    $height = $end_y - $pdf->tMargin;
                } else {
                    $height = $pdf->h - $pdf->tMargin - $pdf->bMargin;
                }
            }
        }
        // restore previous object
        $pdf->rollbackTransaction(true);
        return $height;
    }
    
}
