<?php
/**
 * Generate thumbnails
 *
 * @author Alicia Tang
 */

require(APPPATH.'/libraries/REST_Controller.php');


class Thumb extends CI_Controller {

    public $image_directory = null;

    public $image_sizes = "200_200,50_50,508_345,268_182";
    
    public function message($to = 'World')
    {
        echo "Hello {$to}!".PHP_EOL;
    }
    public function index()
    {
      echo "abc";
    }
}
?>
