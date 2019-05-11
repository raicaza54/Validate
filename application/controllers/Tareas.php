<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Tareas automaticas por terminal
 * 
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Tareas programas o manuales
 * @LastUpdate  2019-05-04
 */
class Tareas extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        if(!is_cli()){
            echo "Operacion no permitida";
        }
        $this->load->library(['DBPermisos']);
    }
    
    public function permisos() {
        $this->dbpermisos->definir();
    }
    
    public function permisosResetear() {
        $this->dbpermisos->resetear();
    }
    
    public function permisosAdmin($truncate = 0) {
        $this->dbpermisos->admin($truncate);
    }
    
    public function permisosEducativo($truncate = 0) {
        $this->dbpermisos->educativo($truncate);
    }
    
}
