<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 	CodeIgniter Asset Library
 * 	An open source PHP library written to easily manage assets for CodeIgniter Framework
 *  
 *  Copyright 2014  Daniel Lee
 *	GitHub:  https://github.com/inputx/CodeIgniter-Asset-Library
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the Apache License (2.0) as published by
 *  the The Apache Software Foundation, either version 2.0 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  Apache License for more details.
 *
 *  You should have received a copy of the Apache License
 *  along with this program.  If not, see <http://www.apache.org/licenses/>
 *
 * @package			dli/framework-library/codeIgniter-asset-library
 * @author			Daniel Lee
 * @copyright		Copyright 2014 Daniel Lee
 * @link			https://github.com/inputx/CodeIgniter-Asset-Library
 * @since			Version 0.1
 * @updated			24th Oct 2014
 * @filesource
*/


/*
|--------------------------------------------------------------------------
| Asset Files version
|--------------------------------------------------------------------------
|
|	In order to clean web browser cache for javascript and 
|	css asset files, update version.
|	
|
*/
$config['assets']['version'] 			= 1;


/*
|--------------------------------------------------------------------------
| Assets path
|--------------------------------------------------------------------------
|
|	Assets path in your project. Could be like:
|
|	*Project root
|	|-- application
|	|-- system
|	|-- assets
|	|   |-- css
|	|   |-- js
|	|   |-- images
|
|	You can add more type of assets. 
*/
$config['assets']['path']['css'] 		= "assets/css/";
$config['assets']['path']['js'] 		= "assets/js/";
$config['assets']['path']['image'] 		= "assets/images/";
$config['assets']['path']['less'] 		= "assets/less/";

/*
|--------------------------------------------------------------------------
| Assets output format
|--------------------------------------------------------------------------
|
|	Assets output format in html
*/
$config['assets']['format']['css'] = '<link type="text/css" rel="stylesheet" href="{:url:}" />';
$config['assets']['format']['js'] = '<script src="{:url:}" type="text/javascript" charset="utf-8"></script>';
$config['assets']['format']['image'] = '<img src="{:url:}" />';
$config['assets']['format']['less'] = '<link rel="stylesheet/less" type="text/css" href="{:url:}" />';