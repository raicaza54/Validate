<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Explorador de resultados Model (arbol-c)
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2019-06-01
 */
class Resultados_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    public function getFileId($id) {
        $this->db->where('fk_empresas', $this->session->userdata('empresaId'));
        $this->db->where('deleted_at', 0);
        return $this->db->get('clie__resultados')->row_array();        
    }
    
    public function getFolderEmpresa($id_empresa) {
        $this->db->select('clie__resultados.type AS tipo, clie__resultados.*');
        $this->db->where('fk_empresas', $id_empresa);
        $this->db->where('deleted_at', 0);
        return $this->db->get('clie__resultados')->result_array();
    }
    
    public function crear($param) {
        extract($param);
        $data = [
            'id'             => $id,
            'fk_empresas'    => (int) $empresaId,
            'order'          => 0,
            'parent_id'      => $parent_id,
            'label'          => $label,
            'have_childrens' => 0,
            'opened'         => 0,
            'type'           => $type,
            'created_user'   => $this->session->userdata('users_id'),
            'created_clie'   => $this->session->userdata('clientes_id'),
            'update_user'    => $this->session->userdata('users_id'),
            'update_clie'    => $this->session->userdata('clientes_id'),
        ];
        $e = $this->db->insert('clie__resultados', $data);
        if($e){
            return $id;
        }else{
            return FALSE;
        }
    }
    
    public function editar($param) {
        extract($param);
        $data = [
            'label'       => strip_tags_content($label),
            'parent_id'   => $parent_id,
            'deleted_at'  => (($deleted_at == 'true') ? 1 : 0),
            'update_user' => $this->session->userdata('users_id'),
            'update_clie' => $this->session->userdata('clientes_id'),
        ];
        $r = $this->db->update('clie__resultados', $data, ['id' => $id]);
        $carpeta = $this->db->where('id', $id)->get('clie__resultados')->row_array();
        if(is_array($carpeta) && array_key_exists('type', $carpeta)){
            if(in_array($carpeta['type'], ['excel', 'csv'])){
                $this->db->update('clie__archivos', ['fk_carpetas' => $parent_id], ['id' => $carpeta['archivos_id']]);
            }
        }
        return $r;
    }

}
