<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['post_controller_constructor'][] = array(
    'class'    => 'Usertracking',
    'function' => 'auto_track',
    'filename' => 'Usertracking.php',
    'filepath' => 'libraries'
);
if(1 == 2){
    $hook['post_controller'][] = array(
        'class'    => 'Db_log',
        'function' => 'logQueries',
        'filename' => 'db_log.php',
        'filepath' => 'hooks'
    );    
}
$hook['post_controller_constructor'][] = function(){
    $CI =& get_instance();
    $CI->config->set_item('path_clie',       $CI->config->item('path_file').'/clie'.$CI->session->userdata('clientes_id').'/');
    $CI->config->set_item('path_user',       $CI->config->item('path_clie').'user'.$CI->session->userdata('users_id').'');
    $CI->config->set_item('path_archivos',   $CI->config->item('path_user').'/archivos/');
    $CI->config->set_item('path_graficas',   $CI->config->item('path_user').'/graficas/');
    $CI->config->set_item('path_resultados', $CI->config->item('path_user').'/resultados/');
    $CI->config->set_item('max_condicion',   200);
    
};