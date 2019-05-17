<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Perfil Model
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2018-05-16
 */
class Perfil_model extends CI_Model {

    private $pref = 'sist__';
    
    function __construct() {
        parent::__construct();
    }
    
    public function getContrato($cliente_id) {
        if(is_numeric($cliente_id)){
            $this->db->where('fk_clientes', $cliente_id);
            $this->db->where('estado', 1);
            $this->db->order_by('id', 'DESC');
            $contrato = $this->db->get($this->pref.'contratos')->row_array();
            return $contrato;
        }else{
            return FALSE;
        }
    }
    
}
