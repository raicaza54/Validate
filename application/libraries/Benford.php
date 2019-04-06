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
 * @Description Libreria analisis de Ley de Benford
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
     * Variable de limites por valor en el caso de dos primeros valores
     * @var type array
     */
    private $benford_12d = array(
        10 => 4.139,
        11 => 3.779,
        12 => 3.476,
        13 => 3.218,
        14 => 2.996,
        15 => 2.803,
        16 => 2.633,
        17 => 2.482,
        18 => 2.348,
        19 => 2.228,
        20 => 2.119,
        21 => 2.020,
        22 => 1.931,
        23 => 1.848,
        24 => 1.773,
        25 => 1.703,
        26 => 1.639,
        27 => 1.579,
        28 => 1.524,
        29 => 1.472,
        30 => 1.424,
        31 => 1.379,
        32 => 1.336,
        33 => 1.296,
        34 => 1.259,
        35 => 1.223,
        36 => 1.190,
        37 => 1.158,
        38 => 1.128,
        39 => 1.100,
        40 => 1.072,
        41 => 1.047,
        42 => 1.022,
        43 => 0.998,
        44 => 0.976,
        45 => 0.955,
        46 => 0.934,
        47 => 0.914,
        48 => 0.895,
        49 => 0.877,
        50 => 0.860,
        51 => 0.843,
        52 => 0.827,
        53 => 0.812,
        54 => 0.797,
        55 => 0.783,
        56 => 0.769,
        57 => 0.755,
        58 => 0.742,
        59 => 0.730,
        60 => 0.718,
        61 => 0.706,
        62 => 0.695,
        63 => 0.684,
        64 => 0.673,
        65 => 0.663,
        66 => 0.653,
        67 => 0.643,
        68 => 0.634,
        69 => 0.625,
        70 => 0.616,
        71 => 0.607,
        72 => 0.599,
        73 => 0.591,
        74 => 0.583,
        75 => 0.575,
        76 => 0.568,
        77 => 0.560,
        78 => 0.553,
        79 => 0.546,
        80 => 0.540,
        81 => 0.533,
        82 => 0.526,
        83 => 0.520,
        84 => 0.514,
        85 => 0.508,
        86 => 0.502,
        87 => 0.496,
        88 => 0.491,
        89 => 0.485,
        90 => 0.480,
        91 => 0.475,
        92 => 0.470,
        93 => 0.464,
        94 => 0.460,
        95 => 0.455,
        96 => 0.450,
        97 => 0.445,
        98 => 0.441,
        99 => 0.436,
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
    
    /**
     * Variable de limites Desviacion Absoluta Media (MAD) dos primeros digitos
     * @var type array
     */
    private $mad_d12 = array(
        array('min' => 0,      'max' => 0.0012, 'descripcion' => 'Conformidad Cuasi-Perfecta'),
        array('min' => 0.0012, 'max' => 0.0018, 'descripcion' => 'Conformidad Aceptable'),
        array('min' => 0.0018, 'max' => 0.0022, 'descripcion' => 'Conformidad Marginalmente Aceptable'),
        array('min' => 0.0022, 'max' => 10000,  'descripcion' => 'No Conformidad'),
    );
    
    public $data = [];
    
    public function __construct() {
        set_time_limit(0);
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
            case 12:
                $d12 = $this->benford_d12();
                break;
            default:
                return FALSE;
                break;
        }
        return array(
            'd1'  => $d1,
            'd2'  => $d2,
            'd12' => $d12
        );
    }
    
    private function frecuencia($param) {
        extract($param);
        $r1 = $this->formatoArray($start, $lenght);
        $valores = array_column($r1, 'valor');
        $r2 = [];
        $x1 = ['x1'];
        $x2 = ['x2'];
        for ($x = $i; $x <= $f; $x++) {
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
    
    private function benford_d12() {
        extract($this->frecuencia([
            'start'  => 0,
            'lenght' => 2,
            'i'      => 10,
            'f'      => 99,
            'm'      => 12
        ]));
        $total = array_sum(array_column($r2, 'frecuencia'));
        for ($x = 10; $x <= 99; $x++) {
            $r2[$x]['observado'] = ($r2[$x]['frecuencia']*100)/$total;
            $r2[$x]['variacion'] = abs($r2[$x]['observado'] - $this->benford_12d[$x])/100;
        }
        $total_variacion = array_sum(array_column($r2, 'variacion'));
        $mad = $total_variacion/99;
        $mad_d12 = 'N/A';
        foreach ($this->mad_d12 as $value){
            if(($mad > $value['min']) AND ($mad <= $value['max'])){
                $mad_d12 = $value['descripcion'];
            }
        }
        $cantidad = count($this->data);
        $i = 0;
        for ($x = 10; $x <= 99; $x++) {
            $i = ($cantidad * ($this->benford_12d[$x]/100));
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
            $grf['data2'][] = number_format($value['benford'], 3, '.', '');
            $grf['data1'][] = number_format($value['observado'], 3, '.', '');
        }
        $r2 = $this->formatoTabla($r2);
        return array(
            'tabla'       => $r2,
            'grafica'     => $grf,
            'x1'          => $x1,
            'x2'          => $x2,
            'mad'         => '<b>' . number_format($mad, 4, ',', '.') . '</b>',
            'madDescribe' => '<b>' . $mad_d12 . '</b>',
            'mad_d12'     => $this->mad_d12
        );
    }
    
    private function benford_d2() {
        extract($this->frecuencia([
            'start'  => 1,
            'lenght' => 1,
            'i'      => 0,
            'f'      => 9,
            'm'      => 2
        ]));
        $total = array_sum(array_column($r2, 'frecuencia'));
        for ($x = 0; $x <= 9; $x++) {
            $r2[$x]['observado'] = ($r2[$x]['frecuencia']*100)/$total;
            $r2[$x]['variacion'] = abs($r2[$x]['observado'] - $this->benford_2d[$x])/100;
        }
        $total_variacion = array_sum(array_column($r2, 'variacion'));
        $mad = $total_variacion/10;
        $mad_d2 = 'N/A';
        foreach ($this->mad_d2 as $value){
            if(($mad > $value['min']) AND ($mad <= $value['max'])){
                $mad_d2 = $value['descripcion'];
            }
        }
        $cantidad = count($this->data);
        $i = 0;
        for ($x = 0; $x <= 9; $x++) {
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
            $grf['data2'][] = number_format($value['benford'], 3, '.', '');
            $grf['data1'][] = number_format($value['observado'], 3, '.', '');
        }
        $r2 = $this->formatoTabla($r2);
        return array(
            'tabla'       => $r2,
            'grafica'     => $grf,
            'x1'          => $x1,
            'x2'          => $x2,
            'mad'         => '<b>' . number_format($mad, 4, ',', '.') . '</b>',
            'madDescribe' => '<b>' . $mad_d2 . '</b>',
            'mad_d2'      => $this->mad_d2
        );
    }
    
    private function benford_d1() {
        extract($this->frecuencia([
            'start'  => 0,
            'lenght' => 1,
            'i'      => 1,
            'f'      => 9,
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
            $grf['data2'][] = number_format($value['benford'], 3, '.', '');
            $grf['data1'][] = number_format($value['observado'], 3, '.', '');
        }
        $r2 = $this->formatoTabla($r2);
        return array(
            'tabla'       => $r2,
            'grafica'     => $grf,
            'x1'          => $x1,
            'x2'          => $x2,
            'mad'         => '<b>' . number_format($mad, 4, ',', '.') . '</b>',
            'madDescribe' => '<b>' . $mad_d1 . '</b>',
            'mad_d1'      => $this->mad_d1
        );
    }
    
    private function formatoTabla($r2) {
        array_walk($r2, function (&$value){
            $e = '';
            if($value['observado'] > $value['benford']){
                $e = '+ ';
            }elseif($value['observado'] < $value['benford']){
                $e = '- ';
            }
            $value['variacion']  = $e.number_format(($value['variacion']*100), 3, ',', '.').'%';
            $value['frecuencia'] = number_format($value['frecuencia'], 0, ',', '.');
            $value['observado']  = number_format($value['observado'], 3, ',', '.') . '%';
            $value['benford']    = number_format($value['benford'], 3, ',', '.') . '%';
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
