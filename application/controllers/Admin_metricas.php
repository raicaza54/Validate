<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Controlador Metricas
 * @LastUpdate  2020-07-10
 */
class Admin_metricas extends CI_Controller {
    
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
    
    function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth/login');
        }
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->load->model([
            'Metricas_model'
        ]);
    }
    
    public function reporte1() {
        $response = $this->response;
        $data = $row = array();
        try {
            $this->load->library('formatpdf/admin/Reporte1_pdf', array(
                'orientation' => 'P',
                'unit'        => 'mm',
                'format'      => 'LETTER',
                'unicode'     => TRUE,
                'encoding'    => 'UTF-8',
                'diskcache'   => FALSE,
            ), 'pdf');
            $d = date('Y-m-d', strtotime($this->input->post('f1')));
            $h = date('Y-m-d', strtotime($this->input->post('f2')));
            $data = $this->Metricas_model->reporte1($d, $h);
            $response = ["data" => base64_encode($this->pdf->run($data))];
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($response['status'])
            ->set_output(json_encode($response));
    }
    
    public function reporte2() {
        $response = $this->response;
        $data = $row = array();
        try {
            $this->load->library('formatpdf/admin/Reporte2_pdf', array(
                'orientation' => 'P',
                'unit'        => 'mm',
                'format'      => 'LETTER',
                'unicode'     => TRUE,
                'encoding'    => 'UTF-8',
                'diskcache'   => FALSE,
            ), 'pdf');
            $d = date('Y-m-d', strtotime($this->input->post('f1')));
            $h = date('Y-m-d', strtotime($this->input->post('f2')));
            $data = $this->Metricas_model->reporte2($d, $h);
            $response = ["data" => base64_encode($this->pdf->run($data))];
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($response['status'])
            ->set_output(json_encode($response));
    }
    
    private function tryCatch($exc, $response){
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