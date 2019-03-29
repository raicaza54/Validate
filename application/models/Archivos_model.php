<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Archivos Model
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2018-02-13
 */
class Archivos_model extends CI_Model {

    private $pref = 'clie__';
    
    function __construct() {
        parent::__construct();
        // Set table name
        $this->table         = $this->pref . 'archivos_detalle';
        // Set orderable column fields
        $column = [];
        for ($x = 1; $x <= 20; $x++) {
            $column[] = 'campo'.$x;
        }
        $this->column_order  = $column;
        // Set searchable column fields
        $this->column_search = $column;
        // Set default order
        $this->order         = [
            'campo1' => 'asc'
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
        $this->db->where('fk_archivos', $postData['id']);
        $query = $this->db->get();
        return $query->result();
    }

    /*
     * Count all records
     */
    public function countAll($postData) {
        $this->db->from($this->table);
        $this->db->where('fk_archivos', $postData['id']);
        return $this->db->count_all_results();
    }

    /*
     * Count records based on the filter params
     * @param $_POST filter data based on the posted parameters
     */
    public function countFiltered($postData) {
        $this->_get_datatables_query($postData);
        $this->db->where('fk_archivos', $postData['id']);
        $query = $this->db->get();
        return $query->num_rows();
    }

    /*
     * Perform the SQL queries needed for an server-side processing requested
     * @param $_POST filter data based on the posted parameters
     */
    private function _get_datatables_query($postData) {
        $this->db->from($this->table);
        $this->db->where('linea', 'f');
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
        return $this->db->get($this->pref.'archivos')->result_array();
    }
    
    public function getEncabezado($id) {
        $this->db->where('fk_archivos', $id);
        $this->db->where('linea', 'e');
        $encabezado = $this->db->get($this->pref.'archivos_detalle')->row_array();
        $e = [];
        for ($x = 1; $x <= 20; $x++) {
            if(trim($encabezado['campo'.$x])){
                $e['campo'.$x] = $encabezado['campo'.$x];
            }
        }
        return (count($e) <= 0) ? FALSE : $e;
    }
    
    public function getCuentas($id) {
        $this->db->select('campo2');
        $this->db->where('fk_archivos', $id);
        $this->db->where('linea', 'f');
        $this->db->group_by('campo2');
        $cuentas = $this->db->get($this->pref.'archivos_detalle')->result_array();
        return $cuentas;
    }
    
    public function getDetalleCountId($id, $digito = NULL, $grafica = NULL, $campoAnalizar = NULL) {
        $r = FALSE;
        $this->db->select([
            'campo1',
            'campo2',
            'campo3',
            'campo4',
            'campo5',
            'campo6',
            'campo7',
            'campo8',
            'campo9',
            'campo10',
            'campo11',
            'campo12',
            'campo13',
            'campo14',
            'campo15',
            'campo16',
            'campo17',
            'campo18',
            'campo19',
            'campo20',
        ]);
        $this->db->where('fk_archivos', $id);
        if(($digito !== NULL) && is_numeric($digito) && ($grafica !== NULL) && is_numeric($grafica)){
            if(($grafica == 1) || ($grafica == 12)){
                $this->db->like($campoAnalizar, $digito, 'after');
            }elseif($grafica == 2){
                $this->db->like('SUBSTR('.$campoAnalizar.', 2, 1)', $digito, 'before', FALSE);
            }else{
                return FALSE;
            }
            $this->db->or_group_start();
            $this->db->where('linea', 'e');
            $this->db->where('fk_archivos', $id);
            $this->db->group_end();
        }
        $this->db->order_by('linea', 'ASC');
        $this->db->order_by('id', 'DESC');
        $r = count($this->db->get($this->pref.'archivos_detalle')->result_array()) - 1;
        return $r;
    }
    
    public function getDetalleId($id, $digito = NULL, $grafica = NULL, $campoAnalizar = NULL) {
        $r = FALSE;
        $this->db->select([
            'campo1',
            'campo2',
            'campo3',
            'campo4',
            'campo5',
            'campo6',
            'campo7',
            'campo8',
            'campo9',
            'campo10',
            'campo11',
            'campo12',
            'campo13',
            'campo14',
            'campo15',
            'campo16',
            'campo17',
            'campo18',
            'campo19',
            'campo20',
        ]);
        $this->db->where('fk_archivos', $id);
        if(($digito !== NULL) && is_numeric($digito) && ($grafica !== NULL) && is_numeric($grafica)){
            if(($grafica == 1) || ($grafica == 12)){
                $this->db->like($campoAnalizar, $digito, 'after');
            }elseif($grafica == 2){
                $this->db->like('SUBSTR('.$campoAnalizar.', 2, 1)', $digito, 'before', FALSE);
            }else{
                return FALSE;
            }
            $this->db->or_group_start();
            $this->db->where('linea', 'e');
            $this->db->where('fk_archivos', $id);
            $this->db->group_end();
        }
        $this->db->limit(500);
        $this->db->order_by('linea', 'ASC');
        $this->db->order_by('id', 'DESC');
        $r = $this->db->get($this->pref.'archivos_detalle')->result_array();
        return $r;
    }
    
    public function getBalenaces($folderId) {
        $this->db->select([
            $this->pref.'carpetas.label',
            $this->pref.'carpetas.type',
            $this->pref.'archivos.id',
            $this->pref.'archivos.ext',
            $this->pref.'archivos.tipo',
        ]);
        $this->db->where($this->pref.'archivos.fk_carpetas',$folderId);
        $this->db->where($this->pref.'carpetas.deleted_at', 0);
        $this->db->where_in($this->pref.'carpetas.type',['excel','csv']);
        $this->db->where($this->pref.'archivos.tipo','blp');
        $this->db->join($this->pref.'archivos',$this->pref.'carpetas.archivos_id = '.$this->pref.'archivos.id', 'inner');
        $r = $this->db->get($this->pref.'carpetas')->result_array();
        return $r;
    }
    
    public function getDetalleIdSpider($id) {
        $this->db->select([
            "campo1  AS 'REGISTRO'",
            "campo2  AS 'CUENTA'",
            "campo3  AS 'CTE'",
            "campo4  AS 'FECHA'",
            "campo5  AS 'DOC'",
            "campo6  AS 'REF'",
            "campo7  AS 'NIT'",
            "campo8  AS 'DETALLE'",
            "campo9  AS 'TIPO'",
            "campo10 AS 'VALOR'",
            "campo11 AS 'BASE'",
            "campo12 AS 'CC'",
            "campo13 AS 'TB'",
            "campo14 AS 'PL'"
        ]);
        $this->db->where('fk_archivos', $id);
        $this->db->where('linea', 'f');
        return $this->db->get($this->pref.'archivos_detalle')->result_array();
    }
    
    public function getDetalleIdBenford($id, $campoAnalizar) {
        /*
        $this->db->where('fk_archivos', $id);
        $this->db->where('linea', 'e');
        $encabezado = $this->db->get($this->pref.'archivos_detalle')->row_array();
        if(!is_array($encabezado) || count($encabezado) <= 0){
            return FALSE;
        }
        $campo = array_search($campoAnalizar, $encabezado);
        */
        if($campoAnalizar !== FALSE){
            $this->db->select($campoAnalizar.' AS valor');
            $this->db->where('fk_archivos', $id);
            $this->db->where('linea', 'f');
            return $this->db->get($this->pref.'archivos_detalle')->result_array();
        }else{
            return FALSE;
        }
    }
    
    public function insert_excel($batch) {
        $this->db->insert($this->pref.'archivos', $batch['archivo']);
        $this->db->insert_batch($this->pref.'archivos_detalle', $batch['detalle']);
    }
    
    public function getManipulacion($archivo, $cuenta) {
        $this->db->select('campo1, campo2, ABS(campo6) as campo6');
        $this->db->where('fk_archivos', $archivo);
        $this->db->where_in('campo1', $cuenta);
        $r = $this->db->get($this->pref.'archivos_detalle')->result_array();
        if ((is_bool($r) && $r === FALSE) || (is_array($r) && (count($r) <= 0))) {
            return 0;
        }else{
            $sum = array_sum(array_column($r, 'campo6'));
            return $sum;
        }
    }

}
