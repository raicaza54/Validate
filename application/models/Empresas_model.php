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
 * @LastUpdate 2019-02-13
 */
class Empresas_model extends CI_Model {

    private $pref = 'clie__';

    function __construct() {
        parent::__construct();
        // Set table name
        $this->table         = 'clie__empresas';
        // Set orderable column fields
        $this->column_order  = [
            NULL,
            'nombre',
            'identificacion',
        ];
        // Set searchable column fields
        $this->column_search = [
            'nombre',
            'identificacion',
        ];
        // Set default order
        $this->order         = [
            'nombre' => 'asc'
        ];
    }

    /*
     * Fetch members data from the database
     * @param $_POST filter data based on the posted parameters
     */
    public function getRows($postData) {
        $this->_get_datatables_query($postData);
        if ($postData['length'] != -1) {
            $this->db->limit($postData['length'], $postData['start']);
        }
        $this->db->join('clie__auditores_empresas', 'clie__auditores_empresas.fk_empresas = clie__empresas.id');
        $this->db->where('clie__auditores_empresas.fk_auditores', $this->session->userdata('users_id'));
        $this->db->where('clie__empresas.deleted_at', 0);
        $this->db->where('clie__empresas.created_clie', $this->session->userdata('clientes_id'));
        if(($this->session->userdata('demo') !== null) && ($this->session->userdata('demo') == 'si')){
            $this->db->or_where('clie__empresas.id', 1);
        }
        $query = $this->db->get();
        return $query->result();
    }

    /*
     * Count all records
     */
    public function countAll() {
        $this->db->from($this->table);
        $this->db->join('clie__auditores_empresas', 'clie__auditores_empresas.fk_empresas = clie__empresas.id');
        $this->db->where('clie__auditores_empresas.fk_auditores', $this->session->userdata('users_id'));        
        $this->db->where('clie__empresas.deleted_at', 0);
        $this->db->where('clie__empresas.created_clie', $this->session->userdata('clientes_id'));
        return $this->db->count_all_results();
    }

    /*
     * Count records based on the filter params
     * @param $_POST filter data based on the posted parameters
     */
    public function countFiltered($postData) {
        $this->_get_datatables_query($postData);
        $this->db->join('clie__auditores_empresas', 'clie__auditores_empresas.fk_empresas = clie__empresas.id');
        $this->db->where('clie__auditores_empresas.fk_auditores', $this->session->userdata('users_id'));
        $this->db->where('clie__empresas.deleted_at', 0);
        $this->db->where('clie__empresas.created_clie', $this->session->userdata('clientes_id'));
        $query = $this->db->get();
        return $query->num_rows();
    }

    /*
     * Perform the SQL queries needed for an server-side processing requested
     * @param $_POST filter data based on the posted parameters
     */
    private function _get_datatables_query($postData) {
        $this->db->select('clie__empresas.id, nombre, identificacion');
        $this->db->from($this->table);
        $i = 0;
        // loop searchable columns 
        foreach ($this->column_search as $item) {
            // if datatable send POST for search
            if ($postData['search']['value']) {
                // first loop
                if ($i === 0) {
                    // open bracket
                    $this->db->group_start();
                    $this->db->like($item, $postData['search']['value']);
                } else {
                    $this->db->or_like($item, $postData['search']['value']);
                }

                // last loop
                if (count($this->column_search) - 1 == $i) {
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if (isset($postData['order'])) {
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function getTodas() {
        $this->db->select('e.id, e.nombre, e.identificacion');
        $this->db->join('clie__auditores_empresas ae', 'ae.fk_empresas = e.id');
        $this->db->where('ae.fk_auditores', $this->session->userdata('users_id'));
        $this->db->where('e.deleted_at', 0);
        $this->db->where('e.created_clie', $this->session->userdata('clientes_id'));
        return $this->db->get('clie__empresas e')->result_array();
    }

    public function getId($id) {
        $this->db->select('
            clie__empresas.id,
            nombre,
            identificacion,
            direccion,
            telefonos,
            correo,
            naturaleza_credito,
            persona,
            persona_tlfs,
            persona_direc,
            persona_correo,
            observacion,
            CONCAT(created.first_name," ",created.last_name) AS created_user,
            clie__empresas.created_clie,
            CONCAT(updated.first_name," ",updated.last_name) AS update_user,
            clie__empresas.update_clie,
            DATE_FORMAT(clie__empresas.created_at,"%d/%m/%Y - %h:%i:%s %p") AS created_at,
            DATE_FORMAT(clie__empresas.update_at,"%d/%m/%Y - %h:%i:%s %p") AS update_at'
        );
        $this->db->where('clie__empresas.created_clie', $this->session->userdata('clientes_id'));
        $this->db->where('clie__empresas.id', $id);
        $this->db->where('clie__empresas.deleted_at', 0);
        $this->db->join('auth__users created', 'clie__empresas.created_user = created.id');
        $this->db->join('auth__users updated', 'clie__empresas.update_user = updated.id');
        if(($this->session->userdata('demo') !== null) && ($this->session->userdata('demo') == 'si') && ($id == 1)){
            $this->db->or_where('clie__empresas.id', 1);
        }        
        $e = $this->db->get('clie__empresas')->row_array();
        return $e;
    }
    
    public function updateData($data, $id) {
        $data = $data + [
            'update_user' => $this->session->userdata('users_id'),
            'update_clie' => $this->session->userdata('clientes_id'),
        ];
        $this->db->update('clie__empresas', $data, [
            'id'           => $id,
            'created_clie' => $this->session->userdata('clientes_id')
        ]);
        return $this->db->affected_rows() == 1;
    }
    
    public function getIdentificacion($identificacion, $id = NULL) {
        if(!is_null($id)){
            $this->db->where('id !=', $id);
        }
        $this->db->where('identificacion', $identificacion);
        $this->db->where('created_clie', $this->session->userdata('clientes_id'));
        $e = $this->db->get('clie__empresas')->row_array();
        return $e;
    }
    
    public function insertData($data) {
        $auditoria = [
            'created_user' => $this->session->userdata('users_id'),
            'created_clie' => $this->session->userdata('clientes_id'),
            'update_user'  => $this->session->userdata('users_id'),
            'update_clie'  => $this->session->userdata('clientes_id'),
        ];
        $this->db->trans_begin();
        $data = $data + $auditoria;
        $this->db->insert('clie__empresas', $data);
        $id = $this->db->insert_id();
        if(($this->db->affected_rows() != 1) || !is_numeric($id)){
            $this->db->trans_rollback();
            return FALSE;
        }
        $fkdata = [
            'fk_auditores' => $this->session->userdata('users_id'),
            'fk_empresas'  => $id,
        ] + $auditoria;
        $this->db->insert('clie__auditores_empresas', $fkdata);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
        }
        return $id;
    }    
    
    public function configEmpresa($columnas, $empresaId) {
        $auditoria = [
            'fk_empresas'  => $empresaId,
            'created_user' => $this->session->userdata('users_id'),
            'created_clie' => $this->session->userdata('clientes_id'),
            'update_user'  => $this->session->userdata('users_id'),
            'update_clie'  => $this->session->userdata('clientes_id'),
        ];
        $this->db->select('id');
        $this->db->where('fk_empresas', $empresaId);
        $config = $this->db->get('clie__empresas_config')->row_array();
        if(count($config) > 0){
            $this->db->update('clie__empresas_config', $columnas, ['id' => $config['id']]);
        }else{
            $this->db->insert('clie__empresas_config', $columnas + $auditoria);
        }
        return $this->db->affected_rows() == 1;
    }
    
    public function configGetColumDefault($users_id, $empresa_id, $cliente_id) {
        $columnas = [
            'columnas_movnat' => [],
            'columnas_movdhb' => [],
            'columnas_blp'    => [],
            'columnas_cxc'    => [],
            'columnas_cxp'    => [],
            'materialidad'    => [],
        ];
        if(is_numeric($empresa_id) && $empresa_id > 0){
            $config = $this->db->query('
                SELECT ec.id, e.nombre ,columnas_movnat, columnas_movdhb, columnas_blp, columnas_cxc, columnas_cxp, materialidad, ec.update_at 
                FROM clie__empresas_config ec INNER JOIN clie__empresas e ON ec.fk_empresas = e.id 
                WHERE e.id IN(
                    SELECT se.id 
                    FROM clie__empresas se INNER JOIN clie__auditores_empresas sae ON sae.fk_empresas = se.id 
                    WHERE sae.fk_auditores = '.$users_id.'
                ) AND ec.created_clie = '.$cliente_id.' AND ec.fk_empresas = '.$empresa_id.';
            ')->row_array();
            if(is_array($config) && count($config) > 0){
                $columnas['id']        = $config['id'];
                $columnas['empresa']   = $config['nombre'];
                $columnas['update_at'] = $config['update_at'];
                if(array_key_exists('columnas_movnat', $config) && strlen(trim($config['columnas_movnat']))){
                    $columnas['columnas_movnat'] = unserialize($config['columnas_movnat']);
                }
                if(array_key_exists('columnas_movdhb', $config) && strlen(trim($config['columnas_movdhb']))){
                    $columnas['columnas_movdhb'] = unserialize($config['columnas_movdhb']);
                }
                if(array_key_exists('columnas_blp', $config) && strlen(trim($config['columnas_blp']))){
                    $columnas['columnas_blp'] = unserialize($config['columnas_blp']);
                }
                if(array_key_exists('columnas_cxc', $config) && strlen(trim($config['columnas_cxc']))){
                    $columnas['columnas_cxc'] = unserialize($config['columnas_cxc']);
                }
                if(array_key_exists('columnas_cxp', $config) && strlen(trim($config['columnas_cxp']))){
                    $columnas['columnas_cxp'] = unserialize($config['columnas_cxp']);
                }
                if(array_key_exists('materialidad', $config) && strlen(trim($config['materialidad']))){
                    $columnas['materialidad'] = unserialize($config['materialidad']);
                }
            }            
        }
        return $columnas;
    }
    
    public function configGetEmpresa($users_id, $empresa_id, $cliente_id) {
        $config = [];
        if(is_numeric($empresa_id) && $empresa_id > 0){
            $config = $this->db->query('
                SELECT ec.* 
                FROM clie__empresas_config ec INNER JOIN clie__empresas e ON ec.fk_empresas = e.id 
                WHERE e.id IN(
                    SELECT se.id 
                    FROM clie__empresas se INNER JOIN clie__auditores_empresas sae ON sae.fk_empresas = se.id 
                    WHERE sae.fk_auditores = '.$users_id.'
                ) AND ec.created_clie = '.$cliente_id.' AND ec.fk_empresas = '.$empresa_id.';
            ')->row_array();
        }
        return $config;
    }
    
    public function editarDemo($users_id, $empresa_id, $cliente_id) {
        $this->db->from('clie__auditores_empresas ae')
            ->join('clie__empresas e', 'ae.fk_empresas = e.id', 'inner')
            ->where('ae.fk_auditores', $users_id)
            ->where('ae.fk_empresas', $empresa_id)
            ->where('e.created_clie', $cliente_id);
        $q = $this->db->count_all_results() > 0;
        return $q;
    }
    
    public function siCredito($empresa_id) {
        $this->db->select('naturaleza_credito')
            ->from('clie__empresas ae')
            ->where('id', $empresa_id);
        $r = $this->db->get()->row_array();
        return $r['naturaleza_credito'];
    }
 
}
