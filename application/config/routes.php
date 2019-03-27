<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	https://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There are three reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router which controller/method to use if those
  | provided in the URL cannot be matched to a valid route.
  |
  |	$route['translate_uri_dashes'] = FALSE;
  |
  | This is not exactly a route, but allows you to automatically route
  | controller and method names that contain dashes. '-' isn't a valid
  | class or method name character, so it requires translation.
  | When you set this option to TRUE, it will replace ALL dashes in the
  | controller and method URI segments.
  |
  | Examples:	my-controller/index	-> my_controller/index
  |		my-controller/my-method	-> my_controller/my_method
 */

/* -------------------------------------------------------------------------
 * Empresas
 * ------------------------------------------------------------------------- */
$route['empresas/v1/datos']['post']    = 'empresas/datos';
$route['empresas/v1/activar']['post'] = 'empresas/activar';

/* -------------------------------------------------------------------------
 * Explorador
 * ------------------------------------------------------------------------- */
$route['explorador/v1/carpetas']['post'] = 'explorador/carpetas';
$route['explorador/v1/crear']['post']    = 'explorador/crear';
$route['explorador/v1/editar']['post']   = 'explorador/editar';

/* -------------------------------------------------------------------------
 * Archivos
 * ------------------------------------------------------------------------- */
$route['archivos/v1/datos']['post']  = 'archivos/datos';
$route['archivos/v1/digito']['post'] = 'archivos/digito';
$route['archivos/v1/subir']          = 'archivos/subir';

/* -------------------------------------------------------------------------
 * Ley de Benford
 * ------------------------------------------------------------------------- */
$route['benford/v1/encabezado']['post'] = 'archivos/encabezado';
$route['benford/v1/procesar']['post']   = 'auditoria/benford';

/* -------------------------------------------------------------------------
 * La Araña
 * ------------------------------------------------------------------------- */
$route['spider/v1/cuentas']['post']  = 'archivos/cuentas';
$route['spider/v1/procesar']['post'] = 'auditoria/spider';

/* -------------------------------------------------------------------------
 * Manipulacion
 * ------------------------------------------------------------------------- */
$route['manipulacion/v1/archivos']['post'] = 'explorador/balances';
$route['manipulacion/v1/procesar']['post'] = 'auditoria/manipulacion';

$route['default_controller']   = 'auditoria';
$route['404_override']         = '';
$route['translate_uri_dashes'] = FALSE;
