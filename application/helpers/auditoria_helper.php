<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Se genera un archivo de texto en la carpeta logs
 *
 * @param cadena/array/object $r variable a evaluar
 */
if (!function_exists('debug_file')) {
    function debug_file($r) {
        $file = fopen(APPPATH . "logs/debug-" . date("Y-m-d") . ".php", "a");
        if (!is_string($r)) {
            $r = var_export($r, TRUE);
        }
        fwrite($file, "debug " . date("Y-m-d h:i:s a - ") . $r . PHP_EOL);
        fclose($file);
    }
}

if (!function_exists('unSerializeArray')) {
    function unSerializeArray($form) {
        $unSerailize = [];
        if(is_array($form) && count($form) > 0){
            foreach ($form as $value) {
                $unSerailize[$value['name']] = $value['value'];
            }            
        }else{
            return $form;
        }
        return $unSerailize;
    }
}