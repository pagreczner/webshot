<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ImageQueue
 *
 * @author root
 */
class Imagequeue extends Model{

    public function Imagequeue() {
        parent::Model();
    }

    public function register_url($url) {
        $this->db->where('url', $url);
        $query = $this->db->get('image_queue');

        if (!empty($url) && $query->num_rows == 0) {
            $data = array('url' => $url);
            $this->db->insert('image_queue', $data);
        }
    }

    public function refresh_url($url) {
        $this->db->where('url', $url);
        $query = $this->db->get('image_queue');

        if ($query->num_rows > 0) {
             $data = array(
               'http_status' => "",
               'num_tries' => 0
            );

            $this->db->where('url', $url);
            $this->db->update('image_queue', $data);
        }
    }

    public function get_next_pending_url() {
       $this->db->where('http_status != "200" and num_tries < 4')->order_by('RAND()',"asc");
       $query = $this->db->get('image_queue');

        if ($query->num_rows > 0) {
            $row = $query->result();
            return $row[0]->url;
        }

        else {
               $this->db->where('http_status = "0"');
               $query = $this->db->get('image_queue');

                if ($query->num_rows > 0) {
            $row = $query->result();
            return $row[0]->url;
         	}
        }



    }

   public function set_image_completed($url, $http_status) {
       // get the current num_tries
       $this->db->where('url', $url);
       $query = $this->db->get('image_queue');
       $num_tries = 0;

        if ($query->num_rows > 0) {
            $row = $query->result();
            $num_tries = $row[0]->num_tries;
        }

       $data = array(
               'updated_at' => gmdate("Y-m-d H:i:s"),
               'http_status' => $http_status,
               'num_tries' => ($num_tries + 1)
            );

       $this->db->where('url', $url);
       $this->db->update('image_queue', $data);
   }
}
?>