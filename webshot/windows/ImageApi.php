<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ImageAPI {

    public $next_pending_url = "http://imageq.isocket.com/api/next_pending_url";
    public $completed_url = "http://imageq.isocket.com/api/completed_url";


    public function ImageAPI() {

    }

    public function get_next_url() {
        $xml = simplexml_load_file($this->next_pending_url);
        if (($xml->item) && ($xml->item->url)) {
            return array("url" => (string)$xml->item->url, "sizes" => (string)$xml->item->sizes);
        }
        return null;
    }

    public function completed_url($url_data, $image_directory, $status) {
        $url = $url_data["url"];
        $sizes = $url_data["sizes"];
        if ($status == "200") {
            // Check if the full image was created.
            $full_image_path =  $image_directory."\\".md5($url).".jpg";
            $full_image_file = fopen($full_image_path, "r");
            $full_image_data = fread($full_image_file, filesize($full_image_path));
            fclose($full_image_file);
            
            /*
            $thumb1_image_path =  $image_directory."\\thumb_50_50_".md5($url).".jpg";
            $thumb2_image_path =  $image_directory."\\thumb_200_200_".md5($url).".jpg";

            

            $thumb1_image_file = fopen($thumb1_image_path, "r");
            $thumb1_image_data = fread($thumb1_image_file, filesize($thumb1_image_path));
            fclose($thumb1_image_file);

            $thumb2_image_file = fopen($thumb2_image_path, "r");
            $thumb2_image_data = fread($thumb2_image_file, filesize($thumb2_image_path));
            fclose($thumb2_image_file);*/

            $post_data = array ( "url" => $url,
                                 "full_image" => base64_encode($full_image_data),
                                 "http_status" => $status);
            foreach (explode(",", $sizes) as $size) {
                $thumb_image_path = $image_directory."\\thumb_".$size."_".md5($url).".jpg";

                echo "$thumb_image_path\n";
                $thumb_image_file = fopen($thumb_image_path, "r");
                $post_data["thumb_".$size."_image"] = base64_encode(fread($thumb_image_file, filesize($thumb_image_path)));
                fclose($thumb_image_file);
            }
        } else {
            $post_data = array ( "url" => $url,
                             "full_image" => "",
                             "http_status" => $status);
        }
        $ch = curl_init($this->completed_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $data = curl_exec($ch);
        curl_close($ch);
    }
}
?>
