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

    function Api() {
        parent::REST_Controller();
        $this->load->model('ImageQueue');
        $this->load->config('image');
        $this->image_directory = $this->config->item("image_directory");
    }

	function next_pending_url_get(){

        $url = $this->ImageQueue->get_next_pending_url();
        $urls = array(
			array('url' => $url),
		);

        if($urls)
        {
            $this->response($urls, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(NULL, 404);
        }
    }

    function completed_url_post() {
        //$this->some_model->updateUser( $this->get('id') );

        $this->ImageQueue->set_image_completed($this->post('url'));

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

        
        $message = array('url' => $this->post('url'), 'message' => 'Completed!');

        $this->response($message, 200); // 200 being the HTTP response code
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
