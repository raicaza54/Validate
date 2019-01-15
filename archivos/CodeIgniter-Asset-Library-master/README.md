CodeIgniter-Asset-Library
=========================

##What is this
This package includes library and helper can manage asset file for CodeIgniter project. 

##Requirements

 - CodeIgniter version > 2.0
 - URL helper in codeigniter

##Installation
The package includes three files
 - asset_config.php copy to application/config
 - asset_helper.php copy to application/helper
 - asset.php copy to application/library


##Asset Configuration
###1. Version number
`$config['assets']['version'] 			= 1;`

The Asset version number is usually to make sure that web browser gets a new version of asset when the site gets updated with a new version asset. e.g.

`asset.css?version=1`

###2. Asset Path
`$config['assets']['path']['css'] 		= "assets/css/";`

`$config['assets']['path']['js'] 		= "assets/js/";`

`$config['assets']['path']['image'] 		= "assets/images/";`

`$config['assets']['path']['less'] 		= "assets/less/";`

Asset path is where project asset file storage at. Normally asset file could be storaged at assets folder in the root of project.


###3. Asset Html Format
`$config['assets']['format']['css'] = '<link type="text/css" rel="stylesheet" href="{:url:}">';`

`$config['assets']['format']['js'] = '<script src="{:url:}" type="text/javascript" charset="utf-8"></script>';`

`$config['assets']['format']['image'] = '<img src="{:url:}">';`

`$config['assets']['format']['less'] = '<link rel="stylesheet/less" type="text/css" href="{:url:}">';`

HTML format that for asset script file in views. 


###4. Auto loading helper and library
Asset library and helper can be auto loading by CodeIgniter project. Add asset library and helper into `application/config/autoload.php`. 


`$autoload['libraries'] 	= array('asset');`

`$autoload['helper'] 		= array('asset_helper');`

##Get Start
###1. Loading Helper
Asset helper can be auto load by autoload config. It also can be loaded on controller like: 
```php
class Welcome extends CI_Controller {
  public function index()
  {
    $this->load->helper("asset_helper");
    // your code
  }
}
```
###2. Add Assets(Helper)
#####Add css asset
`add_asset("css", "1.css");`

#####Add less asset
`add_asset("less", "2.less");`

#####Add image asset
`add_asset("image", "3.jpg");`

#####Add js asset
`add_asset("js", "4.js");`

#####Add multiple CSS Assets 
`add_assets('css', array('1.css', '2.css'))`

#####Add multiple JS Assets 
`add_assets('js', array('1.js', '2.js'))`

#####Add multiple Image Assets 
`add_assets('image', array('1.jpg', '2.png'))`

#####Add multiple LESS Assets 
`add_assets('less', array('1.less', '2.less'))`

###3. Directly Output Assets(Helper)
Directly output assets without controller. following functions can be directly used at view html.
#####Output CSS Asset
`<?php echo asset_css("style/main.css", false); ?>`
The HTML output will be like
`<link type="text/css" rel="stylesheet" href="http://www.website.com/assets/css/style/main.css">`
The base asset path `assets/css` can be changed at asset_config.php, and the output html format could be changed at config file as well. 

Also, the asset https access could be turn of by
`<?php echo asset_css("style/main.css", true); ?>`
The HTML output will be like
`<link type="text/css" rel="stylesheet" href="https://www.website.com/assets/css/style/main.css">`

#####Output JS Asset
`<?php echo asset_js("app/main.js", false); ?>`
The HTML output will be like
`<script src="http://www.website.com/assets/js/app/main.js" type="text/javascript" charset="utf-8"></script>`

#####Output image Asset
`<?php echo asset_image("product/sample.jpg", false); ?>`
The HTML output will be like
`<img src="http://www.website.com/assets/images/" />`

#####Output LESS Asset
`<?php echo asset_less("style/app.less", false); ?>`
The HTML output will be like
`<link rel="stylesheet/less" type="text/css" href="http://www.website.com/assets/less/style/app.less" />`


###3. Output Assets in view(Helper)
After using add asset functions in controller, you need using following function to output asset html in view. 

`<?php echo load_assets('css'); ?>`

Above code will output all css assets that have been added in controller. 

Also, All assets can be output in view by using:

`<?php echo load_assets(); ?>`

###4. Output multiple type asset files in view(Helper)
One function can output all asset files in view.

`<?php echo load_multiple_assets();?>`

Also, filters can be used for output multiple type assets.

`<?php echo load_multiple_assets(array('css', 'less'));?>`


###5. Output external assets in view
Project may need load external assets, Assets come from third party. In this case, following functions can be used in view. 

`<?php echo external_css('http://thirdparty.com/package.css');?>`

`<?php echo external_js('http://thirdparty.com/package.js');?>`

`<?php echo external_image('http://thirdparty.com/package.png');?>`

`<?php echo external_less('http://thirdparty.com/package.less');?>`

