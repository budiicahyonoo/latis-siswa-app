<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
    }

    // GET /profile
    public function index()
    {
        $data['title'] = 'Profile';
        $data['user']  = $this->Auth_model->get_by_id($this->user_id);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('profile/index', $data);
        $this->load->view('templates/footer');
    }

    // POST /profile/update_photo
    public function update_photo()
    {
        if (empty($_FILES['photo']['name'])) {
            $this->session->set_flashdata('error', 'Pilih foto terlebih dahulu.');
            redirect('profile');
        }

        $upload_dir = FCPATH . 'uploads/profile/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $config['upload_path']   = $upload_dir;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 100; // KB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('photo')) {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            redirect('profile');
        }

        $upload_data = $this->upload->data();
        $this->Auth_model->update_photo($this->user_id, $upload_data['file_name']);
        $this->session->set_userdata('photo', $upload_data['file_name']);

        $this->session->set_flashdata('success', 'Foto profile berhasil diperbarui.');
        redirect('profile');
    }
}
