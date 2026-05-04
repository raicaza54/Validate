<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Path de archivos
|--------------------------------------------------------------------------
|
| Ruta en el servidor donde seran alojados los archivos de cada usuario
|
*/
$config['path_file'] = '/var/www/html/archivos';

/*
|--------------------------------------------------------------------------
| Tamaño en Kb de archivos de subida
|--------------------------------------------------------------------------
|
| Los archivos se miden en kilobytes, esto depende del sistema operativo
| como se configura en el archivo php.ini
| 
| The maximum size (in kilobytes) that the file can be. Set to zero for no 
| limit. Note: Most PHP installations have their own limit, as specified in 
| the php.ini file. Usually 2 MB (or 2048 KB) by default.
|
*/
$config['size_file'] = '30000kb';


/*
|--------------------------------------------------------------------------
| Tipos de archivos excel
|--------------------------------------------------------------------------
| 
| Los tipos de mime correspondientes a los tipos de archivos que permite 
| cargar. Por lo general, la extensión de archivo se puede utilizar como el 
| tipo mime. Puede ser una matriz o una cadena separada por tuberías.
| xls|xlsx|ods|doc|docx|odt|pdf|jpg|jpeg|bmp|png
|
*/
$config['types_file'] = 'csv|xlsx';

/*
|--------------------------------------------------------------------------
| Directorio donde cada cliente almacena sus archivos
|--------------------------------------------------------------------------
| 
| Para mantener el orden por cada cliente y poder determinar la cantida de
| megas que se estan utilizando por parte del cliente
|
*/
$config['path_clie'] = NULL;
