<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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
        "data"   => array(),
        "csrf"   => ''
    );    

    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
        $this->load->library(array(
            'benford',
            'spider'
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
    
    function calculos($archivo, $cuenta) {
        $this->load->library('table');
        $this->table->clear();
        $this->db->select('campo1, campo2, ABS(REPLACE(campo6,".","")) as campo6');
        $this->db->where('archivo_id', $archivo);
        $this->db->where('campo1', $cuenta);
        $tabla = $this->db->get('manipulacion')->row_array();
        $r = $this->table->generate($tabla);
        return $tabla['campo6'];
    }
    
    public function manipulacion() {
        $r = [];
        $c = '';
        //DSRI
        $r['dsri'][] = $this->calculos(1,1305); //CXC
        $r['dsri'][] = $this->calculos(2,1305);        
        $r['dsri'][] = $this->calculos(1,41); //Ventas
        $r['dsri'][] = $this->calculos(2,41);
        $dsri = ($r['dsri'][0]/$r['dsri'][2])/($r['dsri'][1]/$r['dsri'][3]);
        $c .= 'DSRI: '.number_format($dsri,3,',','.').'<br/>';

        //GMI
        $r['gmi'][] = $this->calculos(1,61); //Costo venta
        $r['gmi'][] = $this->calculos(2,61);
        $gmi = (($r['dsri'][3]-$r['gmi'][1])/$r['dsri'][3])/(($r['dsri'][2]-$r['gmi'][0])/$r['dsri'][2]);
        $c .= 'GMI: '.number_format($gmi,3,',','.').'<br/>';

        //AQI
        
        
        $this->data['body'] = '<div style="overflow-y: scroll; height: 450px">'.$c.'</div>';
        $this->load->view("plantilla/plantilla", $this->data);      
        return;
        
        $r .= $this->calculos(1,11);
        $r .= $this->calculos(1,12);
        $r .= $this->calculos(1,13);
        $r .= $this->calculos(1,14);
        
        $r .= $this->calculos(2,11);
        $r .= $this->calculos(2,12);
        $r .= $this->calculos(2,13);
        $r .= $this->calculos(2,14);
        
        $r .= $this->calculos(1,15);
        $r .= $this->calculos(1,16);
        $r .= $this->calculos(1,17);
        $r .= $this->calculos(1,18);
        $r .= $this->calculos(1,19);
        
        $r .= $this->calculos(2,15);
        $r .= $this->calculos(2,16);
        $r .= $this->calculos(2,17);
        $r .= $this->calculos(2,18);
        $r .= $this->calculos(2,19);
        
        $r .= $this->calculos(1,15);
        $r .= $this->calculos(2,15);
        
        $r .= $this->calculos(1,5160);
        $r .= $this->calculos(1,5260);
        $r .= $this->calculos(1,7360);
        $r .= $this->calculos(2,5160);
        $r .= $this->calculos(2,5260);
        $r .= $this->calculos(2,7360);
        
        $this->data['body'] = '<div style="overflow-y: scroll; height: 450px">'.$r.'</div>';
        $this->load->view("plantilla/plantilla", $this->data);
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
