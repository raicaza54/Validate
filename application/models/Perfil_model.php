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
        $this->db->select('clie__clientes.nombre AS clie_nombre, clie__clientes.identificacion AS clie_identificacion, 
            clie__clientes.direccion AS clie_direccion, clie__clientes.telefonos AS clie_telefonos, clie__clientes.correo AS clie_correo, 
            auth__users.id, auth__users.email AS user_correo, auth__users.first_name AS user_nombre, auth__users.last_name AS user_apellido, 
            auth__users.phone AS user_telefono, DATE_FORMAT(FROM_UNIXTIME(auth__users.last_login), "%d/%m/%Y - %h:%i:%s") AS user_last_login, 
            auth__users.ip_address AS user_ip_address', FALSE);
        $this->db->join('clie__clientes', 'auth__users.fk_cliente = clie__clientes.id', 'inner');
        $this->db->where('auth__users.id', $users_id);
        $usuario = $this->db->get('auth__users')->row_array();
        if(is_array($usuario) && (count($usuario) > 0)){
            $usuario['id'] = openCypher('encrypt', $usuario['id']);
            return $usuario;
        }else{
            return FALSE;
        }
    }
    
    public function updateUsers($data, $id) {
        $this->db->update('auth__users', $data, ['id' => $id]);
        return $this->db->affected_rows() == 1;
    }    
    
}
