<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Siswa_model');
        $this->load->model('Lembaga_model');
    }

    // GET /siswa -> halaman utama datatable siswa
    public function index()
    {
        $data['title']   = 'Data Siswa';
        $data['lembaga'] = $this->Lembaga_model->get_all();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('siswa/index', $data);
        $this->load->view('templates/footer');
    }

    // GET /siswa/ajax_list -> dipanggil oleh DataTables (server-side processing)
    public function ajax_list()
    {
        $draw       = (int) $this->input->get('draw');
        $start      = (int) $this->input->get('start');
        $length     = (int) $this->input->get('length');
        $search     = trim($this->input->get('search_value')); // search khusus NIS & Nama
        $lembaga_id = trim($this->input->get('lembaga_id'));

        $length = $length > 0 ? $length : 10;

        $list  = $this->Siswa_model->get_datatable($search, $lembaga_id, $length, $start);
        $total_filtered = $this->Siswa_model->count_filtered($search, $lembaga_id);
        $total_all      = $this->Siswa_model->count_all();

        $data = array();
        $no = $start + 1;
        foreach ($list as $row) {
            $foto_url = $row['foto']
                ? base_url('uploads/siswa/' . $row['foto'])
                : base_url('assets/img/no-image.png');

            $data[] = array(
                'no'           => $no++,
                'nis'          => htmlspecialchars($row['nis']),
                'nama_siswa'   => htmlspecialchars($row['nama_siswa']),
                'email'        => htmlspecialchars($row['email']),
                'nama_lembaga' => htmlspecialchars($row['nama_lembaga']),
                'foto'         => $foto_url,
                'action'       => '<a href="' . base_url('siswa/edit/' . $row['id']) . '" class="btn btn-sm btn-primary">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>
                                    <a href="' . base_url('siswa/delete/' . $row['id']) . '" class="btn btn-sm btn-danger btn-delete" data-nama="' . htmlspecialchars($row['nama_siswa']) . '">
                                        <i class="fa fa-trash"></i> Hapus
                                    </a>',
            );
        }

        $output = array(
            'draw'            => $draw,
            'recordsTotal'    => $total_all,
            'recordsFiltered' => $total_filtered,
            'data'            => $data,
        );

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($output));
    }

    // GET /siswa/add -> form tambah
    public function add()
    {
        $data['title']   = 'Tambah Siswa';
        $data['lembaga'] = $this->Lembaga_model->get_all();
        $data['siswa']   = null;
        $data['errors']  = array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('siswa/form', $data);
        $this->load->view('templates/footer');
    }

    // POST /siswa/store -> simpan data siswa baru
    public function store()
    {
        $errors = $this->validate_input(null);

        // upload foto (wajib saat tambah data)
        $foto_name = null;
        if (empty($_FILES['foto']['name'])) {
            $errors['foto'] = 'Foto wajib diupload.';
        } else {
            $upload_result = $this->handle_upload();
            if ($upload_result['error']) {
                $errors['foto'] = $upload_result['error'];
            } else {
                $foto_name = $upload_result['file_name'];
            }
        }

        if (!empty($errors)) {
            $data['title']   = 'Tambah Siswa';
            $data['lembaga'] = $this->Lembaga_model->get_all();
            $data['siswa']   = $this->input->post();
            $data['errors']  = $errors;

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('siswa/form', $data);
            $this->load->view('templates/footer');
            return;
        }

        $this->Siswa_model->insert(array(
            'nis'        => $this->input->post('nis', TRUE),
            'nama_siswa' => $this->input->post('nama_siswa', TRUE),
            'email'      => $this->input->post('email', TRUE),
            'lembaga_id' => $this->input->post('lembaga_id', TRUE),
            'foto'       => $foto_name,
        ));

        $this->session->set_flashdata('success', 'Data siswa berhasil ditambahkan.');
        redirect('siswa');
    }

    // GET /siswa/edit/(id) -> form edit
    public function edit($id)
    {
        $siswa = $this->Siswa_model->get_by_id($id);
        if (!$siswa) {
            show_404();
        }

        $data['title']   = 'Edit Siswa';
        $data['lembaga'] = $this->Lembaga_model->get_all();
        $data['siswa']   = $siswa;
        $data['errors']  = array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('siswa/form', $data);
        $this->load->view('templates/footer');
    }

    // POST /siswa/update/(id) -> simpan perubahan data siswa
    public function update($id)
    {
        $siswa = $this->Siswa_model->get_by_id($id);
        if (!$siswa) {
            show_404();
        }

        $errors = $this->validate_input($id);

        $foto_name = null;
        if (!empty($_FILES['foto']['name'])) {
            $upload_result = $this->handle_upload();
            if ($upload_result['error']) {
                $errors['foto'] = $upload_result['error'];
            } else {
                $foto_name = $upload_result['file_name'];
            }
        }

        if (!empty($errors)) {
            $data['title']   = 'Edit Siswa';
            $data['lembaga'] = $this->Lembaga_model->get_all();
            $data['siswa']   = array_merge($siswa, $this->input->post());
            $data['siswa']['id'] = $id;
            $data['errors']  = $errors;

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('siswa/form', $data);
            $this->load->view('templates/footer');
            return;
        }

        $this->Siswa_model->update($id, array(
            'nis'        => $this->input->post('nis', TRUE),
            'nama_siswa' => $this->input->post('nama_siswa', TRUE),
            'email'      => $this->input->post('email', TRUE),
            'lembaga_id' => $this->input->post('lembaga_id', TRUE),
            'foto'       => $foto_name, // null berarti foto lama dipertahankan
        ));

        // hapus foto lama kalau ada foto baru
        if ($foto_name && !empty($siswa['foto'])) {
            $old_path = FCPATH . 'uploads/siswa/' . $siswa['foto'];
            if (file_exists($old_path)) {
                @unlink($old_path);
            }
        }

        $this->session->set_flashdata('success', 'Data siswa berhasil diperbarui.');
        redirect('siswa');
    }

    // GET /siswa/delete/(id)
    public function delete($id)
    {
        $siswa = $this->Siswa_model->get_by_id($id);
        if ($siswa) {
            if (!empty($siswa['foto'])) {
                $path = FCPATH . 'uploads/siswa/' . $siswa['foto'];
                if (file_exists($path)) {
                    @unlink($path);
                }
            }
            $this->Siswa_model->delete($id);
            $this->session->set_flashdata('success', 'Data siswa berhasil dihapus.');
        }
        redirect('siswa');
    }

    // GET /siswa/export_excel -> export sesuai hasil search & filter yang aktif
    public function export_excel()
    {
        $search     = trim($this->input->get('search_value'));
        $lembaga_id = trim($this->input->get('lembaga_id'));

        $data = $this->Siswa_model->get_all_filtered($search, $lembaga_id);

        $filename = 'data_siswa_' . date('Ymd_His') . '.xls';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        echo '<table border="1">';
        echo '<tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Email</th>
                <th>Lembaga</th>
              </tr>';

        $no = 1;
        foreach ($data as $row) {
            echo '<tr>';
            echo '<td>' . $no++ . '</td>';
            echo '<td>' . htmlspecialchars($row['nis']) . '</td>';
            echo '<td>' . htmlspecialchars($row['nama_siswa']) . '</td>';
            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
            echo '<td>' . htmlspecialchars($row['nama_lembaga']) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        exit;
    }

    /**
     * Validasi input form siswa (dipakai add & edit)
     */
    private function validate_input($exclude_id)
    {
        $errors = array();

        $nis        = $this->input->post('nis', TRUE);
        $nama_siswa = $this->input->post('nama_siswa', TRUE);
        $email      = $this->input->post('email', TRUE);
        $lembaga_id = $this->input->post('lembaga_id', TRUE);

        if (empty($nis)) {
            $errors['nis'] = 'NIS wajib diisi.';
        } elseif (!ctype_digit($nis)) {
            $errors['nis'] = 'NIS harus berupa angka.';
        } elseif ($this->Siswa_model->is_nis_exists($nis, $exclude_id)) {
            $errors['nis'] = 'NIS sudah digunakan siswa lain.';
        }

        if (empty($nama_siswa)) {
            $errors['nama_siswa'] = 'Nama siswa wajib diisi.';
        }

        if (empty($email)) {
            $errors['email'] = 'Email wajib diisi.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid.';
        }

        if (empty($lembaga_id)) {
            $errors['lembaga_id'] = 'Lembaga wajib dipilih.';
        }

        return $errors;
    }

    /**
     * Handle upload foto: hanya JPG/PNG, maksimal 100KB
     */
    private function handle_upload()
    {
        $upload_dir = FCPATH . 'uploads/siswa/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $config['upload_path']   = $upload_dir;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 100; // KB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('foto')) {
            return array('error' => $this->upload->display_errors('', ''), 'file_name' => null);
        }

        $upload_data = $this->upload->data();
        return array('error' => null, 'file_name' => $upload_data['file_name']);
    }
}
