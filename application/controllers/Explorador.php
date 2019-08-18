<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Controlador Explorador de carpetas
 * @LastUpdate  2019-02-14
 */
class Explorador extends CI_Controller {

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
            'Explorador_model',
            'Archivos_model',
        ]);
    }
    
    /**
     * Archivos en directorio para realizar comparacion 
     * de balances en Manipulacion
     */
    public function balances() {
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('folderId', $post) || !is_numeric($post['folderId'])){
                throw new Exception("Debes seleccionar una empresa y un directorio que contengan Balances de Prueba para comprobar Manipulación", 202);
            }
            $balances = $this->Archivos_model->getBalenaces($post['folderId']);
            if($balances === FALSE){
                throw new Exception("Tenemos un problema, no pudimos recuperar los balances", 202);
            }            
            if(is_array($balances) && (count($balances) <= 0)){
                throw new Exception("Debes elegir un directorio donde se encuentren Balances de Prueba, intentalo nuevamente", 202);
            }            
            $response["data"] = [
                'balances' => $balances
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
    
    public function crear() {
        if(!$this->ion_auth->in_group([1,2])){
            return FALSE;
        }        
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(is_array($post)){
                if(array_key_exists('parent_id', $post)){
                    if($post['parent_id'] === '#'){
                       $post['parent_id'] = 0; 
                    }
                }
            }
            $this->form_validation->set_data($post);
            $this->form_validation->set_rules('id',          'Id',      'required|numeric|max_length[20]');
            $this->form_validation->set_rules('label',       'Nombre',  'required|regex_match[/^[\w\d\s.\-áéíñóúüÁÉÍÑÓÚÜ]*$/]|max_length[250]');
            $this->form_validation->set_rules('parent_id',   'Carpeta Padre', 'required|numeric|max_length[20]');
            $this->form_validation->set_rules('type',        'Tipo',    'required|max_length[10]|in_list[csv,default,excel,folder]');
            $this->form_validation->set_rules('archivos_id', 'Archivo', 'required|numeric|max_length[20]');
            $this->form_validation->set_rules('disabled',    'Estado',  'required|numeric|max_length[2]|in_list[0,1]');
            if ($this->form_validation->run() == FALSE){
                throw new Exception(validation_errors('',''), 202);
            }
            $insert = $this->Explorador_model->crear([
                'label'       => $post['label'],
                'empresaId'   => $this->session->userdata('empresaId'),
                'parent_id'   => $post['parent_id'],
                'type'        => $post['type'],
                'id'          => $post['id'],
                'archivos_id' => $post['archivos_id'],
                'disabled'    => $post['disabled']
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
    
    public function editar() {
        if(!$this->ion_auth->in_group([1,2])){
            return FALSE;
        }        
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('label', $post) || !array_key_exists('id', $post) || !array_key_exists('parent_id', $post) || !array_key_exists('deleted_at', $post)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }
            $insert = $this->Explorador_model->editar([
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
    
    public function carpetas() {
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('id', $post)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }            
            $items = $this->arbole->run($post['id']);
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
