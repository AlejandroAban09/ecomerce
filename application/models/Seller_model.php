<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Seller_model extends CI_Model
{

    public function get_seller_profile($user_id)
    {
        $query = $this->db->get_where('seller_profiles', array('user_id' => $user_id));
        return $query->row();
    }

    public function create_store($data)
    {
        return $this->db->insert('seller_profiles', $data);
    }
}
