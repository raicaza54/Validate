<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Explorador de carpetas Model (arbol-c)
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2018-02-13
 */
class Explorador_model extends CI_Model {

    private $pref = 'clie__';
    
    function __construct() {
        parent::__construct();
    }
    
    public function getFolderEmpresa($id_empresa) {
        $this->db->where('fk_empresas', $id_empresa);
        return $this->db->get($this->pref.'carpetas')->result_array();
    }
    
    public function crear($param) {
        extract($param);
        $data = [
            'fk_empresas'    => (int) $empresaId,
            'order'          => 0,
            'parent_id'      => $parent_id,
            'label'          => $carpeta,
            'have_childrens' => 0,
            'opened'         => 0,
            'type'           => 'folder',
            'created_user'   => $this->session->userdata('users_id'),
            'created_clier'  => $this->session->userdata('clientes_id'),
            'update_user'    => $this->session->userdata('users_id'),
            'update_clie'    => $this->session->userdata('clientes_id'),
        ];
        if($this->db->insert($this->pref.'carpetas', $data)){
            return $this->db->insert_id();
        }else{
            return FALSE;
        }
    }
    
    public function editar($param) {
        extract($param);
        $data = [
            'label'          => $carpeta,
            'parent_id'      => $parent_id,
            'update_user'    => $this->session->userdata('users_id'),
            'update_clie'    => $this->session->userdata('clientes_id'),
        ];
        return $this->db->update($this->pref.'carpetas', $data, ['id' => $id]);
    }

}
