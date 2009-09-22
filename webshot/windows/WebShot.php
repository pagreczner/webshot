<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 class WebShot {

     public $output_dir = "";
     public $thumb_width = 50;
     public $thumb_height = 50;

     public $crop_width = 430;
     public $crop_height = 420;


     public function WebShot($output_dir){
        $this->output_dir = $output_dir;
     }

     public function process($url) {
        $this->take_snapshot($url);
        //Check if the thumbnail was indeed created
        if ($this->snapshot_exists($url)) {
            $this->crop_image($url, $this->crop_width, $this->crop_height);
            $this->make_thumbnail($url, $this->thumb_width, $this->thumb_height);
        }
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
        $thumb_filename = $this->thumbnail_file($url);
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

     private function thumbnail_file($url) {
         return $this->output_dir."\\thumb_".md5($url).".jpg";
     }
     private function take_snapshot($url) {
        $webshot = new COM("{B10527B6-F84A-499f-873C-ABAF0DC1D696}");
        $webshot->DllInit($this->output_dir."\\debug.log", 2);
        $handle = $webshot->Create();
        $webshot->SetBrowserWidthMinimum($handle, 1000);
        $webshot->SetBrowserHeightMinimum($handle, 1000);
        $webshot->SetBrowserVisible($handle, 0);
        $webshot->SetVerbose($handle, 1);
        $webshot->SetOutputPath($handle, $this->output_dir."\\%m.jpg");
        echo "Taking Snapshot of ".$url."\n";
        $webshot->Open($handle, $url);
        $webshot->Destroy($handle);
        $webshot->DllUninit();
        $webshot = null;
     }
 }
?>
