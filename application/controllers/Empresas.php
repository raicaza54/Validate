<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Empresas
 *
 * @author Kevin Enriquez
 */
class Empresas extends CI_Controller {
  
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
    
    function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()){
            //redirect('auth/login');
        }
        if (!$this->input->is_ajax_request()) {
            //show_404();
        }
    }
    
    public function datos() {
        echo "Hola mundo";
        exit();
        $response = $this->response;
        try {
            if (!is_array($items)) {
                throw new Exception("No existen datos para mostrar como ejemplo", 204);
            }
            $response["data"] = array(
                'list' => 'Hola mundo'
            );            
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $ex) {
            $response["status"] = $exc->getCode();
            $exception = array(
                "code"    => $exc->getCode(),
                "message" => $exc->getMessage(),
            );
            if ($exception["code"] === 200) {
                $response["title"] = "Procedimiento realizado satisfactoriamente";
                $response["detail"] = (strlen($exception["message"]) && !empty($exception["message"])) ? $exception["message"] : "Petición correcta";
            } elseif ($exception["code"] === 202) {
                $response["title"] = "Petición Aceptada pero incompleta";
                $response["detail"] = (strlen($exception["message"]) && !empty($exception["message"])) ? $exception["message"] : "Petición Aceptada pero incompleta";
                log_message("error", $exc->getCode().' - '.$exc->getMessage());
            } elseif ($exception["code"] === 500) {
                $response["title"] = "Error Interno del Servidor";
                $response["detail"] = (strlen($exception["message"]) && !empty($exception["message"])) ? $exception["message"] : "Internal Server Error";
                log_message("error", $exc->getCode().' - '.$exc->getMessage());
            } else {
                $response["title"] = "Error Interno del Servidor";
                $response["detail"] = (strlen($exception["message"]) && !empty($exception["message"])) ? $exception["message"] : "Internal Server Error";
                log_message("error", $exc->getCode().' - '.$exc->getMessage());
            }
        }
        $jsonResponse = json_encode($response);
        ob_start();
        echo $jsonResponse;
        header('Content-Length: ' . ob_get_length());
        header('Content-type: application/x-json;charset=UTF-8');
        ob_flush();        
    }
    
}
