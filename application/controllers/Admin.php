<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Controlador Administrativo
 * @LastUpdate  2019-08-01
 */
class Admin extends CI_Controller {
    var $data;
    function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth/login');
        }
        $this->load->model([
            'Archivos_model',
            'Users_model',
        ]);        
    }
    
    function index() {
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
            $this->data['body'] = '';
            $this->load->view("plantilla/admin", $this->data);
        }
    }
    
}