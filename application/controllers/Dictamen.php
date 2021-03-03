<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Dictamen
 * @LastUpdate  2020-07-29
 */
class Dictamen extends CI_Controller {
    
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
        $this->load->config('dictamen', TRUE);
        $this->load->model('Dictamen_model');
    }
    
    private function formato($form) {
        $data = [];
        foreach ($form['form'] as $value) {
            $data[$value['name']] = $value['value'];
            if(strpos($value['name'], 'revisor') !== FALSE){
                $data['revisor'][str_replace('revisor.', '', $value['name'])] = $value['value'];
                unset($data[$value['name']]);
            }
        }
        $id_formato = openCypher('decrypt', $data['id_formato']);
        $contenido = [];
        if(isset($data['contenido']) && is_array($data['contenido'])){
            foreach ($data['contenido'] as $value) {
                if(array_key_exists($value['name'], $data)){
                    $contenido[] = [
                        'titulo' => $data[$value['name']],
                        'cuerpo' => str_replace(['%'], ['&#37;'], $value['value']),
                    ];
                    unset($data[$value['name']]);
                }
            }
        }
        $data['formato']['titulo'] = $data['titulo'];
        $data['formato']['formato'] = $data['archivo'];
        unset($data['titulo']);
        unset($data['archivo']);
        unset($data['contenido']);
        $data['formato']['contenido'] = $contenido;
        return [
            'data'       => $data,
            'id_formato' => $id_formato,
        ];
    }
    
    public function salvar() {
        $response = $this->response;
        $data = $row = array();
        try {
            $form = $this->input->post();
            if(!array_key_exists('form', $form) && !is_array($form['form'])){
                throw new Exception("Tenemos un problema, el formato no se reconoce", 202);
            }
            $id_formato = FALSE; $insert = FALSE;
            $formato    = $this->formato($form);
            $id_formato = $formato['id_formato'];
            $data       = $formato['data'];
            if($id_formato == FALSE){
                $insert = TRUE;
                $id_formato = uniqint();
            }
            $datos = [
                'fk_clientes' => $this->session->userdata('clientes_id'),
                'fk_users'    => $this->session->userdata('users_id'),
                'fk_empresas' => $this->session->userdata('empresaId'),
                'etiqueta'    => $data['formato']['formato'],
                'data'        => serialize($data),
            ];
            $this->Dictamen_model->formatoData($datos, $id_formato, $insert);
            $id_formato = openCypher('encrypt', $id_formato);
            $response = ["data" => ['id_formato' => $id_formato]];
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($response['status'])
            ->set_output(json_encode($response));
    }
    
    public function formatos() {
        $response = $this->response;
        $data = $row = array();
        try {
            $borradores = $this->Dictamen_model->borradores();
            $response = [
                "data"       => $this->config->item('dictamen'),
                "borradores" => $borradores,
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
    
    public function descartar() {
        $response = $this->response;
        $data = $row = array();
        try {
            $this->form_validation->set_rules('id', 'Borrador', 'required|max_length[50]');
            if ($this->form_validation->run() == FALSE){
                throw new Exception(validation_errors('',''), 202);
            }
            $id = openCypher('decrypt', $this->input->post('id'));
            if($id == FALSE){
                log_message('error', 'El id del borrador no se desencripto adecuadamente');
                throw new Exception('Algo no anda bien, el codigo del borrador no corresponde, intentelo nuevamente o contacte con soporte técnico', 202);
            }
            if($this->Dictamen_model->descartar($id) <= 0){
                log_message('error', 'No se pudo descartar el borrador');
                throw new Exception('Algo no anda bien, el codigo del borrador no puede ser descartado, intentelo nuevamente o contacte con soporte técnico', 202);
            }
            $response = [
                "data" => 1,
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
    
    public function procesar() {
        $response = $this->response;
        $data = $row = array();
        try {
            $form = $this->input->post();
            if (!is_array($form) || !array_key_exists('form', $form)){
                throw new Exception('Algo no anda bien, los datos no son adecuados, intentelo nuevamente o contacte con soporte técnico', 202);
            }
            $formato = $this->formato($form);
            $data       = $formato['data'];
            $id_formato = $formato['id_formato'];
            $id = uniqint();
            $this->Analisis_model->setInsert([
                'id'            => $id,
                'analisis'      => serialize($data),
                'ejecucion'     => $data['ejecucion'],
                'analisis_tipo' => 'dictamen'
            ]);
            $this->Dictamen_model->descartar($id_formato);
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
    
    public function borrador() {
        $response = $this->response;
        $data = $row = [];
        $revisor = [];
        $formato = [];
        $empresa = '';
        $format = '';
        try {
            $this->form_validation->set_rules('id', 'Borrador', 'required|max_length[50]');
            if ($this->form_validation->run() == FALSE){
                throw new Exception(validation_errors('',''), 202);
            }
            $id = openCypher('decrypt', $this->input->post('id'));
            if($id == FALSE){
                log_message('error', 'El id de formato no se desencripto adecuadamente');
                throw new Exception('Algo no anda bien, el codigo de formato no corresponde, intentelo nuevamente o contacte con soporte técnico', 202);
            }
            $formato = $this->Dictamen_model->borrador($id);
            if(!isset($formato['data'])){
                log_message('error', 'El id de formato no se encuentra en la base de datos');
                throw new Exception('Algo no anda bien, el codigo de formato no encontrado, intentelo nuevamente o contacte con soporte técnico', 202);                
            }
            $formato['data'] = unserialize($formato['data']);
            $id = $formato['id'];
            $empresa = isset($formato['data']['empresa']) ? $formato['data']['empresa'] : '';
            $revisor = isset($formato['data']['revisor']) ? $formato['data']['revisor'] : [];
            $format  = isset($formato['data']['format']) ? $formato['data']['format'] : '';
            $formato = isset($formato['data']['formato']) ? $formato['data']['formato'] : [];
            $response = ['data' => [
                'empresa' => $empresa,
                'revisor' => $revisor,
                'formato' => $formato,
                'format'  => $format,
                'id'      => $id,
            ]];
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($response['status'])
            ->set_output(json_encode($response));        
    }
    
    public function contenido() {
        $response = $this->response;
        $data = $row = array();
        try {
            $this->form_validation->set_rules('formato', 'Formato', 'required|max_length[9]');
            if ($this->form_validation->run() == FALSE){
                throw new Exception(validation_errors('',''), 202);
            }
            $dictamen = $this->config->item('dictamen');
            if(!array_key_exists($this->input->post('formato'), $dictamen)){
                throw new Exception("Tenemos un problema, el formato no se reconoce", 202);
            }
            $empresa = $this->Empresas_model->getId($this->session->userdata('empresaId'));
            $revisor = $this->ion_auth->user($this->session->userdata('users_id'))->row();
            $formato = $dictamen[$this->input->post('formato')];
            if(isset($formato['contenido']) && is_array($formato['contenido'])){
                array_walk($formato['contenido'], function (&$value) use ($empresa){
                    $value['cuerpo'] = str_replace(['@empresa@'], [($empresa['nombre'] ?: '')], $value['cuerpo']);
                });
            }
            if(isset($revisor)){
                $revisor = [
                    'nombre' => (isset($revisor->first_name) ? $revisor->first_name.' ' : '').(isset($revisor->last_name) ? $revisor->last_name : ''),
                    'tp'     => (isset($revisor->professional_card) && (strlen($revisor->professional_card) > 0)) ? $revisor->professional_card : 'SIN DATOS',
                ];
            }else{
                $revisor = NULL;
            }
            $response = ['data' => [
                'empresa' => $empresa['nombre'],
                'revisor' => $revisor,
                'formato' => $formato,
            ]];
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
