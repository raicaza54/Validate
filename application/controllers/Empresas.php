<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Controlador Empresas
 * @LastUpdate  2019-02-14
 */
class Empresas extends CI_Controller {

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
            'Empresas_model',
            'Cliente_model'
        ]);
    }

    public function limites($json = TRUE) {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }        
        $response = $this->response;
        try {
            $cliente_id = $this->session->userdata('clientes_id');
            $limites = $this->Cliente_model->getLimites($cliente_id, 'empresas');
            if (!is_array($limites)) {
                throw new Exception("No existen datos para mostrar", 202);
            }
            $response = ["data" => $limites];
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        if($json){
            $this->output
                ->set_content_type('application/json')
                ->set_status_header($response['status'])
                ->set_output(json_encode($response));
        }else{
            return $response;
        }
    }    
    
    public function datos() {
        $response = $this->response;
        $data = $row = array();
        try {
            //Validaciones de datos
            $items = $this->Empresas_model->getRows($this->input->post());
            if (!is_array($items)) {
                throw new Exception("No existen datos para mostrar", 202);
            }
            $response = [
                "draw"            => $this->input->post('draw'),
                "recordsTotal"    => $this->Empresas_model->countAll(),
                "recordsFiltered" => $this->Empresas_model->countFiltered($this->input->post()),
                "data"            => $items,
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
    
    public function formulario() {
        $response = $this->response;
        $data = $row = array();
        try {
            $post = $this->input->post();
            $this->form_validation->set_data($post);
            $this->form_validation->set_rules('id','Empresa','required|max_length[11]|numeric');
            if($this->form_validation->run() === FALSE){
                throw new Exception(validation_errors('',''), 202);
            }
            $item = $this->Empresas_model->getId($post['id']);
            if (!is_array($item)) {
                throw new Exception("No existen datos para mostrar", 202);
            }
            $response = [
                "data" => ['empresa' => $item],
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
    
    private function _validaciones($form) {
        $this->form_validation->set_rules('empresa-nombre',             'Empresa',                    'required|max_length[150]');
        $this->form_validation->set_rules('empresa-identificacion',     'Identificación',             'required|max_length[150]|callback__identificacionUnico['.$form['empresa-id'].']');
        $this->form_validation->set_rules('empresa-direccion',          'Dirección',                  'required|max_length[500]');
        $this->form_validation->set_rules('empresa-telefonos',          'Telefonos',                  'max_length[150]');
        $this->form_validation->set_rules('empresa-correo',             'Correo',                     'max_length[150]');
        $this->form_validation->set_rules('empresa-naturaleza_credito', 'Naturaleza',                 'max_length[10]|in_list[si,no]');
        $this->form_validation->set_rules('empresa-persona',            'Nombre Persona Contacto',    'required|max_length[150]');
        $this->form_validation->set_rules('empresa-persona_tlfs',       'Teléfono Persona Contacto',  'required|max_length[150]');
        $this->form_validation->set_rules('empresa-persona_direc',      'Dirección Persona Contacto', 'max_length[500]');
        $this->form_validation->set_rules('empresa-persona_correo',     'Correo Persona Contacto',    'max_length[150]');
        $this->form_validation->set_rules('empresa-observacion',        'Observaciones',              'max_length[150]');        
    }
    
    public function actualizar() {
        $response = $this->response;
        $data = $row = array();
        $demo = NULL;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('form', $post) || (count($post['form']) <= 0)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }
            $form = unSerializeArray($post['form']);
            $this->form_validation->set_data($form);
            $this->form_validation->set_rules('empresa-id', 'Identificador', 'required|max_length[11]|numeric');
            $this->_validaciones($form);
            if($this->form_validation->run() === FALSE){
                throw new Exception(validation_errors('',''), 202);
            }
            if($form['empresa-id'] == 1){
                $demo = $this->Empresas_model->editarDemo(
                        $this->session->userdata('users_id'),
                        $form['empresa-id'], 
                        $this->session->userdata('clientes_id')
                );
                if($demo == FALSE){
                    throw new Exception('Empresa Demostración, no es posible editar los datos de la empresa, la misma es unicamente para fines demostrativos', 202);
                }
            }
            $item = $this->Empresas_model->getId($form['empresa-id']);
            if (!is_array($item)) {
                throw new Exception("No existen datos para actualizar", 202);
            }
            $data = [];
            foreach ($form as $key => $value) {
                $data[substr($key,8)] = $value;
            }
            unset($data['id']);
            $update = $this->Empresas_model->updateData($data, $form['empresa-id']);
            if($update == FALSE){
                throw new Exception('No se realizaron cambios en la empresa', 200);
            }
            $response = [
                "data" => ['empresa' => $item],
            ];
            throw new Exception('Los datos se actualizaron correctamente', 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($response['status'])
            ->set_output(json_encode($response));
    }
    
    public function eliminar() {
        $response = $this->response;
        $data = $row = array();
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('id', $post)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }
            $this->form_validation->set_data($post);
            $this->form_validation->set_rules('id', 'Identificador', 'required|max_length[11]|numeric');
            if($this->form_validation->run() === FALSE){
                throw new Exception(validation_errors('',''), 202);
            }
            if($post['id'] == 1){
                $demo = $this->Empresas_model->editarDemo(
                        $this->session->userdata('users_id'),
                        $post['id'], 
                        $this->session->userdata('clientes_id')
                );
                if($demo == FALSE){
                    throw new Exception('Empresa Demostración, no es posible eliminar la empresa, la misma es unicamente para fines demostrativos', 202);
                }
            }
            $item = $this->Empresas_model->getId($post['id']);
            if (!is_array($item)) {
                throw new Exception("No existen datos para actualizar", 202);
            }
            $update = $this->Empresas_model->updateData(['deleted_at' => 1], $post['id']);
            if($update == FALSE){
                throw new Exception('No se realizaron cambios en la empresa', 200);
            }
            $response = [
                "data" => ['empresa' => $item],
            ];
            throw new Exception('La empresa fue eliminada', 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($response['status'])
            ->set_output(json_encode($response));
    }
    
    public function crear() {
        $response = $this->response;
        $data = $row = array();
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('form', $post) || (count($post['form']) <= 0)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }
            if(mb_strtoupper($this->session->userdata('last_name'), 'UTF-8') == 'DEMOS'){
                throw new Exception('Cuenta Demostración, no es posible crear empresas, esta cuenta de usuario es únicamente para fines demostrativos', 202);
            }
            $limites = $this->limites(FALSE);
            if(($limites['data']['empresas']['cant'] + 1) > $limites['data']['empresas']['limite']){
                throw new Exception("No es posible crear esta empresa ya que la misma supera el limite establecido, contacte con soporte", 202);
            }
            $form = unSerializeArray($post['form']);
            $this->form_validation->set_data($form);
            $this->_validaciones($form);
            if($this->form_validation->run() === FALSE){
                throw new Exception(validation_errors('',''), 202);
            }
            $data = [];
            foreach ($form as $key => $value) {
                $data[substr($key,8)] = $value;
            }
            unset($data['id']);
            $insert = $this->Empresas_model->insertData($data);
            if($insert == FALSE){
                throw new Exception('La empresa no pudo ser registrada, contacte a soporte', 200);
            }
            $response = [
                "data" => ['empresa' => $form],
            ];
            throw new Exception('Los datos se actualizaron correctamente', 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($response['status'])
            ->set_output(json_encode($response));
    }
    
    function _identificacionUnico($identificacion, $id = NULL) {
        $empresa = $this->Empresas_model->getIdentificacion($identificacion, $id);
        if(is_array($empresa) && count($empresa)){
            $this->form_validation->set_message('_identificacionUnico', 'La {field} ya se encuentra registrada para la empresa "'.$empresa['nombre'].'"');
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    public function activar() {
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('id', $post)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }
            $this->session->set_userdata(['empresaId' => $post['id']]);
            $item = $this->Empresas_model->getId($post['id']);
            if (!is_array($item)) {
                throw new Exception("No existen datos para mostrar", 202);
            }
            $response["data"] = $item;
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
