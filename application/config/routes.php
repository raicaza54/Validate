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
$route['empresas/v1/datos']['post']      = 'empresas/datos';
$route['empresas/v1/activar']['post']    = 'empresas/activar';
$route['empresas/v1/formulario']['post'] = 'empresas/formulario';
$route['empresas/v1/actualizar']['post'] = 'empresas/actualizar';
$route['empresas/v1/crear']['post']      = 'empresas/crear';
$route['empresas/v1/eliminar']['post']   = 'empresas/eliminar';
$route['empresas/v1/limites']['get']     = 'empresas/limites';

/* -------------------------------------------------------------------------
 * Explorador
 * ------------------------------------------------------------------------- */
$route['explorador/v1/carpetas']['post'] = 'explorador/carpetas';
$route['explorador/v1/crear']['post']    = 'explorador/crear';
$route['explorador/v1/editar']['post']   = 'explorador/editar';

/* -------------------------------------------------------------------------
 * Resultados
 * ------------------------------------------------------------------------- */
$route['resultados/v1/carpetas']['post']        = 'resultados/carpetas';
$route['resultados/v1/crear']['post']           = 'resultados/crear';
$route['resultados/v1/editar']['post']          = 'resultados/editar';
$route['resultados/v1/pdf']['post']             = 'resultados/pdf';
$route['resultados/v1/descargar']['post']       = 'resultados/descargar';
$route['resultados/v1/url/(:any)/(r|d)']['get'] = 'resultados/url/$1/$2';

/* -------------------------------------------------------------------------
 * Archivos
 * ------------------------------------------------------------------------- */
$route['archivos/v1/datos']['post']                = 'archivos/datos';
$route['archivos/v1/digito']['post']               = 'archivos/digito';
$route['archivos/v1/relacionspider']['post']       = 'archivos/relacionspider';
$route['archivos/v1/subir']['post']                = 'archivos/subir';
$route['archivos/v1/preprocesar']['post']          = 'archivos/preprocesar';
$route['archivos/v1/header']                       = 'archivos/header';
$route['archivos/v1/comprobarDatos']               = 'archivos/comprobarDatos';
$route['archivos/v1/encabezado']['post']           = 'archivos/encabezado/0';
$route['archivos/v1/configurar']['post']           = 'archivos/configurar';
$route['archivos/v1/limites']['get']               = 'archivos/limites';
$route['archivos/v1/descargar']['post']            = 'archivos/descargar';
$route['archivos/v1/url/(:any)']['get']            = 'archivos/url/$1';
$route['archivos/v1/exportarDigito']['post']       = 'archivos/exportarDigito';
$route['archivos/v1/exportarspider']['post']       = 'archivos/exportarspider';
$route['archivos/v1/digito/(:any)/(r|d)']['get']   = 'archivos/urlDigito/$1/$2';
$route['archivos/v1/relacion/(:any)/(r|d)']['get'] = 'archivos/urlRelacion/$1/$2';

/* -------------------------------------------------------------------------
 * Ley de Benford
 * ------------------------------------------------------------------------- */
$route['benford/v1/encabezado']['post'] = 'archivos/encabezado/1';
$route['benford/v1/procesar']['post']   = 'auditoria/benford';

/* -------------------------------------------------------------------------
 * La Araña
 * ------------------------------------------------------------------------- */
$route['spider/v1/cuentas']['post']  = 'archivos/cuentas';
$route['spider/v1/procesar']['post'] = 'auditoria/spider';

/* -------------------------------------------------------------------------
 * Manipulacion
 * ------------------------------------------------------------------------- */
$route['manipulacion/v1/archivos']['post']        = 'explorador/balances';
$route['manipulacion/v1/procesar']['post']        = 'auditoria/manipulacion';
$route['manipulacion/v1/editarConfianza']['post'] = 'auditoria/editarConfianza';
$route['manipulacion/v1/confianza']['post']       = 'auditoria/confianza';

/* -------------------------------------------------------------------------
 * Perfil
 * ------------------------------------------------------------------------- */
$route['perfil/v1/datos']['get']               = 'perfil/datos';
$route['perfil/v1/disco']['get']               = 'perfil/disco';
$route['perfil/v1/save']['post']               = 'perfil/saveDatos';
$route['perfil/v1/saveEmpresa']['post']        = 'perfil/saveEmpresa';
$route['perfil/v1/saveLimite']['post']         = 'perfil/saveLimite';
$route['perfil/v1/saveColumnas']['post']       = 'perfil/saveColumnas';
$route['perfil/v1/columnasPorDefecto']['post'] = 'perfil/columnasPorDefecto';

/* -------------------------------------------------------------------------
 * Listas de Control
 * ------------------------------------------------------------------------- */
$route['listascontrol/v1/datos']['post']     = 'archivos/columlistas';
$route['listascontrol/v1/consultar']['post'] = 'auditoria/listascontrol';

/* -------------------------------------------------------------------------
 * Condiciones de Cuenta
 * ------------------------------------------------------------------------- */
$route['condicion/v1/datos']['post']     = 'archivos/columcondicion';
$route['condicion/v1/extraer']['post']   = 'archivos/extraerBase';
$route['condicion/v1/consultar']['post'] = 'auditoria/condicionCuenta';

/* -------------------------------------------------------------------------
 * Terminos y Condiciones
 * ------------------------------------------------------------------------- */
$route['terminosCondiciones']['post'] = 'auditoria/terminosCondiciones';

/* -------------------------------------------------------------------------
 * Ayuda
 * ------------------------------------------------------------------------- */
$route['asistente/v1/salvar']['post'] = 'auditoria/asistente';


/* -------------------------------------------------------------------------
 * Administrador Clientes
 * ------------------------------------------------------------------------- */
$route['admin/v1/clientes/listar']['post'] = 'admin/clientes/listar';

/* -------------------------------------------------------------------------
 * Sistema por Defecto
 * ------------------------------------------------------------------------- */
$route['default_controller']   = 'auditoria';
$route['404_override']         = '';
$route['translate_uri_dashes'] = FALSE;
