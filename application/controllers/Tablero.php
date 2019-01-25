<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Tablero
 *
 * @author Kevin Enriquez
 */
class Tablero extends CI_Controller {

    /**
     * Variable de carga de vista plantilla
     */
    private $data = array('body' => '');
    
    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
    }

    public function index() {
        for($x=0;$x<300;$x++){
            $this->data['body'] .= 'Hola mundo ';
        }

        
        $this->load->view("plantilla/plantilla", $this->data);
    }
    
    public function spider() {
        $ejemplo = $this->db->get('spider')->result_array();
        $this->load->library('table');
        $template = array(
                'table_open'            => '<table border="1" cellpadding="4" cellspacing="0">',

                'thead_open'            => '<thead>',
                'thead_close'           => '</thead>',

                'heading_row_start'     => '<tr>',
                'heading_row_end'       => '</tr>',
                'heading_cell_start'    => '<th>',
                'heading_cell_end'      => '</th>',

                'tbody_open'            => '<tbody>',
                'tbody_close'           => '</tbody>',

                'row_start'             => '<tr>',
                'row_end'               => '</tr>',
                'cell_start'            => '<td>',
                'cell_end'              => '</td>',

                'row_alt_start'         => '<tr>',
                'row_alt_end'           => '</tr>',
                'cell_alt_start'        => '<td>',
                'cell_alt_end'          => '</td>',

                'table_close'           => '</table>'
        );
        
        $this->table->set_template($template);
        $this->table->set_heading('REGISTRO','CUENTA','CTE','FECHA','DOC','REF','NIT','DETALLE','TIPO','VALOR','BASE','CC','TB','PL');
        $tabla = $this->table->generate($ejemplo);
        $this->table->clear();
        $grupos = array();
        foreach ($ejemplo as $value) {
            $grupos[$value['CTE'].$value['DOC']][] = array(
                'grupo'  => $value['CTE'].$value['DOC'],
                'cuenta' => $value['CUENTA'],
                'tipo'   => $value['TIPO'],
                'valor'  => $value['VALOR'],
            );
        }
        $tabla_grupos = '';
        foreach ($grupos as $value) {
            $this->table->set_heading('GRUPO','CUENTA','TIPO','VALOR');
            $tabla_grupos .= $this->table->generate($value).'<br/>';
            $this->table->clear();
        }
        $cuenta = 130505;
        $grupos_clasificado = array(
            'debito'  => array(),
            'credito' => array(),
        );
        foreach ($grupos as $key => $value) {
            if(in_array($cuenta, array_column($value, 'cuenta'))){
                $k = 0;
                foreach ($value as $value_grupo) {
                    if(($value_grupo['cuenta'] == $cuenta) AND ($value_grupo['tipo'] == 1)){
                        foreach ($grupos_clasificado['debito'] as $value_credito) {
                            if(in_array($key, array_column($value_credito, 'grupo'))){
                                $k = 1;
                            }
                        }                        
                        if($k == 0){
                            $grupos_clasificado['debito'][] = $value;
                        }
                    }
                    if(($value_grupo['cuenta'] == $cuenta) AND ($value_grupo['tipo'] == 2)){
                        foreach ($grupos_clasificado['credito'] as $value_credito) {
                            if(in_array($key, array_column($value_credito, 'grupo'))){
                                $k = 1;
                            }
                        }
                        if($k == 0){
                            $grupos_clasificado['credito'][] = $value;
                        }
                    }
                }
            }
        }
        
        
        
        $total_debito = 0;
        $total_debito_cuenta = 0;
        $debito_porcentaje = 0;
        foreach ($grupos_clasificado['debito'] as $key_debito => $value){
            $total_debito = 0;
            $total_debito_cuenta = 0;
            $debito_porcentaje = 0;
            foreach ($value as $value_debito) {
                if(($value_debito['tipo'] == 1) AND ($value_debito['cuenta'] == $cuenta)){
                    $total_debito_cuenta += $value_debito['valor'];
                }
                if($value_debito['tipo'] == 1){
                    $total_debito += $value_debito['valor'];
                }
            }
            $debito_porcentaje = (($total_debito_cuenta/$total_debito)*100);
            foreach ($value as $key_grupo => $value_debito) {
                $grupos_clasificado['debito'][$key_debito][$key_grupo]['tb'] = 0;
                if($value_debito['tipo'] == 2){
                    $grupos_clasificado['debito'][$key_debito][$key_grupo]['tb'] = ($value_debito['valor']*$debito_porcentaje)/100;
                }
            }
        }
        //print_r($grupos_clasificado['debito']);
        $tabla_grupos_clasificados = '';
        foreach ($grupos_clasificado as $key => $value) {
            $tabla_grupos_clasificados .= '############# '.strtoupper($key).' #############';
            foreach ($value as $seguntipo) {
                $this->table->set_heading('GRUPO','CUENTA','TIPO','VALOR','TB');
                $tabla_grupos_clasificados .= $this->table->generate($seguntipo).'<br/>';
                $this->table->clear();
            }        
        }
        $this->data['body'] = $tabla.'<br/>############# GRUPOS #############'.$tabla_grupos.'<br><br/>############# GRUPOS CLAISIFICADOS #############<br/>'.$tabla_grupos_clasificados.'</pre>';
        
        $this->load->view("plantilla/plantilla", $this->data);
        //$this->ion_auth->logout();
    }

}
