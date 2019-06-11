<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Analisis Model, al realizar cada ejecucion se almacena el rsultado
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2019-06-03
 */
class Analisis_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    public function setInsert($data) {
        $data += [
            'fk_empresas'  => $this->session->userdata('empresaId'),
            'created_user' => $this->session->userdata('users_id'),
            'created_clie' => $this->session->userdata('clientes_id'),
            'update_user'  => $this->session->userdata('users_id'),
            'update_clie'  => $this->session->userdata('clientes_id'),
        ];
        $insert = $this->db->insert('clie__analisis', $data);
        return $insert;
    }
    
    public function getData($id) {
        $this->db->where('ejecucion', $id);
        $this->db->where('fk_empresas', $this->session->userdata('empresaId'));
        $this->db->where('created_user', $this->session->userdata('users_id'));
        $this->db->where('created_clie', $this->session->userdata('clientes_id'));
        $data = $this->db->get('clie__analisis')->result_array();
        return $data;
    }
    
    public function setUpdate($data, $id) {
        $this->db->update('clie__analisis', $data, ['id' => $id]);
        return $this->db->affected_rows() == 1;
    }
    
    public function setUpdatePdf($data, $ejecucion) {
        $this->db->update('clie__analisis', $data, ['ejecucion' => $ejecucion]);
        return $this->db->affected_rows() == 1;
    }
    
}
