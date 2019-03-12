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
    private $id = NULL;

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

    private function fileType($type, $t) {
        $fileType = FALSE;
        switch ($type) {
            case '.xlsx':
                $fileType = ($t == 0) ? 'Excel2007' : 'excel';
                break;
            case '.xls':
                $fileType = ($t == 0) ?'Excel5' : 'excel';
                break;
            case '.csv':
                $fileType = ($t == 0) ?'CSV' : 'csv';
                break;
            default:
                return FALSE;
                break;
        }
        return $fileType;
    }
    
    /**
     * Tipo de archivo permitidos filtrados por la opcion de carga de 
     * archivos nativa de codeigniter
     * 
     * Excel2007, Excel5, Excel2003XML, OOCalc, SYLK, Gnumeric, HTML, CSV
     * 
     * @param type $inputFile path de archivo
     */
    private function leer_excel($param) {
        set_time_limit(0);
        extract($param);
        $this->load->library('phpexcel');
        $this->load->library('PHPExcel/iofactory');        
        $fileType = $this->fileType($type, 0);
        $archivo = FALSE;
        try{
            $objIOReader = new IOFactory();
            $objPHPExcel = $objIOReader->createReader($fileType);
            $spreadsheet = $objPHPExcel->load($fullpath);
            $worksheet   = $spreadsheet->setActiveSheetIndex(0);
            $highestRow  = $worksheet->getHighestRow();
            $highestCol  = $worksheet->getHighestColumn();
            $sheet = $worksheet->rangetoArray("A1:T$highestRow",NULL, TRUE, FALSE, TRUE);
            $outsheet = []; $x = 0;
            $created_user  = $this->session->userdata('users_id');
            $created_clier = $this->session->userdata('clientes_id');
            $update_user   = $this->session->userdata('users_id');
            $update_clie   = $this->session->userdata('clientes_id');
            $linea = 'e';
            $this->id = uniqint();
            $tipos = [];
            for ($f = 1; $f <= 20; $f++) {
                $tipos['campo'.$f] = FALSE;
            }            
            foreach ($sheet as $value) {
                $outsheet[$x] = [
                    'fk_archivos'   => $this->id,
                    'linea'         => $linea,
                    'campo1'        => substr($value['A'], 0, 100),
                    'campo2'        => substr($value['B'], 0, 100),
                    'campo3'        => substr($value['C'], 0, 100),
                    'campo4'        => substr($value['D'], 0, 100),
                    'campo5'        => substr($value['E'], 0, 100),
                    'campo6'        => substr($value['F'], 0, 100),
                    'campo7'        => substr($value['G'], 0, 100),
                    'campo8'        => substr($value['H'], 0, 100),
                    'campo9'        => substr($value['I'], 0, 100),
                    'campo10'       => substr($value['J'], 0, 100),
                    'campo11'       => substr($value['K'], 0, 100),
                    'campo12'       => substr($value['L'], 0, 100),
                    'campo13'       => substr($value['M'], 0, 100),
                    'campo14'       => substr($value['N'], 0, 100),
                    'campo15'       => substr($value['O'], 0, 100),
                    'campo16'       => substr($value['P'], 0, 100),
                    'campo17'       => substr($value['Q'], 0, 100),
                    'campo18'       => substr($value['R'], 0, 100),
                    'campo19'       => substr($value['S'], 0, 100),
                    'campo20'       => substr($value['T'], 0, 100),
                    'created_user'  => $created_user,
                    'created_clier' => $created_clier,
                    'update_user'   => $update_user,
                    'update_clie'   => $update_clie
                ];
                if($linea == 'f'){
                    for ($f = 1; $f <= 20; $f++) {
                        if(strlen($outsheet[$x]['campo'.$f])){
                            if(is_int($outsheet[$x]['campo'.$f])){
                                $tipos['campo'.$f] = TRUE;
                            }
                        }
                    }
                }
                $x++;
                $linea = 'f';
            }
            $archivo = [
                'archivo' => [
                    'id'            => $this->id,
                    'fk_carpetas'   => $parent_id,
                    'nombre'        => $filename,
                    'tipo'          => $type,
                    'created_user'  => $created_user,
                    'created_clier' => $created_clier,
                    'update_user'   => $update_user,
                    'update_clie'   => $update_clie,
                    'columnas'      => json_encode($tipos)
                ],
                'detalle' => $outsheet
            ];
            
        } catch (Exception $ex) {
            return FALSE;
        }
        return $archivo;
    }
    
    public function subir() {
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('carpeta', $post)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 204);
            }
            $archivo = $this->do_upload();
            if(is_bool($archivo) || ($archivo === FALSE)){
                throw new Exception("Tenemos un problema con el archivo", 204);
            }
            $xls = $this->leer_excel($archivo + ['parent_id' => $post['carpeta']]);
            $xlsdb = $this->Archivos_model->insert_excel($xls);
            if(is_bool($xlsdb) || ($xlsdb === FALSE)){
                throw new Exception("Tenemos un problema al insertar el archivo en la nube con el archivo", 204);
            }
            $response["data"] = [
                'type'     => $this->fileType($archivo['type'], 1),
                'filename' => $archivo['filename'],
                'id'       => $this->id,
            ];
            throw new Exception("Resultado retornando correctamente", 200);            
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    }
    
    private function do_upload() {
        $r = FALSE;
        $config['upload_path']      = $this->config->item('path_file');
        $config['allowed_types']    = $this->config->item('types_file');
        $config['max_size']         = $this->config->item('size_file');
        $config['file_ext_tolower'] = TRUE;
        $config['encrypt_name']  = TRUE;        
        $this->load->library('upload', $config);
        if ($this->upload->do_upload("archivo")) {
            $data = array('upload_data' => $this->upload->data());
            $archivo  = $data['upload_data']['client_name'];
            $fullpath = $data['upload_data']['full_path'];
            $type     = $data['upload_data']['file_ext'];
            return array(
                'filename' => $archivo,
                'fullpath' => $fullpath,
                'type'     => $type,
            );
        }else{
            log_message('error', var_export($this->upload->display_errors(), TRUE));
            return FALSE;
        }
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
            $tabla_count = '500 de '.$this->Archivos_model->getDetalleCountId($post['id']);
            $response["data"] = [
                'tabla'       => $items,
                'tabla_count' => $tabla_count,
            ];
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
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
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    }
    
    public function cuentas() {
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('id', $post)){
                throw new Exception("Tenemos un problema, no encontramos el detalle del archivo seleccionado", 204);
            }            
            $items = $this->Archivos_model->getCuentas($post['id']);
            if (!is_array($items)) {
                throw new Exception("No existen datos para mostrar", 204);
            }
            $response["data"] = $items;
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    }
    
    public function digito() {
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('id', $post) || !array_key_exists('digito', $post) || !array_key_exists('grafica', $post) || !array_key_exists('campoAnalizar', $post)){
                throw new Exception("Tenemos un problema, no encontramos el detalle del archivo seleccionado", 204);
            }
            $items = $this->Archivos_model->getDetalleId($post['id'], $post['digito'], $post['grafica'], $post['campoAnalizar']);
            if (!is_array($items)) {
                throw new Exception("No existen datos para mostrar", 204);
            }
            $tabla_count = '500 de '.$this->Archivos_model->getDetalleCountId($post['id'], $post['digito'], $post['grafica'], $post['campoAnalizar']);
            $response["data"] = [
                'tabla'       => $items,
                'tabla_count' => $tabla_count
            ];
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
