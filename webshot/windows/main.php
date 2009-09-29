<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once "WebShot.php";
require_once "ImageApi.php";

$i = new ImageAPI();

while (1) {

    $url_data = $i->get_next_url();

    print_r($url_data["url"]);

    if (strlen($url_data["url"]) > 0) {
        $w = new WebShot("C:\\isocket_images\\");
        $status = $w->process($url_data);
        $i->completed_url($url_data, "C:\\isocket_images\\", $status);
    } else {
        sleep(5);
    }
}
?>
