<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * La Araña
 * 
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Libreria para generar Araña
 * @LastUpdate  2019-02-14
 */
class Spider {
    
    private $CI;
    
    public $data = [];
    
    private $datos = array('body' => '');
    
    public function __construct() {
        set_time_limit(0);
        $this->CI = & get_instance();
    }
    
    public function procesar($cuenta) {
        try{
        $ejemplo  = $this->data;
        $grupos = array();
        foreach ($ejemplo as $value) {
            $grupos[$value['CTE'] . $value['DOC']][] = array(
                'grupo'  => $value['CTE'] . $value['DOC'],
                'cuenta' => $value['CUENTA'],
                'tipo'   => $value['TIPO'],
                'valor'  => $value['VALOR'],
            );
        }
        $tabla_grupos = '';
//        foreach ($grupos as $value) {
//            $this->CI->table->set_heading('GRUPO', 'CUENTA', 'TIPO', 'VALOR');
//            $tabla_grupos .= $this->CI->table->generate($value) . '<br/>';
//            $this->CI->table->clear();
//        }
        $cuenta             = $cuenta;
        $grupos_clasificado = array(
            'debito'  => array(),
            'credito' => array(),
        );
        foreach ($grupos as $key => $value) {
            if (in_array($cuenta, array_column($value, 'cuenta'))) {
                $k = 0;
                foreach ($value as $value_grupo) {
                    if (($value_grupo['cuenta'] == $cuenta) AND ( $value_grupo['tipo'] == 1)) {
                        foreach ($grupos_clasificado['debito'] as $value_credito) {
                            if (in_array($key, array_column($value_credito, 'grupo'))) {
                                $k = 1;
                            }
                        }
                        if ($k == 0) {
                            $grupos_clasificado['debito'][] = $value;
                        }
                    }
                    if (($value_grupo['cuenta'] == $cuenta) AND ( $value_grupo['tipo'] == 2)) {
                        foreach ($grupos_clasificado['credito'] as $value_credito) {
                            if (in_array($key, array_column($value_credito, 'grupo'))) {
                                $k = 1;
                            }
                        }
                        if ($k == 0) {
                            $grupos_clasificado['credito'][] = $value;
                        }
                    }
                }
            }
        }
        $total_debito        = 0;
        $total_debito_cuenta = 0;
        $debito_porcentaje   = 0;
        foreach ($grupos_clasificado['debito'] as $key_debito => $value) {
            $total_debito        = 0;
            $total_debito_cuenta = 0;
            $debito_porcentaje   = 0;
            foreach ($value as $value_debito) { //Debitos
                if (($value_debito['tipo'] == 1) AND ( $value_debito['cuenta'] == $cuenta)) {
                    $total_debito_cuenta += $value_debito['valor'];
                }
                if ($value_debito['tipo'] == 1) {
                    $total_debito += $value_debito['valor'];
                }
            }
            if($total_debito > 0){
                $debito_porcentaje = (($total_debito_cuenta / $total_debito) * 100);
            }else{
                $debito_porcentaje = 0;
            }
            foreach ($value as $key_grupo => $value_debito) {
                $grupos_clasificado['debito'][$key_debito][$key_grupo]['tb'] = 0;
                if ($value_debito['tipo'] == 2) {
                    $grupos_clasificado['debito'][$key_debito][$key_grupo]['tb'] = ($value_debito['valor'] * $debito_porcentaje) / 100;
                }
            }
        }
        //print_r($grupos_clasificado['debito']);
//        $tabla_grupos_clasificados = '';
//        foreach ($grupos_clasificado as $key => $value) {
//            $tabla_grupos_clasificados .= '############# ' . strtoupper($key) . ' #############';
//            foreach ($value as $seguntipo) {
//                $this->CI->table->set_heading('GRUPO', 'CUENTA', 'TIPO', 'VALOR', 'TB');
//                $tabla_grupos_clasificados .= $this->CI->table->generate($seguntipo) . '<br/>';
//                $this->CI->table->clear();
//            }
//        }

        $total_credito        = 0;
        $total_credito_cuenta = 0;
        $credito_porcentaje   = 0;
        foreach ($grupos_clasificado['credito'] as $key_credito => $value) {
            $total_credito        = 0;
            $total_credito_cuenta = 0;
            $credito_porcentaje   = 0;
            foreach ($value as $value_credito) { //Credito
                if (($value_credito['tipo'] == 2) AND ( $value_credito['cuenta'] == $cuenta)) {
                    $total_credito_cuenta += $value_credito['valor'];
                }
                if ($value_credito['tipo'] == 2) {
                    $total_credito += $value_credito['valor'];
                }
            }
            if($total_credito > 0){
                $credito_porcentaje = (($total_credito_cuenta / $total_credito) * 100);
            }else{
                $credito_porcentaje = 0;
            }
            foreach ($value as $key_grupo => $value_credito) {
                $grupos_clasificado['credito'][$key_credito][$key_grupo]['tb'] = 0;
                if ($value_credito['tipo'] == 1) {
                    $grupos_clasificado['credito'][$key_credito][$key_grupo]['tb'] = ($value_credito['valor'] * $credito_porcentaje) / 100;
                }
            }
        }
        $this->exportEtpCsv($grupos_clasificado['credito'], 'credito');
        $this->exportEtpCsv($grupos_clasificado['debito'], 'debito');
//        $tabla_grupos_clasificados = '';
//        foreach ($grupos_clasificado as $key => $value) {
//            $tabla_grupos_clasificados .= '############# ' . strtoupper($key) . ' #############';
//            foreach ($value as $seguntipo) {
//                $this->CI->table->set_heading('GRUPO', 'CUENTA', 'TIPO', 'VALOR', 'TB');
//                $tabla_grupos_clasificados .= $this->CI->table->generate($seguntipo) . '<br/>';
//                $this->CI->table->clear();
//            }
//        }
        //print_r($grupos_clasificado);
        $spider                    = array();
        #Construccion de la araña
        $spider[$cuenta]['debito'] = array();
        $total_debito              = 0;
        foreach ($grupos_clasificado['debito'] as $key => $grupos) {
            foreach ($grupos as $key => $value) {
                if ($value['tb'] > 0) {
                    if (!array_key_exists($value['cuenta'], $spider[$cuenta]['debito'])) {
                        $spider[$cuenta]['debito'][$value['cuenta']]['valor'] = $value['tb'];
                    } else {
                        $spider[$cuenta]['debito'][$value['cuenta']]['valor'] += $value['tb'];
                    }
                }
                $total_debito += $value['tb'];
            }
        }
        $total_credito              = 0;
        $spider[$cuenta]['credito'] = array();
        foreach ($grupos_clasificado['credito'] as $key => $grupos) {
            foreach ($grupos as $key => $value) {
                if ($value['tb'] > 0) {
                    if (!array_key_exists($value['cuenta'], $spider[$cuenta]['credito'])) {
                        $spider[$cuenta]['credito'][$value['cuenta']]['valor'] = $value['tb'];
                    } else {
                        $spider[$cuenta]['credito'][$value['cuenta']]['valor'] += $value['tb'];
                    }
                }
                $total_credito += $value['tb'];
            }
        }
        if($total_debito > 0){
            foreach ($spider[$cuenta]['debito'] as $key => $value) {
                $spider[$cuenta]['debito'][$key]['porcentaje'] = round(($spider[$cuenta]['debito'][$key]['valor'] * 100) / $total_debito,10);
            }
        }
        if($total_credito > 0){
            foreach ($spider[$cuenta]['credito'] as $key => $value) {
                $spider[$cuenta]['credito'][$key]['porcentaje'] = round(($spider[$cuenta]['credito'][$key]['valor'] * 100) / $total_credito,10);
            }
        }
        //$this->datos['body'] = $tabla.'<br/>############# GRUPOS #############'.$tabla_grupos.'<br><br/>############# GRUPOS CLAISIFICADOS #############<br/>'.$tabla_grupos_clasificados.'</pre>';
        $c  = 0;
        $my = '';
        $l  = 0;
        $spider[$cuenta]['debito']  = maSort($spider[$cuenta]['debito'], 'porcentaje', 2);
        $spider[$cuenta]['credito'] = maSort($spider[$cuenta]['credito'], 'porcentaje', 2);
        $debitoCount  = @count($spider[$cuenta]['debito']);
        $creditoCount = @count($spider[$cuenta]['credito']);
        if ($debitoCount >= $creditoCount) {
            $c = $debitoCount;
            $my = 'd';
        } else {
            $c = $creditoCount;
            $my = 'c';
        }
        $lineas = '';
        if($my == 'c'){
            $x = ($creditoCount/2)-($debitoCount/2);
        }else{
            $x = 0;
        }
        $t = ($x*45)+15;
        $hsvg   = (($c * 45) - 15) / 2;
        $total_porcentaje = 0;
        $total_dinero = 0;
        if(is_array($spider[$cuenta]['debito'])){
            foreach ($spider[$cuenta]['debito'] as $key => $value) {
                $this->datos['body'] .= '<div class="spd-debito"  style="top: ' . ($x * 45) . 'px;"><div class="ispd-cuenta" onclick="SPIDER.methods.procesarClick(\''.$key.'\')">' . $key . '</div><div class="ispd-dinero">$' . number_format($value['valor'], 2, ',', '.') . '</div><div class="ispd-porcentaje">' . number_format($value['porcentaje'], 3, ',', '.') . '%</div></div>';
                $lineas             .= '<line x1="250" y1="' . $t . '" x2="375" y2="' . $hsvg . '" style="stroke:#000; stroke-width:1"></line>';
                $t                  += 45;
                $x++;
                $total_porcentaje    += $value['porcentaje'];
                $total_dinero        += $value['valor'];
            }
        }
        if($total_dinero > 0){        
            $this->datos['body'] .= '<div class="spd-debito"  style="top: ' . ($x * 45) . 'px; font-weight: bold;"><div class="ispd-cuenta" style="text-align: left; cursor: default; color: #000;">Total</div><div class="ispd-dinero">$' . number_format($total_dinero, 2, ',', '.') . '</div><div class="ispd-porcentaje">' . number_format($total_porcentaje, 3, ',', '.') . '%</div></div>';
        }
        $t = 15;
        if($my == 'd'){
            $x = ($debitoCount/2)-($creditoCount/2);
        }else{
            $x = 0;
        }
        $t = ($x*45)+15;
        $total_porcentaje = 0;
        $total_dinero = 0;
        if(is_array($spider[$cuenta]['credito'])){
            foreach ($spider[$cuenta]['credito'] as $key => $value) {
                $this->datos['body'] .= '<div class="spd-credito" style="top: ' . ($x * 45) . 'px;"><div class="ispd-cuenta" onclick="SPIDER.methods.procesarClick(\''.$key.'\')">' . $key . '</div><div class="ispd-dinero">$' . number_format($value['valor'], 2, ',', '.') . '</div><div class="ispd-porcentaje">' . number_format($value['porcentaje'], 3, ',', '.') . '%</div></div>';
                $lineas             .= '<line x1="625" y1="' . $hsvg . '" x2="750" y2="' . $t . '" style="stroke:#000; stroke-width:1"></line>';
                $t                  += 45;
                $x++;
                $total_porcentaje    += $value['porcentaje'];
                $total_dinero        += $value['valor'];            
            }
        }
        if($total_dinero > 0){
            $this->datos['body'] .= '<div class="spd-credito"  style="top: ' . ($x * 45) . 'px; font-weight: bold;"><div class="ispd-cuenta" style="text-align: left; cursor: default; color: #000;">Total</div><div class="ispd-dinero">$' . number_format($total_dinero, 2, ',', '.') . '</div><div class="ispd-porcentaje">' . number_format($total_porcentaje, 3, ',', '.') . '%</div></div>';
        }
        $this->datos['body'] .= '<div class="spd-spider"  style="top: ' . ((($c * 45) / 2) - 22) . 'px; text-align: center">' . $cuenta . '</div>';
        $this->datos['body'] .= '<div class="spd-spider"  style="top: ' . ((($c * 45) / 2) + 7) . 'px; text-align: center">$' .number_format(abs($total_credito - $total_debito),2,',','.') . '</div>';
        $this->datos['body'] .= '<svg width="1000" height="' . (($c * 45) - 30) . '" viewBox="0 0 1000 ' . (($c * 45) - 30) . '">';
        $this->datos['body'] .= $lineas;
        $this->datos['body'] .= '</svg>';
        } catch (Exception $exc){
            $this->datos['status'] = $exc->getCode();
            $exception = array(
                "code"    => $exc->getCode(),
                "message" => $exc->getMessage(),
            );
            if ($exception["code"] === 200) {
                $this->datos["detail"] = (strlen($exception["message"]) && !empty($exception["message"])) ? $exception["message"] : "Petición correcta";
            } elseif ($exception["code"] === 202) {
                $this->datos["detail"] = (strlen($exception["message"]) && !empty($exception["message"])) ? $exception["message"] : "Petición Aceptada pero incompleta";
            } elseif ($exception["code"] === 500) {
                $this->datos["detail"] = (strlen($exception["message"]) && !empty($exception["message"])) ? $exception["message"] : "Internal Server Error";
            } else{
                $this->datos["detail"] = (strlen($exception["message"]) && !empty($exception["message"])) ? $exception["message"] : "Internal Server Error";
            }
        }        
        return $this->datos;
    }    
    
    private function exportEtpCsv($data, $archivo) {
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"test" . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen(APPPATH.'logs/'.$archivo.'.csv', 'w');
        fputcsv($handle, array("grupo", "cuenta", "tipo", "valor"));
        foreach ($data as $keydat) {
            foreach ($keydat as $key) {
                $narray = array($key["grupo"], $key["cuenta"], $key["tipo"], $key["valor"]);
                fputcsv($handle, $narray);
            }
        }
        fclose($handle);
    }

}
