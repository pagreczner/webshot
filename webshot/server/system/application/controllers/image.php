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

class Image extends Controller{
    //put your code here

   public $image_directory = null;
   public $default_full_image = null;
   public $default_thumb_image = null;

   function Image()
	{
		parent::Controller();
        $this->load->config('image');
        $this->image_directory = $this->config->item("image_directory");
        $this->default_full_image = $this->config->item("default_full_image");
        $this->default_thumb_image = $this->config->item("default_thumb_image");
        $this->load->model('ImageQueue');
	}

    function index() {
        echo "";
    }

    function thumb() {
        $domain = $this->get_domain_from_uri();

        $this->ImageQueue->register_url($domain);
        
        $image_path = $this->image_directory."/".$this->default_thumb_image;

        if (strlen($domain) > 0) {
            if (file_exists($this->image_directory."/".md5($domain).".jpg")) {
                $image_path = $this->image_directory."/thumb_".md5($domain).".jpg";
            }
        }

        $this->return_image($image_path);
    }

    function full() {
        $domain = $this->get_domain_from_uri();
        $image_path = $this->image_directory."/".$this->default_full_image;

        if (($domain) && (strlen($domain) > 0)) {
            if (file_exists($this->image_directory."/".md5($domain).".jpg")) {
                $image_path = $this->image_directory."/".md5($domain).".jpg";
            }
        }
        
        $this->return_image($image_path);
        
    }
    /* Private functions */
    private function get_domain_from_uri() {

        if ($this->uri->total_segments() > 2) {
            //remove the .gif at the end
            return "http://".preg_replace("/.jpg$/", "", $this->uri->segment(3));
        } else {
            return null;
        }
    }

    private function return_image($image_path) {
        $image_data = file_get_contents($image_path);
        header("Content-Type: image/gif");
        echo $image_data;
    }

}
?>
