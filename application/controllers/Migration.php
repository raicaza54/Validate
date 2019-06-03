<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ejecucion via terminal
 * var/www/html/auditoria$ php index.php migration version 1
 * this->migration->version(2)ejecutará el método up de
 * las migraciones 001 y 002 y el método down de las superiores
 */
class Migration extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if(!is_cli()){
            echo "Operación no permitida";
        }
    }

    public function version($id) {
        $class = 'dbauditoria_'.str_pad($id,3,0,STR_PAD_LEFT);
        echo "Class: ".$class."\n";
        $this->load->library('migrations/'.$class);
        $this->$class->up();
    }
    
    public function downgrade($id) {
        $class = 'dbauditoria_'.str_pad($id,3,0,STR_PAD_LEFT);
        $this->load->library('migrations/'.$class);
        $this->$class->down();
    }

}