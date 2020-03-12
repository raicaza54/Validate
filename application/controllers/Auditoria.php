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
        $this->load->library([
            'benford',
            'spider',
            'manipulacion',
            'listascontrol'
        ]);
        $this->load->model([
            'Archivos_model',
            'Users_model',
        ]);
    }

    public function index() {
        if($this->session->flashdata('terminos') === TRUE){
            $this->ion_auth->logout();
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect('auth/login', 'refresh');            
        }
        $terminos = $this->Users_model->terminoCondiciones($this->session->userdata('users_id'));
        if((int) $terminos['estado'] <= 0){
            $this->session->set_flashdata('terminos', TRUE);
            $view_html = $this->load->view('terminos/terminos001_vw', NULL, TRUE);
            $this->load->view('auth/plantilla', ['body' => $view_html]);
        }else{
            if($terminos['ayudame'] == 1){
                $this->session->set_flashdata('ayudame', TRUE);
                $this->Users_model->ayudaAceptada($this->session->userdata('users_id'));
            }
            $this->load->view("plantilla/plantilla", $this->data);
        }
    }
    
    public function terminosCondiciones() {
        try {
            $this->form_validation->set_rules('terminos', 'Terminos y Condiciones', 'required|max_length[2]|in_list[ok]');
            if ($this->form_validation->run() == FALSE) {
                $this->ion_auth->logout();
                $this->session->set_flashdata('message', 'Los datos enviados no son correctos');
                redirect('auth/login', 'refresh');
            }
            $terminos = $this->Users_model->terminoCondiciones($this->session->userdata('users_id'));
            if($this->Users_model->aceptarTerminoCondiciones()){
                if(is_null($terminos['ayudame']) || ($terminos['ayudame'] == 1)){
                    $this->session->set_flashdata('ayudame', TRUE);
                }
                redirect('/', 'refresh');
            }else{
                $this->session->set_flashdata('message', 'Algo no anda bien, los datos enviados no son correctos');
                redirect('auth/login', 'refresh');
            }
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
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
                throw new Exception("Algo no anda bien, los datos estan incompletos o corruptos", 202);
            }
            $form = unSerializeArray($post['form']);
            $this->form_validation->set_data($form);
            $this->form_validation->set_rules('ejecucion', 'Ejecución', 'required|max_length[50]|min_length[10]');
            if(array_key_exists('clickSpider', $form) && strlen($form['clickSpider'])){
                $this->form_validation->set_rules('clickSpider', 'Cuenta', 'required|numeric');
            }else{
                $this->form_validation->set_rules('archivoIdProcesar', 'Archivo', 'required|max_length[50]|min_length[10]');
                $this->form_validation->set_rules('campoSpider[]', 'Cuenta(s)', 'required|alpha_dash');
                $this->form_validation->set_rules('comprobantes[]', 'Comprobante(s)', 'alpha_dash');                
            }
            if ($this->form_validation->run() == FALSE){
                throw new Exception(validation_errors('',''), 202);
            }
            $campoSpider       = '';
            $archivoIdProcesar = '';
            $comprobantes      = '';
            $xspider           = [];
            $campoSpiders      = [];
            if(array_key_exists('clickSpider', $form) && strlen($form['clickSpider'])){
                $xspider = $this->session->userdata('spider');
                $campoSpider       = $form['clickSpider'];
                $archivoIdProcesar = $xspider['archivoIdProcesar'];
                $comprobantes      = $xspider['comprobantes'];
                $campoSpiders      = $xspider['campoSpider'];
                if(is_array($xspider['campoSpider']) && count($xspider['campoSpider']) > 1 && !in_array($campoSpider, $xspider['campoSpider'])){
                    $campoSpiders = $form['clickSpider'];
                }
            }else{
                $campoSpider       = $form['campoSpider'];
                $archivoIdProcesar = $form['archivoIdProcesar'];
                $comprobantes      = $form['comprobantes'];
                $this->session->set_userdata([
                    'spider' => [
                        'campoSpider'       => $campoSpider,
                        'archivoIdProcesar' => $archivoIdProcesar,
                        'comprobantes'      => $comprobantes,
                    ]
                ]);
                if(is_array($form['campoSpider']) && count($form['campoSpider'])){
                    $campoSpiders = $form['campoSpider'];
                    $campoSpider  = $form['campoSpider'][0];
                }
            }
            $column = $this->archivo->columnSpider($archivoIdProcesar);
            $items = $this->Archivos_model->getDetalleIdSpider($archivoIdProcesar, $column, $comprobantes);
            $cuentas = $this->Archivos_model->getCuentasN($archivoIdProcesar, $column);            
            if(!is_array($items) || (count($items) <= 0)){
                throw new Exception("Algo no anda bien, el archivo no posee filas para analizar", 202);
            }
            $this->spider->data  = $items;
            $this->spider->comp  = $comprobantes;
            $this->spider->ctas  = $campoSpiders;
            $this->spider->ctasn = $cuentas;
            $spider = $this->spider->procesar($campoSpider);
            if(is_array($spider) && array_key_exists('alto', $spider) && $spider['alto'] <= 0){
                throw new Exception("No existen datos para graficar la araña", 202);
            }
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
                throw new Exception("Algo no anda bien, los datos estan incompletos o corruptos", 202);
            }
            $form = unSerializeArray($post['form']);
            if(!is_array($form) || !array_key_exists('archivoIdProcesar', $form) || !array_key_exists('digito', $form) || !array_key_exists('ejecucion', $form)){
                throw new Exception("Algo no anda bien, faltan algunos datos, estan incompletos o corruptos", 202);
            }
            $columndh = $this->archivo->columnas($form['archivoIdProcesar']);
            if(!is_array($columndh)){
                throw new Exception("Algo no anda bien, archivo no encontrado", 202);
            }
            if($form['campoAnalizar'] == 'valor'){
                if($columndh === FALSE){
                    throw new Exception("Para este tipo de archivo se deben definir las columnas de Debitos y Creditos", 202);
                }else{
                    $form['campoAnalizar'] = $columndh['debehaber'];
                }
            }
            $items = $this->Archivos_model->getDetalleIdBenford($form['archivoIdProcesar'], $form['campoAnalizar']);
            if(!is_array($items) || (count($items) <= 0)){
                throw new Exception("Algo no anda bien, el archivo no posee filas para analizar", 202);
            }
            $this->benford->data = $items;
            $tabla = $this->benford->procesar($form['digito']);
            if(is_bool($tabla) || (($tabla['d1'] == FALSE) && ($tabla['d2'] == FALSE) && ($tabla['d12'] == FALSE))){
                throw new Exception("Algo no anda bien, la columna seleccionada no fue posible procesarla", 202);
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
    
    public function condicionCuenta() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        set_time_limit(0);
        $response = $this->response;
        $this->load->model('Condicion_model');
        $this->load->library('Condicion');
        try {
            $post = $this->input->post();
            $this->form_validation->set_rules('id',        'Identificador',       'required|max_length[50]');
            $this->form_validation->set_rules('ejecucion', 'Identificador',       'required|max_length[50]');
            $this->form_validation->set_rules('data',      'Condición de Cuenta', 'required');
            if ($this->form_validation->run() == FALSE){
                throw new Exception(validation_errors('',''), 202);
            }
            $data = $this->input->post('data');
            $data = json_decode($data, TRUE);
            if(!is_array($data) || count($data) <= 0){
                throw new Exception('Las condiciones de cuenta no se configuraron de forma correcta, intentelo nuevamente', 202);
            }
            $data = array_map("unserialize", array_unique(array_map("serialize", $data)));
            array_walk($data, function (&$value){
                $value[0] = $value[0];
                $value[1] = trim(str_replace([',','%'],['.',''],$value[1]));
                $value[2] = trim(str_replace([',','%'],['.',''],$value[2]));
            });
            $data = array_filter($data, function ($value){
               return ((ctype_alnum($value[0]) ||  is_numeric($value[0])) &&  is_numeric($value[1]) &&  is_numeric($value[2])) ? TRUE : FALSE;
            });
            if(!is_array($data) || count($data) <= 0){
                throw new Exception('Algo no anda bien, no existen datos de Condición de Cuenta para comprobar los valores, intentelo nuevamente', 202);
            }
            $condicion = [];
            foreach ($data as $value) {
                $condicion[] = [
                    'cuenta'     => $value[0],
                    'porcentaje' => $value[1],
                    'tolerancia' => $value[2],
                ];
            }
            $insert = $this->Condicion_model->set_condicion($condicion);
            $column = $this->archivo->columnCondicion($post['id']);
            $items = $this->Archivos_model->getBases($post['id'], $column);
            if(!is_array($items) || count($items) <= 0){
                throw new Exception('Algo no anda bien, el archivo no es legible, intentelo nuevamente o contacte con soporte', 202);   
            }
            $condiciones = [];
            foreach ($condicion as $value) {
                $condiciones[$value['cuenta']] = [
                    'porcentaje' => $value['porcentaje'],
                    'tolerancia' => $value['tolerancia'],
                ];
            }
            $this->condicion->condiciones = $condiciones;
            $this->condicion->datos = $items;            
            $condicionesExc = $this->condicion->run();
            $id = uniqint();
            $response["data"] = $condicionesExc;
            $this->Analisis_model->setInsert([
                'id'            => $id,
                'analisis'      => json_encode($response["data"]),
                'ejecucion'     => $post['ejecucion'],
                'analisis_tipo' => 'condicioncuenta'
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
    
    public function asistente() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->load->model([
            'Empresas_model',
            'Explorador_model',
            'Resultados_model',
        ]);
        set_time_limit(0);
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!array_key_exists('form', $post)){
                throw new Exception('Datos no correctos', 202);
            }
            $this->form_validation->set_data($post['form']);
            $this->form_validation->set_rules('nombre',         'Empresa',                          'required|max_length[250]');
            $this->form_validation->set_rules('identificacion', 'NIT',                              'required|max_length[250]');
            $this->form_validation->set_rules('direccion',      'Direccion',                        'required|max_length[500]');
            $this->form_validation->set_rules('persona',        'Persona Contacto',                 'required|max_length[250]');
            $this->form_validation->set_rules('persona_tlfs',   'Tel&eacute;fono Persona Contacto', 'required|max_length[250]');
            $this->form_validation->set_rules('crp-label',      'Nombre de Carpeta Archivos',       'required|max_length[250]');
            $this->form_validation->set_rules('res-label',      'Nombre de Carpeta Resultados',     'required|max_length[250]');
            if ($this->form_validation->run() == FALSE){
                throw new Exception(validation_errors('',''), 202);
            }
            $dataEmpresa = [
                'nombre'         => $post['form']['nombre'],
                'identificacion' => $post['form']['identificacion'],
                'direccion'      => $post['form']['direccion'],
                'persona'        => $post['form']['persona'],
                'persona_tlfs'   => $post['form']['persona_tlfs'],
            ];
            $empresaId = $this->Empresas_model->insertData($dataEmpresa);
            if($empresaId == FALSE){
                throw new Exception('Algo no anda bien, la empresa no pudo ser registrada, contacte a soporte', 200);
            }
            $id = uniqint();
            $insertCarpeta = $this->Explorador_model->crear([
                'label'       => $post['form']['crp-label'],
                'empresaId'   => $empresaId,
                'parent_id'   => 0,
                'type'        => 'folder',
                'id'          => $id,
                'archivos_id' => $id,
                'disabled'    => 0
            ]);            
            if($insertCarpeta === FALSE){
                throw new Exception("Algo no anda bien, no fue posible crear carpeta de archivos", 202);
            }
            $id = uniqint();
            $insertResultado = $this->Resultados_model->crear([
                'label'       => $post['form']['res-label'],
                'empresaId'   => $empresaId,
                'parent_id'   => '#',
                'type'        => 'folder',
                'id'          => $id
            ]);
            if($insertResultado === FALSE){
                throw new Exception("Algo no anda bien, no fue posible crear carpeta de resultados", 202);
            }
            $response["data"] = [
                'empresaId' => $empresaId
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
