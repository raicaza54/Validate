<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Manipulacion
 * Las prácticas para manipular la información contable suscitan en la
 * actualidad un gran interés. La literatura, básicamente anglosajona,
 * se ha referido con profusión a la figura del auditor, y específicamente 
 * a la calidad del auditor como factor limitador de estas prácticas.
 * 
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Libreria analisis basado en manipulacion de datos
 * @LastUpdate  2019-02-14
 */
class Manipulacion {
    /**
     * Saldo final de las cuenats en el balance de prueba
     * @var type Array
     */
    private $cuentaValue = [];
    
    private $CI;
    public $data = [];
    
    function __construct() {
        set_time_limit(0);
        $this->CI = & get_instance();
        $this->CI->load->model('Archivos_model');
    }
    
    public function run($balances) {
        $t  = $balances[0];
        $t1 = $balances[1];
        $this->cuentasValues([
            't'   => $t,
            't1'  => $t1
        ]);
        $dsri = $this->dsri();
        debug_file('dsri: '.$dsri);
        $gmi = $this->gmi();
        debug_file('gmi: '.$gmi);
        $aqi = $this->aqi();
        debug_file('aqi: '.$aqi);
    }
    
    private function cuentasValues($param) {
        extract($param);
        $this->cuentaValue['cxc']['t']       = $this->getValue($t, 1305); //CXC
        $this->cuentaValue['cxc']['t-1']     = $this->getValue($t1, 1305);
        $this->cuentaValue['ventas']['t']    = $this->getValue($t, 41); //Ventas
        $this->cuentaValue['ventas']['t-1']  = $this->getValue($t1, 41);
        $this->cuentaValue['cventas']['t']   = $this->getValue($t, 61); //Costo venta
        $this->cuentaValue['cventas']['t-1'] = $this->getValue($t1, 61);
        
    }
    
    private function dsri() {
        $c = $this->cuentaValue;
        $dsri = ($c['cxc']['t']/$c['ventas']['t'])/($c['cxc']['t-1']/$c['ventas']['t-1']);
        return number_format($dsri,3,'.','');
    }
    
    private function gmi() {
        $c = $this->cuentaValue;
        $gmi = (($c['ventas']['t-1']-$c['cventas']['t-1'])/$c['ventas']['t-1'])/(($c['ventas']['t']-$c['cventas']['t'])/$c['ventas']['t']);
        return number_format($gmi,3,'.','');
    }
    
    private function aqi() {
        $c = $this->cuentaValue;
        $gmi = (($c['ventas']['t-1']-$c['cventas']['t-1'])/$c['ventas']['t-1'])/(($c['ventas']['t']-$c['cventas']['t'])/$c['ventas']['t']);
        return number_format($gmi,3,'.','');
    }
    
    private function getValue($archivo, $cuenta) {
        $n = $this->CI->Archivos_model->getManipulacion($archivo, $cuenta);
        return $n;
    }
    
    /*
    function manipulacion_ejemplo() {
        $r = [];
        $c = '';
        //DSRI

        $c .= 'DSRI: '.number_format($dsri,3,',','.').'<br/>';

        //GMI

        $c .= 'GMI: '.number_format($gmi,3,',','.').'<br/>';

        //AQI
        
        
        $this->data['body'] = '<div style="overflow-y: scroll; height: 450px">'.$c.'</div>';
        $this->load->view("plantilla/plantilla", $this->data);      
        return;
        
        $r .= $this->calculos(1,11);
        $r .= $this->calculos(1,12);
        $r .= $this->calculos(1,13);
        $r .= $this->calculos(1,14);
        
        $r .= $this->calculos(2,11);
        $r .= $this->calculos(2,12);
        $r .= $this->calculos(2,13);
        $r .= $this->calculos(2,14);
        
        $r .= $this->calculos(1,15);
        $r .= $this->calculos(1,16);
        $r .= $this->calculos(1,17);
        $r .= $this->calculos(1,18);
        $r .= $this->calculos(1,19);
        
        $r .= $this->calculos(2,15);
        $r .= $this->calculos(2,16);
        $r .= $this->calculos(2,17);
        $r .= $this->calculos(2,18);
        $r .= $this->calculos(2,19);
        
        $r .= $this->calculos(1,15);
        $r .= $this->calculos(2,15);
        
        $r .= $this->calculos(1,5160);
        $r .= $this->calculos(1,5260);
        $r .= $this->calculos(1,7360);
        $r .= $this->calculos(2,5160);
        $r .= $this->calculos(2,5260);
        $r .= $this->calculos(2,7360);
        
        $this->data['body'] = '<div style="overflow-y: scroll; height: 450px">'.$r.'</div>';
        $this->load->view("plantilla/plantilla", $this->data);
    } 
    */
    
}