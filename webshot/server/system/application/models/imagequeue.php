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

        if ($query->num_rows == 0) {
            $data = array('url' => $url);
            $this->db->insert('image_queue', $data);
        }
    }

    public function get_next_pending_url() {
       $this->db->where('updated_at is NULL');
       $query = $this->db->get('image_queue');

        if ($query->num_rows > 0) {
            $row = $query->result();
            return $row[0]->url;
        }
    }

   public function set_image_completed($url) {
       $data = array(
               'updated_at' => gmdate("Y-m-d H:i:s")
            );

       $this->db->where('url', $url);
       $this->db->update('image_queue', $data);
   }
}
?>
