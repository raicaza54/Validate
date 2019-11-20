<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Condicion de Cuenta
 * 
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Libreria de calculos condicion de cuenta
 * @LastUpdate  2019-10-28
 */
class Condicion {
    
    private $CI;
    public $condiciones;
    public $datos;
    
    function __construct() {
        set_time_limit(0);
        $this->CI = & get_instance();
    }
    
    function run() {
        $condicionEx = [];
        $condicion = [];
        $max = 0; $min = 0;
        $cond = '';
        $condCalc = 0;
        if(count($this->datos) > 0){
            foreach ($this->datos as $value) {
                if(array_key_exists($value['cta'], $this->condiciones)){
                    $condicion = $this->condiciones[$value['cta']];
                    $max = number_format((floatval($condicion['porcentaje']) + floatval($condicion['tolerancia'])), 3);
                    $min = number_format((floatval($condicion['porcentaje']) - floatval($condicion['tolerancia'])), 3);
                    $calculo = number_format($value['calculo'], 3);
                    if(($calculo > $max) || ($calculo < $min )){
                        $cond = '-';
                        $condCalc = number_format($calculo - $min, 3);
                        if($calculo > $max){
                            $cond = '+';
                            $condCalc = number_format($calculo - $max, 3);
                        }
                        $condicionEx[] = [
                            'doc'            => $value['doc'],
                            'identificacion' => $value['identificacion'],
                            'cta'            => $value['cta'],
                            'base'           => $value['base'],
                            'valor'          => $value['valor'],
                            'calculo'        => $calculo,
                            'max'            => $max,
                            'min'            => $min,
                            'porcentaje'     => $condicion['porcentaje'],
                            'tolerancia'     => $condicion['tolerancia'],
                            'condicion'      => $cond,
                            'diferencia'     => $condCalc,
                        ];
                    }
                }
            }
        }
        return $condicionEx;
    }
    
}
