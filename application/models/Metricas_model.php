<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Analisis Model, al realizar cada ejecucion se almacena el resultado
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2019-06-03
 */
class Metricas_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    public function reporte1($d, $h) {
        $r1 = $this->db->query('
            SELECT CONCAT(au.first_name," ",au.last_name) AS nombre, COUNT(*) AS cantidad FROM track__seguimiento ts RIGHT JOIN auth__users au ON ts.user_identifier = au.id
            WHERE post LIKE "%password%" AND date(ts.`timestamp`) BETWEEN "'.$d.'" AND "'.$h.'" GROUP BY au.id ORDER BY cantidad DESC
        ');
        $r['usuario'] = $r1->result_array();
        $r2 = $this->db->query('
            SELECT DAYNAME(ts.`timestamp`) AS dia, COUNT(*) AS cantidad FROM track__seguimiento ts RIGHT JOIN auth__users au ON ts.user_identifier = au.id
            WHERE post LIKE "%password%" AND date(ts.`timestamp`) BETWEEN "'.$d.'" AND "'.$h.'" GROUP BY DAYNAME(ts.`timestamp`) ORDER BY cantidad DESC
        ');
        $r['dia'] = $r2->result_array();
        $r3 = $this->db->query('
            SELECT HOUR(ts.`timestamp`) AS hora, COUNT(*) AS cantidad FROM track__seguimiento ts RIGHT JOIN auth__users au ON ts.user_identifier = au.id
            WHERE post LIKE "%password%" AND date(ts.`timestamp`) BETWEEN "'.$d.'" AND "'.$h.'" GROUP BY HOUR(ts.`timestamp`) ORDER BY hora        
        ');
        $r['hora'] = $r3->result_array();
        return $r;
    }
    
    public function reporte2($d, $h) {
        $r = $this->db->query('
            SELECT CONCAT(au.first_name," ",au.last_name) AS nombre, tb.fecha, tb.acciones FROM (
            SELECT ts.user_identifier, date(ts.`timestamp`) as fecha, GROUP_CONCAT(DISTINCT ts.`method`) AS acciones FROM track__seguimiento ts WHERE ts.`method` IN(
            "benford",
            "benfordPdf",
            "condicionCuenta",
            "condicioncuentaPdf",
            "manipulacion",
            "manipulacionPdf",
            "listascontrol",
            "listascontrolPdf",
            "spider",
            "spiderPdf",
            "configurar",
            "login",
            "logout",
            "subir") AND date(ts.`timestamp`) BETWEEN "'.$d.'" AND "'.$h.'" GROUP BY date(ts.`timestamp`), ts.user_identifier) AS tb INNER JOIN auth__users au ON tb.user_identifier = au.id ORDER BY nombre,fecha
        ');
        return $r->result_array();
    }
    
}
