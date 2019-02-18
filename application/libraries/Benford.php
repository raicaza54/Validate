<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ley Benford
 * La ley de Benford (por el físico Frank Benford​), también conocida 
 * como la ley del primer dígito, asegura que, en gran variedad de conjuntos 
 * de datos numéricos que existen en la vida real, la primera cifra es 1 con 
 * mucha más frecuencia que el resto de los números.
 * 
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Controlador Archivos XLS/CSV
 * @LastUpdate  2019-02-14
 */
class Benford {
    
    private $CI;
    
    public function __construct() {
        $this->CI = & get_instance();
    }
    
    
    
}
