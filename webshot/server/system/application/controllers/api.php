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

    function Api() {
        parent::REST_Controller();
        $this->load->model('ImageQueue');
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
        
        $message = array('url' => $this->post('url'), 'message' => 'Completed!');

        $this->response($message, 200); // 200 being the HTTP response code
    }

}
?>
