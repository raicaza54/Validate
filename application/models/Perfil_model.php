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
 * @LastUpdate 2019-05-16
 */
class Perfil_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    public function getUsuario($users_id) {
        $this->db->select('cl.nombre AS empr_nombre, cl.identificacion AS empr_identificacion, 
            cl.direccion AS empr_direccion, cl.telefonos AS empr_telefonos, cl.correo AS empr_correo, cl.color AS empr_color, 
            cl.firma AS empr_firma, cl.path_logotipo AS empr_logotipo, cl.usar_logotipo AS empr_usarlogotipo, cl.usar_firma AS empr_usarfirma,
            au.id, au.email AS user_correo, au.first_name AS user_nombre, au.last_name AS user_apellido, cl.usar_demo AS empr_usardemo,
            au.phone AS user_telefono, DATE_FORMAT(FROM_UNIXTIME(au.last_login), "%d/%m/%Y - %h:%i:%s") AS user_last_login, 
            au.ip_address AS user_ip_address', FALSE);
        $this->db->from('auth__users au')->join('clie__clientes cl', 'au.fk_cliente = cl.id', 'inner');
        $this->db->where('au.id', $users_id);
        $usuario = $this->db->get()->row_array();
        if(is_array($usuario) && (count($usuario) > 0)){
            $usuario['id'] = openCypher('encrypt', $usuario['id']);
            return $usuario;
        }else{
            return FALSE;
        }
    }
    
    public function getDatosCompra($users_id){
        $this->db->select('email, CONCAT(first_name," ",last_name) AS nombre, phone AS telefono');
        $this->db->where('id', $users_id);
        $r = $this->db->get('auth__users')->row_array();
        return $r;
    }
    
    public function setDatosCompra($data, $users_id){
        $auditoria = [
            'created_user' => $this->session->userdata('users_id'),
            'created_clie' => $this->session->userdata('clientes_id'),
            'update_user'  => $this->session->userdata('users_id'),
            'update_clie'  => $this->session->userdata('clientes_id'),
        ];        
        $this->db->where('fk_users', $users_id);
        $compra = $this->db->get('sist__comprar')->row_array();
        if(is_array($compra) && (count($compra) > 0)){
            $this->db->update(
                'sist__comprar',
                $data + ['envios' => $compra['envios'] + 1],
                ['fk_users' => $users_id]
            );
        }else{
            $data = $data + $auditoria;
            $this->db->insert('sist__comprar',$data);
        }
        return $this->db->affected_rows() == 1;
    }
    
    
    public function updateUsers($data, $id) {
        $this->db->update('auth__users', $data, ['id' => $id]);
        return $this->db->affected_rows() == 1;
    }    
    
}
