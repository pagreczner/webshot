<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Image Directory
|--------------------------------------------------------------------------
*/
$config['image_directory'] = BASEPATH.'/../images';
$config['image_tmp_dir'] = BASEPATH.'/../tmp';
$config['image_firefox'] = '/usr/bin/firefox';
$config['image_xvfb'] = '/usr/bin/Xvfb';
$config['sizes']= array();
$config['sizes']['508_345']='default_508.png';
$config['sizes']['50_50']='default_thumb.jpg';
$config['sizes']['200_200']='default_200.jpg';
$config['sizes']['268_182']='default_268.png';
$config['full']=array('width'=>1024,'height'=>768,'default' => 'default_full.jpg');
