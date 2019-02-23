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
                $e[] = $encabezado['campo'.$x];
            }
        }
        return (count($e) <= 0) ? FALSE : $e;
    }
    
    public function getDetalleId($id, $digito = NULL, $grafica = NULL) {
        $r = FALSE;
        $this->db->select('campo1, campo2, campo3, campo4, campo5, campo6, campo7, '
                . 'campo8, campo9, campo10, campo11, campo12, campo13, campo14, campo15');
        $this->db->where('fk_archivos', $id);
        if(($digito !== NULL) && is_numeric($digito) && ($grafica !== NULL) && is_numeric($grafica)){
            if(($grafica == 1) || ($grafica == 12)){
                $this->db->like('campo9', $digito, 'after');
            }elseif($grafica == 2){
                $this->db->like('SUBSTR(campo9, 1, 2)', $digito, 'before', FALSE);
            }else{
                return FALSE;
            }
            $this->db->or_where('linea', 'e');
        }
        $this->db->limit(500);
        $this->db->order_by('linea', 'ASC');
        $this->db->order_by('id', 'DESC');
        $r = $this->db->get($this->pref.'archivos_detalle')->result_array();
        return $r;
    }
    
    public function getDetalleIdBenford($id, $campoAnalizar) {
        $this->db->where('fk_archivos', $id);
        $this->db->where('linea', 'e');
        $encabezado = $this->db->get($this->pref.'archivos_detalle')->row_array();
        if(!is_array($encabezado) || count($encabezado) <= 0){
            return FALSE;
        }
        $campo = array_search($campoAnalizar, $encabezado);
        if($campo !== FALSE){
            $this->db->select($campo.' AS valor');
            $this->db->where('fk_archivos', $id);
            $this->db->where('linea', 'f');
            return $this->db->get($this->pref.'archivos_detalle')->result_array();
        }else{
            return FALSE;
        }
    }

}
