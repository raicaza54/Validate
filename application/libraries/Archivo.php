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
    private $pref = 'clie__';
    
    public function __construct() {
        set_time_limit(0);
        $this->CI = & get_instance();
        $this->CI->load->model('Archivos_model');
    }
    
    public function columnas($id) {
        $column = $this->CI->Archivos_model->getEncabezado($id);
        $column = array_keys($column);
        $columnDef = [];
        $columnSql = $column;
        $columnas = $this->CI->db->select('columnas')->where('id', $id)->get($this->pref . 'archivos')->row_array();
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
        return [
            'column'    => $column,
            'columnSql' => $columnSql,
            'columnDef' => $columnDef
        ];
    }
    
    private function columnsDef($e, $key, $x) {
        $r = FALSE;
        switch ($e[1]) {
            case 'date':
                $r = [
                    'sql' => "DATE_FORMAT(".$key.", '%d/%m/%Y') AS ".$key,
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
    
}
