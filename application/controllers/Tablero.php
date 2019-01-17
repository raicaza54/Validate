<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Tablero
 *
 * @author Kevin Enriquez
 */
class Tablero extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
    }

    public function index() {
        echo 'Hola mundo';
    }

}
