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
$hook['post_controller_constructor'] = function(){
    $CI =& get_instance();
    $CI->config->set_item('path_clie', $CI->config->item('path_file').'/cli'.$CI->session->userdata('clientes_id').'/');
    $CI->config->set_item('path_user', $CI->config->item('path_file').'/cli'.$CI->session->userdata('clientes_id').'/'.$CI->session->userdata('users_id').'/');
};