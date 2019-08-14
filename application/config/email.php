<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Configuracion de envio de correo
 * @LastUpdate  2019-08-12
 */

$config['protocol']     = 'smtp';
$config['charset']      = 'utf-8';
$config['mailtype']     = 'html';
$config['wordwrap']     = TRUE;
$config['smtp_host']    = "email-smtp.us-east-1.amazonaws.com"; // eg. tls://email-smtp.eu-west-1.amazonaws.com
$config['smtp_user']    = "AKIAZJR3TY36FY2OHMNU";
$config['smtp_pass']    = "BDbaRRh86DvYXMAt18NZy+gRTganNayoAgg0eCvrL97n";
$config['smtp_port']    = "465";
$config['smtp_timeout'] = "20";
$config['crlf']         = "\r\n";
$config['newline']      = "\r\n";
$config['mailsender']   = "notificaciones@geoiss.com";
