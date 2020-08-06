<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Controlador Perfil
 * @LastUpdate  2019-05-10
 */
class Perfil extends CI_Controller {

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

    public function datos() {
        $this->config->load('imagenes');
        $response = $this->response;
        try {
            $cliente_id = $this->session->userdata('clientes_id');
            $users_id = $this->session->userdata('users_id');
            $empresaId = $this->session->userdata('empresaId');
            $contrato = $this->Cliente_model->getContrato($cliente_id);
            $disco = $this->Cliente_model->disco();
            $columnas = $this->Empresas_model->configGetColumDefault($users_id, $empresaId, $cliente_id);
            if(!is_array($contrato) || (count($contrato) <= 0)){
                throw new Exception("Tenemos un problema con el contrato, por favor contactar con soporte", 202);
            }
            $usuario = $this->Perfil_model->getUsuario($users_id);
            if(!is_array($usuario) || (count($usuario) <= 0)){
                throw new Exception("Tenemos un problema con su cuenta de usuario, por favor contactar con soporte", 202);
            }
            $extension = pathinfo($usuario['empr_logotipo'], PATHINFO_EXTENSION);
            $b64 = null;
            if(!isset($usuario['empr_logotipo']) && empty($usuario['empr_logotipo'])){
                $b64 = $this->config->item('img_300x300');
            }elseif(isset($usuario['empr_logotipo'])){
                $b64 = $this->base64_encode_image($usuario['empr_logotipo'], $extension);
            }
            $response = [
                "data" => [
                    'contrato' => $contrato,
                    'usuario'  => $usuario,
                    'disco'    => $disco,
                    'columnas' => $columnas,
                    'logo'     => $b64
                ],
            ];
            //$this->archivo->columnaCompare($empresaId, $this->session->userdata('archivoId'), 'blp');
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($response['status'])
            ->set_output(json_encode($response));
    }
    
    public function datosCompra() {
        $response = $this->response;
        try {
            $cliente_id = $this->session->userdata('clientes_id');
            $users_id = $this->session->userdata('users_id');
            $cliente = $this->Perfil_model->getDatosCompra($users_id);
            $response = [
                "data" => [
                    'cliente' => $cliente,
                ],
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
    
    public function disco() {
        $response = $this->response;
        try {
            $cliente_id = $this->session->userdata('clientes_id');
            $users_id = $this->session->userdata('users_id');
            $disco = $this->Cliente_model->disco();
            $response = [
                "data" => [
                    'disco' => $disco,
                ],
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
    
    public function saveCompra() {
        $response = $this->response;
        $data = [];
        try {
            $post = $this->input->post();
            $form = unSerializeArray($post['form']);
            $this->form_validation->set_data($form);
            $this->form_validation->set_rules('com-nombre',      'Nombre y Apellido',    'required|max_length[250]');
            $this->form_validation->set_rules('com-correo',      'Correo Electrónico',   'required|valid_email|max_length[250]');
            $this->form_validation->set_rules('com-telefono',    'Teléfono de Contacto', 'required|max_length[250]');
            $this->form_validation->set_rules('com-observacion', 'Observación',          'max_length[1024]');
            if ($this->form_validation->run() == FALSE){
                throw new Exception(validation_errors('',''), 202);
            }
            $perfil = $this->Perfil_model->setDatosCompra([
                'nombre'      => $form['com-nombre'],
                'correo'      => $form['com-correo'],
                'telefono'    => $form['com-telefono'],
                'observacion' => $form['com-observacion'],
                'fk_clientes' => $this->session->userdata('clientes_id'),
                'fk_users'    => $this->session->userdata('users_id'),
                'user_ip'     => $this->input->ip_address(),
            ], $this->session->userdata('users_id'));
            if($perfil == FALSE){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }
            $response = ["data" => ''];            
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response = $this->tryCatch($exc, $response);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($response['status'])
            ->set_output(json_encode($response));
    }    
    
    public function columnasPorDefecto() {
        $response = $this->response;
        $data = [];
        try {
            $post = $this->input->post();
            $this->form_validation->set_rules('id', 'Identificador', 'required|max_length[50]');
            $this->form_validation->set_rules('archivoFormato', 'Formato de archivo', 'required|in_list[naturaleza,debehaber]');
            $this->form_validation->set_rules('archivoTipo', 'Tipo de archivo', 'required|in_list[mov,blp,cxc,cxp]');
            if ($this->form_validation->run() == FALSE){
                throw new Exception(validation_errors('',''), 202);
            }
            $cliente_id = $this->session->userdata('clientes_id');
            $users_id = $this->session->userdata('users_id');
            $empresaId = $this->session->userdata('empresaId');
            $columnas = $this->Empresas_model->configGetColumDefault($users_id, $empresaId, $cliente_id);
            if($post['archivoTipo'] == 'blp'){
                $data = $columnas['columnas_blp'];
            }elseif($post['archivoTipo'] == 'mov' && $post['archivoFormato'] == 'naturaleza'){
                $data = $columnas['columnas_movnat'];
            }elseif($post['archivoTipo'] == 'mov' && $post['archivoFormato'] == 'debehaber'){
                $data = $columnas['columnas_movdhb'];
            }
            $response = [
                "data" => $data
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
    
    public function saveColumnas() {
        $response = $this->response;
        $data = [];
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('form', $post) || (count($post['form']) <= 0)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }
            $form = unSerializeArray($post['form']);
            $cliente_id = $this->session->userdata('clientes_id');
            $users_id   = $this->session->userdata('users_id');
            $empresaId  = $this->session->userdata('empresaId');            
            $columnas = json_decode($this->archivo->configColumnas($form), TRUE);
            $config = $this->Empresas_model->configGetEmpresa($users_id, $empresaId, $cliente_id);            
            if(array_key_exists('columnas_' . $form['tipo'], $config)){
                $data['columnas_' . $form['tipo']] = unserialize($config['columnas_' . $form['tipo']]);
                $data['columnas_' . $form['tipo']]['columnDef'] = $columnas;
                $data['columnas_' . $form['tipo']] = serialize($data['columnas_' . $form['tipo']]);
                $this->Empresas_model->configColumDefault($data, $config['fk_empresas']);
            }else{
                throw new Exception("Tenemos un problema, los datos de tipo de archivo estan incompletos o corruptos", 202);
            }
            $response = [
                "data" => []
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
    
    public function saveEmpresa() {
        $response = $this->response;
        $data = [];
        try {
            $form = $this->input->post();
            if(!is_array($form)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }
            if(!is_array($form) || !array_key_exists('idCliente', $form)){
                throw new Exception("Tenemos un problema, debe contactar a soporte tecnico", 202);
            }
            $id = openCypher('decrypt', $form['idCliente']);
            if(is_bool($form) && ($id === FALSE)){
                log_message('error', 'Al intentar hacer decrypt al id de usuario este no corresponde');
                throw new Exception("Tenemos un problema, los datos son corruptos e ilegibles, debe contactar a soporte tecnico", 202);
            }
            $this->form_validation->set_data($form);
            $this->form_validation->set_rules('empr_correo',         'Correo Electrónico', 'max_length[500]');
            $this->form_validation->set_rules('empr_telefonos',      'Teléfonos',          'max_length[150]');
            $this->form_validation->set_rules('empr_direccion',      'Dirección',          'max_length[500]');
            $this->form_validation->set_rules('empr_firma',          'Firma',              'max_length[2000]');
            $this->form_validation->set_rules('empr_usarlogotipo',   'Utilizar Logotipo',  'max_length[10]|in_list[si,no]');
            $this->form_validation->set_rules('empr_usarfirma',      'Utilizar Firma',     'max_length[10]|in_list[si,no]');
            $this->form_validation->set_rules('empr_color',          'Color Gráficas',     'max_length[50]');
            if ($this->form_validation->run() == FALSE){
                throw new Exception(validation_errors('',''), 202);
            }
            $archivo = [];
            if (!empty($_FILES['archivo']['name'])){
                $archivo = $this->do_upload();
            }
            if(isset($archivo['msg']) && $archivo['msg'] != 'Success'){
                throw new Exception($archivo['msg'], 202);
            }
            $data = [
                'direccion'     => $form['empr_direccion'],
                'telefonos'     => $form['empr_telefonos'],
                'correo'        => $form['empr_correo'],
                'usar_logotipo' => $form['empr_usarlogotipo'],
                'firma'         => $form['empr_firma'],
                'usar_firma'    => $form['empr_usarfirma'],
                'color'         => $form['empr_color'],
            ];
            if(isset($archivo['fullpath']) && !empty($archivo['fullpath'])){
                $data['path_logotipo'] = $archivo['fullpath'];
            }
            $this->Cliente_model->updateCliente($data, $id);
            $response = [
                "data" => []
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
    
    public function saveLimite() {
        $response = $this->response;
        $data = [];
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('form', $post) || (count($post['form']) <= 0)){
                log_message('error', 'La clave form no esta definida en el arreglo: '.var_export($post, TRUE));
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }
            $form = unSerializeArray($post['form']);
            if(!is_array($form)){
                log_message('error', 'El formulario no es un arreglo: '.var_export($form, TRUE));
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }
            if(!is_array($form) || !array_key_exists('idCliente', $form)){
                log_message('error', 'idCliente no esta definido: '.var_export($form, TRUE));
                throw new Exception("Tenemos un problema, debe contactar a soporte tecnico", 202);
            }
            $this->form_validation->set_data($form);
            $this->form_validation->set_rules('empr_usardemo',      'Utilizar Empresa Demo',     'max_length[10]|in_list[si,no]');
            if ($this->form_validation->run() == FALSE){
                throw new Exception(validation_errors('',''), 202);
            }
            if(in_array($this->session->userdata('clientes_id'), $this->config->item('id_demo'))){
                log_message('error', 'El cliente intenta desactivar el demo');
                throw new Exception('Cuenta Demostración, no es posible desactivar los datos, la misma es unicamente para fines demostrativos', 202);
            }
            $data = [
                'usar_demo' => $form['empr_usardemo'],
            ];
            $this->Cliente_model->updateCliente($data, $this->session->userdata('clientes_id'));
            $this->session->set_userdata([
                'demo'      => $data['usar_demo'],
                'empresaId' => 1
            ]);
            if(($form['empr_usardemo'] == 'no') && ($this->session->userdata('empresaId') == 1)){
                $this->session->unset_userdata('empresaId');
            }
            $response = [
                "data" => []
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
        $config['upload_path']      = $this->config->item('path_graficas');
        $config['overwrite']        = TRUE;
        $config['allowed_types']    = 'png|jpg|jpeg';
        $config['max_size']         = $this->config->item('size_file');
        $config['file_ext_tolower'] = TRUE;
        $config['file_name']        = 'logo';
        $config['max_width']        = '300';
        $config['max_height']       = '300';
        $config['min_width']        = '200';
        $config['min_height']       = '200';
        $this->load->library('upload', $config);
        if ($this->upload->do_upload("archivo")) {
            $data = array('upload_data' => $this->upload->data());
            $archivo  = $data['upload_data']['client_name'];
            $fullpath = $data['upload_data']['full_path'];
            $type     = $data['upload_data']['file_ext'];
            return array(
                'status'   => TRUE,
                'filename' => $archivo,
                'fullpath' => $fullpath,
                'type'     => $type,
                'msg'      => 'Success',
            );
        }else{
            log_message('error', var_export($this->upload->display_errors('',''), TRUE));
            return array(
                'status'   => FALSE,
                'filename' => '',
                'fullpath' => '',
                'type'     => '',
                'msg'      => $this->upload->display_errors('',''),
            );            
        }
    }    
    
    function base64_encode_image ($filename = string, $filetype = string) {
        if ($filename) {
            $imgbinary = fread(fopen($filename, "r"), filesize($filename));
            return 'data:image/' . $filetype . ';base64,' . base64_encode($imgbinary);
        }
    }    
    
    public function saveDatos() {
        $response = $this->response;
        $data = [];
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('form', $post) || (count($post['form']) <= 0)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 202);
            }
            $form = unSerializeArray($post['form']);
            if(!is_array($form) || !array_key_exists('idUsuario', $form)){
                throw new Exception("Tenemos un problema, debe contactar a soporte tecnico", 202);
            }
            $id = openCypher('decrypt', $form['idUsuario']);
            if(is_bool($form) && ($id === FALSE)){
                log_message('error', 'Al intentar hacer decrypt al id de usuario este no corresponde');
                throw new Exception("Tenemos un problema, los datos son corruptos e ilegibles, debe contactar a soporte tecnico", 202);
            }
            $this->form_validation->set_data($form);
            $this->form_validation->set_rules('user_nombre', 'Nombres', 'required|max_length[50]');
            $this->form_validation->set_rules('user_apellido', 'Apellidos', 'required|max_length[50]');
            $this->form_validation->set_rules('user_telefono', 'Teléfono', 'max_length[20]');
            $this->form_validation->set_rules('email', 'Correo', 'required|valid_email');
            if(strlen($form['user_clave'])){
                $this->form_validation->set_rules('user_clave', 'Clave', 'required|min_length['. $this->config->item('min_password_length', 'ion_auth').']|max_length[20]|callback_valid_password');
                $this->form_validation->set_rules('user_repetir', 'Repetir Clave', 'required|matches[user_clave]');
                $data = $data + ['password' => $form['user_clave']];
            }
            if ($this->form_validation->run() == FALSE){
                throw new Exception(validation_errors('',''), 202);
            }
            $data += [
                'first_name'        => $form['user_nombre'],
                'last_name'         => $form['user_apellido'],
                'phone'             => $form['user_telefono'],
            ];
            $update = $this->ion_auth->update($id, $data);            
            if(is_bool($update) && $update === FALSE){
                throw new Exception('Los datos no fueron actualizados, si tiene algún problema no dude en contactar con soporte técnico', 202);
            }
            $response = [
                "data" => []
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
    
    /**
     * Validate the password
     *
     * @param string $password
     *
     * @return bool
     */
    public function valid_password($password = '') {
        $password        = trim($password);
        $regex_lowercase = '/[a-z]/';
        $regex_uppercase = '/[A-Z]/';
        $regex_number    = '/[0-9]/';
        $regex_special   = '/[!@#$%^&*()\-_=+{};:,.~]/';
        if (empty($password)) {
            $this->form_validation->set_message('valid_password', 'El campo {field} es obligatorio.');
            return FALSE;
        }
        if (preg_match_all($regex_lowercase, $password) < 1) {
            $this->form_validation->set_message('valid_password', 'El campo {field} debe contener al menos una letra minúscula.');
            return FALSE;
        }
        if (preg_match_all($regex_uppercase, $password) < 1) {
            $this->form_validation->set_message('valid_password', 'El campo {field} debe contener al menos una letra mayúscula.');
            return FALSE;
        }
        if (preg_match_all($regex_number, $password) < 1) {
            $this->form_validation->set_message('valid_password', 'El campo {field} debe tener al menos un número.');
            return FALSE;
        }
        if (preg_match_all($regex_special, $password) < 1) {
            $this->form_validation->set_message('valid_password', 'El campo {field} debe tener al menos un carácter especial. ' . htmlentities('!@#$%^&*()\-_=+{};:,.~'));
            return FALSE;
        }
        if (strlen($password) < $this->config->item('min_password_length', 'ion_auth')) {
            $this->form_validation->set_message('valid_password', 'El campo {field} debe tener al menos '.$this->config->item('min_password_length', 'ion_auth').' caracteres de longitud.');
            return FALSE;
        }
        if (strlen($password) > 20) {
            $this->form_validation->set_message('valid_password', 'El campo {field} no puede exceder los 20 caracteres de longitud.');
            return FALSE;
        }
        return TRUE;
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
