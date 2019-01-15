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

class Asset {
	private $asset              = array(
                "css"               =>  array()
            ,   "js"                =>  array()
            ,   "image"             =>  array()
            ,   "less"              =>  array()
        );
    private $enable             = false;
    private $path               = array();
    private $asset_path         = "";
    private $version            = "";
    private $format             = array();
	private $asset_format       = "";
    
    public function __construct($params = array()){}

    /**
     * Add asset files
     *
     * add multiple asset files
     *
     * @access  public
     * @param   string  asset type
     * @param   array   file name
     * @return  null
     */ 
    public function add_assets($type, $files = array())
    {
        if(is_array($files))
        {
            switch($type)
            {
                case "css":
                case "js":
                case "image":
                case "less":
                    $assets             = $this->asset[$type];
                    $assets             = array_merge($assets, $files);
                    $this->asset[$type] = $assets;
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Add css file
     *
     * add single css file as asset
     *
     * @access  public
     * @param   string  file name
     * @return  null
     */ 
    public function add_css($file)
    {
        $this->add("css", $file);
    }

    /**
     * Add js file
     *
     * add single js file as asset
     *
     * @access  public
     * @param   string  file name
     * @return  null
     */ 
    public function add_js($file)
    {
        $this->add("js", $file);
    }

    /**
     * Add image file
     *
     * add single image file as asset
     *
     * @access  public
     * @param   string  file name
     * @return  null
     */ 
    public function add_image($file)
    {
        $this->add("image", $file);
    }

    /**
     * Add less file
     *
     * add single less file as asset
     *
     * @access  public
     * @param   string  file name
     * @return  null
     */ 
    public function add_less($file)
    {
        $this->add("less", $file);
    }

    /**
     * Add asset file
     *
     * add single file as asset
     *
     * @access  private
     * @param   string  file name
     * @return  null
     */ 
    private function add($type, $file)
    {
        array_push($this->asset[$type], $file);
    }

    /**
     * Load css asset
     *
     * load single or multiple css asset
     *
     * @access  public
     * @param   boolen  enable/disable https access
     * @return  string
     */
    public function load_css($https = false)
    {
        return $this->load("css", $https);
    }

    /**
     * Load js asset
     *
     * load single or multiple js asset
     *
     * @access  public
     * @param   boolen  enable/disable https access
     * @return  string
     */
    public function load_js($https = false)
    {
        return $this->load("js", $https);
    }

    /**
     * Load image asset
     *
     * load single or multiple image asset
     *
     * @access  public
     * @param   boolen  enable/disable https access
     * @return  string
     */
    public function load_image($https = false)
    {
        return $this->load("image", $https);
    }

    /**
     * Load less asset
     *
     * load single or multiple less asset
     *
     * @access  public
     * @param   boolen  enable/disable https access
     * @return  string
     */
    public function load_less($https = false)
    {
        return $this->load("less", $https);
    }

    /**
     * Load assets
     *
     * load multiple asset file, and output html code
     *
     * @access  private
     * @param   string  asset type
     * @param   boolen  enable/disable https access
     * @return  string
     */
    private function load($type, $https = false)
    {
        $assets = $this->asset[$type];
        $assets_output = "";
        if(!empty($assets))
        {
            foreach($assets as $asset)
            {
                
                switch($type)
                {
                    case "css":
                        $assets_output .= $this->asset_html_css     ($asset, $https);
                        break;
                    case "js":
                        $assets_output .= $this->asset_html_js      ($asset, $https);
                        break;
                    case "image":
                        $assets_output .= $this->asset_html_image   ($asset, $https);
                        break;
                    case "less":
                        $assets_output .= $this->asset_html_less    ($asset, $https);
                        break;
                    default:
                        break;
                }
            }
        }
        
        return $assets_output;
    }


    /**
     * print out css asset as html
     *
     * print out css html element like <link type="text/css" href="" rel="stylesheet">
     *
     * @access  public
     * @param   string  file name
     * @param   boolen  enable/disable https access
     * @return  string
     */
    public function asset_html_css($file, $https)
    {
        return $this->asset_html_setting("css",$file, $https);
    }

    /**
     * print out js asset as html
     *
     * print out js html element like <script type="text/javascript" src="">
     *
     * @access  public
     * @param   string  file name
     * @param   boolen  enable/disable https access
     * @return  string
     */
    public function asset_html_js($file, $https)
    {
        return $this->asset_html_setting("js",$file, $https);
    }

    /**
     * print out image asset as html
     *
     * print out image html element like <img src="">
     *
     * @access  public
     * @param   string  file name
     * @param   boolen  enable/disable https access
     * @return  string
     */
    public function asset_html_image($file, $https)
    {
        return $this->asset_html_setting("image",$file, $https);
    }

    /**
     * print out less asset as html
     *
     * print out less html element like <link rel="stylesheet/less" type="text/css" href="style.less">
     *
     * @access  public
     * @param   string  file name
     * @param   boolen  enable/disable https access
     * @return  string
     */
    public function asset_html_less($file, $https)
    {
        return $this->asset_html_setting("less",$file, $https);
    }

    /**
     * print out asset as html
     *
     * print out html element for different type such as css, js, less and image
     *
     * @access  private
     * @param   string  file name
     * @param   boolen  enable/disable https access
     * @return  string
     */
    private function asset_html_setting($type, $file, $https)
    {
        $CI =& get_instance();
        $CI->load->helper("url");
        $CI->load->config("asset_config");
        
        $assets_config = $CI->config->item('assets');

        if(isset($assets_config['version']))
        {
            $this->version = $assets_config['version'];
        }

        if(isset($assets_config['path']))
        {
            $this->path = $assets_config['path'];
        }

        if($this->path[$type])
        {
            $this->asset_path = $this->path[$type];
        }

        if(isset($assets_config['format']))
        {
            $this->format = $assets_config['format'];
        }
        
        if(isset($this->format[$type]))
        {
            $this->asset_format = $this->format[$type];
        }

        $url = site_url($this->asset_path.$file);

        if($https)
        {
            $url = str_replace('http://', 'https://', $url);
        }

        $asset_output = str_replace("{:url:}", $url."?version=".$this->version, $this->asset_format);

        return $asset_output;
        
    }

    /**
     * print out external asset link as html
     *
     * print out html element for exteral asset such as css, js, less and image
     *
     * @access  public
     * @param   string  asset type
     * @param   string  link of external asset
     * @return  string
     */
    public function external_asset($type, $url)
    {
        $asset_output = "";

        if($url == "")
        {
            return $asset_output;
        }

        $CI =& get_instance();
        $CI->load->config("asset_config");
        $assets_config = $CI->config->item('assets');

        if(isset($assets_config['format']))
        {
            $this->format = $assets_config['format'];
        }
        
        if(isset($this->format[$type]))
        {
            $this->asset_format = $this->format[$type];
        }

        $asset_output = str_replace("{:url:}", $url, $this->asset_format);

        return $asset_output;
    } 

}