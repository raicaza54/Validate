<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Libreria Archivo
 * @LastUpdate  2019-04-16
 */
//{"campo1":["Registro","num"],"campo2":["Cuenta","num"],"campo3":["Tipo Documento","string"],"campo4":["Fecha","date"],"campo5":["Num Documento","num"],"campo6":["Referencia","string"],"campo7":["NIT","right"],"campo8":["Detalle","string"],"campo9":["Tipo","num"],"campo10":["Valor","float"],"campo11":["Campo","string"],"campo12":["Campo","string"],"campo13":["Campo","string"],"campo14":["Campo","string"],"campo15":["Campo","float"],"campo16":["Campo","float"],"campo17":["Campo","string"],"campo18":["Campo","string"],"campo19":["Campo","string"],"campo20":["Campo","string"]}
class Archivo {
    
    private $CI;
    var $columnManipulacion = [
        'cta',
        'valor',
    ];    
    var $columnListas = [
        'nombre',
        'identificacion',
    ];    
    var $columnCondicion = [
        'base',
        'valor',
    ];    
    var $columnSpider = [
        'cta',
        'comp',
        'doc',
        'tipo',
        'valor',
        'debe',
        'haber',
    ];
    
    public function __construct() {
        set_time_limit(0);
        $this->CI = & get_instance();
        $this->CI->load->model('Archivos_model');
    }
    
    public function columnas($id) {
        $column = $this->CI->Archivos_model->getEncabezado($id);
        if(!is_array($column)){
            return FALSE;
        }
        $columndh = $column['columnDef'];
        $column = array_keys($column['encabezado']);
        $columnDef = [];
        $columnDefs = [];
        $columnSql = $column;
        $columnas = $this->CI->db->select('nombre, columnas, tipo, formato, pid, file_name')->where('id', $id)->get('clie__archivos')->row_array();
        if(is_array($columnas) && count($columnas) && array_key_exists('columnas', $columnas)){
            $columnDefs = json_decode($columnas['columnas'], TRUE);
            foreach ($column as $key => $value) {
                if(array_key_exists($value, $columnDefs)){
                    $col = $this->columnsDef($columnDefs[$value], $value, $key);
                    $columnSql[$key]  = $col['sql'];
                    $columnDef[$key] = $col['def'];
                }
            }
        }
        $formato = ((is_array($columnas) && array_key_exists('formato', $columnas)) ? $columnas['formato'] : '');
        $debehaber = []; $dh = '';
        if($formato == 'debehaber'){
            if (is_array($columndh) && (count($columndh) > 0)) {
                foreach ($columndh as $key => $value) {
                    if(in_array($value[0], ['debe','haber'])){
                        $debehaber[] = $key;
                    }
                }
            }
            if(count($debehaber) == 2){
                $dh = '(';
                foreach ($debehaber as $value){
                    $dh .= $value.'+';
                }
                $dh = substr($dh, 0, -1);
                $dh .= ')';
            }
        }
        return [
            'archivo'   => [
                'nombre'    => ((is_array($columnas) && array_key_exists('nombre', $columnas)) ? $columnas['nombre'] : ''),
                'tipo'      => ((is_array($columnas) && array_key_exists('tipo', $columnas)) ? $columnas['tipo'] : ''),
                'pid'       => ((is_array($columnas) && array_key_exists('pid', $columnas)) ? $columnas['pid'] : ''),
                'file_name' => ((is_array($columnas) && array_key_exists('file_name', $columnas)) ? $columnas['file_name'] : ''),
                'formato'   => $formato
            ],
            'column'    => $column,
            'columnSql' => $columnSql,
            'columnDef' => $columnDef,
            'debehaber' => $dh,
        ];
    }
    
    private function columnsDef($e, $key, $x) {
        $r = FALSE;
        switch ($e[1]) {
            case 'date':
                $r = [
                    //'sql' => "DATE_FORMAT(".$key.", '%d/%m/%Y') AS ".$key,
                    'sql' => "IF(LENGTH(".$key."),DATE_FORMAT(DATE('1899-12-30') + INTERVAL ".$key." DAY, '%d/%m/%Y'), DATE_FORMAT(".$key.", '%d/%m/%Y')) AS ".$key,
                    'def' => ['targets' => $x, 'className' => "dt-body-right"],
                ];
                break;
            case 'float':
                $r = [
                    'sql' => "FORMAT(".$key.", 2, 'de_DE') AS ".$key,
                    'def' => ['targets' => $x, 'className' => "dt-body-right"],
                ];
                break;
            case 'num':
                $r = [
                    'sql' => $key,
                    'def' => ['targets' => $x, 'className' => "dt-body-right"],
                ];
                break;
            case 'right':
                $r = [
                    'sql' => $key,
                    'def' => ['targets' => $x, 'className' => "dt-body-right"],
                ];
                break;
            default:
                $r = [
                    'sql' => $key,
                    'def' => ['targets' => $x, 'className' => "dt-body-left"],
                ];
                break;
        }
        return $r;
    }    
    
    private function configColumna($e) {
        $r = FALSE;
        if($e == 'num'){
            $r = ['campo', 'num'];
        }elseif($e == 'float'){
            $r = ['campo', 'float'];
        }elseif($e == 'string'){
            $r = ['campo', 'string'];
        }elseif($e == 'date'){
            $r = ['campo', 'date'];
        }elseif($e == 'cta'){
            $r = ['cta', 'num'];
        }elseif($e == 'comp'){
            $r = ['comp', 'string'];
        }elseif($e == 'doc'){
            $r = ['doc', 'num'];
        }elseif($e == 'tipo'){
            $r = ['tipo', 'num'];
        }elseif($e == 'valor'){
            $r = ['valor', 'float'];
        }elseif($e == 'debe'){
            $r = ['debe', 'float'];
        }elseif($e == 'haber'){
            $r = ['haber', 'float'];
        }elseif($e == 'identificacion'){
            $r = ['identificacion', 'string'];
        }elseif($e == 'nombre'){
            $r = ['nombre', 'string'];
        }elseif($e == 'base'){
            $r = ['base', 'float'];
        }
        return [
            $r[0],
            $r[1]
        ];
    }

    public function configColumnas($post) {
        $columnas = [];
        foreach ($post as $key => $value) {
            if(strpos($key, 'campo') !== FALSE){
                $columnas[$key] = $this->configColumna($value);
            }
        }
        return json_encode($columnas);
    }
    
    public function columnManipulacion($archivo_id) {
        $columnDef = [];
        $columnas = $this->CI->Archivos_model->getColumn($archivo_id);
        if(is_array($columnas) && count($columnas)){
            foreach ($columnas as $key => $value) {
                foreach ($this->columnManipulacion as $col) {
                    if($col == $value[0]){
                        $columnDef[$col] = $key;
                    }
                }
            }
        }else{
            return FALSE;
        }
        $columnString = $this->columnString($columnDef);
        return [
            'string' => $columnString,
            'array'  => $columnDef
        ];
    }
    
    public function columnSpider($archivo_id) {
        $columnDef = [];
        $encabezado = $this->CI->Archivos_model->getEncabezado($archivo_id);
        $columnas = $encabezado['columnDef'];
        if(is_array($columnas) && count($columnas)){
            foreach ($columnas as $key => $value) {
                foreach ($this->columnSpider as $col) {
                    if($col == $value[0]){
                        $columnDef[$col] = $key;
                    }
                }
            }
        }else{
            return FALSE;
        }
        $columnString = $this->columnString($columnDef, $encabezado['formato']);
        return [
            'string' => $columnString,
            'array'  => $columnDef
        ];
    }
    
    public function columnListas($archivo_id) {
        $columnDef = [];
        $encabezado = $this->CI->Archivos_model->getEncabezado($archivo_id);
        $columnas = $encabezado['columnDef'];
        if(is_array($columnas) && count($columnas)){
            foreach ($columnas as $key => $value) {
                foreach ($this->columnListas as $col) {
                    if($col == $value[0]){
                        $columnDef[$col] = $key;
                    }
                }
            }
        }else{
            return FALSE;
        }
        $columnString = $this->columnString($columnDef, $encabezado['formato']);
        return [
            'string' => $columnString,
            'array'  => $columnDef
        ];
    }
    
    public function columnCondicion($archivo_id) {
        $columnDef = [];
        $encabezado = $this->CI->Archivos_model->getEncabezado($archivo_id);
        $columnas = $encabezado['columnDef'];
        if(is_array($columnas) && count($columnas)){
            foreach ($columnas as $key => $value) {
                foreach ($this->columnCondicion as $col) {
                    if($col == $value[0]){
                        $columnDef[$col] = $key;
                    }
                }
            }
        }else{
            return FALSE;
        }
        $columnString = $this->columnString($columnDef, $encabezado['formato']);
        return [
            'string' => $columnString,
            'array'  => $columnDef
        ];
    }
    
    private function columnString($column, $formato = '') {
        $columnString = ''; $debehaber = [];
        if(is_array($column) && count($column)){
            foreach ($column as $key => $value) {
                if(($formato == 'debehaber') && ($key == 'debe')){
                    $columnString .= 'IF('.$value.' > 0,1,2) AS tipo, ';
                    $debehaber[] = $value;
                }elseif(($formato == 'debehaber') && ($key == 'haber')){
                    $debehaber[] = $value;
                }else{
                    $columnString .= $value.' AS '.$key.', ';
                }
            }
            if(count($debehaber) == 2){
                $dh = '(';
                foreach ($debehaber as $value){
                    $dh .= $value.'+';
                }
                $dh = substr($dh, 0, -1);
                $dh .= ')';
                $columnString .= $dh.' AS valor, ';
            }                
            $columnString = substr($columnString, 0, -2);
        }else{
            return FALSE;
        }
        return $columnString;
    }
}
