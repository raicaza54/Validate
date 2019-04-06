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
 * @LastUpdate 2018-02-13
 */
class Empresas_model extends CI_Model {

    private $pref = 'clie__';

    function __construct() {
        parent::__construct();
        // Set table name
        $this->table         = $this->pref . 'empresas';
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
        $this->db->join($this->pref . 'auditores_empresas', $this->pref . 'auditores_empresas.fk_empresas = ' . $this->pref . 'empresas.id');
        $this->db->where($this->pref . 'auditores_empresas.fk_auditores', $this->session->userdata('users_id'));        
        $query = $this->db->get();
        return $query->result();
    }

    /*
     * Count all records
     */
    public function countAll() {
        $this->db->from($this->table);
        $this->db->join($this->pref . 'auditores_empresas', $this->pref . 'auditores_empresas.fk_empresas = ' . $this->pref . 'empresas.id');
        $this->db->where($this->pref . 'auditores_empresas.fk_auditores', $this->session->userdata('users_id'));        
        return $this->db->count_all_results();
    }

    /*
     * Count records based on the filter params
     * @param $_POST filter data based on the posted parameters
     */
    public function countFiltered($postData) {
        $this->_get_datatables_query($postData);
        $this->db->join($this->pref . 'auditores_empresas', $this->pref . 'auditores_empresas.fk_empresas = ' . $this->pref . 'empresas.id');
        $this->db->where($this->pref . 'auditores_empresas.fk_auditores', $this->session->userdata('users_id'));        
        $query = $this->db->get();
        return $query->num_rows();
    }

    /*
     * Perform the SQL queries needed for an server-side processing requested
     * @param $_POST filter data based on the posted parameters
     */
    private function _get_datatables_query($postData) {
        $this->db->select($this->pref . 'empresas.id, nombre, identificacion');
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
        $this->db->select($this->pref . 'empresas.id, nombre, identificacion');
        $this->db->join($this->pref . 'auditores_empresas', $this->pref . 'auditores_empresas.fk_empresas = ' . $this->pref . 'empresas.id');
        $this->db->where($this->pref . 'auditores_empresas.fk_auditores', $this->session->userdata('users_id'));
        return $this->db->get($this->pref . 'empresas')->result_array();
    }

    public function getId($id) {
        $this->db->select('id, nombre, identificacion, telefonos, correo, persona, persona_tlfs');
        $this->db->where('id', $id);
        return $this->db->get($this->pref . 'empresas')->row_array();
    }

}
