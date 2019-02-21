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
     * Variable de limites por valor en el caso del segundo valor
     * @var type array
     */
    private $benford_2d = array(
        0 => 11.968,
        1 => 11.389,
        2 => 10.882,
        3 => 10.433,
        4 => 10.031,
        5 => 9.668,
        6 => 9.337,
        7 => 9.035,
        8 => 8.757,
        9 => 8.500
    );
    
    /**
     * Variable de limites Desviacion Absoluta Media (MAD) primer digito
     * @var type array
     */
    private $mad_d1 = array(
        array('min' => 0,     'max' => 0.006, 'descripcion' => 'Conformidad Cuasi-Perfecta'),
        array('min' => 0.006, 'max' => 0.012, 'descripcion' => 'Conformidad Aceptable'),
        array('min' => 0.012, 'max' => 0.015, 'descripcion' => 'Conformidad Marginalmente Aceptable'),
        array('min' => 0.015, 'max' => 10000, 'descripcion' => 'No Conformidad'),
    );
    
    /**
     * Variable de limites Desviacion Absoluta Media (MAD) segundo digito
     * @var type array
     */
    private $mad_d2 = array(
        array('min' => 0,     'max' => 0.008, 'descripcion' => 'Conformidad Cuasi-Perfecta'),
        array('min' => 0.008, 'max' => 0.010, 'descripcion' => 'Conformidad Aceptable'),
        array('min' => 0.010, 'max' => 0.012, 'descripcion' => 'Conformidad Marginalmente Aceptable'),
        array('min' => 0.012, 'max' => 10000, 'descripcion' => 'No Conformidad'),
    );
    
    public $data = [];
    
    public function __construct() {
        $this->CI = & get_instance();
    }
    
    private function formatoArray($start, $lenght) {
        $r1 = [];
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
    
    public function procesar($digito) {
        $d1  = FALSE;
        $d2  = FALSE;
        $d12 = FALSE;
        switch ($digito) {
            case 1:
                $d1 = $this->benford_d1();
                break;
            case 2:
                $d2 = $this->benford_d2();
                break;
            default:
                return FALSE;
                break;
        }
        return array(
            'd1'  => $d1,
            'd2'  => $d2,
            'd12' => $d12,
        );
    }
    
    private function frecuencia($param) {
        extract($param);
        $r1 = $this->formatoArray($start, $lenght);
        $valores = array_column($r1, 'valor');
        $r2 = [];
        $x1 = ['x1'];
        $x2 = ['x2'];
        for ($x = $i; $x <= 9; $x++) {
            $r2[$x] = array(
                'numero'     => $x,
                'frecuencia' => $this->contar_valores($valores, $x),
                'observado'  => 0,
                'benford'    => $this->{'benford_'.$m.'d'}[$x],
                'variacion'  => 0
            );
            $x1[] = $x;
            $x2[] = $x;
        }
        return array(
            'r2' => $r2,
            'x1' => $x1,
            'x2' => $x2
        );
    }
    
    private function benford_d2() {
        extract($this->frecuencia([
            'start'  => 1,
            'lenght' => 1,
            'i'      => 0,
            'm'      => 2
        ]));
        $total = array_sum(array_column($r2, 'frecuencia'));
        for ($x = 0; $x <= 9; $x++) {
            $r2[$x]['observado'] = ($r2[$x]['frecuencia']*100)/$total;
            $r2[$x]['variacion'] = abs($r2[$x]['observado'] - $this->benford_2d[$x])/100;
        }
        $total_variacion = array_sum(array_column($r2, 'variacion'));
        $mad = $total_variacion/9;
        $mad_d2 = 'N/A';
        foreach ($this->mad_d2 as $value){
            if(($mad > $value['min']) AND ($mad <= $value['max'])){
                $mad_d2 = $value['descripcion'];
            }
        }
        $cantidad = count($this->data);
        $i = 0;
        for ($x = 1; $x <= 9; $x++) {
            $i = ($cantidad * ($this->benford_2d[$x]/100));
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
        $r2 = $this->formatoTabla($r2);
        return array(
            'tabla'       => $r2,
            'grafica'     => $grf,
            'x1'          => $x1,
            'x2'          => $x2,
            'mad'         => '<b>'.number_format($mad, 12, ',', '.').'</b>',
            'madDescribe' => '<b>'.$mad_d2.'</b>',
        );
    }
    
    private function benford_d1() {
        extract($this->frecuencia([
            'start'  => 0,
            'lenght' => 1,
            'i'      => 1,
            'm'      => 1
        ]));
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
        $r2 = $this->formatoTabla($r2);
        return array(
            'tabla'       => $r2,
            'grafica'     => $grf,
            'x1'          => $x1,
            'x2'          => $x2,
            'mad'         => '<b>'.number_format($mad, 12, ',', '.').'</b>',
            'madDescribe' => '<b>'.$mad_d1.'</b>',
        );
    }
    
    private function formatoTabla($r2) {
        array_walk($r2, function (&$value){
            $value['frecuencia'] = number_format($value['frecuencia'], 0, ',', '.');
            $value['observado']  = number_format($value['observado'], 3, ',', '.') . '%';
            $value['benford']    = number_format($value['benford'], 3, ',', '.') . '%';
            $value['variacion']  = number_format($value['variacion'], 12, ',', '.');
        });        
        return $r2;
    }
    
    private function contar_valores($a, $buscado) {
        if (!is_array($a))
            return NULL;
        $v = array_count_values($a);
        return array_key_exists($buscado, $a) ? $v[$buscado] : 0;
    }    
    
}
