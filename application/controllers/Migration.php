<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ejecucion via terminal
 * var/www/html/auditoria$ php index.php migration index
 * this->migration->version(2)ejecutará el método up de
 * las migraciones 001 y 002 y el método down de las superiores
 */
class Migration extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('migration');
        if(!is_cli()){
            echo "Operación no permitida";
        }
    }

    public function index() {
        if (!$this->migration->version(1)) {
            echo $this->migration->error_string()."\n";
            echo "error\n";
        } else {
            echo "success\n";
        }
    }

}