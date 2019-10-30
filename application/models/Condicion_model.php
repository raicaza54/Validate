<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Condicion de Cuenta Model, tabla de porcentajes y tolerancia por empresa
 * para aplicar el calculo de condicion de cuenta
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2019-10-28
 */
class Condicion_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    function get_condicion() {
        $this->db->where('fk_users', $this->session->userdata('users_id'));
        $this->db->where('fk_empresa', $this->session->userdata('empresaId'));
        $this->db->where('created_clie', $this->session->userdata('clientes_id'));
        $e = $this->db->get('clie__condicion_cuenta')->result_array();
        return $e;
    }
    
    function set_condicion($data) {
        $auditoria = [
            'created_user' => $this->session->userdata('users_id'),
            'created_clie' => $this->session->userdata('clientes_id'),
            'update_user'  => $this->session->userdata('users_id'),
            'update_clie'  => $this->session->userdata('clientes_id'),
        ];
        $data = array_map(function($value) use ($auditoria){
            return $value + $auditoria;
        }, $data);
        $this->db->delete('clie__condicion_cuenta',[
            'fk_users'   => $this->session->userdata('users_id'),
            'fk_empresa' => $this->session->userdata('empresaId'),                    
        ]);
        $this->db->insert_batch('clie__condicion_cuenta', $data);
        return $this->db->affected_rows() == 1;
    }
    
}