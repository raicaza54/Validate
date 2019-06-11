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
    private $id = NULL;

    function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->load->model(['Cliente_model','Archivos_model']);
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
    
    
    private function leer_csv($param) {
        set_time_limit(0);
        extract($param);
        $fileType = $this->fileType($type, 0);
        $archivo = FALSE;
        $response = $this->response;
        try{
            $outsheet = []; $x = 0;
            $created_user  = $this->session->userdata('users_id');
            $created_clie = $this->session->userdata('clientes_id');
            $update_user   = $this->session->userdata('users_id');
            $update_clie   = $this->session->userdata('clientes_id');
            $linea = 'e';
            $this->id = uniqint();
            $num = 0;
            for ($f = 1; $f <= 20; $f++) {
                $tipos['campo'.$f] = 'string';
                $header[] = 'campo'.$f;
            }
            $sheet = $this->csvimport->get_array($fullpath, $header, FALSE, FALSE, ';');
            $c = 0;
            foreach ($sheet['line_headers'] as $value) {
                $c++;
                $line_headers['campo'.$c] = $value;
                if($c >= 20){
                    break;
                }
            }
            if($c < 20){
                for ($f = $c; $f <= 20; $f++) {
                    $line_headers['campo'.$f] = '';
                }                
            }
            $sheet = $sheet['result'];
            if(count($sheet) > $limite['filas']){
                return [
                    'error' => 'El archivo ('.number_format(count($sheet),0,'.',',').') supera el limite ('.number_format($limite['filas'],0,'.',',').') permitido para este tipo de archivo'
                ];
            }
            array_unshift($sheet, $line_headers);
            foreach ($sheet as $value) {
                $outsheet[$x] = [
                    'fk_archivos'   => $this->id,
                    'linea'         => $linea,
                    'campo1'        => substr($value['campo1'],  0, 100),
                    'campo2'        => substr($value['campo2'],  0, 100),
                    'campo3'        => substr($value['campo3'],  0, 100),
                    'campo4'        => substr($value['campo4'],  0, 100),
                    'campo5'        => substr($value['campo5'],  0, 100),
                    'campo6'        => substr($value['campo6'],  0, 100),
                    'campo7'        => substr($value['campo7'],  0, 100),
                    'campo8'        => substr($value['campo8'],  0, 100),
                    'campo9'        => substr($value['campo9'],  0, 100),
                    'campo10'       => substr($value['campo10'], 0, 100),
                    'campo11'       => substr($value['campo11'], 0, 100),
                    'campo12'       => substr($value['campo12'], 0, 100),
                    'campo13'       => substr($value['campo13'], 0, 100),
                    'campo14'       => substr($value['campo14'], 0, 100),
                    'campo15'       => substr($value['campo15'], 0, 100),
                    'campo16'       => substr($value['campo16'], 0, 100),
                    'campo17'       => substr($value['campo17'], 0, 100),
                    'campo18'       => substr($value['campo18'], 0, 100),
                    'campo19'       => substr($value['campo19'], 0, 100),
                    'campo20'       => substr($value['campo20'], 0, 100),
                    'created_user'  => $created_user,
                    'created_clie' => $created_clie,
                    'update_user'   => $update_user,
                    'update_clie'   => $update_clie
                ];
                $x++;
                $linea = 'f';
            }
            $archivo = [
                'archivo' => [
                    'id'            => $this->id,
                    'fk_carpetas'   => $parent_id,
                    'nombre'        => $filename,
                    'file_name'     => $fullpath,
                    'ext'           => $type,
                    'tipo'          => $tipo,
                    'created_user'  => $created_user,
                    'created_clie' => $created_clie,
                    'update_user'   => $update_user,
                    'update_clie'   => $update_clie,
                ],
                'detalle' => $outsheet
            ];
        } catch (Exception $exc) {
            $status = $exc->getCode();
        }
        if($status == 200){
            return $archivo;
        }else{
            log_message('error', $status.': '.$exc->getMessage());
            return FALSE;
        }
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
            $created_clie = $this->session->userdata('clientes_id');
            $update_user   = $this->session->userdata('users_id');
            $update_clie   = $this->session->userdata('clientes_id');
            $linea = 'e';
            $this->id = uniqint();
            $num = 0;
            for ($f = 1; $f <= 20; $f++) {
                $tipos['campo'.$f] = 'string';
            }
            if(count($sheet) > $limite['filas']){
                return [
                    'error' => 'El archivo posee ('.number_format(count($sheet),0,'.',',').') filas, las cuales superan el limite  de ('.number_format($limite['filas'],0,'.',',').') filas permitidas para este tipo de archivo'
                ];
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
                    'created_clie' => $created_clie,
                    'update_user'   => $update_user,
                    'update_clie'   => $update_clie
                ];
                $x++;
                $linea = 'f';
            }
            $archivo = [
                'archivo' => [
                    'id'            => $this->id,
                    'fk_carpetas'   => $parent_id,
                    'nombre'        => $filename,
                    'file_name'     => $fullpath,
                    'ext'           => $type,
                    'tipo'          => $tipo,
                    'created_user'  => $created_user,
                    'created_clie'  => $created_clie,
                    'update_user'   => $update_user,
                    'update_clie'   => $update_clie,
                ],
                'detalle' => $outsheet
            ];
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $status = $exc->getCode();
        }
        if($status == 200){
            return $archivo;
        }else{
            log_message('error', $status.': '.$exc->getMessage());
            return FALSE;
        }
    }
    
    public function subir() {
        try {
            if(strlen($this->permisos->viewaccess('mpe-importar-archivo')) > 0){
                throw new Exception("Tenemos un problema, usted no tiene permisos para ejecutar esta funcionalidad", 400);
            }
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('folderId', $post) || !array_key_exists('tipo', $post)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 400);
            }
            $limites = $this->limites(FALSE);
            $tipo = '';
            if($post['tipo'] == 'mov'){
                $tipo = 'movimiento';
            }elseif($post['tipo'] == 'blp'){
                $tipo = 'balances';
            }else{
                $tipo = $post['tipo'];
            }
            if(!is_array($limites['data']) || !array_key_exists($tipo, $limites['data'])){
                throw new Exception("Tenemos un problema, el tipo de archivo definido no es correcto", 400);
            }
            $limite = $limites['data'][$tipo];
            if(($limite['cant'] + 1) > $limite['limite']){
                throw new Exception("Tenemos un problema, este tipo de archivo supera la cantidad permitida, le sugerimos contactar con el ejecutivo de ventas encargado", 400);
            }
            $archivo = $this->do_upload();
            if(is_bool($archivo) || ($archivo === FALSE)){
                throw new Exception("Tenemos un problema con el archivo", 418);
            }
            if(($archivo['type'] == '.xls') || ($archivo['type'] == '.xlsx')){
                $xls = $this->leer_excel($archivo + [
                    'parent_id' => $post['folderId'],
                    'tipo'      => $post['tipo'],
                    'limite'    => $limite,
                ]);
            }elseif($archivo['type'] == '.csv'){
                $xls = $this->leer_csv($archivo + [
                    'parent_id' => $post['folderId'],
                    'tipo'      => $post['tipo'],
                    'limite'    => $limite,
                ]);
            }
            if(is_array($xls) && array_key_exists('error', $xls)){
                throw new Exception($xls['error'], 418);
            }
            $xlsdb = $this->Archivos_model->insert_excel($xls);
            if(is_bool($xlsdb) && ($xlsdb === FALSE)){
                throw new Exception("Tenemos un problema al insertar el archivo en la nube con el archivo", 418);
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
            ->set_status_header($response['status'])
            ->set_output(json_encode($response));
    }
    
    private function do_upload() {
        $r = FALSE;
        $config['upload_path']      = $this->config->item('path_clie').'archivos';
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
    
    private function columnsDef($e, $key) {
        $r = FALSE;
        switch ($e) {
            case 'date':
                $r = "DATE_FORMAT(".$key.", '%d/%m/%Y') AS ".$key;
                break;
            case 'float':
                $r = "FORMAT(".$key.", 2, 'de_DE') AS ".$key;
                break;
            default:
                $r = $key;
                break;
        }
        return $r;
    }    

    public function header() {
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('id', $post)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 400);
            }
            $campoAnalizar = NULL;
            if(array_key_exists('campoAnalizar', $post)){
                $campoAnalizar = $post['campoAnalizar'];
            }
            $columnas = $this->archivo->columnas($post['id']);
            $dataColumns = $this->Archivos_model->getEncabezado($post['id'], $campoAnalizar);
            if (!is_array($dataColumns)) {
                throw new Exception("No existen datos para mostrar", 418);
            }
            $columns = [];
            $columnsDef = [];
            $x = 0;
            foreach ($dataColumns['encabezado'] as $key => $value) {
                $columns[] = [
                    'title' => $value,
                    'data'  => $key
                ];
                $x++;
            }
            $response = [
                "archivo"   => $columnas['archivo'],
                "column"    => $columns,
                "columnDef" => $columnas['columnDef'],
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
    
    public function datos() {
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('id', $post)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 400);
            }
            $items = $this->Archivos_model->getRows($this->input->post());
            if (!is_array($items)) {
                throw new Exception("No existen datos para mostrar", 418);
            }
            $response = [
                "draw"            => $this->input->post('draw'),
                "recordsTotal"    => $this->Archivos_model->countAll($this->input->post()),
                "recordsFiltered" => $this->Archivos_model->countFiltered($this->input->post()),
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
    
    public function limites($json = TRUE) {
        $response = $this->response;
        try {
            $cliente_id = $this->session->userdata('clientes_id');
            $limites = $this->Cliente_model->getLimites($cliente_id);
            if (!is_array($limites)) {
                throw new Exception("No existen datos para mostrar", 418);
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
    
    public function configurar() {
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('form', $post) || (count($post['form']) <= 0)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 400);
            }
            $form = unSerializeArray($post['form']);
            if(!is_array($form) || !array_key_exists('archivoId', $form) || !array_key_exists('campo1', $form)){
                throw new Exception("Tenemos un problema, faltan algunos datos, estan incompletos o corruptos", 400);
            }
            if(!array_key_exists('archivoTipo', $form)){
                throw new Exception("Tenemos un problema, faltan algunos datos, como el tipo de archivo, estan incompletos o corruptos", 400);
            }
            if(!in_array($form['archivoTipo'], ['mov','blp','cxc','cxp'])){
                throw new Exception("Tenemos un problema, faltan algunos datos, el tipo no es compatible, estan incompletos o corruptos", 400);
            }
            $columnas = $this->archivo->configColumnas($form);
            $this->Archivos_model->setColumnas($columnas, $form, $form['archivoId']);
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
    
    public function encabezado($benford = 0) {
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('id', $post)){
                throw new Exception("Tenemos un problema, no encontramos el detalle del archivo seleccionado", 400);
            }
            $items = $this->Archivos_model->getEncabezado($post['id']);
            if (!is_array($items) && count($items)) {
                throw new Exception("No existen datos para mostrar", 418);
            }
            $x = 0;
            if($benford == 1){
                foreach ($items['columnDef'] as $value) {
                    if(in_array($value[1], ['num','float','int'])){
                        $x++;
                    }
                }
                if ($x <= 0) {
                    throw new Exception("El archivo no posee campos de tipo número o valores para analizar, verifique e intentelo nuevamente", 400);
                }                
            }
            $response["data"] = [
                'encabezado' => $items['encabezado'],
                'columnDef'  => $items['columnDef'],
                'tipo'       => $items['tipo']
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
    
    public function cuentas() {
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('id', $post)){
                throw new Exception("Tenemos un problema, no encontramos el detalle del archivo seleccionado", 400);
            }
            $items = $this->Archivos_model->getCuentas($post['id']);
            if (!is_array($items)) {
                throw new Exception("No existen datos para mostrar", 418);
            }
            $column = $this->archivo->columnSpider($post['id']);
            if(!is_array($column) || !array_key_exists('array', $column)){
                throw new Exception("Tenemos un problema, no estan definidas las columnas del archivo, se requiere configuración", 418);
            }
            if(!(is_array($column['array']) && (count($column['array']) >= 0))){
                throw new Exception("Tenemos un problema, el tipo de columna no corresponde con el archivo, se requiere configuración", 418);
            }
            if(count(array_diff(['cta','comp','doc','tipo','valor'], array_keys($column['array']))) > 0){
                throw new Exception("Tenemos un problema, las columnas no están definidas del todo para poder aplicar el análisis de La Araña", 400);
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
    
    public function digito() {
        $response = $this->response;
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('id', $post) || !array_key_exists('digito', $post) || !array_key_exists('grafica', $post) || !array_key_exists('campoAnalizar', $post)){
                throw new Exception("Tenemos un problema, no encontramos el detalle del archivo seleccionado", 400);
            }
            $items = $this->Archivos_model->getRows($post);
            if (!is_array($items)) {
                throw new Exception("No existen datos para mostrar", 418);
            }
            $response = [
                "draw"            => $this->input->post('draw'),
                "recordsTotal"    => $this->Archivos_model->countAll($this->input->post()),
                "recordsFiltered" => $this->Archivos_model->countFiltered($this->input->post()),
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
