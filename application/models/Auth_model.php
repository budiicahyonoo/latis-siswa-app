<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    public function get_by_username($username)
    {
        // prepared statement via query binding ('?')
        $query = $this->db->query(
            "SELECT * FROM users WHERE username = ? LIMIT 1",
            array($username)
        );
        return $query->row_array();
    }

    public function get_by_id($id)
    {
        $query = $this->db->query(
            "SELECT * FROM users WHERE id = ? LIMIT 1",
            array($id)
        );
        return $query->row_array();
    }

    public function update_photo($id, $photo)
    {
        $this->db->query(
            "UPDATE users SET photo = ? WHERE id = ?",
            array($photo, $id)
        );
        return true;
    }
}
