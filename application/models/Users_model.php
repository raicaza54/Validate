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
        $this->db->select_sum('ayudame');
        $this->db->select_sum('estado');
        $this->db->where('fk_users', $id_user);
        $r = $this->db->get('auth__users_terminos');
        return $r->row_array();
    }
    
    function ayudaAceptada($id_user) {
        $this->db->update('auth__users_terminos',['ayudame' => 0],[
            'ayudame'   => 1,
            'fk_users' => $id_user
        ]);
        return $this->db->affected_rows() == 1;
    }
    
    function aceptarTerminoCondiciones() {
        $this->load->library('user_agent');
        if ($this->agent->is_browser()) {
            $agent = $this->agent->browser() . ' ' . $this->agent->version();
        } elseif ($this->agent->is_robot()) {
            $agent = $this->agent->robot();
        } elseif ($this->agent->is_mobile()) {
            $agent = $this->agent->mobile();
        } else {
            $agent = 'Unidentified User Agent';
        }
        $platform = $this->agent->platform();
        $this->db->insert('auth__users_terminos', [
            'fk_users'      => $this->session->userdata('users_id'),
            'aceptado'      => date('Y-m-d H:i:s'),
            'estado'        => '1',
            'ayudame'       => '0',
            'user_agent'    => $agent,
            'user_platform' => $platform,
            'user_ip'       => $this->input->ip_address(),
            'username'      => $this->session->userdata('username'),
            'email'         => $this->session->userdata('email'),
            'created_user'  => $this->session->userdata('users_id'),
            'created_clie'  => $this->session->userdata('clientes_id'),
            'update_user'   => $this->session->userdata('users_id'),
            'update_clie'   => $this->session->userdata('clientes_id'),
        ]);
        return $this->db->affected_rows() == 1;
    }
    
}
