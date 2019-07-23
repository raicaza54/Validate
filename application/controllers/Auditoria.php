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

    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
        $this->load->library(array(
            'benford',
            'spider',
            'manipulacion',
            'listascontrol'
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
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }
            $form = unSerializeArray($post['form']);
            $this->form_validation->set_data($form);
            $this->form_validation->set_rules('ejecucion', 'Ejecución', 'required|max_length[50]|min_length[10]');
            if ($this->form_validation->run() == FALSE){
                throw new Exception('<ul>'.validation_errors('<li>','</li>').'</ul>', 202);
            }
            if(!is_array($form) || !array_key_exists('archivoIdProcesar', $form) || !array_key_exists('campoSpider', $form) || !array_key_exists('ejecucion', $form)){
                throw new Exception("Tenemos un problema, faltan algunos datos, estan incompletos o corruptos", 202);
            }
            $column = $this->archivo->columnSpider($form['archivoIdProcesar']);
            $items = $this->Archivos_model->getDetalleIdSpider($form['archivoIdProcesar'], $column);
            if(!is_array($items) || (count($items) <= 0)){
                throw new Exception("Tenemos un problema, el archivo no posee filas para analizar", 202);
            }
            $this->spider->data = $items;
            $spider = $this->spider->procesar($form['campoSpider']);
            $id = uniqint();
            $form = $form + ['id' => $id];
            $response["data"] = $spider + ['form' => $form];
            $this->Analisis_model->setInsert([
                'id'            => $id,
                'analisis'      => serialize($response["data"]),
                'ejecucion'     => $form['ejecucion'],
                'analisis_tipo' => 'spider'
            ]);
            throw new Exception("Se ha creado la araña correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($response['status'])
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
            if(!is_array($post) || !array_key_exists('balances', $post) || (count($post['balances']) != 2) || !array_key_exists('ejecucion', $post)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }
            $manipulacion = $this->manipulacion->run($post['balances']);
            $id = uniqint();
            $response["data"] = $manipulacion;
            $this->Analisis_model->setInsert([
                'id'            => $id,
                'analisis'      => serialize($response["data"]),
                'ejecucion'     => $post['ejecucion'],
                'analisis_tipo' => 'manipulacion'
            ]);
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($response['status'])
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
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }
            $form = unSerializeArray($post['form']);
            if(!is_array($form) || !array_key_exists('archivoIdProcesar', $form) || !array_key_exists('digito', $form) || !array_key_exists('ejecucion', $form)){
                throw new Exception("Tenemos un problema, faltan algunos datos, estan incompletos o corruptos", 202);
            }
            $columndh = $this->archivo->columnas($form['archivoIdProcesar']);
            if($form['campoAnalizar'] == 'valor'){
                if($columndh === FALSE){
                    throw new Exception("Para este tipo de archivo se deben definir las columnas de Debitos y Creditos", 202);
                }else{
                    $form['campoAnalizar'] = $columndh['debehaber'];
                }
            }
            $items = $this->Archivos_model->getDetalleIdBenford($form['archivoIdProcesar'], $form['campoAnalizar']);
            if(!is_array($items) || (count($items) <= 0)){
                throw new Exception("Tenemos un problema, el archivo no posee filas para analizar", 202);
            }
            $this->benford->data = $items;
            $tabla = $this->benford->procesar($form['digito']);
            if(is_bool($tabla) || (($tabla['d1'] == FALSE) && ($tabla['d2'] == FALSE) && ($tabla['d12'] == FALSE))){
                throw new Exception("Tenemos un problema, la columna seleccionada no fue posible procesarla", 202);
            }
            $id = uniqint();
            $form = $form + ['id' => $id];
            $response["data"] = $tabla + ['form' => $form];
            $this->Analisis_model->setInsert([
                'id'            => $id,
                'analisis'      => serialize($response["data"]),
                'ejecucion'     => $form['ejecucion'],
                'observacion'   => 'Digito: ' . $form['digito'],
                'analisis_tipo' => 'benford'
            ]);
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($response['status'])
            ->set_output(json_encode($response));
    }
    
    public function listascontrol() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        set_time_limit(0);
        $response = $this->response;
        try {
            $post = $this->input->post();
            $this->form_validation->set_rules('id',        'Identificador', 'required|max_length[50]');
            $this->form_validation->set_rules('ejecucion', 'Identificador', 'required|max_length[50]');
            if ($this->form_validation->run() == FALSE){
                throw new Exception(validation_errors('',''), 202);
            }
            $consulta = $this->listascontrol->run($post['id']);
            $id = uniqint();
            $response["data"] = $consulta;
            $this->Analisis_model->setInsert([
                'id'            => $id,
                'analisis'      => serialize($response["data"]),
                'ejecucion'     => $post['ejecucion'],
                'analisis_tipo' => 'listascontrol'
            ]);
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
