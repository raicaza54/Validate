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
    
    /**
     * Variable de limites por valor en el caso del primer valor
     * @var type array
     */
    private $benford_1d = array(
        1 => 30.103,
        2 => 17.609,
        3 => 12.494,
        4 => 9.691,
        5 => 7.918,
        6 => 6.695,
        7 => 5.799,
        8 => 5.115,
        9 => 4.576,
    );
    
    /**
     * Variable de limites Desviacion Absoluta Media (MAD)
     * @var type array
     */
    private $mad_d1 = array(
        array('min' => 0,     'max' => 0.006, 'descripcion' => 'Conformidad Cuasi-Perfecta'),
        array('min' => 0.006, 'max' => 0.012, 'descripcion' => 'Conformidad Aceptable'),
        array('min' => 0.012, 'max' => 0.015, 'descripcion' => 'Conformidad Marginalmente Aceptable'),
        array('min' => 0.015, 'max' => 10000, 'descripcion' => 'No Conformidad'),
    );    
    
    public $data = [];
    
    public function __construct() {
        $this->CI = & get_instance();
    }
    
    private function formatoArray($start, $lenght) {
        foreach ($this->data as $key => $value) {
            if(is_numeric($value['valor']) && ((int) $value['valor'] > 0)){
                $r1[] = array(
                    'id'    => $key,
                    'valor' => substr($value['valor'], $start, $lenght),
                );                
            }
        }
        return $r1;
    }
    
    public function benford_d1() {
        $r1 = $this->formatoArray(0, 1);
        $valores = array_column($r1, 'valor');
        $r2 = [];
        for ($x = 1; $x <= 9; $x++) {
            $r2[$x] = array(
                'numero'     => $x,
                'frecuencia' => $this->contar_valores($valores, $x),
                'observado'  => 0,
                'benford'    => $this->benford_1d[$x],
                'variacion'  => 0
            );
        }
        $total = array_sum(array_column($r2, 'frecuencia'));
        for ($x = 1; $x <= 9; $x++) {
            $r2[$x]['observado'] = ($r2[$x]['frecuencia']*100)/$total;
            $r2[$x]['variacion'] = abs($r2[$x]['observado'] - $this->benford_1d[$x])/100;
        }
        $total_variacion = array_sum(array_column($r2, 'variacion'));
        $mad = $total_variacion/9;
        $mad_d1 = 'N/A';
        foreach ($this->mad_d1 as $value){            
            if(($mad > $value['min']) AND ($mad <= $value['max'])){
                $mad_d1 = $value['descripcion'];
            }
        }
        $cantidad = count($this->data);
        $i = 0;
        for ($x = 1; $x <= 9; $x++) {
            $i = ($cantidad * ($this->benford_1d[$x]/100));
            $r3[$x] = array(
                'esperado'  => $i,
                'observado' => pow(($r2[$x]['frecuencia']-$i),2)/$i,
            );
        }
        $total_chi = array_sum(array_column($r3, 'observado'));
        $chi = 'NO CONFIABLE';
        if($total_chi > 15.5073){
            $chi = 'CONFIABLE';
        }
        $grf = ['data1' => ['data1'], 'data2' => ['data2']];
        foreach ($r2 as $key => $value) {
            $grf['data1'][] = number_format($value['benford'], 3, '.', '');
            $grf['data2'][] = number_format($value['observado'], 3, '.', '');
        }
        debug_file($grf);
        return array(
            'tabla'   => $r2,
            'grafica' => $grf
        );
    }
    
    private function contar_valores($a, $buscado) {
        if (!is_array($a))
            return NULL;
        $v = array_count_values($a);
        return array_key_exists($buscado, $a) ? $v[$buscado] : 0;
    }    
    
}
