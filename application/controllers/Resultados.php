<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Controlador Explorador de Resultados
 * @LastUpdate  2019-05-01
 */
class Resultados extends CI_Controller {

    public $response = array(
        "meta"   => array(
            "copyright" => "Validate 2019",
            "authors"   => array(
                "Kevin Enriquez",
            )
        ),
        "status" => "422",
        "source" => array(
            "pointer" => ""
        ),
        "title"  => "Invalid Attribute",
        "detail" => "",
        "data"   => array()
    );

    private $empresa;
    
    function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
        $this->load->model([
            'Resultados_model',
            'Archivos_model',
            'Analisis_model',
            'Empresas_model',
        ]);
    }
   
    private function nn() {
        $fp = fopen(APPPATH.'logs/distr_norm_estand_'.date('YmdHis').'.csv', 'w');
        $k = 0.01;
        $i = 0;
        for ($x = -5; $x <= 4; $x++) {
            for ($y = 0; $y <= 1000; $y++) {
                if($x < 4.10 && $x >= -4.10){
                    $i++;
                    $a = numberFormat($x, 2);
                    $b = distr_norm_estand($x);
                    $linea = [$a, $b];                    
                    echo 'score: '.$a.' / '.$b;
                    fputcsv($fp, $linea, ';', '"');
                    echo "\n";
                }
                $x += $k;
            }
        }
        fclose($fp);
    }
    
    private function crear() {
        if (!$this->input->is_ajax_request()) show_404();
        if(!$this->ion_auth->in_group([1,2])){
            return FALSE;
        }        
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('label', $post) || !array_key_exists('parent_id', $post) || !array_key_exists('type', $post) || !array_key_exists('id', $post)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }
            $insert = $this->Resultados_model->crear([
                'label'       => $post['label'],
                'empresaId'   => $this->session->userdata('empresaId'),
                'parent_id'   => $post['parent_id'],
                'type'        => $post['type'],
                'id'          => $post['id']
            ]);
            if($insert === FALSE){
                throw new Exception("Tenemos un problema, no fue posible crear carpeta", 202);
            }
            $response["data"] = [
                'id'      => $insert,
                'label'   => $post['label'],
                'file'    => $post['id'],
            ];
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($response['status'])
            ->set_output(json_encode($response));
    }
    
    private function editar() {
        if (!$this->input->is_ajax_request()) show_404();
        if(!$this->ion_auth->in_group([1,2])){
            return FALSE;
        }        
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('label', $post) || !array_key_exists('id', $post) || !array_key_exists('parent_id', $post) || !array_key_exists('deleted_at', $post)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }
            $insert = $this->Resultados_model->editar([
                'label'      => $post['label'],
                'id'         => $post['id'],
                'parent_id'  => $post['parent_id'],
                'deleted_at' => $post['deleted_at'],
            ]);
            if($insert === FALSE){
                throw new Exception("Tenemos un problema, no fue posible crear carpeta", 202);
            }            
            $response["data"] = [];
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($response['status'])
            ->set_output(json_encode($response));
    }
    
    public function url($file, $dwl = 'r') {
        $file = xss_clean($file);
        $file = strip_tags($file);
        if(!file_exists($this->config->item('path_resultados').$file.'.pdf') || (strlen($file) > 50)){
            show_error("Archivo no encontrado", 404);
        }
        if(!in_array($dwl,['r', 'd'])){
            show_error("Archivo no encontrado", 404);
        }
        $pdfResultado = $this->Resultados_model->getFileId(substr($file,3));
        $filename = 'Resultado.pdf';
        if(is_array($pdfResultado) && count($pdfResultado) && array_key_exists('label', $pdfResultado) && strlen($pdfResultado['label'])){
            $filename = $pdfResultado['label'];
        }
        if($dwl == 'r'){
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="'.$filename.'"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');        
            readfile($this->config->item('path_resultados').$file.".pdf");            
        }elseif($dwl == 'd'){
            download($this->config->item('path_resultados').$file.'.pdf', $filename);
        }
    }
    
    private function descargar() {
        if (!$this->input->is_ajax_request()) show_404();
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('id', $post)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }
            $pdfResultado = $this->Resultados_model->getFileId($post['id']);
            if (!is_array($pdfResultado) && (count($pdfResultado) <= 0)) {
                throw new Exception("Tenemos un problema con los datos, el archivo para guardar no esta disponible", 202);
            }
            $tipo = $pdfResultado['type']; $pref = '';
            if ($tipo == 'benford') {
                $pref = 'BEN';
            }elseif ($tipo == 'spider') {
                $pref = 'SPI';
            }elseif ($tipo == 'manipulacion') {
                $pref = 'MAN';
            }elseif ($tipo == 'listascontrol') {
                $pref = 'LSC';
            }elseif ($tipo == 'condicioncuenta') {
                $pref = 'CCU';
            }
            if(!file_exists($this->config->item('path_resultados').$pref.$post['id'].'.pdf')){
                throw new Exception("Tenemos un problema interno, el archivo no puede ser localizado, contacte con soporte", 202);
            }
            $response["data"] = ['url' => base_url('resultados/v1/url/'.$pref.$post['id'])];
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($response['status'])
            ->set_output(json_encode($response));
    }
    
    public function pdf() {
        if (!$this->input->is_ajax_request()) show_404();
        $response = $this->response;
        try {
            $post = $this->input->post();
            $this->form_validation->set_data($post);
            $this->form_validation->set_rules('nombre',             'Nombre de Archivo',         'required|max_length[150]');
            $this->form_validation->set_rules('idPdf',              'Código PDF',                'required|max_length[50]');
            $this->form_validation->set_rules('carpeta',            'Destino',                   'required|max_length[50]');
            $this->form_validation->set_rules('archivoId',          'Código Archivo',            'required|max_length[50]');
            $this->form_validation->set_rules('pdfBenford',         'Archivo Benford',           'max_length[50]');
            $this->form_validation->set_rules('pdfSpider',          'Archivo Spider',            'max_length[50]');
            $this->form_validation->set_rules('pdfManipulacion',    'Archivo Manipulacion',      'max_length[50]');
            $this->form_validation->set_rules('pdflistasControl',   'Archivo Listas de Control', 'max_length[50]');
            $this->form_validation->set_rules('pdfcondicionCuenta', 'Archivo Listas de Control', 'max_length[50]');
            if($this->form_validation->run() === FALSE){
                throw new Exception(validation_errors('',''), 202);
            }            
            $pdf = ''; $pref = '';
            if(array_key_exists('pdfBenford', $post) && (strlen($post['pdfBenford']) > 5)){
                $pdf = 'pdfBenford';
                $pref = 'BEN';
            } elseif (array_key_exists('pdfSpider', $post) && (strlen($post['pdfSpider']) > 5)) {
                $pdf = 'pdfSpider';
                $pref = 'SPI';
            } elseif (array_key_exists('pdfManipulacion', $post) && (strlen($post['pdfManipulacion']) > 5)) {
                $pdf = 'pdfManipulacion';
                $pref = 'MAN';
            } elseif (array_key_exists('pdflistasControl', $post) && (strlen($post['pdflistasControl']) > 5)) {
                $pdf = 'pdflistasControl';
                $pref = 'LSC';
            } elseif (array_key_exists('pdfcondicionCuenta', $post) && (strlen($post['pdfcondicionCuenta']) > 5)) {
                $pdf = 'pdfcondicionCuenta';
                $pref = 'CCU';
            }
            if(empty($pdf)){
                throw new Exception("Tenemos un problema con los datos, el archivo para guardar no esta disponible", 202);
            }
            $idAnalisis = trim($post[$pdf]);
            $pdfAnalisis = $this->Analisis_model->getData($idAnalisis);
            if (!is_array($pdfAnalisis) && (count($pdfAnalisis) <= 0)) {
                throw new Exception("Tenemos un problema con los datos, el archivo para guardar no esta disponible", 202);
            }
            $tipo = $pdfAnalisis[0]['analisis_tipo'];
            if (($tipo == 'benford') && (strlen($post['pdfBenford']) <= 0)) {
                throw new Exception("Tenemos un problema con los datos Ley de Benford, no corresponden los tipos definidos", 202);
            }
            if (($tipo == 'spider') && (strlen($post['pdfSpider']) <= 0)) {
                throw new Exception("Tenemos un problema con los datos La Araña, no corresponden los tipos definidos", 202);
            }
            if (($tipo == 'manipulacion') && (strlen($post['pdfManipulacion']) <= 0)) {
                throw new Exception("Tenemos un problema con los datos Manipulación, no corresponden los tipos definidos", 202);
            }
            if (($tipo == 'listascontrol') && (strlen($post['pdflistasControl']) <= 0)) {
                throw new Exception("Tenemos un problema con los datos de Listas de Control, no corresponden los tipos definidos", 202);
            }
            if (($tipo == 'pdfcondicionCuenta') && (strlen($post['pdfcondicionCuenta']) <= 0)) {
                throw new Exception("Tenemos un problema con los datos de Condición de Cuenta, no corresponden los tipos definidos", 202);
            }
            $consecutivo = $this->Resultados_model->get_consecutivo($this->session->userdata('clientes_id'), $idAnalisis);
            if(is_bool($consecutivo) && $consecutivo === FALSE){
                $consecutivo = $this->Resultados_model->get_consecutivo($this->session->userdata('clientes_id'), $idAnalisis);
            }
            if(is_bool($consecutivo) && $consecutivo === FALSE){
                throw new Exception("Tenemos un problema con la generación del reporte, intente nuevamente", 202);
            }            
            $this->empresa =  $this->Empresas_model->getId($this->session->userdata('empresaId'));
            $rPdf = $this->{$tipo.'Pdf'}($pdfAnalisis, $post['idPdf'], $consecutivo);
            if($rPdf == FALSE){
                throw new Exception("Tenemos un problema, el archivo no pudo ser creado", 202);
            }
            $insert = $this->Resultados_model->crear([
                'label'       => $post['nombre'],
                'archivos_id' => $post['archivoId'],
                'empresaId'   => $this->session->userdata('empresaId'),
                'parent_id'   => $post['carpeta'],
                'type'        => $tipo,
                'fk_analisis' => $idAnalisis,
                'id'          => $post['idPdf']
            ]);
            if($insert == FALSE){
                throw new Exception("Tenemos un problema, no fue posible crear el archivo PDF", 202);
            }
            $update = $this->Analisis_model->setUpdatePdf(
                [
                    'pdf'        => 1,
                    'update_pdf' => date('Y-m-d H:i:s'),
                ],
                $idAnalisis
            );
            $response["data"] = ['url' => base_url('resultados/v1/url/'.$pref.$post['idPdf'])];
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($response['status'])
            ->set_output(json_encode($response));        
    }
    
    private function benfordPdf($analisis, $idPdf, $consecutivo) {
        if (!$this->input->is_ajax_request()) show_404();
        if (count($analisis) != 3) {
            return FALSE;
        }
        $e = []; $d1 = []; $d2 = []; $d12 = [];
        foreach ($analisis as $graficas) {
            $e = unserialize($graficas['analisis']);
            if(array_key_exists('d1', $e) && is_array($e['d1'])){
                $d1 = $e;
            }
            if(array_key_exists('d2', $e) && is_array($e['d2'])){
                $d2 = $e;
            }
            if(array_key_exists('d12', $e) && is_array($e['d12'])){
                $d12 = $e;
            }
        }
        $this->load->library('formatpdf/Benford_pdf', array(
            'orientation' => 'P',
            'unit'        => 'mm',
            'format'      => 'LETTER',
            'unicode'     => TRUE,
            'encoding'    => 'UTF-8',
            'diskcache'   => FALSE,
            'empresa'     => $this->empresa,
            'codigo'      => $consecutivo,
            'namePdf'     => $idPdf,
        ), 'pdf');
        $data = [];
        if (!(is_array($d1) && (count($d1) > 0)) || !(is_array($d2) && (count($d2) > 0)) || !(is_array($d12) && (count($d12) > 0))) {
            return FALSE;
        }
        $dataPdf = [
            'd1'  => $d1,
            'd2'  => $d2,
            'd12' => $d12
        ];
        return $this->pdf->run($data, $dataPdf);
    }
    
    private function manipulacionPdf($analisis, $idPdf, $consecutivo) {
        if (!$this->input->is_ajax_request()) show_404();
        $e = []; $pdf = [];
        foreach ($analisis as $manipulacion) {
            $pdf = unserialize($manipulacion['analisis']);
        }        
        $this->load->library('formatpdf/Manipulacion_pdf', array(
            'orientation' => 'P',
            'unit'        => 'mm',
            'format'      => 'LETTER',
            'unicode'     => TRUE,
            'encoding'    => 'UTF-8',
            'diskcache'   => FALSE,
            'empresa'     => $this->empresa,
            'codigo'      => $consecutivo,
            'namePdf'     => $idPdf,
        ), 'pdf');
        $data = [];
        $dataPdf = [$pdf];
        return $this->pdf->run($data, $dataPdf);
    }
    
    private function condicioncuentaPdf($analisis, $idPdf, $consecutivo) {
        if (!$this->input->is_ajax_request()) show_404();
        $e = []; $pdf = [];
        foreach ($analisis as $condicioncuenta) {
            $pdf = json_decode($condicioncuenta['analisis'], TRUE);
        }
        $this->load->library('formatpdf/Condicioncuenta_pdf', array(
            'orientation' => 'L',
            'unit'        => 'mm',
            'format'      => 'LETTER',
            'unicode'     => TRUE,
            'encoding'    => 'UTF-8',
            'diskcache'   => FALSE,
            'empresa'     => $this->empresa,
            'codigo'      => $consecutivo,
            'namePdf'     => $idPdf,
        ), 'pdf');
        $data = [];
        $dataPdf = $pdf;
        return $this->pdf->run($data, $dataPdf);
    }
    
    private function listascontrolPdf($analisis, $idPdf, $consecutivo) {
        if (!$this->input->is_ajax_request()) show_404();
        $e = []; $pdf = [];
        foreach ($analisis as $listascontrol) {
            $pdf = unserialize($listascontrol['analisis']);
        }
        $this->load->library('formatpdf/Listascontrol_pdf', array(
            'orientation' => 'P',
            'unit'        => 'mm',
            'format'      => 'LETTER',
            'unicode'     => TRUE,
            'encoding'    => 'UTF-8',
            'diskcache'   => FALSE,
            'empresa'     => $this->empresa,
            'codigo'      => $consecutivo,
            'namePdf'     => $idPdf,
        ), 'pdf');
        $data = [];
        $dataPdf = [$pdf];
        return $this->pdf->run($data, $dataPdf);
    }
    
    private function spiderPdf($analisis, $idPdf, $consecutivo) {
        if (!$this->input->is_ajax_request()) show_404();
        $e = []; $pdf = [];
        foreach ($analisis as $spider) {
            $pdf = unserialize($spider['analisis']);
        }

        $alto = 279.000; $a = 0;
        if(is_array($pdf) && array_key_exists('alto', $pdf)){
            $a = ($pdf['alto'] * 279) / 35;
            if($a > $alto){
                $alto = number_format($a, 3, '.','');
            }
        }
        $this->load->library('formatpdf/Spider_pdf', array(
            'orientation' => 'P',
            'unit'        => 'mm',
            'format'      => array(216.000, $alto),
            'unicode'     => TRUE,
            'encoding'    => 'UTF-8',
            'diskcache'   => FALSE,
            'empresa'     => $this->empresa,
            'codigo'      => $consecutivo,
            'namePdf'     => $idPdf,
        ), 'pdf');
        $data = [];
        $dataPdf = [$pdf];
        return $this->pdf->run($data, $dataPdf);
    }
    
    public function carpetas() {
        if (!$this->input->is_ajax_request()) show_404();
        $response = $this->response;
        try {
            $post = $this->input->post();
            
            if(!is_array($post) || !array_key_exists('id', $post)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }            
            $items = $this->arbolr->run($post['id'], $post['id_archivo']);
            if (!is_array($items)) {
                throw new Exception("No existen datos para mostrar", 202);
            }
            $response["data"] = $items;
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($response['status'])
            ->set_output(json_encode($response));
    }

    private function tryCatch($exc, $response) {
        if (!$this->input->is_ajax_request()) show_404();
        $response["status"] = $exc->getCode();
        $exception          = array(
            "code"    => $exc->getCode(),
            "message" => $exc->getMessage(),
        );
        if ($exception["code"] === 200) {
            $response["title"]  = "Procedimiento realizado satisfactoriamente";
            $response["detail"] = (strlen($exception["message"]) && !empty($exception["message"])) ? $exception["message"] : "Petición correcta";
        } elseif ($exception["code"] === 202) {
            $response["title"]  = "Petición Aceptada pero incompleta";
            $response["detail"] = (strlen($exception["message"]) && !empty($exception["message"])) ? $exception["message"] : "Petición Aceptada pero incompleta";
            log_message("error", $exc->getCode() . ' - ' . $exc->getMessage());
        } elseif ($exception["code"] === 500) {
            $response["title"]  = "Error Interno del Servidor";
            $response["detail"] = (strlen($exception["message"]) && !empty($exception["message"])) ? $exception["message"] : "Internal Server Error";
            log_message("error", $exc->getCode() . ' - ' . $exc->getMessage());
        } else {
            $response["title"]  = "Error del cliente";
            $response["detail"] = (strlen($exception["message"]) && !empty($exception["message"])) ? $exception["message"] : "Internal Server Error";
            log_message("error", $exc->getCode() . ' - ' . $exc->getMessage());
        }
        return $response;
    }

}
