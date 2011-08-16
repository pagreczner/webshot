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

    public $image_directory = '';
    public $image_sizes = array('50_50','200_200','508_345','268_182');
    function __construct() {
        parent::__construct();
        $this->load->model('ImageQueue');
        $this->load->config('image');
        $this->image_directory = realpath($this->config->item("image_directory"));
        $this->Xvfb = realpath($this->config->item("image_xvfb"));
        $this->firefox = realpath($this->config->item("image_firefox"));
        $this->image_sizes = array_keys($this->config->item("sizes"));
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
    public function capture($url=null, $log = null)
    {
      $log= ($log)?$log: '/dev/null';
      if(! $url) $url = $this->ImageQueue->get_next_pending_url();
      if(! $url) return false;
      $filename = $this->image_directory.'/'.md5($url).'.png';
      // start X11 screen
      $code = null;
      system($this->Xvfb.' :5 -screen 0 1024x768x24 &> '.$log.' &', $code);
      system('DISPLAY=:5.0 firefox -no-remote -width 900 -height 768 '.$url.' &>'.$log.' &', $code);
      sleep(15);
      system('DISPLAY=:5.0 import -window root '.$filename, $code);
      system('mogrify -crop 885x587+0+157 '.$filename);
      if(file_exists($filename))return $url; 
      return false;
    }
    public function generate($url)
    {
      $origin = $this->image_directory.'/'.md5($url).'.png';
      if(! file_exists($origin)) {
        echo 'file does not exist';
        return;
      }
      foreach( self::$image_sizes as $size)
      {
        $filename = $this->image_directory.'/thumb_'.$size.'_'.md5($url).'.jpg';
        system('convert '.$origin.' -resize '.str_replace('_','x',$size).' '.$filename); 
      }
    }
    public function index()
    {
      $this->clean();
      while($url = $this->capture()){
        $this->generate($url);
        $this->clean();
        $this->ImageQueue->set_image_completed($url, 200);
      } 
    }
}
?>
