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
 * @LastUpdate 2020-08-01
 */
class Dictamen_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
   
    public function insertData($data){
//        $auditoria = [
//            'created_user' => $this->session->userdata('users_id'),
//            'created_clie' => $this->session->userdata('clientes_id'),
//            'update_user'  => $this->session->userdata('users_id'),
//            'update_clie'  => $this->session->userdata('clientes_id'),
//        ];
//        $data = $data + $auditoria;
//        $this->db->insert('clie__dictamen', $data);
        return $this->db->affected_rows() == 1;
    }
    
}
