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
        if ($r === '-') {
            $r = '------------------------ ' . date("h:i:s a") . ' ------------------------------';
        } elseif (!is_string($r)) {
            $r = var_export($r, TRUE);
        }
        fwrite($file, "debug " . date("Y-m-d h:i:s a - ") . $r . PHP_EOL);
        fclose($file);
    }

}

if (!function_exists('unSerializeArray')) {

    function unSerializeArray($form) {
        $unSerailize = [];
        if (is_array($form) && count($form) > 0) {
            foreach ($form as $value) {
                $unSerailize[$value['name']] = $value['value'];
            }
        } else {
            return $form;
        }
        return $unSerailize;
    }

}

if (!function_exists('maSort')) {

    function maSort($ma = '', $sortkey = '', $sortorder = 1) { // sortorder: 1=asc, 2=desc
        if ($ma && is_array($ma) && $sortkey) { // confirm inputs
            foreach ($ma as $k => $a)
                $temp["$a[$sortkey]"][$k] = $a; // temp ma with sort value, quotes convert key to string in case numeric float
            if ($sortorder == 2) { // descending
                krsort($temp);
            } else { // ascending
                ksort($temp);
            }
            $newma = array(); // blank output multiarray to add to
            foreach ($temp as $sma)
                $newma += $sma; // add sorted arrays to output array
            unset($ma, $sma, $temp); // release memory
            return $newma;
        }
    }

}

if (!function_exists('uniqint')) {

    function uniqint() {
        return hexdec(uniqid());
    }

}

if (!function_exists('user_id')) {

    function user_id() {
        $CI = & get_instance();
        if ($CI->ion_auth->logged_in()) {
            return $CI->session->userdata('user_id');
        } else {
            return FALSE;
        }
    }

}

if (!function_exists('strip_tags_content')) {

    function strip_tags_content($text) {
        return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
    }

}

if (!function_exists('is_json')) {

    function is_json($strJson) {
        json_decode($strJson);
        return (json_last_error() === JSON_ERROR_NONE);
    }

}

if (!function_exists('openCypher')) {

    /**
     * Encriptar y desencriptar cadenas de texto con PHP
     * @param type $action string encrypt/decrypt
     * @param type $string string cadena a procesar
     * @return type string cadena procesada
     */
    function openCypher($action = 'encrypt', $string = false) {
        $action         = trim($action);
        $output         = false;
        $myKey          = 'Aophei3ahfo7Che8';
        $myIV           = 'Ii5oFei5wah6ooco';
        $encrypt_method = 'AES-256-CBC';
        $secret_key     = hash('sha256', $myKey);
        $secret_iv      = substr(hash('sha256', $myIV), 0, 16);
        if ($action && ($action == 'encrypt' || $action == 'decrypt') && $string) {
            $string = trim(strval($string));
            if ($action == 'encrypt') {
                $output = openssl_encrypt($string, $encrypt_method, $secret_key, 0, $secret_iv);
            }
            if ($action == 'decrypt') {
                $output = openssl_decrypt($string, $encrypt_method, $secret_key, 0, $secret_iv);
            }
        }
        return $output;
    }

}

if (!function_exists('money')) {

    function money($value) {
        if (substr($value, 0, 2) == '$-') {
            $value = '-$' . substr($value, 2);
        }
        return $value;
    }

}

if (!function_exists('download')) {

    function download($path, $name) {
        // make sure it's a file before doing anything!
        $CI = & get_instance();
        if (is_file($path)) {
            // get the file mime type using the file extension
            $CI->load->helper('file');
            $mime = get_mime_by_extension($path);
            // Build the headers to push out the file properly.
            header('Pragma: public');     // required
            header('Expires: 0');         // no cache
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
            header('Cache-Control: private', false);
            header('Content-Type: ' . $mime);  // Add the mime type from Code igniter.
            header('Content-Disposition: attachment; filename="' . basename($name) . '"');  // Add the file name
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($path)); // provide file size
            header('Connection: close');
            readfile($path); // push it out
            exit();
        }
    }

}

if (!function_exists('date5format')) {

    function date5format() {
        $excelDateTime = 43606;
        $date_format   = floor($excelDateTime);
        $time_format   = $excelDateTime - $date_format;
        $mysql_strdate = ($date_format > 0) ? ( $date_format - 25568 ) * 86400 + $time_format * 86400 : $time_format * 86400;
        return $mysql_strdate;
    }

}

if (!function_exists('mkdir_validate')) {

    function mkdir_validate($path) {
        if (!is_dir($path)) {
            if (!mkdir($path, 0755, TRUE)) {
                log_message('error', 'upload_no_filepath');
                return FALSE;
            }
        }
        if (!is_really_writable($path)) {
            if (!chmod($path, 0755)) {
                log_message('error', 'upload_not_writable');
                return FALSE;
            }
        }
        return TRUE;
    }

}

if (!function_exists('formatBytes')) {
    /**
     * 500 Mb => 524288000 Bytes
     * @param type $bytes
     * @param type $precision
     * @return type
     */
    function formatBytes($bytes, $precision = 2) {
        $unit = ["B", "Kb", "Mb", "Gb"];
        $exp = floor(log($bytes, 1024)) | 0;
        return round($bytes / (pow(1024, $exp)), $precision).' '.$unit[$exp];
    }

}
