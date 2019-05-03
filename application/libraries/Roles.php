<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Roles
 * se controla el acceso a funcionalidades dependiendo de los permisos de grupo
 * o prevalecen los permisos individuales
 * 
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Libreria de permisos y roles de usuarios
 * @LastUpdate  2019-04-02
 */

class Roles {

    private $CI;

    public function __construct() {
        $this->CI = & get_instance();
    }
    
    public function viewaccess($accion) {
        $e = '';
        if($accion == 'explorador-actualizar'){
            if(!$this->CI->ion_auth->in_group([1,2,3])){
                $e = 'fa-disabled';
            }
        }elseif($accion == 'explorador-crear'){
            if(!$this->CI->ion_auth->in_group([1,2])){
                $e = 'fa-disabled';
            }
        }elseif($accion == 'explorador-editar'){
            if(!$this->CI->ion_auth->in_group([1,2])){
                $e = 'fa-disabled';
            }
        }elseif($accion == 'explorador-eliminar'){
            if(!$this->CI->ion_auth->in_group([1,2])){
                $e = 'fa-disabled';
            }
        }elseif($accion == 'principal-archivo'){
            if(!$this->CI->ion_auth->in_group([1,2])){
                $e = 'item-disabled';
            }
        }
        return $e;
    }
    
}
