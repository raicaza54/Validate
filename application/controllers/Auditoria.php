<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Auditoria
 *
 * @author Kevin Enriquez
 */
class Auditoria extends CI_Controller {
  
    /**
     * Variable de carga de vista plantilla
     */
    private $data = array('body' => '');    
    
    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
    }
    
    public function index() {
        $this->data['body'] .= '<br/><br/><a href="' . base_url('tablero/spider/130505') . '">130505</a> - ' .
                '<a href="' . base_url('tablero/spider/135515') . '">135515</a> - ' .
                '<a href="' . base_url('tablero/spider/413595') . '">413595</a> - ' .
                '<a href="' . base_url('tablero/spider/240801') . '">240801</a> - ' .
                '<a href="' . base_url('tablero/spider/135517') . '">135517</a> - ' .
                '<a href="' . base_url('tablero/spider/11100501') . '">11100501</a>';
        $this->load->view("plantilla/plantilla", $this->data);        
    }
    
}
