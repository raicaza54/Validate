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
    
    /**
     * Valores optimos de cada indicador
     * @var type float
     */
    private $DSRI = 0.823;
    private $GMI  = 0.906;
    private $AQI  = 0.593;
    private $SGI  = 0.717;
    private $DEPI = 0.107;
    
    public $data = [];
    
    function __construct() {
        set_time_limit(0);
        $this->CI = & get_instance();
        $this->CI->load->model('Archivos_model');
    }
    
    public function run($balances) {
        foreach ($balances as $value) {
            if($value['posicion'] == 't'){
                $t  = $value['balance'];
            }elseif($value['posicion'] == 't-1'){
                $t1 = $value['balance'];
            }
        } 
        $this->cuentasValues([
            't'   => $t,
            't1'  => $t1
        ]);
        $dsri = $this->dsri();
        $gmi  = $this->gmi();
        $aqi  = $this->aqi();
        $sgi  = $this->sgi();
        $depi = $this->depi();
        $c_dsri = $this->DSRI * $dsri;
        $c_gmi  = $this->GMI  * $gmi;
        $c_aqi  = $this->AQI  * $aqi;
        $c_sgi  = $this->SGI  * $sgi;
        $c_depi = $this->DEPI * $depi;
        
        $r_dsri = $this->DSRI - $c_dsri;
        $r_gmi  = $this->GMI - $c_gmi;
        $r_aqi  = $this->AQI - $c_aqi;
        $r_sgi  = $this->SGI - $c_sgi;
        $r_depi = $this->DEPI - $c_depi;

        $t5ind = $r_dsri + $r_gmi + $r_aqi + $r_sgi + $r_depi;

        $a_dsri = ($r_dsri / $t5ind) * 100;
        $a_gmi  = ($r_gmi  / $t5ind) * 100;
        $a_aqi  = ($r_aqi  / $t5ind) * 100;
        $a_sgi  = ($r_sgi  / $t5ind) * 100;
        $a_depi = ($r_depi / $t5ind) * 100;
        
        $this->cuentaValue['dsri']  = [
            'manipulacion' => $this->DSRI,
            'resultado'    => $dsri,
            'obtenido'     => $c_dsri,
            'aporte'       => $a_dsri,
        ];
        $this->cuentaValue['gmi']   = [
            'manipulacion' => $this->GMI,
            'resultado'    => $gmi,
            'obtenido'     => $c_gmi,
            'aporte'       => $a_gmi,
        ];
        $this->cuentaValue['aqi']   = [
            'manipulacion' => $this->AQI,
            'resultado'    => $aqi,
            'obtenido'     => $c_aqi,
            'aporte'       => $a_aqi,
        ];
        $this->cuentaValue['sgi']   = [
            'manipulacion' => $this->SGI,
            'resultado'    => $sgi,
            'obtenido'     => $c_sgi,
            'aporte'       => $a_sgi,
        ];
        $this->cuentaValue['depi']  = [
            'manipulacion' => $this->DEPI,
            'resultado'    => $depi,
            'obtenido'     => $c_depi,
            'aporte'       => $a_depi,
        ];
        $this->cuentaValue['m5ind'] = $this->m5ind();
        return $this->cuentaValue;
    }
    
    private function cuentasValues($param) {
        extract($param);
        $this->cuentaValue['cxc']['t']            = $this->getValue($t,  [1305]);           //CXC
        $this->cuentaValue['cxc']['t-1']          = $this->getValue($t1, [1305]);           //CXC
        $this->cuentaValue['ventas']['t']         = $this->getValue($t,  [41]);             //Ventas
        $this->cuentaValue['ventas']['t-1']       = $this->getValue($t1, [41]);             //Ventas
        $this->cuentaValue['cventas']['t']        = $this->getValue($t,  [61]);             //Costo venta
        $this->cuentaValue['cventas']['t-1']      = $this->getValue($t1, [61]);             //Costo venta
        $this->cuentaValue['acorrientes']['t']    = $this->getValue($t, [11,12,13,14]);     //Activos corrientes
        $this->cuentaValue['acorrientes']['t-1']  = $this->getValue($t1, [11,12,13,14]);    //Activos corrientes
        $this->cuentaValue['inmmaterial']['t']    = $this->getValue($t, [15]);              //Inmovilizado Material
        $this->cuentaValue['inmmaterial']['t-1']  = $this->getValue($t1, [15]);             //Inmovilizado Material        
        $this->cuentaValue['actvtotales']['t']    = $this->getValue($t, [16,17,18,19]);     //Activos Totales
        $this->cuentaValue['actvtotales']['t-1']  = $this->getValue($t1, [16,17,18,19]);    //Activos Totales
        $this->cuentaValue['depreciacion']['t']   = $this->getValue($t, [5160,5260,7360]);  //Depresiacion
        $this->cuentaValue['depreciacion']['t-1'] = $this->getValue($t1, [5160,5260,7360]); //Depresiacion
        /**
         * Se totaliza la suma para obtener el total de activos
         */
        $this->cuentaValue['actvtotales']['t']   += $this->cuentaValue['acorrientes']['t'] + $this->cuentaValue['inmmaterial']['t'];
        $this->cuentaValue['actvtotales']['t-1'] += $this->cuentaValue['acorrientes']['t-1'] + $this->cuentaValue['inmmaterial']['t-1'];
    }
    
    private function dsri() {
        $c = $this->cuentaValue;
        $dsri = ($c['cxc']['t']/$c['ventas']['t']) / ($c['cxc']['t-1']/$c['ventas']['t-1']);
        return number_format($dsri,3,'.','');
    }
    
    private function gmi() {
        $c = $this->cuentaValue;
        $gmi = (($c['ventas']['t-1']-$c['cventas']['t-1'])/$c['ventas']['t-1']) / (($c['ventas']['t']-$c['cventas']['t'])/$c['ventas']['t']);
        return number_format($gmi,3,'.','');
    }
    
    private function aqi() {
        $c = $this->cuentaValue;
        $gmi = ((1-($c['acorrientes']['t']+$c['inmmaterial']['t'])/$c['actvtotales']['t'])) / ((1-($c['acorrientes']['t-1']+$c['inmmaterial']['t-1'])/$c['actvtotales']['t-1']));
        return number_format($gmi,3,'.','');
    }
    
    private function sgi() {
        $c = $this->cuentaValue;
        $sgi = $c['ventas']['t'] / $c['ventas']['t-1'];
        return number_format($sgi,3,'.','');
    }
    
    private function depi() {
        $c = $this->cuentaValue;
        $depi = ($c['depreciacion']['t']/($c['depreciacion']['t']+$c['inmmaterial']['t'])) / ($c['depreciacion']['t-1']/($c['depreciacion']['t-1']+$c['inmmaterial']['t-1']));
        return number_format($depi,3,'.','');
    }
    
    private function m5ind() {
        $c = $this->cuentaValue;
        $m5ind = -6.065 + $this->DSRI * $c['dsri']['resultado'] + $this->GMI * $c['gmi']['resultado'] + $this->AQI * $c['aqi']['resultado'] + $this->SGI * $c['sgi']['resultado'] + $this->DEPI * $c['depi']['resultado'];
        return number_format($m5ind,3,'.','');
    }
    
    private function getValue($archivo, $cuenta) {
        $n = $this->CI->Archivos_model->getManipulacion($archivo, $cuenta);
        return $n;
    }
}