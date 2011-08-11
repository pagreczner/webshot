<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Base_Controller
 *
 * @author root
 */
class Base_Controller extends CI_Controller {
    //put your code here

    protected $data = array();
    protected $views = array();
    protected $layout = "layouts/default";

    protected $partial = "blank";
    protected $page_title = "ISocket Images";

    function Base_Controller() {
    	    	
        parent::__construct();

        $this->load->helper('url');
        
         // set the default partial
        $this->_get_partial();
    }


    function _get_partial() {
		$uri = $this->router->class . '/' . $this->router->method;
		if (is_file(realpath(dirname(__FILE__) . '/../views/' . $uri . EXT))) {
			$this->partial = $this->router->class . '/' . $this->router->method;
		}
	}

    function redirect($url) {
        header("Location: ".$url);
    }
    
    function render() {
        $this->views['main'] = $this->partial;
        $this->views['title'] = $this->page_title;
        $this->load->view($this->layout, array('data'=>$this->data, 'views'=>$this->views));
    }
}
?>
