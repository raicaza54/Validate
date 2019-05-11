<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Permisos
 * se controla el acceso a funcionalidades dependiendo de los permisos de grupo
 * o prevalecen los permisos individuales
 * 
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Libreria de permisos y roles de usuarios
 * @LastUpdate  2019-04-02
 */
class Permisos {

    private $CI;

    public function __construct() {
        $this->CI = & get_instance();
    }
    
    /**
     * viewaccess: permite acceso a un recurso dado como una cadena, permisos 
     * definidos en grupos y de forma individual, de forma tal que prevalecen
     * los permisos individuales sobre los de grupo, tenga en cuenta que si
     * un mismo permiso esta de forma individual y grupal este queda anulado,
     * caso contrario si no existe e individualmente existe este se permite
     * 
     * @param type $accion
     * @return string
     */
    public function viewaccess($accion) {
        $e = 'item-disabled';
        $grupo = $this->CI->ion_auth->get_users_groups()->result_array();
        $grupoId = array_column($grupo, 'id');
        $this->CI->db->select('sist__permisos.id, sist__permisos.permiso');
        $this->CI->db->join('sist__permisos', 'sist__permisos.id = sist__permisos_groups.fk_permisos', 'inner');
        $this->CI->db->where_in('fk_groups', $grupoId);
        $this->CI->db->group_start();
        $this->CI->db->where_in('sist__permisos.permiso', $accion);
        $this->CI->db->or_where('sist__permisos.permiso', 'root-all');
        $this->CI->db->group_end();
        $permiso = $this->CI->db->get('sist__permisos_groups')->row_array();
        if(is_array($permiso) && (count($permiso) > 0)){
            $this->CI->db->where('fk_permisos', $permiso['id']);
            $this->CI->db->where('fk_users', $this->CI->session->userdata('user_id'));
            $permiso_user = $this->CI->db->get('sist__permisos_users')->row_array();
            if(!is_array($permiso_user) || (count($permiso_user) <= 0)){
                $e = '';
            }
        }else{
            $this->CI->db->select('sist__permisos.id, sist__permisos.permiso');
            $this->CI->db->join('sist__permisos', 'sist__permisos.id = sist__permisos_users.fk_permisos', 'inner');
            $this->CI->db->where('sist__permisos.permiso', $accion);
            $this->CI->db->where('fk_users', $this->CI->session->userdata('user_id'));
            $permiso_user = $this->CI->db->get('sist__permisos_users')->row_array();
            if(is_array($permiso_user) && (count($permiso_user) > 0)){
                $e = '';
            }            
        }
        return $e;
    }
}
