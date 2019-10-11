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
 * @LastUpdate 2019-10-09
 */
class Users_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    function terminoCondiciones($id_user) {
        $this->db->where('fk_users', $id_user);
        $this->db->where('estado', 1);
        $r = $this->db->get('auth__users_terminos');
        return $r->num_rows();
    }
    
    function aceptarTerminoCondiciones() {
        $this->db->insert('auth__users_terminos', [
            'fk_users'     => $this->session->userdata('users_id'),
            'aceptado'     => date('Y-m-d H:i:s'),
            'estado'       => '1',
            'username'     => $this->session->userdata('username'),
            'email'        => $this->session->userdata('email'),
            'created_user' => $this->session->userdata('users_id'),
            'created_clie' => $this->session->userdata('clientes_id'),
            'update_user'  => $this->session->userdata('users_id'),
            'update_clie'  => $this->session->userdata('clientes_id'),
        ]);
        return $this->db->affected_rows() == 1;
    }
    
}
