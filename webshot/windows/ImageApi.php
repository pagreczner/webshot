<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ImageAPI {

    public $next_pending_url = "http://192.168.23.13:7003/api/next_pending_url";
    public $completed_url = "http://192.168.23.13:7003/api/completed_url";


    public function ImageAPI() {

    }

    public function get_next_url() {
        $xml = simplexml_load_file($this->next_pending_url);
        if (($xml->item) && ($xml->item->url) && ($xml->item->url != "")) {
            return (string)$xml->item->url;
        }
        return null;
    }

    public function completed_url($url) {

        $ch = curl_init($this->completed_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "url=".urlencode($url));
        $data = curl_exec($ch);

        curl_close($ch);
        print $data;
    }
}
?>
