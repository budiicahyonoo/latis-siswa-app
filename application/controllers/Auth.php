<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
    }

    // GET /auth  -> tampilkan form login (kalau sudah login, langsung ke siswa)
    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('siswa');
        }
        $data['title'] = 'Login';
        $this->load->view('auth/login', $data);
    }

    // POST /auth/login
    public function login()
    {
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data['title'] = 'Login';
            $data['error'] = validation_errors();
            $this->load->view('auth/login', $data);
            return;
        }

        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);

        $user = $this->Auth_model->get_by_username($username);

        if ($user && password_verify($password, $user['password'])) {
            $session_data = array(
                'user_id'    => $user['id'],
                'username'   => $user['username'],
                'nama'       => $user['nama'],
                'position'   => $user['position'],
                'photo'      => $user['photo'],
                'logged_in'  => TRUE,
            );
            $this->session->set_userdata($session_data);
            redirect('siswa');
        } else {
            $data['title'] = 'Login';
            $data['error'] = 'Username atau password salah.';
            $this->load->view('auth/login', $data);
        }
    }

    // GET /auth/logout
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
