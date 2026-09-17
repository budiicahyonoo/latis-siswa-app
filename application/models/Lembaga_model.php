<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lembaga_model extends CI_Model
{
    public function get_all()
    {
        $query = $this->db->query("SELECT * FROM lembaga ORDER BY nama_lembaga ASC");
        return $query->result_array();
    }

    public function get_by_id($id)
    {
        $query = $this->db->query("SELECT * FROM lembaga WHERE id = ? LIMIT 1", array($id));
        return $query->row_array();
    }
}
