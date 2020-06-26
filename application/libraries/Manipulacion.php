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
    public $data  = [];
    private $ct   = '';
    private $ct1  = '';
    
    /**
     * Valores optimos de cada indicador (5)
     * @var type float
     */
    private $DSRI    = 0.823;
    private $GMI     = 0.906;
    private $AQI     = 0.593;
    private $SGI     = 0.717;
    private $DEPI    = 0.107;
    private $riesgo5 = -2.76;

    /**
     * Valores optimos de cada indicador (8)
     * @var type float
     */    
    private $DSRI8   = 0.92;
    private $GMI8    = 0.528;
    private $AQI8    = 0.404;
    private $SGI8    = 0.892;
    private $DEPI8   = 0.115;
    private $SGAI8   = -0.172;
    private $LVGI8   = -0.327;
    private $TATA8   = 4.679;
    private $riesgo8 = -2.22;

    function __construct() {
        set_time_limit(0);
        $this->CI = & get_instance();
        $this->CI->load->model('Archivos_model');
    }
    
    private function columnDef($archivo_id) {
        return $this->CI->archivo->columnManipulacion($archivo_id);
    }
    
    public function indicadores($balances) {
        foreach ($balances as $value) {
            if($value['posicion'] == 't'){
                $this->ct = $this->columnDef($value['balance']);
                $t  = $value['balance'];
            }elseif($value['posicion'] == 't-1'){
                $this->ct1 = $this->columnDef($value['balance']);
                $t1 = $value['balance'];
            }
        }
        $at   = 0;
        $at1  = 0;
        $pt   = 0;
        $pt1  = 0;
        $ot   = 0;
        $ot1  = 0;
        $this->cuentasValues8([
            't'   => $t,
            't1'  => $t1
        ]);
        $data = [];
        foreach ($this->cuentaValue as $key => $value) {
            if (in_array($key, ['acorrientes', 'anocorrientes', 'obligacionesfc', 'otrospasivosc', 'pnocorrientes', 'obligacionesfnoc'])) {
                $data[$key] = $value;
                if (in_array($key, ['acorrientes', 'anocorrientes'])) {
                    $at += $value['t'];
                    $at1 += $value['t-1'];
                    $data['suma_acorrientes_anocorrientes'] = [
                        't' => $at,
                        't-1' => $at1
                    ];
                }
                if (in_array($key, ['otrospasivosc', 'pnocorrientes'])) {
                    $pt += $value['t'];
                    $pt1 += $value['t-1'];
                    $data['suma_otrospasivosc_pnocorrientes'] = [
                        't' => $pt,
                        't-1' => $pt1
                    ];
                }
                if (in_array($key, ['obligacionesfc', 'obligacionesfnoc'])) {
                    $ot += $value['t'];
                    $ot1 += $value['t-1'];
                    $data['suma_obligacionesfc_obligacionesfnoc'] = [
                        't' => $ot,
                        't-1' => $ot1
                    ];
                }
            }
        }
        return $data;
    }
    
    public function run($balances) {
        foreach ($balances as $value) {
            if($value['posicion'] == 't'){
                $this->ct = $this->columnDef($value['balance']);
                $t  = $value['balance'];
            }elseif($value['posicion'] == 't-1'){
                $this->ct1 = $this->columnDef($value['balance']);
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

        //$t5ind = $r_dsri + $r_gmi + $r_aqi + $r_sgi + $r_depi;

        $a_dsri = $r_dsri;
        $a_gmi  = $r_gmi;
        $a_aqi  = $r_aqi;
        $a_sgi  = $r_sgi;
        $a_depi = $r_depi;
        
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
        $manipulacion = (($this->cuentaValue['m5ind'] >= $this->riesgo5) ? FALSE : TRUE);
        if($manipulacion == TRUE){
            $this->cuentaValue['mensaje'] = 'El Indicador de Cambio arroja un score de '.number_format($this->cuentaValue['m5ind'], 3, ',', '.').' este resultado es inferior a '.$this->riesgo5.' se sugiere menor riesgo de manipulaci&oacute;n';
        }else{
            $this->cuentaValue['mensaje'] = 'El Indicador de Cambio arroja un score de '.number_format($this->cuentaValue['m5ind'], 3, ',', '.').' este resultado es superior a '.$this->riesgo5.' se sugiere mayor riesgo de manipulaci&oacute;n';
        }
        $this->cuentaValue['probabilidad'] = distr_norm_estand($this->cuentaValue['m5ind']);
        $this->cuentaValue['analisis'] = $this->analisis($this->cuentaValue);
        foreach ($this->cuentaValue['analisis'] as $key => $value) {
            $this->cuentaValue[$key]['ideal'] = $value['ideal'];
            $this->cuentaValue[$key]['aporte'] = $value['ideal'] - $this->cuentaValue[$key]['resultado'];
        }
        return $this->cuentaValue;
    }
    
    private function cuentasValues8($param) {
        extract($param);
        $this->cuentaValue['acorrientes']['t']        = $this->getValue($t,      [11,12,13,14], $this->ct);     //Activos corrientes
        $this->cuentaValue['acorrientes']['t-1']      = $this->getValue($t1,     [11,12,13,14], $this->ct1);    //Activos corrientes        
        $this->cuentaValue['anocorrientes']['t']      = $this->getValue($t,      [15,16,17,18], $this->ct);     //Activos no corrientes
        $this->cuentaValue['anocorrientes']['t-1']    = $this->getValue($t1,     [15,16,17,18], $this->ct1);    //Activos no corrientes
        $this->cuentaValue['obligacionesfc']['t']     = $this->getValue($t,      [21], $this->ct);              //Obligaciones financieras corrientes
        $this->cuentaValue['obligacionesfc']['t-1']   = $this->getValue($t1,     [21], $this->ct1);             //Obligaciones financieras corrientes
        $this->cuentaValue['obligacionesfnoc']['t']   = 0;                                                      //Obligaciones financieras no corrientes
        $this->cuentaValue['obligacionesfnoc']['t-1'] = 0;                                                      //Obligaciones financieras no corrientes
        $this->cuentaValue['otrospasivosc']['t']      = $this->getValue($t,      [22,23,24,25,26], $this->ct);  //Otros Pasivos Corrientes
        $this->cuentaValue['otrospasivosc']['t-1']    = $this->getValue($t1,     [22,23,24,25,26], $this->ct1); //Otros Pasivos Corrientes
        $this->cuentaValue['pnocorrientes']['t']      = $this->getValue($t,      [27,28,29], $this->ct);        //Pasivos No Corrientes
        $this->cuentaValue['pnocorrientes']['t-1']    = $this->getValue($t1,     [27,28,29], $this->ct1);       //Pasivos No Corrientes        
    }
    
    private function cuentasValues($param) {
        extract($param);
        $this->cuentaValue['cxc']['t']               = $this->getValue($t,      [1305], $this->ct);            //CXC
        $this->cuentaValue['cxc']['t-1']             = $this->getValue($t1,     [1305], $this->ct1);           //CXC
        $this->cuentaValue['ventas']['t']            = abs($this->getValue($t,  [41], $this->ct));             //Ventas
        $this->cuentaValue['ventas']['t-1']          = abs($this->getValue($t1, [41], $this->ct1));            //Ventas
        $this->cuentaValue['cventas']['t']           = $this->getValue($t,      [61], $this->ct);              //Costo venta
        $this->cuentaValue['cventas']['t-1']         = $this->getValue($t1,     [61], $this->ct1);             //Costo venta
        $this->cuentaValue['acorrientes']['t']       = $this->getValue($t,      [11,12,13,14], $this->ct);     //Activos corrientes
        $this->cuentaValue['acorrientes']['t-1']     = $this->getValue($t1,     [11,12,13,14], $this->ct1);    //Activos corrientes
        $this->cuentaValue['inmmaterial']['t']       = $this->getValue($t,      [15], $this->ct);              //Inmovilizado Material
        $this->cuentaValue['inmmaterial']['t-1']     = $this->getValue($t1,     [15], $this->ct1);             //Inmovilizado Material
        $this->cuentaValue['actvtotales']['t']       = $this->getValue($t,      [16,17,18,19], $this->ct);     //Activos Totales
        $this->cuentaValue['actvtotales']['t-1']     = $this->getValue($t1,     [16,17,18,19], $this->ct1);    //Activos Totales
        $this->cuentaValue['depreciacion']['t']      = $this->getValue($t,      [5160,5260,7360], $this->ct);  //Depresiacion
        $this->cuentaValue['depreciacion']['t-1']    = $this->getValue($t1,     [5160,5260,7360], $this->ct1); //Depresiacion
        
        # Se totaliza la suma para obtener el total de activos
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
        //debug_file("((".$c['ventas']['t-1']."-".$c['cventas']['t-1'].")/".$c['ventas']['t-1'].") / ((".$c['ventas']['t']."-".$c['cventas']['t'].")/".$c['ventas']['t'].")");
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
        #Anterior
        //$depi = ($c['depreciacion']['t']/($c['depreciacion']['t']+$c['inmmaterial']['t'])) / ($c['depreciacion']['t-1']/($c['depreciacion']['t-1']+$c['inmmaterial']['t-1']));
        #Ajuste 01
        $depi = ($c['depreciacion']['t-1']/($c['depreciacion']['t-1']+$c['inmmaterial']['t-1'])) / ($c['depreciacion']['t']/($c['depreciacion']['t']+$c['inmmaterial']['t']));
        return number_format($depi,3,'.','');
    }
    
    private function m5ind() {
        $c = $this->cuentaValue;
        $m5ind = -6.065 + $this->DSRI * $c['dsri']['resultado'] + $this->GMI * $c['gmi']['resultado'] + $this->AQI * $c['aqi']['resultado'] + $this->SGI * $c['sgi']['resultado'] + $this->DEPI * $c['depi']['resultado'];
        return number_format($m5ind,3,'.','');
    }
    
    private function getValue($archivo, $cuenta, $column) {
        $n = $this->CI->Archivos_model->getManipulacion($archivo, $cuenta, $column);
        return $n;
    }
    
    private function analisis(array $indicadores) {
        $data = [];
        $analisis['dsri'] = ['value' => 1.031, 'min' => 'Neutral', 'max' => 'Evaluar reconocimiento de ingresos'];
        $analisis['gmi']  = ['value' => 1.014, 'min' => 'Neutral', 'max' => '¿Porque se deterioran los margenes?'];
        $analisis['aqi']  = ['value' => 1.039, 'min' => 'Neutral', 'max' => 'Evaluar capitalización de gastos'];
        $analisis['sgi']  = ['value' => 1.134, 'min' => 'Neutral', 'max' => 'Alto crecimiento de ventas'];
        $analisis['depi'] = ['value' => 1,     'min' => 'Neutral', 'max' => 'Tasa de depreciación decreciente'];
        $analisis['sgai'] = ['value' => 1,     'min' => 'Neutral', 'max' => 'Gastos crecientes'];
        $analisis['lvgi'] = ['value' => 1,     'min' => 'Neutral', 'max' => 'Mayor endeudamiento'];
        $analisis['tata'] = ['value' => 0.018, 'min' => 'Neutral', 'max' => 'Evaluar los cambios en el capital de trabajo'];
        foreach ($indicadores as $key => $value) {
            if(array_key_exists($key, $analisis)){
                $data[$key]['ideal'] = $analisis[$key]['value'];
                $data[$key]['msg'] = $analisis[$key]['min'];
                if($value['resultado'] > $analisis[$key]['value']){
                    $data[$key]['msg'] = $analisis[$key]['max'];
                }
            }
        }
        return $data;
    }
    
}