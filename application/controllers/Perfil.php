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
            'Perfil_model'
        ]);
    }

    public function datos() {
        $response = $this->response;
        try {
            $cliente_id = $this->session->userdata('clientes_id');
            $users_id = $this->session->userdata('users_id');
            $contrato = $this->Cliente_model->getContrato($cliente_id);
            if(!is_array($contrato) || (count($contrato) <= 0)){
                throw new Exception("Tenemos un problema con el contrato, por favor contactar con soporte", 418);
            }
            $usuario = $this->Perfil_model->getUsuario($users_id);
            if(!is_array($usuario) || (count($usuario) <= 0)){
                throw new Exception("Tenemos un problema con su cuenta de usuario, por favor contactar con soporte", 418);
            }            
            $response = [
                "data" => [
                    'contrato' => $contrato,
                    'usuario'  => $usuario,
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
    
    public function saveDatos() {
        $response = $this->response;
        $this->load->model('Ion_auth_model');
        $data = [];
        try {
            $post = $this->input->post();
            if(!is_array($post) || !array_key_exists('form', $post) || (count($post['form']) <= 0)){
                throw new Exception("Tenemos un problema, los datos estan incompletos o corruptos", 400);
            }
            $form = unSerializeArray($post['form']);
            if(!is_array($form) || !array_key_exists('id', $form)){
                throw new Exception("Tenemos un problema, debe contactar a soporte tecnico", 400);
            }
            $id = openCypher('decrypt', $form['id']);
            if(is_bool($form) && ($if === FALSE)){
                log_message('error', 'Al intentar hacer decrypt al id de usuario este no corresponde');
                throw new Exception("Tenemos un problema, los datos son corruptos e ilegibles, debe contactar a soporte tecnico", 400);
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
                throw new Exception('<ul>'.validation_errors('<li>','</li>').'</ul>', 400);
            }
            $data += [
                'first_name' => $form['user_nombre'],
                'last_name'  => $form['user_apellido'],
                'phone'      => $form['user_telefono']
            ];
            $update = $this->ion_auth->update($id, $data);            
            if(is_bool($update) && $update === FALSE){
                throw new Exception('Los datos no fueron actualizados, si tiene algún problema no dude en contactar con soporte técnico', 418);
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
