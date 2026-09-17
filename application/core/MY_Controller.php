<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base controller: memastikan user sudah login (session aktif)
 * sebelum bisa mengakses halaman apapun kecuali Auth (login).
 */
class MY_Controller extends CI_Controller
{
    protected $user_id;
    protected $username;
    protected $nama;

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        $this->user_id  = $this->session->userdata('user_id');
        $this->username = $this->session->userdata('username');
        $this->nama     = $this->session->userdata('nama');
    }
}
