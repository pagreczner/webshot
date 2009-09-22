<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once "WebShot.php";
require_once "ImageApi.php";

$i = new ImageAPI();

while (1) {

    $url = $i->get_next_url();

    if (strlen($url) > 0) {
        $w = new WebShot("C:\\isocket_images\\");
        $w->process($url);
        $i->completed_url($url, "C:\\isocket_images\\");
    } else {
        sleep(5);
    }
}
?>
