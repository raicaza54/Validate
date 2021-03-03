<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Materialidad
 * @LastUpdate  2021-02-23
 */
class Materialidad extends CI_Controller {
    
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
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->load->model([
            'Cliente_model',
            'Perfil_model',
            'Empresas_model'
        ]);
    }
    
    public function guardar() {
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('form', $post) || (count($post['form']) <= 0)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }
            $form = unSerializeArray($post['form']);
            $form['utladi'] = number_mysql($form['utladi']);
            $form['utlope'] = number_mysql($form['utlope']);
            $form['utlbru'] = number_mysql($form['utlbru']);
            $form['ingope'] = number_mysql($form['ingope']);
            $form['activo'] = number_mysql($form['activo']);
            $form['patrim'] = number_mysql($form['patrim']);
            $materialidad = ['materialidad' => serialize($form)];
            $this->Empresas_model->configEmpresa($materialidad ,$this->session->userdata('empresaId'));
            $response = ['data' => []];
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($response['status'])
            ->set_output(json_encode($response));
    }
    
    public function procesar() {
        $response = $this->response;
        $data = $row = array();
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('form', $post) || (count($post['form']) <= 0)){
                throw new Exception('Algo no anda bien, los datos no son adecuados, intentelo nuevamente o contacte con soporte técnico', 202);
            }
            $data = unSerializeArray($post['form']);
            $id = uniqint();
            $data['utladi'] = number_mysql($data['utladi']);
            $data['utlope'] = number_mysql($data['utlope']);
            $data['utlbru'] = number_mysql($data['utlbru']);
            $data['ingope'] = number_mysql($data['ingope']);
            $data['activo'] = number_mysql($data['activo']);
            $data['patrim'] = number_mysql($data['patrim']);            
            $this->Analisis_model->setInsert([
                'id'            => $id,
                'analisis'      => serialize($data),
                'ejecucion'     => $data['ejecucion'],
                'analisis_tipo' => 'materialidad'
            ]);
            $response = ["data" => 1];
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