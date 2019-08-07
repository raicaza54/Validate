<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Listas Model, base de datos que tiene contenido que incrimina a las empresas o 
 * personas en delitos financieros, nacional o internacional, terrorismo, lavado
 * de activos entre otros
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2019-07-07
 */
class Listas_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    public function getData() {
        $this->db->select('CONCAT(id,"~",nombre) AS nombre, CONCAT(id,"~",identificacion) AS identificacion');
        $this->db->where('deleted_at', 0);
        $data = $this->db->get('sist__listas')->result_array();
        return $data;
    }
    
    public function extrac($a, $campo) {
$query = $this->db->query("
SELECT
    *
FROM
    (
    SELECT
        sl.lista,
        sl.nombre,
        sl.aka,
        sl.identificacion,
        sl.otros,
        sl.deleted_at,
        levenshtein_ratio('$a', sl.$campo) AS ratio
    FROM
        sist__listas AS sl
    WHERE
        deleted_at = 0
) AS li
WHERE
    li.ratio > 85");
        
        //$query = $this->db->query("SELECT * FROM sist__listas WHERE levenshtein_ratio('$a', $campo) > 85 AND deleted_at = 0");
        $data = $query->result_array();
        //$this->db->like($campo, $a, 'none');
        //$this->db->where('deleted_at', 0);
        //$data = $this->db->get('sist__listas')->result_array();
        debug_file($data);
        return $data;        
    }
    
}
