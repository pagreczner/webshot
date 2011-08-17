<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Image Directory
|--------------------------------------------------------------------------
*/
// where to store the created the thumbnails
$config['image_directory'] = BASEPATH.'/../images';

// where to find the default images
$config['default_directory'] = BASEPATH.'/../defaults';

/*
 | supported size and their default images
 | keys must in the format of $width_$height
 */
$config['sizes']= array();
$config['sizes']['508_345']='default_508.png';
$config['sizes']['50_50']='default_thumb.jpg';
$config['sizes']['200_200']='default_200.jpg';
$config['sizes']['268_182']='default_268.png';
$config['full']=array('width'=>1024,'height'=>768,'default' => 'default_full.jpg');

// where is the command for Xvfb and firefox
$config['image_firefox'] = '/usr/bin/firefox';
$config['image_xvfb'] = '/usr/bin/Xvfb';
$config['image_convert'] = '/usr/bin/convert';
