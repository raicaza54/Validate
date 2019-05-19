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
        $this->db->select('clie__archivos.tipo, '.'clie__carpetas.*');
        $this->db->join('clie__archivos', 'clie__archivos.id = clie__carpetas.archivos_id', 'left');
        $this->db->where('fk_empresas', $id_empresa);
        $this->db->where('deleted_at', 0);
        return $this->db->get('clie__carpetas')->result_array();
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
            'type'           => $type,
            'created_user'   => $this->session->userdata('users_id'),
            'created_clie'  => $this->session->userdata('clientes_id'),
            'update_user'    => $this->session->userdata('users_id'),
            'update_clie'    => $this->session->userdata('clientes_id'),
            'archivos_id'    => $archivos_id,
        ];
        if($this->db->insert('clie__carpetas', $data)){
            return $this->db->insert_id();
        }else{
            return FALSE;
        }
    }
    
    public function editar($param) {
        extract($param);
        $data = [
            'label'       => strip_tags_content($carpeta),
            'parent_id'   => $parent_id,
            'deleted_at'  => (($deleted_at == 'true') ? 1 : 0),
            'update_user' => $this->session->userdata('users_id'),
            'update_clie' => $this->session->userdata('clientes_id'),
        ];
        $r = $this->db->update('clie__carpetas', $data, ['id' => $id]);
        $carpeta = $this->db->where('id', $id)->get('clie__carpetas')->row_array();
        if(is_array($carpeta) && array_key_exists('type', $carpeta)){
            if(in_array($carpeta['type'], ['excel', 'csv'])){
                $this->db->update('clie__archivos', ['fk_carpetas' => $parent_id], ['id' => $carpeta['archivos_id']]);
            }
        }
        return $r;
    }

}
