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

    public $column = [];
            
    function __construct() {
        parent::__construct();
        // Set table name
        $this->table         = 'clie__archivos_detalle';
        // Set orderable column fields
        $column = [];
        for ($x = 1; $x <= 20; $x++) {
            $column[] = 'campo'.$x;
        }
        $this->column_order  = $column;
        // Set searchable column fields
        $this->column_search = $column;
        $this->column = $column;
        // Set default order
        $this->order         = [
            'campo1' => 'asc'
        ];
    }
    
    public function getColumn($archivo_id) {
        $this->db->select('columnas');
        $this->db->where('id', $archivo_id);
        $column = $this->db->get('clie__archivos')->row_array();
        if(is_array($column) && count($column)){
            $columnas = $column['columnas'];
            if((strlen($columnas) > 0) && (is_json($columnas))){
                return json_decode($columnas, TRUE);
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
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
        $columnas = $this->archivo->columnas($postData['id']);
        $this->db->select($columnas['columnSql']);
        $this->db->from($this->table);
        $this->db->where('linea', 'f');
        if((array_key_exists('digito', $postData)) && ($postData['digito'] !== NULL) && is_numeric($postData['digito']) && ($postData['grafica'] !== NULL) && is_numeric($postData['grafica'])){
            if(($postData['grafica'] == 1) || ($postData['grafica'] == 12)){
                $this->db->like($postData['campoAnalizar'], $postData['digito'], 'after');
            }elseif($postData['grafica'] == 2){
                $this->db->like('SUBSTR('.$postData['campoAnalizar'].', 2, 1)', $postData['digito'], 'before', FALSE);
            }else{
                return FALSE;
            }
            if(is_numeric($postData['min']) || is_numeric($postData['max'])){
                $this->db->group_start();
                if(is_numeric($postData['min']) && is_numeric($postData['max'])){
                    $this->db->where('FLOOR('.$postData['campoAnalizar'].") >=", $postData['min']);
                    $this->db->where('FLOOR('.$postData['campoAnalizar'].") <=", $postData['max']);                    
                }elseif((strlen($postData['min']) <= 0) && is_numeric($postData['max'])){
                    $this->db->where('FLOOR('.$postData['campoAnalizar'].") <=", $postData['max']);                    
                }elseif(is_numeric($postData['min']) && (strlen($postData['max']) <= 0)){
                    $this->db->where('FLOOR('.$postData['campoAnalizar'].") >=", $postData['min']);
                }
                $this->db->group_end();
            }
        }
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
            $this->db->order_by('LENGTH('.$this->column_order[$postData['order']['0']['column']].')', $postData['order']['0']['dir']);
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by('LENGTH('.key($order).')', $order[key($order)]);
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }    
    
    
    public function getTodas() {
        return $this->db->get('clie__archivos')->result_array();
    }
    
    public function getEncabezado($id, $campoAnalizar = NULL) {
        $this->db->where('fk_archivos', $id);
        $this->db->where('linea', 'e');
        $encabezado = $this->db->get('clie__archivos_detalle')->row_array();
        $e = [];
        for ($x = 1; $x <= 20; $x++) {
            if(trim($encabezado['campo'.$x])){
                $e['campo'.$x] = $encabezado['campo'.$x];
            }
        }
        if(($campoAnalizar != '') && (array_key_exists($campoAnalizar, $encabezado))){
            $e = [$campoAnalizar => $encabezado[$campoAnalizar]] + $e;
        }
        $colum     = $this->db->select('tipo, columnas')->where('id', $id)->get('clie__archivos')->row_array();
        $columnDef = json_decode($colum['columnas'], TRUE);
        $tipo      = $colum['tipo'];
        return [
            'tipo'       => $tipo,
            'columnDef'  => $columnDef,
            'encabezado' => (count($e) <= 0) ? FALSE : $e,
        ];
    }
    
    public function setColumnas($data, $form, $id) {
        $this->db->set('columnas', "'".$data."'", FALSE); 
        $this->db->set('tipo', $form['archivoTipo']); 
        $this->db->where('id', $id);
        return $this->db->update('clie__archivos');
    }
    
    public function getCuentas($id) {
        $this->db->select('campo2');
        $this->db->where('fk_archivos', $id);
        $this->db->where('linea', 'f');
        $this->db->group_by('campo2');
        $cuentas = $this->db->get('clie__archivos_detalle')->result_array();
        return $cuentas;
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
        $r = $this->db->get('clie__archivos_detalle')->result_array();
        return $r;
    }
    
    public function getBalenaces($folderId) {
        $this->db->select([
            'clie__carpetas.label',
            'clie__carpetas.type',
            'clie__archivos.id',
            'clie__archivos.ext',
            'clie__archivos.tipo',
            'clie__archivos.columnas',
            "'T' AS estado",
        ]);
        $this->db->where('clie__archivos.fk_carpetas', $folderId);
        $this->db->where('clie__carpetas.parent_id', $folderId);
        $this->db->where('clie__carpetas.deleted_at', 0);
        $this->db->where_in('clie__carpetas.type',['excel','csv']);
        $this->db->where('clie__archivos.tipo','blp');
        $this->db->join('clie__archivos', 'clie__carpetas.archivos_id = clie__archivos.id', 'inner');
        $r = $this->db->get('clie__carpetas')->result_array();
        if(is_array($r) && count($r)){
            foreach ($r as $key => $value) {
                if((!strpos($value['columnas'], 'cta') !== FALSE) || (!strpos($value['columnas'], 'valor') !== FALSE)){
                    $r[$key]['estado'] = 'F';
                }
            }
        }
        return $r;
    }
    
    public function getDetalleIdSpider($id, $column) {
        $this->db->select($column['string']);
        $this->db->where('fk_archivos', $id);
        $this->db->where('linea', 'f');
        return $this->db->get('clie__archivos_detalle')->result_array();
    }
    
    public function getDetalleIdBenford($id, $campoAnalizar) {
        if($campoAnalizar !== FALSE){
            $this->db->select($campoAnalizar.' AS valor');
            $this->db->where('fk_archivos', $id);
            $this->db->where('linea', 'f');
            return $this->db->get('clie__archivos_detalle')->result_array();
        }else{
            return FALSE;
        }
    }
    
    public function insert_excel($batch) {
        $arch = $this->db->insert('clie__archivos', $batch['archivo']);
        $detall = $this->db->insert_batch('clie__archivos_detalle', $batch['detalle']);
        return $arch;
    }
    
    public function getManipulacion($archivo, $cuenta, $column) {
        $this->db->select($column['string']);
        $this->db->where('fk_archivos', $archivo);
        $this->db->where_in($column['array']['cta'], $cuenta);
        $r = $this->db->get('clie__archivos_detalle')->result_array();
        if ((is_bool($r) && $r === FALSE) || (is_array($r) && (count($r) <= 0))) {
            return 0;
        }else{
            $sum = array_sum(array_column($r, 'valor'));
            return $sum;
        }
    }

}
