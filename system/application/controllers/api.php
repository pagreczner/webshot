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

require(APPPATH.'/libraries/REST_Controller.php');


class Api extends REST_Controller {

    public $image_directory = null;

    public $image_sizes = "200_200,50_50,508_345,268_182,275_175,275_165";

    function __construct() {
        parent::REST_Controller();
        $this->load->model('ImageQueue');
        $this->load->config('image');
        $this->image_directory = $this->config->item("image_directory");               
    }
    

	function next_pending_url_get(){

        $url = $this->ImageQueue->get_next_pending_url();
        $urls = array(
			array('url' => $url, 'sizes' => $this->image_sizes),
		);
        
        $this->response($urls, 200); // 200 being the HTTP response code        
    }     
    
	function next_simple_url_get(){
        $url = $this->ImageQueue->get_next_pending_url();        
        $this->output->set_status_header(200);
        $this->output->set_output( $url );        
    }    
        
    function completed_url_post() {
        //$this->some_model->updateUser( $this->get('id') );

        $this->ImageQueue->set_image_completed($this->post('url'), $this->post('http_status'));

        // get the normal image

        if ($this->post('full_image') != null) {
            $this->save_full_image($this->post('url'), $this->post('full_image'));
        }

        // get the thumbnail image
        if ($this->post('thumb_50_50_image') != null) {
            $this->save_thumb_image($this->post('url'), $this->post('thumb_50_50_image'), "50_50");
        }

        // get the thumbnail image
        if ($this->post('thumb_200_200_image') != null) {
            $this->save_thumb_image($this->post('url'), $this->post('thumb_200_200_image'), "200_200");
        }

        if ($this->post('thumb_250_250_image') != null) {
            $this->save_thumb_image($this->post('url'), $this->post('thumb_250_250_image'), "250_250");
        }


        if ($this->post('thumb_508_345_image') != null) {
            $this->save_thumb_image($this->post('url'), $this->post('thumb_508_345_image'), "508_345");
        }

	if ($this->post('thumb_268_182_image') != null) {
            $this->save_thumb_image($this->post('url'), $this->post('thumb_268_182_image'), "268_182");
        }

        if ($this->post('thumb_275_175_image') != null) { 
            $this->save_thumb_image($this->post('url'), $this->post('thumb_275_175_image'), "275_175");
        }

        if ($this->post('thumb_275_165_image') != null) { 
            $this->save_thumb_image($this->post('url'), $this->post('thumb_275_165_image'), "275_165");
        }

        
        $message = array('url' => $this->post('url'), 'message' => 'Completed!');

        $this->response($message, 200); // 200 being the HTTP response code
    }


	function completed_no_windows_post()
	{
	    $this->ImageQueue->set_image_completed($this->post('url'), $this->post('status'));
	    
        if ($this->post('full') != null) {
            $this->copy_full_image($this->post('url'), $this->post('full'));
        }

        // get the thumbnail image
        if ($this->post('t50') != null) {
            $this->copy_thumb_image($this->post('url'), $this->post('t50'), "50_50");
        }

        // get the thumbnail image
        if ($this->post('t200') != null) {
            $this->copy_thumb_image($this->post('url'), $this->post('t200'), "200_200");
        }

        if ($this->post('t250') != null) {
            $this->copy_thumb_image($this->post('url'), $this->post('t250'), "250_250");
        }

        if ($this->post('t508') != null) {
            $this->copy_thumb_image($this->post('url'), $this->post('t508'), "508_345");
        }

	if ($this->post('t268') != null) {
            $this->copy_thumb_image($this->post('url'), $this->post('t268'), "268_182");
        }

        if ($this->post('t275') != null) { 
            $this->copy_thumb_image($this->post('url'), $this->post('t275'), "275_175");
        }

        if ($this->post('tt275') != null) {
            $this->copy_thumb_image($this->post('url'), $this->post('tt275'), "275_165");
        }

        $message = array('url' => $this->post('url'), 'message' => 'Completed!');

        $this->response($message, 200); // 200 being the HTTP response code   
	    
	}
	
    private function copy_full_image($url, $path) {
        $image_path = $this->image_directory."/".md5($url).".jpg";        
        copy($path,$image_path);
    }	
    
    private function copy_thumb_image($url, $path, $type) {
        $image_path = $this->image_directory."/thumb_".$type."_".md5($url).".jpg";
        copy($path,$image_path);
    }    

    private function save_full_image($url, $data) {
        $image_path = $this->image_directory."/".md5($url).".jpg";
        $file = fopen($image_path, "w");
        fwrite($file, base64_decode($data));
        fclose($file);
    }

    private function save_thumb_image($url, $data, $type) {
        $image_path = $this->image_directory."/thumb_".$type."_".md5($url).".jpg";
        $file = fopen($image_path, "w");
        fwrite($file, base64_decode($data));
        fclose($file);
    }

}
?>
