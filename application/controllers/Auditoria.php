<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Controlador Auditoria
 * @LastUpdate  2019-02-10
 */
class Auditoria extends CI_Controller {

    /**
     * Variable de carga de vista plantilla
     */
    private $data = array('body' => '');
    
    public $response = array(
        "meta"   => array(
            "copyright" => "Verify 2019",
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

    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
        $this->load->library(array(
            'benford',
            'spider',
            'manipulacion'
        ));
        $this->load->model(array(
            'Archivos_model'
        ));
    }

    public function index() {
        $this->load->view("plantilla/plantilla", $this->data);
    }
    
    public function spider() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        set_time_limit(0);
        $response = $this->response;
        $formData = [];
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('form', $post) || (count($post['form']) <= 0)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 204);
            }
            $form = unSerializeArray($post['form']);
            if(!is_array($form) || !array_key_exists('archivoIdProcesar', $form) || !array_key_exists('campoSpider', $form)){
                throw new Exception("Tenemos un problema, faltan algunos datos, estan incompletos o corruptos", 204);
            }
            $items = $this->Archivos_model->getDetalleIdSpider($form['archivoIdProcesar']);
            if(!is_array($items) || (count($items) <= 0)){
                throw new Exception("Tenemos un problema, el archivo no posee filas para analizar", 204);
            }            
            $this->spider->data = $items;
            $spider = $this->spider->procesar($form['campoSpider']);
            $response["data"] = $spider + ['form' => $form];
            throw new Exception("Se ha creado la araña correctamente", 200);            
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));        
    }
    
    public function manipulacion() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        set_time_limit(0);
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('balances', $post) || (count($post['balances']) != 2)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 204);
            }
            $manipulacion = $this->manipulacion->run($post['balances']);
            $response["data"] = $manipulacion;
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));        
    }
    
    public function benford() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        set_time_limit(0);
        $response = $this->response;
        $formData = [];
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('form', $post) || (count($post['form']) <= 0)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 204);
            }
            $form = unSerializeArray($post['form']);
            if(!is_array($form) || !array_key_exists('archivoIdProcesar', $form) || !array_key_exists('digito', $form)){
                throw new Exception("Tenemos un problema, faltan algunos datos, estan incompletos o corruptos", 204);
            }
            $items = $this->Archivos_model->getDetalleIdBenford($form['archivoIdProcesar'], $form['campoAnalizar']);
            if(!is_array($items) || (count($items) <= 0)){
                throw new Exception("Tenemos un problema, el archivo no posee filas para analizar", 204);
            }
            $this->benford->data = $items;
            $tabla = $this->benford->procesar($form['digito']);
            if(is_bool($tabla) || (($tabla['d1'] == FALSE) && ($tabla['d2'] == FALSE) && ($tabla['d12'] == FALSE))){
                throw new Exception("Tenemos un problema, la columna seleccionada no fue posible procesarla", 204);
            }
            $response["data"] = $tabla + ['form' => $form];
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));        
    }
    
    private function tryCatch($exc, $response) {
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
            $response["title"]  = "Error Interno del Servidor";
            $response["detail"] = (strlen($exception["message"]) && !empty($exception["message"])) ? $exception["message"] : "Internal Server Error";
            log_message("error", $exc->getCode() . ' - ' . $exc->getMessage());
        }
        return $response;
    }    

}
