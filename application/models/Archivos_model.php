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
    
    public function getDetalleId($id) {
        $this->db->select('campo1, campo2, campo3, campo4, campo5, campo6, campo7, '
                . 'campo8, campo9, campo10, campo11, campo12, campo13, campo14, campo15');
        $this->db->where('fk_archivos', $id);
        $this->db->limit(500);
        $this->db->order_by('linea', 'ASC');
        $this->db->order_by('id', 'DESC');
        return $this->db->get($this->pref.'archivos_detalle')->result_array();
    }

}
