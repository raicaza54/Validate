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
 * @LastUpdate 2019-02-13
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
        $this->order = [
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
        if($columnas['archivo']['formato'] == 'debehaber'){
            $postData['campoAnalizar'] = $columnas['debehaber'];
        }
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
            //$this->db->order_by('LENGTH('.$this->column_order[$postData['order']['0']['column']].')', $postData['order']['0']['dir']);
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            //$this->db->order_by('LENGTH('.key($order).')', $order[key($order)]);
            $this->db->order_by(key($order), $order[key($order)]);
        }
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
        $colum     = $this->db->select('tipo, columnas, formato')->where('id', $id)->get('clie__archivos')->row_array();
        $columnDef = json_decode($colum['columnas'], TRUE);
        $tipo      = $colum['tipo'];
        $formato   = $colum['formato'];
        return [
            'tipo'       => $tipo,
            'formato'    => $formato,
            'columnDef'  => $columnDef,
            'encabezado' => (count($e) <= 0) ? FALSE : $e,
        ];
    }
    
    public function setColumnas($data, $form, $id) {
        $this->db->set('columnas', "'".$data."'", FALSE); 
        $this->db->set('tipo', $form['archivoTipo']); 
        if(array_key_exists('archivoFormato', $form)){
            $this->db->set('formato', $form['archivoFormato']);
        }
        $this->db->where('id', $id);
        return $this->db->update('clie__archivos');
    }
    
    public function getCuentas($id, $column) {
        $this->db->select($column['array']['cta'], FALSE);
        $this->db->where('fk_archivos', $id);
        $this->db->where('linea', 'f');
        $this->db->group_by($column['array']['cta']);
        $cuentas = $this->db->get('clie__archivos_detalle')->result_array();
        return $cuentas;
    }
    
    public function getComprobantes($id, $column) {
        $this->db->select($column['array']['comp'], FALSE);
        $this->db->where('fk_archivos', $id);
        $this->db->where('linea', 'f');
        $this->db->group_by($column['array']['comp']);
        $cuentas = $this->db->get('clie__archivos_detalle')->result_array();
        return $cuentas;
    }
    
    public function getCuentasN($id, $column) {
        $cuentasn = FALSE; $cuentas = [];
        $this->db->select('archivos_id, columnas', FALSE);
        $this->db->where('tipo', 'blp');
        $this->db->where('deleted_at', '0');
        $this->db->where('disabled', '0');
        $this->db->like('clie__archivos.columnas', 'cta');
        $this->db->like('clie__archivos.columnas', 'ctan');
        $this->db->like('clie__archivos.columnas', 'valor');
        $this->db->join('clie__carpetas', 'clie__archivos.id = clie__carpetas.archivos_id');
        $balances = $this->db->get('clie__archivos')->result_array();
        $col = []; $colSql = [];
        if(is_array($balances) && count($balances)){
            foreach ($balances as $value) {
                $col = json_decode($value['columnas'], TRUE);
                if(is_array($col) && count($col)){
                    foreach ($col as $key => $valueCol) {
                        if($valueCol[0] != 'campo') $colSql[$valueCol[0]] = $key;
                    }
                    $this->db->select('DISTINCT TRIM('.$colSql['cta'].') AS cta, UPPER(TRIM('.$colSql['ctan'].')) AS ctan', FALSE);
                    $this->db->where('linea', 'f');
                    $this->db->where('fk_archivos', $value['archivos_id']);
                    $cuentas = array_merge_recursive($cuentas, $this->db->get('clie__archivos_detalle')->result_array());
                    unique_multidim_array($cuentas, 'cta');
                }
            }
        }
        $cuentasn = array_column($cuentas, 'ctan', 'cta');
        return $cuentasn;
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
                if((!strpos($value['columnas'], 'cta') !== FALSE) || (!strpos($value['columnas'], 'valor') !== FALSE) || (!strpos($value['columnas'], 'ctan') !== FALSE)){
                    $r[$key]['estado'] = 'F';
                }
            }
        }
        return $r;
    }
    
    public function getDetalleIdSpider($id, $column, $comp) {
        $this->db->select($column['string']);
        $this->db->where('fk_archivos', $id);
        $this->db->where('linea', 'f');
        if(is_array($comp) && count($comp)){
            $this->db->where_in($column['array']['comp'], $comp);
        }
        $r = $this->db->get('clie__archivos_detalle')->result_array();
        return $r;
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
    
    public function getDetalleIdBadBenford($id, $campoAnalizar, $numero) {
        if($campoAnalizar !== FALSE){
            $this->db->select($campoAnalizar.' AS valor, COUNT(*) AS cantidad');
            $this->db->where('fk_archivos', $id);
            $this->db->where('linea', 'f');
            $this->db->like($campoAnalizar, $numero, 'after');
            $this->db->group_by($campoAnalizar);
            $this->db->order_by('cantidad', 'DESC');
            $this->db->limit(10, 0);
            $r = $this->db->get('clie__archivos_detalle')->result_array();
            return $r;
        }else{
            return FALSE;
        }
    }
    
    public function insert_preprocesar($batch) {
        $this->db->insert('clie__archivos', $batch['archivo']);
        return $this->db->affected_rows() == 1;
    }
    
    public function insert_excel($batch, $id) {
        //$detall = $this->db->insert_batch('clie__archivos_detalle', $batch['detalle']);
        $this->db->update('clie__carpetas', ['disabled' => 0], ['archivos_id' => $id, 'deleted_at' => 0]);
        return TRUE;
    }
    
    public function update_excel($data, $id) {
        $this->db->update('clie__archivos', ['file_name' => $data['fullpath']], ['id' => $id]);
        return $this->db->affected_rows() == 1;
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
    
    public function getById($id) {
        $this->db->select('id, fk_carpetas, nombre, ext, tipo, file_name');
        $this->db->where('created_clie', $this->session->userdata('clientes_id'));
        $this->db->where('id', $id);
        $e = $this->db->get('clie__archivos')->row_array();
        return $e;
    }
    
    public function getFileId($filename) {
        $this->db->select('id, fk_carpetas, nombre, ext, tipo, file_name');
        $this->db->where('created_clie', $this->session->userdata('clientes_id'));
        $this->db->like('file_name', $filename, 'before');
        $e = $this->db->get('clie__archivos')->row_array();
        return $e;        
    }
    
    public function getDetalleAll($id){
        $this->db->where('fk_archivos', $id);
        $this->db->where('linea', 'f');
        $r = $this->db->get('clie__archivos_detalle')->result_array();
        return $r;
    }
    
    public function getCuentasBase($id_archivo, $column) {
        $this->db->select($column['array']['cta'].' AS cta');
        $this->db->where('created_clie', $this->session->userdata('clientes_id'));
        $this->db->where('fk_archivos', $id_archivo);
        $this->db->where('linea', 'f');
        $this->db->where($column['array']['base'].'>', 0);
        $this->db->group_by($column['array']['cta']);
        $this->db->order_by($column['array']['cta']);
        $r = $this->db->get('clie__archivos_detalle')->result_array();
        return $r;
    }
    
    public function getBases($id_archivo, $column) {
        $this->db->select($column['string']);
        $this->db->select('(('.$column['array']['valor'].'/'.$column['array']['base'].')*100) AS calculo');
        $this->db->where('created_clie', $this->session->userdata('clientes_id'));
        $this->db->where('fk_archivos', $id_archivo);
        $this->db->where('linea', 'f');
        $this->db->where($column['array']['base'].'>', 0);
        $this->db->limit(500);
        $r = $this->db->get('clie__archivos_detalle')->result_array();
        return $r;
    }
    
    public function getDigito($postData) {
        $columnas = $this->archivo->columnas($postData['id']);
        if($columnas['archivo']['formato'] == 'debehaber'){
            $postData['campoAnalizar'] = $columnas['debehaber'];
        }
        $this->db->select($columnas['columnSql']);
        $this->db->from($this->table);
        $this->db->where('fk_archivos', $postData['id']);
        $this->db->group_start();
        if((array_key_exists('digito', $postData)) && ($postData['digito'] !== NULL) && is_numeric($postData['digito']) && ($postData['grafica'] !== NULL) && is_numeric($postData['grafica'])){
            if(($postData['grafica'] == 1) || ($postData['grafica'] == 12)){
                $this->db->like($postData['campoAnalizar'], $postData['digito'], 'after');
            }elseif($postData['grafica'] == 2){
                $this->db->like('SUBSTR('.$postData['campoAnalizar'].', 2, 1)', $postData['digito'], 'before', FALSE);
            }else{
                return FALSE;
            }
        }
        $this->db->or_where('linea', 'e');
        $this->db->group_end();
        $query = $this->db->get();
        return $query->result_array();
    }
}
