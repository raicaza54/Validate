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
        $this->CI = & get_instance();
    }
    
    public function procesar($cuenta) {
        $ejemplo  = $this->data;
        $this->CI->load->library('table');
        $template = array(
            'table_open' => '<table border="1" cellpadding="4" cellspacing="0">',
            'thead_open'  => '<thead>',
            'thead_close' => '</thead>',
            'heading_row_start'  => '<tr>',
            'heading_row_end'    => '</tr>',
            'heading_cell_start' => '<th>',
            'heading_cell_end'   => '</th>',
            'tbody_open'  => '<tbody>',
            'tbody_close' => '</tbody>',
            'row_start'  => '<tr>',
            'row_end'    => '</tr>',
            'cell_start' => '<td>',
            'cell_end'   => '</td>',
            'row_alt_start'  => '<tr>',
            'row_alt_end'    => '</tr>',
            'cell_alt_start' => '<td>',
            'cell_alt_end'   => '</td>',
            'table_close' => '</table>'
        );

        $this->CI->table->set_template($template);
        $this->CI->table->set_heading('REGISTRO', 'CUENTA', 'CTE', 'FECHA', 'DOC', 'REF', 'NIT', 'DETALLE', 'TIPO', 'VALOR', 'BASE', 'CC', 'TB', 'PL');
        $tabla  = $this->CI->table->generate($ejemplo);
        $this->CI->table->clear();
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
        foreach ($grupos as $value) {
            $this->CI->table->set_heading('GRUPO', 'CUENTA', 'TIPO', 'VALOR');
            $tabla_grupos .= $this->CI->table->generate($value) . '<br/>';
            $this->CI->table->clear();
        }
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
            $debito_porcentaje = (($total_debito_cuenta / $total_debito) * 100);
            foreach ($value as $key_grupo => $value_debito) {
                $grupos_clasificado['debito'][$key_debito][$key_grupo]['tb'] = 0;
                if ($value_debito['tipo'] == 2) {
                    $grupos_clasificado['debito'][$key_debito][$key_grupo]['tb'] = ($value_debito['valor'] * $debito_porcentaje) / 100;
                }
            }
        }
        //print_r($grupos_clasificado['debito']);
        $tabla_grupos_clasificados = '';
        foreach ($grupos_clasificado as $key => $value) {
            $tabla_grupos_clasificados .= '############# ' . strtoupper($key) . ' #############';
            foreach ($value as $seguntipo) {
                $this->CI->table->set_heading('GRUPO', 'CUENTA', 'TIPO', 'VALOR', 'TB');
                $tabla_grupos_clasificados .= $this->CI->table->generate($seguntipo) . '<br/>';
                $this->CI->table->clear();
            }
        }

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
            $credito_porcentaje = (($total_credito_cuenta / $total_credito) * 100);
            foreach ($value as $key_grupo => $value_credito) {
                $grupos_clasificado['credito'][$key_credito][$key_grupo]['tb'] = 0;
                if ($value_credito['tipo'] == 1) {
                    $grupos_clasificado['credito'][$key_credito][$key_grupo]['tb'] = ($value_credito['valor'] * $credito_porcentaje) / 100;
                }
            }
        }
        $tabla_grupos_clasificados = '';
        foreach ($grupos_clasificado as $key => $value) {
            $tabla_grupos_clasificados .= '############# ' . strtoupper($key) . ' #############';
            foreach ($value as $seguntipo) {
                $this->CI->table->set_heading('GRUPO', 'CUENTA', 'TIPO', 'VALOR', 'TB');
                $tabla_grupos_clasificados .= $this->CI->table->generate($seguntipo) . '<br/>';
                $this->CI->table->clear();
            }
        }
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
        foreach ($spider[$cuenta]['debito'] as $key => $value) {
            $spider[$cuenta]['debito'][$key]['porcentaje'] = ($spider[$cuenta]['debito'][$key]['valor'] * 100) / $total_debito;
        }
        foreach ($spider[$cuenta]['credito'] as $key => $value) {
            $spider[$cuenta]['credito'][$key]['porcentaje'] = ($spider[$cuenta]['credito'][$key]['valor'] * 100) / $total_credito;
        }
        //$this->datos['body'] = $tabla.'<br/>############# GRUPOS #############'.$tabla_grupos.'<br><br/>############# GRUPOS CLAISIFICADOS #############<br/>'.$tabla_grupos_clasificados.'</pre>';

        $c = 0;
        $l = 0;
        if (count($spider[$cuenta]['debito']) >= count($spider[$cuenta]['credito'])) {
            $c = count($spider[$cuenta]['debito']);
        } else {
            $c = count($spider[$cuenta]['credito']);
        }
        $lineas = '';
        $t      = 15;
        $x      = 0;
        $hsvg   = (($c * 60) - 30) / 2;
        foreach ($spider[$cuenta]['debito'] as $key => $value) {
            $this->datos['body'] .= '<div class="spd-debito"  style="top: ' . ($x * 60) . 'px;"><a data-toggle="popover" data-placement="top" data-html="true" data-content="Nombre de cuenta<br/>Porcentaje: ' . number_format($value['porcentaje'], 2, ',', '.') . '%" href="' . base_url('tablero/spider/'.$key) . '">' . $key . '</a><div style="float: right">$' . number_format($value['valor'], 0, ',', '.') . '</div></div>';
            $lineas             .= '<line x1="150" y1="' . $t . '" x2="302" y2="' . $hsvg . '" style="stroke:#000; stroke-width:1"></line>';
            $t                  += 60;
            $x++;
        }
        $t = 15;
        $x = 0;
        foreach ($spider[$cuenta]['credito'] as $key => $value) {
            $this->datos['body'] .= '<div class="spd-credito" style="top: ' . ($x * 60) . 'px;"><a data-toggle="popover" data-placement="top" data-html="true" data-content="Nombre de cuenta<br/>Porcentaje: ' . number_format($value['porcentaje'], 2, ',', '.') . '%" href="' . base_url('tablero/spider/'.$key) . '">' . $key . '</a><div style="float: right">$' . number_format($value['valor'], 0, ',', '.') . '</div></div>';
            $lineas             .= '<line x1="452" y1="' . $hsvg . '" x2="604" y2="' . $t . '" style="stroke:#000; stroke-width:1"></line>';
            $t                  += 60;
            $x++;
        }
        $this->datos['body'] .= '<div class="spd-spider"  style="top: ' . ((($c * 60) / 2) - 30) . 'px; text-align: center">' . $cuenta . '</div>';
        $this->datos['body'] .= '<svg width="754" height="' . (($c * 60) - 30) . '" viewBox="0 0 754 ' . (($c * 60) - 30) . '">';
        $this->datos['body'] .= $lineas;
        $this->datos['body'] .= '</svg>';
        //$this->datos['body'] .= '<pre>'.var_export($spider, TRUE).'</pre>';
        $this->datos['body'] .= '<br/><br/><a href="' . base_url('tablero/spider/130505') . '">130505</a> - ' .
                '<a href="' . base_url('tablero/spider/135515') . '">135515</a> - ' .
                '<a href="' . base_url('tablero/spider/413595') . '">413595</a> - ' .
                '<a href="' . base_url('tablero/spider/240801') . '">240801</a> - ' .
                '<a href="' . base_url('tablero/spider/135517') . '">135517</a> - ' .
                '<a href="' . base_url('tablero/spider/11100501') . '">11100501</a>';
        //$this->CI->load->view("plantilla/plantilla", $this->datos);
        return $this->datos;
    }
    
}
