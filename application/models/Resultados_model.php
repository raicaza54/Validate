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
        $this->db->where('created_clie', $this->session->userdata('clientes_id'));
        $this->db->where('id', $id);
        $this->db->where('deleted_at', 0);
        return $this->db->get('clie__resultados')->row_array();        
    }
    
    public function getFolderEmpresa($id_empresa, $id_archivo = 0) {
        $this->db->select('clie__resultados.type AS tipo, clie__resultados.*');
        if($this->session->userdata('empresaId') == 1){
            $this->db->group_start();
                $this->db->where('fk_empresas', $id_empresa);
                $this->db->where('deleted_at', 0);
                $this->db->where('type', 'folder');
            $this->db->group_end();
            $this->db->or_group_start();
                $this->db->where('created_clie', $this->session->userdata('clientes_id'));
                $this->db->where('deleted_at', 0);
                $this->db->where('type !=', 'folder');                
            $this->db->group_end();
        }else{
            $this->db->where('fk_empresas', $id_empresa);
            $this->db->where('deleted_at', 0);                        
        }
        $r = $this->db->get('clie__resultados')->result_array();
        $fl = [];
        if($id_archivo != 0){
            $fl = array_filter($r, function ($value, $key) use ($id_archivo){
                return $value['archivos_id'] == $id_archivo;
            }, ARRAY_FILTER_USE_BOTH);
            $row = [];
            foreach ($fl as $value) {
                $this->filtrado[] = $value;
                $row = array_merge($row, $this->filtro($r, $value['parent_id']));
            }
            $r = unique_multidim_array($row, 'id');
        }        
        return $r;
    }
    
    var $filtrado = [];
    private function filtro($arbol, $parent_id){
        foreach ($arbol as $value) {
            if($value['id'] == $parent_id){
                $this->filtrado[] = $value;
                $this->filtro($arbol, $value['parent_id']);
            }
        }            
        return $this->filtrado;
    }
    
    public function crear($param) {
        extract($param);
        $data = [
            'id'             => $id,
            'fk_analisis'    => $fk_analisis,
            'fk_empresas'    => (int) $empresaId,
            'order'          => 0,
            'parent_id'      => $parent_id,
            'archivos_id'    => $archivos_id,
            'label'          => $label,
            'have_childrens' => 0,
            'opened'         => 0,
            'type'           => $type,
            'created_user'   => $this->session->userdata('users_id'),
            'created_clie'   => $this->session->userdata('clientes_id'),
            'update_user'    => $this->session->userdata('users_id'),
            'update_clie'    => $this->session->userdata('clientes_id'),
        ];
        return $this->db->insert('clie__resultados', $data);
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
    
    public function get_consecutivo($id_cliente, $analisis) {
        $this->db->trans_begin();
        $this->db->select_max('correlativo');
        $this->db->where('fk_clientes', $id_cliente);
        $correlativo = $this->db->get('clie__consecutivos')->row_array();
        if(is_numeric($correlativo['correlativo'])){
            $num = (int) $correlativo['correlativo'] + 1;
        }else{
            $num = 1;
        }
        $consecutivo = str_pad($id_cliente, 4, 0, STR_PAD_LEFT) . '-' . str_pad($num, 10, 0, STR_PAD_LEFT);
        $this->db->insert('clie__consecutivos', [
            'fk_clientes' => $id_cliente,
            'fk_analisis' => $analisis,
            'correlativo' => $num,
            'consecutivo' => $consecutivo
        ]);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $consecutivo = FALSE;
        } else {
            $this->db->trans_commit();
        }
        return $consecutivo;
    }

}
