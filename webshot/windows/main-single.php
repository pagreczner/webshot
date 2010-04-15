<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once "WebShot.php";
require_once "ImageApi.php";

$i = new ImageAPI();

if ($argc > 1) {


    try {
        $url_data["url"] = $argv[1];
		$url_data["sizes"] = '200_200,50_50,508_345';
        if (strlen($url_data["url"]) > 0) {
            $w = new WebShot("C:\\isocket_images\\");
            try {
            $status = $w->process($url_data);
            } catch (Exception $e) {
                print "Image Snapshot Exception for ".$url_data["url"];
                print $e->toString()."\n";
            }
			//$status=0;
            //print $status;
			$i->completed_url($url_data, "C:\\isocket_images\\", $status);
        } 
    } catch (Exception $e) {
        print "Image Snapshot Exception";
        print $e->toString()."\n";
    }
    sleep(5);
    
}

else print "No Args"
?>
