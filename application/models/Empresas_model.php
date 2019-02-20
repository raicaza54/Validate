<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Empresas Model
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2018-02-13
 */
class Empresas_model extends CI_Model {

    private $pref = 'clie__';
    
    function __construct() {
        parent::__construct();
    }
    
    public function getTodas() {
        $this->db->select('id, nombre, identificacion');
        return $this->db->get($this->pref.'empresas')->result_array();
    }
    
    public function getId($id) {
        $this->db->select('id, nombre, identificacion, telefonos, correo, persona, persona_tlfs');
        $this->db->where('id', $id);
        return $this->db->get($this->pref.'empresas')->row_array();
    }

}
