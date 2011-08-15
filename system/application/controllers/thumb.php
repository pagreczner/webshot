<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of api
 *
 * @author root
 */
class Thumb extends CI_Controller {

    public $image_directory = null;

    public static $image_sizes = array('200x200','50x50','508x345','268x182');

    function __construct() {
        parent::__construct();
        $this->load->model('ImageQueue');
        $this->load->config('image');
        $this->image_directory = realpath($this->config->item("image_directory"));
        $this->image_tmp_dir = realpath($this->config->item("image_tmp_dir"));
        $this->Xvfb = realpath($this->config->item("image_xvfb"));
        $this->firefox = realpath($this->config->item("image_firefox"));
    }
    public function clean()
    {
      // delete  tmp files
      $mask = $this->image_tmp_dir.'/temp*';
      array_map( "unlink", glob( $mask ) );
      
      // stop Xvfb & firefox
      system('killall -9 Xvfb');
      system('killall -9 firefox');
      
      // remove left ove locks
      unlink('/tmp/.X5-lock');
      system('rm -rf /root/.mozilla/firefox/*');
    }
    public function capture($url, $log = null)
    {
      $log= ($log)?$log: '/dev/null';
      if(! $url) $url = $this->ImageQueue->get_next_pending_url();
      $filename = $this->image_directory.'/'.md5($url).'.png';
      // start X11 screen
      $code = null;
      system($this->Xvfb.' :5 -screen 0 1024x768x24 &> '.$log.' &', $code);
      system('DISPLAY=:5.0 firefox -no-remote -width 900 -height 768 '.$url.' &>'.$log.' &', $code);
      sleep(15);
      system('DISPLAY=:5.0 import -window root '.$filename, $code);
      $size = filesize($filename);
    }
    public function generate($url)
    {
      $origin = $this->image_directory.'/'.md5($url).'.png';
      foreach( self::$image_sizes as $size)
      {
        $filename = $this->image_directory.'/thumb_'.str_replace('x','_',$size).'_'.md5($url).'jpg';
        system('convert '.$origin.' -resize '.$size.' '.$filename); 
      }
    }
}
?>
