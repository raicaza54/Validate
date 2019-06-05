<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Controlador Explorador de Resultados
 * @LastUpdate  2019-05-01
 */
class Resultados extends CI_Controller {

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
//        if (!$this->input->is_ajax_request()) {
//            show_404();
//        }
        $this->load->model([
            'Resultados_model',
            'Archivos_model',
        ]);
    }
    
    public function crear() {
        if(!$this->ion_auth->in_group([1,2])){
            return FALSE;
        }        
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('label', $post) || !array_key_exists('parent_id', $post) || !array_key_exists('type', $post) || !array_key_exists('id', $post)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 400);
            }
            $insert = $this->Resultados_model->crear([
                'label'       => $post['label'],
                'empresaId'   => $this->session->userdata('empresaId'),
                'parent_id'   => $post['parent_id'],
                'type'        => $post['type'],
                'id'          => $post['id']
            ]);
            if($insert === FALSE){
                throw new Exception("Tenemos un problema, no fue posible crear carpeta", 418);
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
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 400);
            }
            $insert = $this->Resultados_model->editar([
                'label'      => $post['label'],
                'id'         => $post['id'],
                'parent_id'  => $post['parent_id'],
                'deleted_at' => $post['deleted_at'],
            ]);
            if($insert === FALSE){
                throw new Exception("Tenemos un problema, no fue posible crear carpeta", 418);
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
    
    public function benforPdf() {
        $this->load->library('formatpdf/Benford', array(
            'orientation' => 'P',
            'unit'        => 'mm',
            'format'      => 'LETTER',
            'unicode'     => TRUE,
            'encoding'    => 'UTF-8',
            'diskcache'   => FALSE,
            'empresa'     => 'Demostración',
            'codigo'      => '000165410',
        ), 'pdf');
        $data = [];
        $dataPdf = [
            'd1'  => unserialize('a:4:{s:2:"d1";a:7:{s:5:"tabla";a:9:{i:1;a:5:{s:6:"numero";i:1;s:10:"frecuencia";s:6:"13.288";s:9:"observado";s:7:"30,849%";s:7:"benford";s:7:"30,103%";s:9:"variacion";s:8:"+ 0,746%";}i:2;a:5:{s:6:"numero";i:2;s:10:"frecuencia";s:5:"6.229";s:9:"observado";s:7:"14,461%";s:7:"benford";s:7:"17,609%";s:9:"variacion";s:8:"- 3,148%";}i:3;a:5:{s:6:"numero";i:3;s:10:"frecuencia";s:5:"3.860";s:9:"observado";s:6:"8,961%";s:7:"benford";s:7:"12,494%";s:9:"variacion";s:8:"- 3,533%";}i:4;a:5:{s:6:"numero";i:4;s:10:"frecuencia";s:5:"4.163";s:9:"observado";s:6:"9,665%";s:7:"benford";s:6:"9,691%";s:9:"variacion";s:8:"- 0,026%";}i:5;a:5:{s:6:"numero";i:5;s:10:"frecuencia";s:5:"3.355";s:9:"observado";s:6:"7,789%";s:7:"benford";s:6:"7,918%";s:9:"variacion";s:8:"- 0,129%";}i:6;a:5:{s:6:"numero";i:6;s:10:"frecuencia";s:5:"3.233";s:9:"observado";s:6:"7,506%";s:7:"benford";s:6:"6,695%";s:9:"variacion";s:8:"+ 0,811%";}i:7;a:5:{s:6:"numero";i:7;s:10:"frecuencia";s:5:"3.383";s:9:"observado";s:6:"7,854%";s:7:"benford";s:6:"5,799%";s:9:"variacion";s:8:"+ 2,055%";}i:8;a:5:{s:6:"numero";i:8;s:10:"frecuencia";s:5:"3.280";s:9:"observado";s:6:"7,615%";s:7:"benford";s:6:"5,115%";s:9:"variacion";s:8:"+ 2,500%";}i:9;a:5:{s:6:"numero";i:9;s:10:"frecuencia";s:5:"2.283";s:9:"observado";s:6:"5,300%";s:7:"benford";s:6:"4,576%";s:9:"variacion";s:8:"+ 0,724%";}}s:7:"grafica";a:2:{s:5:"data1";a:10:{i:0;s:5:"data1";i:1;s:6:"30.849";i:2;s:6:"14.461";i:3;s:5:"8.961";i:4;s:5:"9.665";i:5;s:5:"7.789";i:6;s:5:"7.506";i:7;s:5:"7.854";i:8;s:5:"7.615";i:9;s:5:"5.300";}s:5:"data2";a:10:{i:0;s:5:"data2";i:1;s:6:"30.103";i:2;s:6:"17.609";i:3;s:6:"12.494";i:4;s:5:"9.691";i:5;s:5:"7.918";i:6;s:5:"6.695";i:7;s:5:"5.799";i:8;s:5:"5.115";i:9;s:5:"4.576";}}s:2:"x1";a:10:{i:0;s:2:"x1";i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;i:7;i:8;i:8;i:9;i:9;}s:2:"x2";a:10:{i:0;s:2:"x2";i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;i:7;i:8;i:8;i:9;i:9;}s:3:"mad";s:13:"<b>0,0152</b>";s:11:"madDescribe";s:21:"<b>No Conformidad</b>";s:6:"mad_d1";a:4:{i:0;a:3:{s:3:"min";i:0;s:3:"max";d:0.006;s:11:"descripcion";s:26:"Conformidad Cuasi-Perfecta";}i:1;a:3:{s:3:"min";d:0.006;s:3:"max";d:0.012;s:11:"descripcion";s:21:"Conformidad Aceptable";}i:2;a:3:{s:3:"min";d:0.012;s:3:"max";d:0.015;s:11:"descripcion";s:35:"Conformidad Marginalmente Aceptable";}i:3;a:3:{s:3:"min";d:0.015;s:3:"max";i:10000;s:11:"descripcion";s:14:"No Conformidad";}}}s:2:"d2";b:0;s:3:"d12";b:0;s:4:"form";a:5:{s:13:"campoAnalizar";s:7:"campo10";s:17:"archivoIdProcesar";s:16:"1627562318735920";s:6:"digito";s:1:"1";s:9:"ejecucion";s:15:"375582074733891";s:2:"id";i:1635468455024500;}}'),
            'd2'  => unserialize('a:4:{s:2:"d1";b:0;s:2:"d2";a:7:{s:5:"tabla";a:10:{i:0;a:5:{s:6:"numero";i:0;s:10:"frecuencia";s:5:"7.042";s:9:"observado";s:7:"16,364%";s:7:"benford";s:7:"11,968%";s:9:"variacion";s:8:"+ 4,396%";}i:1;a:5:{s:6:"numero";i:1;s:10:"frecuencia";s:5:"6.081";s:9:"observado";s:7:"14,131%";s:7:"benford";s:7:"11,389%";s:9:"variacion";s:8:"+ 2,742%";}i:2;a:5:{s:6:"numero";i:2;s:10:"frecuencia";s:5:"5.100";s:9:"observado";s:7:"11,851%";s:7:"benford";s:7:"10,882%";s:9:"variacion";s:8:"+ 0,969%";}i:3;a:5:{s:6:"numero";i:3;s:10:"frecuencia";s:5:"3.861";s:9:"observado";s:6:"8,972%";s:7:"benford";s:7:"10,433%";s:9:"variacion";s:8:"- 1,461%";}i:4;a:5:{s:6:"numero";i:4;s:10:"frecuencia";s:5:"3.697";s:9:"observado";s:6:"8,591%";s:7:"benford";s:7:"10,031%";s:9:"variacion";s:8:"- 1,440%";}i:5;a:5:{s:6:"numero";i:5;s:10:"frecuencia";s:5:"3.567";s:9:"observado";s:6:"8,289%";s:7:"benford";s:6:"9,668%";s:9:"variacion";s:8:"- 1,379%";}i:6;a:5:{s:6:"numero";i:6;s:10:"frecuencia";s:5:"3.250";s:9:"observado";s:6:"7,552%";s:7:"benford";s:6:"9,337%";s:9:"variacion";s:8:"- 1,785%";}i:7;a:5:{s:6:"numero";i:7;s:10:"frecuencia";s:5:"3.321";s:9:"observado";s:6:"7,717%";s:7:"benford";s:6:"9,035%";s:9:"variacion";s:8:"- 1,318%";}i:8;a:5:{s:6:"numero";i:8;s:10:"frecuencia";s:5:"3.593";s:9:"observado";s:6:"8,349%";s:7:"benford";s:6:"8,757%";s:9:"variacion";s:8:"- 0,408%";}i:9;a:5:{s:6:"numero";i:9;s:10:"frecuencia";s:5:"3.521";s:9:"observado";s:6:"8,182%";s:7:"benford";s:6:"8,500%";s:9:"variacion";s:8:"- 0,318%";}}s:7:"grafica";a:2:{s:5:"data1";a:11:{i:0;s:5:"data1";i:1;s:6:"16.364";i:2;s:6:"14.131";i:3;s:6:"11.851";i:4;s:5:"8.972";i:5;s:5:"8.591";i:6;s:5:"8.289";i:7;s:5:"7.552";i:8;s:5:"7.717";i:9;s:5:"8.349";i:10;s:5:"8.182";}s:5:"data2";a:11:{i:0;s:5:"data2";i:1;s:6:"11.968";i:2;s:6:"11.389";i:3;s:6:"10.882";i:4;s:6:"10.433";i:5;s:6:"10.031";i:6;s:5:"9.668";i:7;s:5:"9.337";i:8;s:5:"9.035";i:9;s:5:"8.757";i:10;s:5:"8.500";}}s:2:"x1";a:11:{i:0;s:2:"x1";i:1;i:0;i:2;i:1;i:3;i:2;i:4;i:3;i:5;i:4;i:6;i:5;i:7;i:6;i:8;i:7;i:9;i:8;i:10;i:9;}s:2:"x2";a:11:{i:0;s:2:"x2";i:1;i:0;i:2;i:1;i:3;i:2;i:4;i:3;i:5;i:4;i:6;i:5;i:7;i:6;i:8;i:7;i:9;i:8;i:10;i:9;}s:3:"mad";s:13:"<b>0,0162</b>";s:11:"madDescribe";s:21:"<b>No Conformidad</b>";s:6:"mad_d2";a:4:{i:0;a:3:{s:3:"min";i:0;s:3:"max";d:0.008;s:11:"descripcion";s:26:"Conformidad Cuasi-Perfecta";}i:1;a:3:{s:3:"min";d:0.008;s:3:"max";d:0.01;s:11:"descripcion";s:21:"Conformidad Aceptable";}i:2;a:3:{s:3:"min";d:0.01;s:3:"max";d:0.012;s:11:"descripcion";s:35:"Conformidad Marginalmente Aceptable";}i:3;a:3:{s:3:"min";d:0.012;s:3:"max";i:10000;s:11:"descripcion";s:14:"No Conformidad";}}}s:3:"d12";b:0;s:4:"form";a:5:{s:13:"campoAnalizar";s:7:"campo10";s:17:"archivoIdProcesar";s:16:"1627562318735920";s:6:"digito";s:1:"2";s:9:"ejecucion";s:15:"375582074733891";s:2:"id";s:16:"1635468455024500";}}'),
            'd12' => unserialize('a:4:{s:2:"d1";b:0;s:2:"d2";b:0;s:3:"d12";a:7:{s:5:"tabla";a:90:{i:10;a:5:{s:6:"numero";i:10;s:10:"frecuencia";s:5:"2.475";s:9:"observado";s:6:"5,751%";s:7:"benford";s:6:"4,139%";s:9:"variacion";s:8:"+ 1,612%";}i:11;a:5:{s:6:"numero";i:11;s:10:"frecuencia";s:5:"2.738";s:9:"observado";s:6:"6,363%";s:7:"benford";s:6:"3,779%";s:9:"variacion";s:8:"+ 2,584%";}i:12;a:5:{s:6:"numero";i:12;s:10:"frecuencia";s:5:"2.026";s:9:"observado";s:6:"4,708%";s:7:"benford";s:6:"3,476%";s:9:"variacion";s:8:"+ 1,232%";}i:13;a:5:{s:6:"numero";i:13;s:10:"frecuencia";s:5:"1.045";s:9:"observado";s:6:"2,428%";s:7:"benford";s:6:"3,218%";s:9:"variacion";s:8:"- 0,790%";}i:14;a:5:{s:6:"numero";i:14;s:10:"frecuencia";s:3:"877";s:9:"observado";s:6:"2,038%";s:7:"benford";s:6:"2,996%";s:9:"variacion";s:8:"- 0,958%";}i:15;a:5:{s:6:"numero";i:15;s:10:"frecuencia";s:3:"853";s:9:"observado";s:6:"1,982%";s:7:"benford";s:6:"2,803%";s:9:"variacion";s:8:"- 0,821%";}i:16;a:5:{s:6:"numero";i:16;s:10:"frecuencia";s:3:"745";s:9:"observado";s:6:"1,731%";s:7:"benford";s:6:"2,633%";s:9:"variacion";s:8:"- 0,902%";}i:17;a:5:{s:6:"numero";i:17;s:10:"frecuencia";s:3:"804";s:9:"observado";s:6:"1,868%";s:7:"benford";s:6:"2,482%";s:9:"variacion";s:8:"- 0,614%";}i:18;a:5:{s:6:"numero";i:18;s:10:"frecuencia";s:3:"823";s:9:"observado";s:6:"1,912%";s:7:"benford";s:6:"2,348%";s:9:"variacion";s:8:"- 0,436%";}i:19;a:5:{s:6:"numero";i:19;s:10:"frecuencia";s:3:"884";s:9:"observado";s:6:"2,054%";s:7:"benford";s:6:"2,228%";s:9:"variacion";s:8:"- 0,174%";}i:20;a:5:{s:6:"numero";i:20;s:10:"frecuencia";s:5:"1.228";s:9:"observado";s:6:"2,854%";s:7:"benford";s:6:"2,119%";s:9:"variacion";s:8:"+ 0,735%";}i:21;a:5:{s:6:"numero";i:21;s:10:"frecuencia";s:3:"862";s:9:"observado";s:6:"2,003%";s:7:"benford";s:6:"2,020%";s:9:"variacion";s:8:"- 0,017%";}i:22;a:5:{s:6:"numero";i:22;s:10:"frecuencia";s:3:"829";s:9:"observado";s:6:"1,926%";s:7:"benford";s:6:"1,931%";s:9:"variacion";s:8:"- 0,005%";}i:23;a:5:{s:6:"numero";i:23;s:10:"frecuencia";s:3:"623";s:9:"observado";s:6:"1,448%";s:7:"benford";s:6:"1,848%";s:9:"variacion";s:8:"- 0,400%";}i:24;a:5:{s:6:"numero";i:24;s:10:"frecuencia";s:3:"542";s:9:"observado";s:6:"1,259%";s:7:"benford";s:6:"1,773%";s:9:"variacion";s:8:"- 0,514%";}i:25;a:5:{s:6:"numero";i:25;s:10:"frecuencia";s:3:"673";s:9:"observado";s:6:"1,564%";s:7:"benford";s:6:"1,703%";s:9:"variacion";s:8:"- 0,139%";}i:26;a:5:{s:6:"numero";i:26;s:10:"frecuencia";s:3:"363";s:9:"observado";s:6:"0,844%";s:7:"benford";s:6:"1,639%";s:9:"variacion";s:8:"- 0,795%";}i:27;a:5:{s:6:"numero";i:27;s:10:"frecuencia";s:3:"441";s:9:"observado";s:6:"1,025%";s:7:"benford";s:6:"1,579%";s:9:"variacion";s:8:"- 0,554%";}i:28;a:5:{s:6:"numero";i:28;s:10:"frecuencia";s:3:"379";s:9:"observado";s:6:"0,881%";s:7:"benford";s:6:"1,524%";s:9:"variacion";s:8:"- 0,643%";}i:29;a:5:{s:6:"numero";i:29;s:10:"frecuencia";s:3:"280";s:9:"observado";s:6:"0,651%";s:7:"benford";s:6:"1,472%";s:9:"variacion";s:8:"- 0,821%";}i:30;a:5:{s:6:"numero";i:30;s:10:"frecuencia";s:3:"708";s:9:"observado";s:6:"1,645%";s:7:"benford";s:6:"1,424%";s:9:"variacion";s:8:"+ 0,221%";}i:31;a:5:{s:6:"numero";i:31;s:10:"frecuencia";s:3:"258";s:9:"observado";s:6:"0,600%";s:7:"benford";s:6:"1,379%";s:9:"variacion";s:8:"- 0,779%";}i:32;a:5:{s:6:"numero";i:32;s:10:"frecuencia";s:3:"355";s:9:"observado";s:6:"0,825%";s:7:"benford";s:6:"1,336%";s:9:"variacion";s:8:"- 0,511%";}i:33;a:5:{s:6:"numero";i:33;s:10:"frecuencia";s:3:"447";s:9:"observado";s:6:"1,039%";s:7:"benford";s:6:"1,296%";s:9:"variacion";s:8:"- 0,257%";}i:34;a:5:{s:6:"numero";i:34;s:10:"frecuencia";s:3:"409";s:9:"observado";s:6:"0,950%";s:7:"benford";s:6:"1,259%";s:9:"variacion";s:8:"- 0,309%";}i:35;a:5:{s:6:"numero";i:35;s:10:"frecuencia";s:3:"303";s:9:"observado";s:6:"0,704%";s:7:"benford";s:6:"1,223%";s:9:"variacion";s:8:"- 0,519%";}i:36;a:5:{s:6:"numero";i:36;s:10:"frecuencia";s:3:"279";s:9:"observado";s:6:"0,648%";s:7:"benford";s:6:"1,190%";s:9:"variacion";s:8:"- 0,542%";}i:37;a:5:{s:6:"numero";i:37;s:10:"frecuencia";s:3:"412";s:9:"observado";s:6:"0,957%";s:7:"benford";s:6:"1,158%";s:9:"variacion";s:8:"- 0,201%";}i:38;a:5:{s:6:"numero";i:38;s:10:"frecuencia";s:3:"381";s:9:"observado";s:6:"0,885%";s:7:"benford";s:6:"1,128%";s:9:"variacion";s:8:"- 0,243%";}i:39;a:5:{s:6:"numero";i:39;s:10:"frecuencia";s:3:"301";s:9:"observado";s:6:"0,699%";s:7:"benford";s:6:"1,100%";s:9:"variacion";s:8:"- 0,401%";}i:40;a:5:{s:6:"numero";i:40;s:10:"frecuencia";s:3:"556";s:9:"observado";s:6:"1,292%";s:7:"benford";s:6:"1,072%";s:9:"variacion";s:8:"+ 0,220%";}i:41;a:5:{s:6:"numero";i:41;s:10:"frecuencia";s:3:"490";s:9:"observado";s:6:"1,139%";s:7:"benford";s:6:"1,047%";s:9:"variacion";s:8:"+ 0,092%";}i:42;a:5:{s:6:"numero";i:42;s:10:"frecuencia";s:3:"599";s:9:"observado";s:6:"1,392%";s:7:"benford";s:6:"1,022%";s:9:"variacion";s:8:"+ 0,370%";}i:43;a:5:{s:6:"numero";i:43;s:10:"frecuencia";s:3:"485";s:9:"observado";s:6:"1,127%";s:7:"benford";s:6:"0,998%";s:9:"variacion";s:8:"+ 0,129%";}i:44;a:5:{s:6:"numero";i:44;s:10:"frecuencia";s:3:"492";s:9:"observado";s:6:"1,143%";s:7:"benford";s:6:"0,976%";s:9:"variacion";s:8:"+ 0,167%";}i:45;a:5:{s:6:"numero";i:45;s:10:"frecuencia";s:3:"368";s:9:"observado";s:6:"0,855%";s:7:"benford";s:6:"0,955%";s:9:"variacion";s:8:"- 0,100%";}i:46;a:5:{s:6:"numero";i:46;s:10:"frecuencia";s:3:"319";s:9:"observado";s:6:"0,741%";s:7:"benford";s:6:"0,934%";s:9:"variacion";s:8:"- 0,193%";}i:47;a:5:{s:6:"numero";i:47;s:10:"frecuencia";s:3:"235";s:9:"observado";s:6:"0,546%";s:7:"benford";s:6:"0,914%";s:9:"variacion";s:8:"- 0,368%";}i:48;a:5:{s:6:"numero";i:48;s:10:"frecuencia";s:3:"315";s:9:"observado";s:6:"0,732%";s:7:"benford";s:6:"0,895%";s:9:"variacion";s:8:"- 0,163%";}i:49;a:5:{s:6:"numero";i:49;s:10:"frecuencia";s:3:"303";s:9:"observado";s:6:"0,704%";s:7:"benford";s:6:"0,877%";s:9:"variacion";s:8:"- 0,173%";}i:50;a:5:{s:6:"numero";i:50;s:10:"frecuencia";s:3:"546";s:9:"observado";s:6:"1,269%";s:7:"benford";s:6:"0,860%";s:9:"variacion";s:8:"+ 0,409%";}i:51;a:5:{s:6:"numero";i:51;s:10:"frecuencia";s:3:"557";s:9:"observado";s:6:"1,294%";s:7:"benford";s:6:"0,843%";s:9:"variacion";s:8:"+ 0,451%";}i:52;a:5:{s:6:"numero";i:52;s:10:"frecuencia";s:3:"253";s:9:"observado";s:6:"0,588%";s:7:"benford";s:6:"0,827%";s:9:"variacion";s:8:"- 0,239%";}i:53;a:5:{s:6:"numero";i:53;s:10:"frecuencia";s:3:"424";s:9:"observado";s:6:"0,985%";s:7:"benford";s:6:"0,812%";s:9:"variacion";s:8:"+ 0,173%";}i:54;a:5:{s:6:"numero";i:54;s:10:"frecuencia";s:3:"349";s:9:"observado";s:6:"0,811%";s:7:"benford";s:6:"0,797%";s:9:"variacion";s:8:"+ 0,014%";}i:55;a:5:{s:6:"numero";i:55;s:10:"frecuencia";s:3:"259";s:9:"observado";s:6:"0,602%";s:7:"benford";s:6:"0,783%";s:9:"variacion";s:8:"- 0,181%";}i:56;a:5:{s:6:"numero";i:56;s:10:"frecuencia";s:3:"283";s:9:"observado";s:6:"0,658%";s:7:"benford";s:6:"0,769%";s:9:"variacion";s:8:"- 0,111%";}i:57;a:5:{s:6:"numero";i:57;s:10:"frecuencia";s:3:"181";s:9:"observado";s:6:"0,421%";s:7:"benford";s:6:"0,755%";s:9:"variacion";s:8:"- 0,334%";}i:58;a:5:{s:6:"numero";i:58;s:10:"frecuencia";s:3:"239";s:9:"observado";s:6:"0,555%";s:7:"benford";s:6:"0,742%";s:9:"variacion";s:8:"- 0,187%";}i:59;a:5:{s:6:"numero";i:59;s:10:"frecuencia";s:3:"262";s:9:"observado";s:6:"0,609%";s:7:"benford";s:6:"0,730%";s:9:"variacion";s:8:"- 0,121%";}i:60;a:5:{s:6:"numero";i:60;s:10:"frecuencia";s:3:"407";s:9:"observado";s:6:"0,946%";s:7:"benford";s:6:"0,718%";s:9:"variacion";s:8:"+ 0,228%";}i:61;a:5:{s:6:"numero";i:61;s:10:"frecuencia";s:3:"280";s:9:"observado";s:6:"0,651%";s:7:"benford";s:6:"0,706%";s:9:"variacion";s:8:"- 0,055%";}i:62;a:5:{s:6:"numero";i:62;s:10:"frecuencia";s:3:"206";s:9:"observado";s:6:"0,479%";s:7:"benford";s:6:"0,695%";s:9:"variacion";s:8:"- 0,216%";}i:63;a:5:{s:6:"numero";i:63;s:10:"frecuencia";s:3:"258";s:9:"observado";s:6:"0,600%";s:7:"benford";s:6:"0,684%";s:9:"variacion";s:8:"- 0,084%";}i:64;a:5:{s:6:"numero";i:64;s:10:"frecuencia";s:3:"237";s:9:"observado";s:6:"0,551%";s:7:"benford";s:6:"0,673%";s:9:"variacion";s:8:"- 0,122%";}i:65;a:5:{s:6:"numero";i:65;s:10:"frecuencia";s:3:"262";s:9:"observado";s:6:"0,609%";s:7:"benford";s:6:"0,663%";s:9:"variacion";s:8:"- 0,054%";}i:66;a:5:{s:6:"numero";i:66;s:10:"frecuencia";s:3:"323";s:9:"observado";s:6:"0,751%";s:7:"benford";s:6:"0,653%";s:9:"variacion";s:8:"+ 0,098%";}i:67;a:5:{s:6:"numero";i:67;s:10:"frecuencia";s:3:"403";s:9:"observado";s:6:"0,936%";s:7:"benford";s:6:"0,643%";s:9:"variacion";s:8:"+ 0,293%";}i:68;a:5:{s:6:"numero";i:68;s:10:"frecuencia";s:3:"394";s:9:"observado";s:6:"0,916%";s:7:"benford";s:6:"0,634%";s:9:"variacion";s:8:"+ 0,282%";}i:69;a:5:{s:6:"numero";i:69;s:10:"frecuencia";s:3:"460";s:9:"observado";s:6:"1,069%";s:7:"benford";s:6:"0,625%";s:9:"variacion";s:8:"+ 0,444%";}i:70;a:5:{s:6:"numero";i:70;s:10:"frecuencia";s:3:"445";s:9:"observado";s:6:"1,034%";s:7:"benford";s:6:"0,616%";s:9:"variacion";s:8:"+ 0,418%";}i:71;a:5:{s:6:"numero";i:71;s:10:"frecuencia";s:3:"369";s:9:"observado";s:6:"0,857%";s:7:"benford";s:6:"0,607%";s:9:"variacion";s:8:"+ 0,250%";}i:72;a:5:{s:6:"numero";i:72;s:10:"frecuencia";s:3:"283";s:9:"observado";s:6:"0,658%";s:7:"benford";s:6:"0,599%";s:9:"variacion";s:8:"+ 0,059%";}i:73;a:5:{s:6:"numero";i:73;s:10:"frecuencia";s:3:"172";s:9:"observado";s:6:"0,400%";s:7:"benford";s:6:"0,591%";s:9:"variacion";s:8:"- 0,191%";}i:74;a:5:{s:6:"numero";i:74;s:10:"frecuencia";s:3:"384";s:9:"observado";s:6:"0,892%";s:7:"benford";s:6:"0,583%";s:9:"variacion";s:8:"+ 0,309%";}i:75;a:5:{s:6:"numero";i:75;s:10:"frecuencia";s:3:"239";s:9:"observado";s:6:"0,555%";s:7:"benford";s:6:"0,575%";s:9:"variacion";s:8:"- 0,020%";}i:76;a:5:{s:6:"numero";i:76;s:10:"frecuencia";s:3:"328";s:9:"observado";s:6:"0,762%";s:7:"benford";s:6:"0,568%";s:9:"variacion";s:8:"+ 0,194%";}i:77;a:5:{s:6:"numero";i:77;s:10:"frecuencia";s:3:"296";s:9:"observado";s:6:"0,688%";s:7:"benford";s:6:"0,560%";s:9:"variacion";s:8:"+ 0,128%";}i:78;a:5:{s:6:"numero";i:78;s:10:"frecuencia";s:3:"508";s:9:"observado";s:6:"1,180%";s:7:"benford";s:6:"0,553%";s:9:"variacion";s:8:"+ 0,627%";}i:79;a:5:{s:6:"numero";i:79;s:10:"frecuencia";s:3:"358";s:9:"observado";s:6:"0,832%";s:7:"benford";s:6:"0,546%";s:9:"variacion";s:8:"+ 0,286%";}i:80;a:5:{s:6:"numero";i:80;s:10:"frecuencia";s:3:"375";s:9:"observado";s:6:"0,871%";s:7:"benford";s:6:"0,540%";s:9:"variacion";s:8:"+ 0,331%";}i:81;a:5:{s:6:"numero";i:81;s:10:"frecuencia";s:3:"300";s:9:"observado";s:6:"0,697%";s:7:"benford";s:6:"0,533%";s:9:"variacion";s:8:"+ 0,164%";}i:82;a:5:{s:6:"numero";i:82;s:10:"frecuencia";s:3:"311";s:9:"observado";s:6:"0,723%";s:7:"benford";s:6:"0,526%";s:9:"variacion";s:8:"+ 0,197%";}i:83;a:5:{s:6:"numero";i:83;s:10:"frecuencia";s:3:"184";s:9:"observado";s:6:"0,428%";s:7:"benford";s:6:"0,520%";s:9:"variacion";s:8:"- 0,092%";}i:84;a:5:{s:6:"numero";i:84;s:10:"frecuencia";s:3:"215";s:9:"observado";s:6:"0,500%";s:7:"benford";s:6:"0,514%";s:9:"variacion";s:8:"- 0,014%";}i:85;a:5:{s:6:"numero";i:85;s:10:"frecuencia";s:3:"439";s:9:"observado";s:6:"1,020%";s:7:"benford";s:6:"0,508%";s:9:"variacion";s:8:"+ 0,512%";}i:86;a:5:{s:6:"numero";i:86;s:10:"frecuencia";s:3:"386";s:9:"observado";s:6:"0,897%";s:7:"benford";s:6:"0,502%";s:9:"variacion";s:8:"+ 0,395%";}i:87;a:5:{s:6:"numero";i:87;s:10:"frecuencia";s:3:"344";s:9:"observado";s:6:"0,799%";s:7:"benford";s:6:"0,496%";s:9:"variacion";s:8:"+ 0,303%";}i:88;a:5:{s:6:"numero";i:88;s:10:"frecuencia";s:3:"274";s:9:"observado";s:6:"0,637%";s:7:"benford";s:6:"0,491%";s:9:"variacion";s:8:"+ 0,146%";}i:89;a:5:{s:6:"numero";i:89;s:10:"frecuencia";s:3:"452";s:9:"observado";s:6:"1,050%";s:7:"benford";s:6:"0,485%";s:9:"variacion";s:8:"+ 0,565%";}i:90;a:5:{s:6:"numero";i:90;s:10:"frecuencia";s:3:"302";s:9:"observado";s:6:"0,702%";s:7:"benford";s:6:"0,480%";s:9:"variacion";s:8:"+ 0,222%";}i:91;a:5:{s:6:"numero";i:91;s:10:"frecuencia";s:3:"227";s:9:"observado";s:6:"0,528%";s:7:"benford";s:6:"0,475%";s:9:"variacion";s:8:"+ 0,053%";}i:92;a:5:{s:6:"numero";i:92;s:10:"frecuencia";s:3:"238";s:9:"observado";s:6:"0,553%";s:7:"benford";s:6:"0,470%";s:9:"variacion";s:8:"+ 0,083%";}i:93;a:5:{s:6:"numero";i:93;s:10:"frecuencia";s:3:"223";s:9:"observado";s:6:"0,518%";s:7:"benford";s:6:"0,464%";s:9:"variacion";s:8:"+ 0,054%";}i:94;a:5:{s:6:"numero";i:94;s:10:"frecuencia";s:3:"192";s:9:"observado";s:6:"0,446%";s:7:"benford";s:6:"0,460%";s:9:"variacion";s:8:"- 0,014%";}i:95;a:5:{s:6:"numero";i:95;s:10:"frecuencia";s:3:"171";s:9:"observado";s:6:"0,397%";s:7:"benford";s:6:"0,455%";s:9:"variacion";s:8:"- 0,058%";}i:96;a:5:{s:6:"numero";i:96;s:10:"frecuencia";s:3:"224";s:9:"observado";s:6:"0,521%";s:7:"benford";s:6:"0,450%";s:9:"variacion";s:8:"+ 0,071%";}i:97;a:5:{s:6:"numero";i:97;s:10:"frecuencia";s:3:"205";s:9:"observado";s:6:"0,476%";s:7:"benford";s:6:"0,445%";s:9:"variacion";s:8:"+ 0,031%";}i:98;a:5:{s:6:"numero";i:98;s:10:"frecuencia";s:3:"280";s:9:"observado";s:6:"0,651%";s:7:"benford";s:6:"0,441%";s:9:"variacion";s:8:"+ 0,210%";}i:99;a:5:{s:6:"numero";i:99;s:10:"frecuencia";s:3:"221";s:9:"observado";s:6:"0,514%";s:7:"benford";s:6:"0,436%";s:9:"variacion";s:8:"+ 0,078%";}}s:7:"grafica";a:2:{s:5:"data1";a:91:{i:0;s:5:"data1";i:1;s:5:"5.751";i:2;s:5:"6.363";i:3;s:5:"4.708";i:4;s:5:"2.428";i:5;s:5:"2.038";i:6;s:5:"1.982";i:7;s:5:"1.731";i:8;s:5:"1.868";i:9;s:5:"1.912";i:10;s:5:"2.054";i:11;s:5:"2.854";i:12;s:5:"2.003";i:13;s:5:"1.926";i:14;s:5:"1.448";i:15;s:5:"1.259";i:16;s:5:"1.564";i:17;s:5:"0.844";i:18;s:5:"1.025";i:19;s:5:"0.881";i:20;s:5:"0.651";i:21;s:5:"1.645";i:22;s:5:"0.600";i:23;s:5:"0.825";i:24;s:5:"1.039";i:25;s:5:"0.950";i:26;s:5:"0.704";i:27;s:5:"0.648";i:28;s:5:"0.957";i:29;s:5:"0.885";i:30;s:5:"0.699";i:31;s:5:"1.292";i:32;s:5:"1.139";i:33;s:5:"1.392";i:34;s:5:"1.127";i:35;s:5:"1.143";i:36;s:5:"0.855";i:37;s:5:"0.741";i:38;s:5:"0.546";i:39;s:5:"0.732";i:40;s:5:"0.704";i:41;s:5:"1.269";i:42;s:5:"1.294";i:43;s:5:"0.588";i:44;s:5:"0.985";i:45;s:5:"0.811";i:46;s:5:"0.602";i:47;s:5:"0.658";i:48;s:5:"0.421";i:49;s:5:"0.555";i:50;s:5:"0.609";i:51;s:5:"0.946";i:52;s:5:"0.651";i:53;s:5:"0.479";i:54;s:5:"0.600";i:55;s:5:"0.551";i:56;s:5:"0.609";i:57;s:5:"0.751";i:58;s:5:"0.936";i:59;s:5:"0.916";i:60;s:5:"1.069";i:61;s:5:"1.034";i:62;s:5:"0.857";i:63;s:5:"0.658";i:64;s:5:"0.400";i:65;s:5:"0.892";i:66;s:5:"0.555";i:67;s:5:"0.762";i:68;s:5:"0.688";i:69;s:5:"1.180";i:70;s:5:"0.832";i:71;s:5:"0.871";i:72;s:5:"0.697";i:73;s:5:"0.723";i:74;s:5:"0.428";i:75;s:5:"0.500";i:76;s:5:"1.020";i:77;s:5:"0.897";i:78;s:5:"0.799";i:79;s:5:"0.637";i:80;s:5:"1.050";i:81;s:5:"0.702";i:82;s:5:"0.528";i:83;s:5:"0.553";i:84;s:5:"0.518";i:85;s:5:"0.446";i:86;s:5:"0.397";i:87;s:5:"0.521";i:88;s:5:"0.476";i:89;s:5:"0.651";i:90;s:5:"0.514";}s:5:"data2";a:91:{i:0;s:5:"data2";i:1;s:5:"4.139";i:2;s:5:"3.779";i:3;s:5:"3.476";i:4;s:5:"3.218";i:5;s:5:"2.996";i:6;s:5:"2.803";i:7;s:5:"2.633";i:8;s:5:"2.482";i:9;s:5:"2.348";i:10;s:5:"2.228";i:11;s:5:"2.119";i:12;s:5:"2.020";i:13;s:5:"1.931";i:14;s:5:"1.848";i:15;s:5:"1.773";i:16;s:5:"1.703";i:17;s:5:"1.639";i:18;s:5:"1.579";i:19;s:5:"1.524";i:20;s:5:"1.472";i:21;s:5:"1.424";i:22;s:5:"1.379";i:23;s:5:"1.336";i:24;s:5:"1.296";i:25;s:5:"1.259";i:26;s:5:"1.223";i:27;s:5:"1.190";i:28;s:5:"1.158";i:29;s:5:"1.128";i:30;s:5:"1.100";i:31;s:5:"1.072";i:32;s:5:"1.047";i:33;s:5:"1.022";i:34;s:5:"0.998";i:35;s:5:"0.976";i:36;s:5:"0.955";i:37;s:5:"0.934";i:38;s:5:"0.914";i:39;s:5:"0.895";i:40;s:5:"0.877";i:41;s:5:"0.860";i:42;s:5:"0.843";i:43;s:5:"0.827";i:44;s:5:"0.812";i:45;s:5:"0.797";i:46;s:5:"0.783";i:47;s:5:"0.769";i:48;s:5:"0.755";i:49;s:5:"0.742";i:50;s:5:"0.730";i:51;s:5:"0.718";i:52;s:5:"0.706";i:53;s:5:"0.695";i:54;s:5:"0.684";i:55;s:5:"0.673";i:56;s:5:"0.663";i:57;s:5:"0.653";i:58;s:5:"0.643";i:59;s:5:"0.634";i:60;s:5:"0.625";i:61;s:5:"0.616";i:62;s:5:"0.607";i:63;s:5:"0.599";i:64;s:5:"0.591";i:65;s:5:"0.583";i:66;s:5:"0.575";i:67;s:5:"0.568";i:68;s:5:"0.560";i:69;s:5:"0.553";i:70;s:5:"0.546";i:71;s:5:"0.540";i:72;s:5:"0.533";i:73;s:5:"0.526";i:74;s:5:"0.520";i:75;s:5:"0.514";i:76;s:5:"0.508";i:77;s:5:"0.502";i:78;s:5:"0.496";i:79;s:5:"0.491";i:80;s:5:"0.485";i:81;s:5:"0.480";i:82;s:5:"0.475";i:83;s:5:"0.470";i:84;s:5:"0.464";i:85;s:5:"0.460";i:86;s:5:"0.455";i:87;s:5:"0.450";i:88;s:5:"0.445";i:89;s:5:"0.441";i:90;s:5:"0.436";}}s:2:"x1";a:91:{i:0;s:2:"x1";i:1;i:10;i:2;i:11;i:3;i:12;i:4;i:13;i:5;i:14;i:6;i:15;i:7;i:16;i:8;i:17;i:9;i:18;i:10;i:19;i:11;i:20;i:12;i:21;i:13;i:22;i:14;i:23;i:15;i:24;i:16;i:25;i:17;i:26;i:18;i:27;i:19;i:28;i:20;i:29;i:21;i:30;i:22;i:31;i:23;i:32;i:24;i:33;i:25;i:34;i:26;i:35;i:27;i:36;i:28;i:37;i:29;i:38;i:30;i:39;i:31;i:40;i:32;i:41;i:33;i:42;i:34;i:43;i:35;i:44;i:36;i:45;i:37;i:46;i:38;i:47;i:39;i:48;i:40;i:49;i:41;i:50;i:42;i:51;i:43;i:52;i:44;i:53;i:45;i:54;i:46;i:55;i:47;i:56;i:48;i:57;i:49;i:58;i:50;i:59;i:51;i:60;i:52;i:61;i:53;i:62;i:54;i:63;i:55;i:64;i:56;i:65;i:57;i:66;i:58;i:67;i:59;i:68;i:60;i:69;i:61;i:70;i:62;i:71;i:63;i:72;i:64;i:73;i:65;i:74;i:66;i:75;i:67;i:76;i:68;i:77;i:69;i:78;i:70;i:79;i:71;i:80;i:72;i:81;i:73;i:82;i:74;i:83;i:75;i:84;i:76;i:85;i:77;i:86;i:78;i:87;i:79;i:88;i:80;i:89;i:81;i:90;i:82;i:91;i:83;i:92;i:84;i:93;i:85;i:94;i:86;i:95;i:87;i:96;i:88;i:97;i:89;i:98;i:90;i:99;}s:2:"x2";a:91:{i:0;s:2:"x2";i:1;i:10;i:2;i:11;i:3;i:12;i:4;i:13;i:5;i:14;i:6;i:15;i:7;i:16;i:8;i:17;i:9;i:18;i:10;i:19;i:11;i:20;i:12;i:21;i:13;i:22;i:14;i:23;i:15;i:24;i:16;i:25;i:17;i:26;i:18;i:27;i:19;i:28;i:20;i:29;i:21;i:30;i:22;i:31;i:23;i:32;i:24;i:33;i:25;i:34;i:26;i:35;i:27;i:36;i:28;i:37;i:29;i:38;i:30;i:39;i:31;i:40;i:32;i:41;i:33;i:42;i:34;i:43;i:35;i:44;i:36;i:45;i:37;i:46;i:38;i:47;i:39;i:48;i:40;i:49;i:41;i:50;i:42;i:51;i:43;i:52;i:44;i:53;i:45;i:54;i:46;i:55;i:47;i:56;i:48;i:57;i:49;i:58;i:50;i:59;i:51;i:60;i:52;i:61;i:53;i:62;i:54;i:63;i:55;i:64;i:56;i:65;i:57;i:66;i:58;i:67;i:59;i:68;i:60;i:69;i:61;i:70;i:62;i:71;i:63;i:72;i:64;i:73;i:65;i:74;i:66;i:75;i:67;i:76;i:68;i:77;i:69;i:78;i:70;i:79;i:71;i:80;i:72;i:81;i:73;i:82;i:74;i:83;i:75;i:84;i:76;i:85;i:77;i:86;i:78;i:87;i:79;i:88;i:80;i:89;i:81;i:90;i:82;i:91;i:83;i:92;i:84;i:93;i:85;i:94;i:86;i:95;i:87;i:96;i:88;i:97;i:89;i:98;i:90;i:99;}s:3:"mad";s:13:"<b>0,0031</b>";s:11:"madDescribe";s:21:"<b>No Conformidad</b>";s:7:"mad_d12";a:4:{i:0;a:3:{s:3:"min";i:0;s:3:"max";d:0.0012;s:11:"descripcion";s:26:"Conformidad Cuasi-Perfecta";}i:1;a:3:{s:3:"min";d:0.0012;s:3:"max";d:0.0018;s:11:"descripcion";s:21:"Conformidad Aceptable";}i:2;a:3:{s:3:"min";d:0.0018;s:3:"max";d:0.0022;s:11:"descripcion";s:35:"Conformidad Marginalmente Aceptable";}i:3;a:3:{s:3:"min";d:0.0022;s:3:"max";i:10000;s:11:"descripcion";s:14:"No Conformidad";}}}s:4:"form";a:5:{s:13:"campoAnalizar";s:7:"campo10";s:17:"archivoIdProcesar";s:16:"1627562318735920";s:6:"digito";s:2:"12";s:9:"ejecucion";s:15:"375582074733891";s:2:"id";s:16:"1635468455024500";}}')
        ];
        $this->pdf->run($data, $dataPdf);
    }
    
    public function carpetas() {
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('id', $post)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 400);
            }            
            $items = $this->arbolr->run($post['id']);
            if (!is_array($items)) {
                throw new Exception("No existen datos para mostrar", 418);
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
