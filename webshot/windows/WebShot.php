<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 class WebShot {

     public $output_dir = "";
     public $thumb1_width = 50;
     public $thumb1_height = 50;

     public $thumb2_width = 200;
     public $thumb2_height = 200;

     public $crop_width = 1000;
     public $crop_height = 800;


     public function WebShot($output_dir){
        $this->output_dir = $output_dir;
     }

     public function process($url_data) {
        $url = $url_data["url"];
        $sizes = $url_data["sizes"];
        $status = $this->take_snapshot($url);
        if ($status == "200") {
            //Check if the thumbnail was indeed created
            if ($this->snapshot_exists($url)) {
                $this->crop_image($url, $this->crop_width, $this->crop_height);
                foreach(explode(',', $sizes) as $size) {
                    $size_arr = explode('_', $size);
                    if (sizeof($size_arr) == 2) {
                        $width = $size_arr[0];
                        $height = $size_arr[1];
						if ($height < $width) $this->crop_image($url, $width, $height);
                        $this->make_thumbnail($url, $width, $height);
                    }
                }
            }
        }
        return $status;
     }

     private function crop_image($url, $cwidth, $cheight) {
         $filename = $this->snapshot_file($url);
         list($width, $height) = getimagesize($filename);
         //just incase the original image is smaller than requested
         if ($width < $cwidth) $cwidth = $width;
         if ($height < $cheight) $cheight = $height;
         $target = imagecreatetruecolor($cwidth, $cheight) ;
         $source = imagecreatefromjpeg($filename);
         imagecopyresampled($target, $source, 0, 0, 0, 0, $cwidth, $cheight, $cwidth, $cheight);
         imagejpeg($target, $filename, 100);
     }
     private function make_thumbnail($url, $thumb_width, $thumb_height) {
        $filename = $this->snapshot_file($url);
        $thumb_filename = $this->thumbnail_file($url, $thumb_width, $thumb_height);
        list($width, $height) = getimagesize($filename);
        $tn = imagecreatetruecolor($thumb_width, $thumb_height) ;
        $image = imagecreatefromjpeg($filename) ;
        imagecopyresampled($tn, $image, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height) ;
        imagejpeg($tn, $thumb_filename, 100) ;
     }
     private function snapshot_exists($url) {
         return file_exists($this->snapshot_file($url));
     }

     private function snapshot_file($url) {
         return $this->output_dir."\\".md5($url).".jpg";
     }

     private function thumbnail_file($url, $width, $height) {
         return $this->output_dir."\\thumb_".$width."_".$height."_".md5($url).".jpg";
     }
     private function take_snapshot($url) {

        $error = "";
        $http_status = "";

        $webshot = new COM("{B10527B6-F84A-499f-873C-ABAF0DC1D696}");
        $webshot->DllInit($this->output_dir."\\debug.log", 2);
        $handle = $webshot->Create();
        $webshot->SetBrowserWidthMinimum($handle, 1000);
		$webshot->SetBrowserWidthMaximum($handle, 1000); // Inserted to fix a bug where a site widens the browser, making the API capture a blank corner
        $webshot->SetBrowserHeightMinimum($handle, 1000);
		$webshot->SetBrowserHeightMaximum($handle, 1000); // Inserted to save time on sites which are super long - like blogs
        $webshot->SetBrowserVisible($handle, 1);
        $webshot->SetPageTimeout($handle, 120); //increased to 120 from 60 as some sites fail to load by 60 sec
        $webshot->SetVerbose($handle, 1);
		$webshot->SetImageHeight($handle, 508); //The largest image now needed is 508x345px so no need to save a image bigger than that.
		$webshot->SetImagewidth($handle, 508);
		//$webshot->SetDisableScripts  ($handle, 1);

		
        $webshot->SetOutputPath($handle, $this->output_dir."\\%m.jpg");
        echo "Taking Snapshot of ".$url."\n";
        $webshot->Open($handle, $url);
        $error = (string) $webshot->GetError($handle);
        $http_status = (string) $webshot->GetHttpCode($handle);
        $webshot->Destroy($handle);
        $webshot->DllUninit();
        $webshot = null;
        if (strlen($error) > 0 ) {
            //return $error;
            return "404";
        } else {
            return $http_status;
        }

     }
 }
?>
