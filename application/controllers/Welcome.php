<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
    
    private $mad_d1 = array(
        array('min' => 0,     'max' => 0.006, 'descripcion' => 'Conformidad Cuasi-Perfecta'),
        array('min' => 0.006, 'max' => 0.012, 'descripcion' => 'Conformidad Aceptable'),
        array('min' => 0.012, 'max' => 0.015, 'descripcion' => 'Conformidad Marginalmente Aceptable'),
        array('min' => 0.015, 'max' => 10000, 'descripcion' => 'No Conformidad'),
    );
    
    public function index() {
        $ejemplo = $this->db->get('ejemplo')->result_array();
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
        $this->table->set_heading('ID', 'VALOR');
        $tabla = $this->table->generate($ejemplo);        
        $this->table->clear();
        
        $this->table->set_heading('ID', 'Digito 1');
        foreach ($ejemplo as $key => $value) {
            if($value['valor'] > 0){
                $r1[] = array(
                    'id'    => $key,
                    'valor' => substr($value['valor'],0,1),
                );                
            }
        }
        $t1 = $this->table->generate($r1);
        $this->table->clear();
        $this->table->set_heading('Numero', 'Frecuencia', 'Observado', 'Benford', 'Variacion');
        
        for($x=1; $x<=9; $x++){
            $r2[$x] = array(
                'numero'     => $x,
                'frecuencia' => $this->contar_valores(array_column($r1, 'valor'), $x),
                'observado'  => 0,
                'benford'    => $this->benford_1d[$x],
                'variacion'  => 0
            );
        }
        $total = array_sum(array_column($r2, 'frecuencia'));
        for($x=1; $x<=9; $x++){
            $r2[$x]['observado'] = ($r2[$x]['frecuencia']*100)/$total;
            $r2[$x]['variacion'] = abs($r2[$x]['observado'] - $this->benford_1d[$x])/100;
        }
        $total_variacion = array_sum(array_column($r2, 'variacion'));
        $mad = $total_variacion/9;
        $t2 = $this->table->generate($r2);
        $mad_d1 = 'N/A';
        foreach ($this->mad_d1 as $value){            
            if(($mad > $value['min']) AND ($mad <= $value['max'])){
                $mad_d1 = $value['descripcion'];
            }
        }
        
        
        $this->table->clear();
        $this->table->set_heading('Esperado', 'Observado');
        $cantidad = count($ejemplo);
        $i = 0;
        for($x=1; $x<=9; $x++){
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
        $t3 = $this->table->generate($r3);
        $this->load->view('welcome_message', array(
            'tabla'    => $tabla, 
            'r1'       => $t1,
            'r2'       => $t2,
            'r3'       => $t3,
            'mad'      => $mad,
            'mad_d1'   => $mad_d1,
            'chi'      => $chi,
        ));
        

        
        
    }
    
    function contar_valores($a, $buscado) {
        if (!is_array($a))
            return NULL;
        $v = array_count_values($a);
        return array_key_exists($buscado, $a) ? $v[$buscado] : 0;
    }

}
