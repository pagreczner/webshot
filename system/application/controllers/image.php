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

   function Image()
	{
		parent::Base_Controller();
        $this->load->config('image');        
        $this->image_directory = $this->config->item("image_directory");
        $this->default_full_image = $this->config->item("default_full_image");
        $this->default_thumb_image = $this->config->item("default_thumb_image");
        $this->default_508_image = $this->config->item("default_508_image");
	$this->default_268_image = $this->config->item("default_268_image");
	$this->default_200_image = $this->config->item("default_200_image");
	$this->load->model('ImageQueue');
	}

    function index() {
        echo "";
    }

    function test() {
        $url = $this->input->post('url');
  
        if ($url) {
            $this->redirect("/image/full/".base64_encode($url).".jpg");
        } else {
            $this->render();
        }
    }

    function thumb_50_50() {
        $url = $this->get_url_from_uri();

        $this->ImageQueue->register_url($url);

        if ($this->is_refresh_request()) {
            $this->ImageQueue->refresh_url($url);
        }
        
        $image_path = $this->image_directory."/".$this->default_thumb_image;

        if (strlen($url) > 0) {
            if (file_exists($this->image_directory."/".md5($url).".jpg")) {
                $image_path = $this->image_directory."/thumb_50_50_".md5($url).".jpg";
            }
        }

        $this->return_image($image_path);
    }

    function thumb_200_200() {
        $url = $this->get_url_from_uri();

        $this->ImageQueue->register_url($url);

        if ($this->is_refresh_request()) {
            $this->ImageQueue->refresh_url($url);
        }

        $image_path = $this->image_directory."/".$this->default_200_image;

        if (strlen($url) > 0) {
            if (file_exists($this->image_directory."/".md5($url).".jpg")) {
                $image_path = $this->image_directory."/thumb_200_200_".md5($url).".jpg";
            }
        }

        $this->return_image($image_path);
    }


    function thumb_250_250() {
        $url = $this->get_url_from_uri();

        $this->ImageQueue->register_url($url);

        if ($this->is_refresh_request()) {
            $this->ImageQueue->refresh_url($url);
        }

        $image_path = $this->image_directory."/".$this->default_200_image;

        if (strlen($url) > 0) {
            if (file_exists($this->image_directory."/".md5($url).".jpg")) {
                $image_path = $this->image_directory."/thumb_250_250_".md5($url).".jpg";
            }
	}

        $this->return_image($image_path);
    }


    function thumb_508_345() {
        $url = $this->get_url_from_uri();

        $this->ImageQueue->register_url($url);

        if ($this->is_refresh_request()) {
            $this->ImageQueue->refresh_url($url);
        }

        $image_path = $this->image_directory."/".$this->default_508_image;        

        if (strlen($url) > 0) {
            if (file_exists($this->image_directory."/".md5($url).".jpg")) {
                $image_path = $this->image_directory."/thumb_508_345_".md5($url).".jpg";
            }
        }

        $this->return_image($image_path);
    }

	function thumb_268_182() {
        $url = $this->get_url_from_uri();

        $this->ImageQueue->register_url($url);

        if ($this->is_refresh_request()) {
            $this->ImageQueue->refresh_url($url);
        }

        $image_path = $this->image_directory."/".$this->default_268_image;

        if (strlen($url) > 0) {
            if (file_exists($this->image_directory."/".md5($url).".jpg")) {
                $image_path = $this->image_directory."/thumb_268_182_".md5($url).".jpg";
            }
        }

        $this->return_image($image_path);
    }

    function full() {
        $url = $this->get_url_from_uri();
        $this->ImageQueue->register_url($url);

        if ($this->is_refresh_request()) {
            $this->ImageQueue->refresh_url($url);
        }
        $image_path = $this->image_directory."/".$this->default_full_image;

        if (($url) && (strlen($url) > 0)) {
            if (file_exists($this->image_directory."/".md5($url).".jpg")) {
                $image_path = $this->image_directory."/".md5($url).".jpg";
            }
        }
        
        $this->return_image($image_path);
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
        $image_data = file_get_contents($image_path);
        header("Content-Type: image/gif");
        echo $image_data;
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
