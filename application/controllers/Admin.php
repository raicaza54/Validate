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
    }
    
    function index() {
        $this->data['body'] = '';
        $this->load->view("plantilla/admin", $this->data);
    }
    
}