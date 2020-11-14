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
   
    function formatoData($data, $id_formato, $insert){
        $auditoria = [
            'update_user'  => $this->session->userdata('users_id'),
            'update_clie'  => $this->session->userdata('clientes_id'),
        ];
        $data = $data + $auditoria;
        if($insert == TRUE){
            $data = $data + [
                'created_user' => $this->session->userdata('users_id'),
                'created_clie' => $this->session->userdata('clientes_id')
            ];
            $data = $data + ['id' => $id_formato];
            $this->db->insert('clie__dictamen', $data);
        }else{
            $this->db->update('clie__dictamen', $data, [
                'id'          => $id_formato,
                'fk_clientes' => $this->session->userdata('clientes_id'),
                'fk_empresas' => $this->session->userdata('empresaId'),
                'fk_users'    => $this->session->userdata('users_id'),
            ]);
        }
        return $this->db->affected_rows() == 1;
    }
    
    function descartar($id){
        $auditoria = [
            'update_user'  => $this->session->userdata('users_id'),
            'update_clie'  => $this->session->userdata('clientes_id'),
        ];
        $data = ['estado' => 0] + $auditoria;
        $this->db->update('clie__dictamen', $data, [
            'id'          => $id,
            'fk_clientes' => $this->session->userdata('clientes_id'),
            'fk_empresas' => $this->session->userdata('empresaId')
        ]);
        return $this->db->affected_rows() == 1;
    }
    
    function borradores() {
        $this->db->select('id, etiqueta, DATE_FORMAT(update_at,"%d/%m/%Y - %h:%i:%s %p") AS update_at');
        $this->db->where('estado', 1);
        $this->db->where('fk_clientes', $this->session->userdata('clientes_id'));
        $this->db->where('fk_users', $this->session->userdata('users_id'));
        $this->db->where('fk_empresas', $this->session->userdata('empresaId'));
        $this->db->order_by('update_at', 'DESC');
        $r = $this->db->get('clie__dictamen')->result_array();
        foreach ($r as &$value) {
            $value['id'] = openCypher('encrypt', $value['id']);
        }
        return $r;
    }
    
    function borrador($id) {
        $this->db->select('id, data');
        $this->db->where('estado', 1);
        $this->db->where('id', $id);
        $this->db->where('fk_clientes', $this->session->userdata('clientes_id'));
        $this->db->where('fk_users', $this->session->userdata('users_id'));
        $this->db->where('fk_empresas', $this->session->userdata('empresaId'));
        $r = $this->db->get('clie__dictamen')->row_array();
        $r['id'] = openCypher('encrypt', $r['id']);
        return $r;
    }
    
}
