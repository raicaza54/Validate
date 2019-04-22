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
        if($r == '-'){
            $r = '------------------------ '.date("h:i:s a").' ------------------------------';
        }elseif (!is_string($r)) {
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

if (!function_exists('maSort')) {
    function maSort($ma = '', $sortkey = '', $sortorder = 1) { // sortorder: 1=asc, 2=desc
      if ($ma && is_array($ma) && $sortkey) { // confirm inputs
        foreach ($ma as $k=>$a) $temp["$a[$sortkey]"][$k] = $a; // temp ma with sort value, quotes convert key to string in case numeric float
        if ($sortorder == 2) { // descending
          krsort($temp);
        } else { // ascending
          ksort($temp);
        }
        $newma = array(); // blank output multiarray to add to
        foreach ($temp as $sma) $newma += $sma; // add sorted arrays to output array
        unset($ma, $sma, $temp); // release memory
        return $newma;
      }
    }
}

if (!function_exists('uniqint')) {
    function uniqint(){
        return hexdec(uniqid());
    }
}

if (!function_exists('user_id')) {
    function user_id(){
        $CI = & get_instance();
        if ($CI->ion_auth->logged_in()) {
            return $CI->session->userdata('user_id');
        }else{
            return FALSE;
        }
    }
}

if (!function_exists('strip_tags_content')) {
    function strip_tags_content($text) {
        return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
    }
}