<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Listas de Control, Son bases de datos nacionales e internacionales que recogen 
 * información, reportes y antecedentes de diferentes organismos, tratándose de 
 * personas naturales y jurídicas, que pueden presentar actividades sospechosas, 
 * investigaciones, procesos o condenas por los delitos de Lavado de Activos y 
 * Financiación del terrorismo.
 * 
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Libreria analisis basado en manipulacion de datos
 * @LastUpdate  2019-02-14
 */

require_once APPPATH.'libraries/fuzzywuzzy/Fuzz.php';
require_once APPPATH.'libraries/fuzzywuzzy/Process.php';

class Listascontrol {
    private $CI;
    private $fuzz;
    private $process;
    private $listas;
    
    function __construct() {
        set_time_limit(0);
        $this->CI = & get_instance();
        $this->CI->load->model([
            'Listas_model',
            'Archivos_model'
        ]);
        $this->fuzz = new FuzzyWuzzy\Fuzz;
        $this->process = new FuzzyWuzzy\Process($this->fuzz);
        //$this->listas = $this->CI->Listas_model->getData();
    }
    
    function run($id) {
        $coincide = [];
        $archivo = $this->CI->Archivos_model->getDetalleAll($id);
        $column = $this->CI->archivo->columnListas($id);
        $ifNombre = array_key_exists('nombre', $column['array']);
        $ifidentificacion = array_key_exists('identificacion', $column['array']);
        //$nombres = array_column($this->listas, 'nombre');
        //$identificaciones = array_column($this->listas, 'identificacion');
        foreach ($archivo as $key => $archivoValue) {
            if($ifNombre == TRUE){
                if(strlen(trim($archivoValue[$column['array']['nombre']])) > 3){
                    //$coincideNombre = $this->process->extractBests($archivoValue[$column['array']['nombre']], $nombres, null, [$this->fuzz, 'ratio'], 80, 3)->toArray();
                    $coincideNombre = $this->CI->Listas_model->extrac($archivoValue[$column['array']['nombre']], 'nombre');
                    if(count($coincideNombre)){
                        $coincide['nombre'][] = [
                            'buscado'    => $archivoValue[$column['array']['nombre']],
                            'resultados' => $coincideNombre
                        ];
                    }
                
                }
            }
            if($ifidentificacion == TRUE){
                if(strlen(trim($archivoValue[$column['array']['identificacion']])) > 3){
                    //$coincideIdentificacion = $this->process->extractBests($archivoValue[$column['array']['identificacion']], $identificaciones, null, [$this->fuzz, 'ratio'], 80, 3)->toArray();
                    $coincideIdentificacion = $this->CI->Listas_model->extrac($archivoValue[$column['array']['identificacion']], 'identificacion');
                    if(count($coincideIdentificacion)){
                        $coincide['identificacion'][] = [
                            'buscado'    => $archivoValue[$column['array']['identificacion']],
                            'resultados' => $coincideIdentificacion
                        ];
                    }
                }
            }
        }
        return $coincide;
    }
}
