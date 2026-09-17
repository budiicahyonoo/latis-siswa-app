<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_model extends CI_Model
{
    protected $table = 'siswa';

    /**
     * Query dasar join siswa + lembaga, dengan filter search (NIS & Nama saja)
     * dan filter dropdown lembaga. Dipakai bersama oleh datatable & export excel
     * supaya hasilnya selalu konsisten.
     */
    private function base_query($search = '', $lembaga_id = '')
    {
        $sql = "SELECT s.id, s.nis, s.nama_siswa, s.email, s.foto, s.lembaga_id, l.nama_lembaga
                FROM siswa s
                JOIN lembaga l ON l.id = s.lembaga_id
                WHERE 1=1";
        $params = array();

        if ($search !== '') {
            // pencarian HANYA pada kolom NIS & Nama Siswa
            $sql .= " AND (s.nis LIKE ? OR s.nama_siswa LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }

        if ($lembaga_id !== '') {
            $sql .= " AND s.lembaga_id = ?";
            $params[] = $lembaga_id;
        }

        return array('sql' => $sql, 'params' => $params);
    }

    /**
     * Data untuk DataTables (server-side processing) dengan pagination
     */
    public function get_datatable($search, $lembaga_id, $limit, $offset, $order_col = 's.id', $order_dir = 'DESC')
    {
        $base = $this->base_query($search, $lembaga_id);
        $sql = $base['sql'] . " ORDER BY {$order_col} {$order_dir} LIMIT ? OFFSET ?";
        $params = array_merge($base['params'], array($limit, $offset));

        $query = $this->db->query($sql, $params);
        return $query->result_array();
    }

    public function count_filtered($search, $lembaga_id)
    {
        $base = $this->base_query($search, $lembaga_id);
        $sql = "SELECT COUNT(*) as total FROM ({$base['sql']}) as t";
        $query = $this->db->query($sql, $base['params']);
        return (int) $query->row_array()['total'];
    }

    public function count_all()
    {
        $query = $this->db->query("SELECT COUNT(*) as total FROM siswa");
        return (int) $query->row_array()['total'];
    }

    /**
     * Data lengkap (tanpa limit) untuk export excel, sesuai filter yang sedang aktif
     */
    public function get_all_filtered($search, $lembaga_id)
    {
        $base = $this->base_query($search, $lembaga_id);
        $sql = $base['sql'] . " ORDER BY s.id DESC";
        $query = $this->db->query($sql, $base['params']);
        return $query->result_array();
    }

    public function get_by_id($id)
    {
        $query = $this->db->query(
            "SELECT * FROM siswa WHERE id = ? LIMIT 1",
            array($id)
        );
        return $query->row_array();
    }

    public function is_nis_exists($nis, $exclude_id = null)
    {
        if ($exclude_id) {
            $query = $this->db->query(
                "SELECT id FROM siswa WHERE nis = ? AND id != ? LIMIT 1",
                array($nis, $exclude_id)
            );
        } else {
            $query = $this->db->query(
                "SELECT id FROM siswa WHERE nis = ? LIMIT 1",
                array($nis)
            );
        }
        return $query->num_rows() > 0;
    }

    public function insert($data)
    {
        $this->db->query(
            "INSERT INTO siswa (nis, nama_siswa, email, lembaga_id, foto) VALUES (?, ?, ?, ?, ?)",
            array($data['nis'], $data['nama_siswa'], $data['email'], $data['lembaga_id'], $data['foto'])
        );
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        if (!empty($data['foto'])) {
            $this->db->query(
                "UPDATE siswa SET nis = ?, nama_siswa = ?, email = ?, lembaga_id = ?, foto = ? WHERE id = ?",
                array($data['nis'], $data['nama_siswa'], $data['email'], $data['lembaga_id'], $data['foto'], $id)
            );
        } else {
            $this->db->query(
                "UPDATE siswa SET nis = ?, nama_siswa = ?, email = ?, lembaga_id = ? WHERE id = ?",
                array($data['nis'], $data['nama_siswa'], $data['email'], $data['lembaga_id'], $id)
            );
        }
        return true;
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM siswa WHERE id = ?", array($id));
        return true;
    }
}
