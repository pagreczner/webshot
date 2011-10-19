<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Image
 *
 * @author root
 */

require(APPPATH.'/libraries/Base_Controller.php');

class Image extends Base_Controller{
    //put your code here

   public $image_directory = null;
   public $default_full_image = null;
   public $default_thumb_image = null;

   function __construct()
	{
		parent::__construct();
    $this->load->config('image');        
    $this->image_directory = realpath($this->config->item("image_directory"));
    $this->defaults_directory = realpath($this->config->item("defaults_directory"));
    $this->sizes = $this->config->item("sizes");
	  $this->load->model('ImageQueue');
	}

    private function thumb($width, $height)
    {
      $url = $this->get_url_from_uri();
      if (! ImageQueue::isValidURL($url)){

          show_404('file does not exist, invalid url '.$url);
      }
      $this->ImageQueue->register_url($url);

      if ($this->is_refresh_request()) {
        $this->ImageQueue->refresh_url($url);
      }
      $image_path = $this->defaults_directory."/".$this->sizes[$width.'_'.$height];
      $filename = $this->image_directory."/thumb_".$width."_".$height."_".md5($url).".jpg";
      if (file_exists($filename)){ 
        $image_path = $filename;
      }
      $this->return_image($image_path);
    }
    function thumb_50_50(){
      return $this->thumb(50,50);
    }
    function thumb_200_200(){
      return $this->thumb(200,200);
    }
    function thumb_508_345() {
        return $this->thumb(508,345);
    }
    function thumb_268_182(){
      return $this->thumb(268,182);
    }
    function full() {
        return $this->thumb();
    }


    /* Private functions */
    private function get_url_from_uri() {

        if ($this->uri->total_segments() > 2) {
            $base64_url = preg_replace("/.jpg$/", "", $this->uri->segment(3));
            return base64_decode($base64_url);
        } else {
            return null;
        }
    }

    private function return_image($image_path) {
        if( !file_exists($image_path)){ 
          show_404('file does not exist, make sure you configure the default images correctly');
        }
        $path_parts = pathinfo($image_path);
        $image_data = file_get_contents($image_path);
        header("Content-Type: image/".$path_parts['extension']);
        print $image_data;
    }

    private function is_refresh_request() {    	    	
    	    	
        if ($this->uri->total_segments() > 3) {
            if ($this->uri->segment(4) == "refresh") {
                return true;
            }
        }
        return false;
    }

}
?>
