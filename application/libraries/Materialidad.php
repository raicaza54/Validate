<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Materialidad
 * Materialidad o importancia relativa: es la cifra o cifras determinadas por el 
 * auditor que señalan las posibles incorrecciones o errores materiales en los 
 * estados financieros en su conjunto y en determinados tipos de transacciones, 
 * saldos contables o información a revelar.
 * 
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Libreria analisis basado en materialidad
 * @LastUpdate  2020-12-19
 */
class Materialidad {
    private $CI;
    function __construct() {
        set_time_limit(0);
        $this->CI = & get_instance();
        $this->CI->load->model(['Archivos_model','Empresas_model']);
    }    
    
    function run($col, $empresaId, $archivoId) {
        $siCredito = $this->CI->Empresas_model->siCredito($empresaId);
        $cta41     = $this->CI->Archivos_model->getManipulacion($archivoId, [41], $col);
        $cta61     = $this->CI->Archivos_model->getManipulacion($archivoId, [61], $col);
        $cta5174   = $this->CI->Archivos_model->getManipulacion($archivoId, [51, 52, 71, 72, 73], $col);
        $cta42     = $this->CI->Archivos_model->getManipulacion($archivoId, [42], $col);
        $cta53     = $this->CI->Archivos_model->getManipulacion($archivoId, [53], $col);
        $cta4253   = $cta42 + $cta53;
        $cta54     = $this->CI->Archivos_model->getManipulacion($archivoId, [54], $col);
        $cta3137   = $this->CI->Archivos_model->getManipulacion($archivoId, [31, 32, 33, 34, 35, 36, 37], $col);
        $cta3839   = $this->CI->Archivos_model->getManipulacion($archivoId, [38, 39], $col);
        if($siCredito == 'si'){
            $activo = $this->CI->Archivos_model->getManipulacion($archivoId, [11,12,13,14,15,16,17,18,19], $col);  //ACTIVOS
            $ingope = $cta41;                                                                                      //INGRESOS OPERACIONALES
            $utlve  = $cta41 + $cta61;                                                                             //UTILIDAD EN VENTAS
            $utlope = $utlve + $cta5174;                                                                           //UTILIDAD OPERACIONAL
            $utladi = $utlope + $cta4253;                                                                          //UTILIDAD ANTES DE IMPUESTOS
            $utlddi = $utladi + $cta54;                                                                            //UTILIDAD DESPUES DE IMPUESTOS
            $patrim = $cta3137 + $cta3839 + $utlddi;                                                               //PATRIMONIO            
            $utlbru = $utlve;                                                                                      //UTILIDAD BRUTA
        }else{
            $activo = $this->CI->Archivos_model->getManipulacion($archivoId, [11,12,13,14,15,16,17,18,19], $col);  //ACTIVOS
            $ingope = $cta41;                                                                                      //INGRESOS OPERACIONALES
            $utlve  = $cta41 + $cta61;                                                                             //UTILIDAD EN VENTAS
            $utlope = $utlve - $cta5174;                                                                           //UTILIDAD OPERACIONAL
            $utladi = $utlope - $cta53 + $cta42;                                                                   //UTILIDAD ANTES DE IMPUESTOS
            $utlddi = $utladi - $cta54;                                                                            //UTILIDAD DESPUES DE IMPUESTOS
            $patrim = $cta3137 + $cta3839 + $utlddi;                                                               //PATRIMONIO            
            $utlbru = $utlve;                                                                                      //UTILIDAD BRUTA
        }
        return [
            'utladi' => $utladi,
            'utlope' => $utlope,
            'utlbru' => $utlbru,
            'ingope' => $ingope,
            'activo' => $activo,
            'patrim' => $patrim,            
        ];
    }
    
    function getConfig() {
        $e = FALSE;
        $cliente_id = $this->CI->session->userdata('clientes_id');
        $users_id = $this->CI->session->userdata('users_id');
        $empresaId = $this->CI->session->userdata('empresaId');
        $columnas = $this->CI->Empresas_model->configGetColumDefault($users_id, $empresaId, $cliente_id);
        if(array_key_exists('materialidad', $columnas) && is_array($columnas['materialidad']) && count($columnas['materialidad'])){
            $archivo = $this->CI->Archivos_model->getById($columnas['materialidad']['archivoId']);
            $columnas['materialidad']['archivo'] = [
                'id'     => $archivo['id'],
                'nombre' => $archivo['nombre'],                
            ];
            $e = $columnas['materialidad'];
        }
        return $e;
    }
}