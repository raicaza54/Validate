<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Controlador Archivos XLS/CSV
 * @LastUpdate  2019-02-14
 */
class Archivos extends CI_Controller {

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
        "data"   => array(),
        "csrf"   => ''
    );

    function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->load->model('Archivos_model');
    }

    public function datos() {
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('id', $post)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 204);
            }
            $items = $this->Archivos_model->getDetalleId($post['id']);
            if (!is_array($items)) {
                throw new Exception("No existen datos para mostrar", 204);
            }
            $response["data"] = $items;
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $response['csrf'] = $this->security->get_csrf_hash();
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    }
    
    public function encabezado() {
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('id', $post)){
                throw new Exception("Tenemos un problema, no encontramos el detalle del archivo seleccionado", 204);
            }            
            $items = $this->Archivos_model->getEncabezado($post['id']);
            if (!is_array($items)) {
                throw new Exception("No existen datos para mostrar", 204);
            }
            $response["data"] = $items;
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $response['csrf'] = $this->security->get_csrf_hash();
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    }
    
    public function digito() {
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('id', $post) || !array_key_exists('digito', $post)){
                throw new Exception("Tenemos un problema, no encontramos el detalle del archivo seleccionado", 204);
            }            
            $items = $this->Archivos_model->getDetalleId($post['id'], $post['digito']);
            if (!is_array($items)) {
                throw new Exception("No existen datos para mostrar", 204);
            }
            $response["data"] = $items;
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $response['csrf'] = $this->security->get_csrf_hash();
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
