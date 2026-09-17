# 🎓 Latis Siswa — Student Data Management System

Aplikasi manajemen data siswa berbasis web untuk lembaga **Latiseducation** dan **Tutorindonesia**. Dibangun sebagai bagian dari _Test Skill IT Fullstack Developer_.

Aplikasi ini memungkinkan admin untuk login, mengelola data siswa (tambah/edit/hapus), melihat data dalam tabel interaktif dengan pencarian & filter, serta mengekspor data ke Excel.

---

## ✨ Fitur Utama

- **Autentikasi & Session Management**
  Login/logout dengan password ter-hash (bcrypt), session dikelola native oleh CodeIgniter, setiap halaman internal dilindungi guard session.

- **CRUD Data Siswa**
  Tambah, lihat, edit, dan hapus data siswa secara penuh, terhubung ke database MySQL.

- **Validasi Data**
  - NIS → wajib diisi, hanya angka, dan unik (tidak boleh duplikat)
  - Nama Siswa → wajib diisi
  - Email → wajib diisi dan harus format email valid
  - Foto → hanya menerima file JPG/PNG, maksimal 100KB

- **Dropdown Lembaga Dinamis**
  Pilihan lembaga (Latiseducation / Tutorindonesia) diambil langsung dari tabel `lembaga` di database — bukan hardcode, sehingga mudah ditambah lembaga baru tanpa ubah kode.

- **Tabel Data Interaktif (DataTables)**
  - Server-side processing (efisien untuk data besar, tidak me-load semua data sekaligus)
  - Pagination otomatis
  - Pencarian **khusus pada kolom NIS & Nama Siswa** (tidak melebar ke semua kolom)
  - Filter berdasarkan lembaga

- **Export Excel**
  Hasil export mengikuti kondisi pencarian & filter yang sedang aktif di tabel — jika user memfilter lembaga tertentu, file Excel yang di-download juga hanya berisi data lembaga itu.

- **Halaman Profile**
  Menampilkan nama, posisi, dan foto kandidat; foto bisa diperbarui langsung dari halaman ini.

- **Sidebar Navigation**
  Navigasi konsisten di semua halaman: Siswa, Profile, Logout.

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|---|---|
| Backend Framework | CodeIgniter 3.1.13 (PHP, MVC) |
| Database | MySQL (native driver `mysqli`, prepared statement / query binding) |
| Frontend | Bootstrap 5, Font Awesome |
| Data Table | DataTables.net (server-side processing via AJAX) |
| Auth | PHP `password_hash` / `password_verify` (bcrypt) + CI Native Session |

---

## 🗂️ Struktur Aplikasi

```
application/
├── config/          # konfigurasi database, session, routing, autoload
├── core/
│   └── MY_Controller.php     # guard: cek session login sebelum akses halaman internal
├── controllers/
│   ├── Auth.php              # login & logout
│   ├── Siswa.php             # CRUD siswa, ajax datatable, export excel
│   └── Profile.php           # lihat & update foto profile
├── models/
│   ├── Auth_model.php        # query user (login)
│   ├── Lembaga_model.php     # query data lembaga (dropdown)
│   └── Siswa_model.php       # query CRUD siswa, filter, pagination
└── views/
    ├── auth/login.php
    ├── siswa/index.php       # datatable + search + filter + export
    ├── siswa/form.php        # form tambah/edit siswa
    ├── profile/index.php
    └── templates/            # header, sidebar, footer (layout bersama)

uploads/
├── siswa/           # foto siswa
└── profile/         # foto profile kandidat

database.sql         # schema tabel + data awal (users, lembaga, siswa)
```

### Alur Data Singkat

```
Browser ──> Controller ──> Model ──> MySQL (prepared statement)
   ▲                                        │
   └──────────── View (Bootstrap) <─────────┘
```

Semua query database melewati **query binding (`?`)** di level model — tidak ada query string yang digabung manual — untuk mencegah SQL Injection.

---

## 🔐 Keamanan yang Diterapkan

- Password disimpan dalam bentuk hash (bcrypt), tidak pernah plain text
- Semua query SQL menggunakan prepared statement
- Session dicek di setiap controller yang butuh login (`MY_Controller`)
- Validasi tipe & ukuran file upload di sisi server (bukan hanya di frontend)
- Output data di-escape (`htmlspecialchars`) sebelum ditampilkan untuk mencegah XSS

---

## 🚀 Instalasi & Menjalankan Secara Lokal

### Prasyarat
- PHP >= 7.2
- MySQL / MariaDB
- Web server Laragon

### Langkah

1. Clone repository ini
   ```bash
   git clone <url-repo-ini>
   ```
2. Import `database.sql` ke MySQL (otomatis membuat database, tabel, dan data awal)
3. Sesuaikan kredensial database di `application/config/database.php`
4. Pastikan folder `uploads/siswa/` dan `uploads/profile/` writable
5. Arahkan document root ke folder project, lalu akses lewat browser

### Kredensial Login Default
```
Username : admin
Password : password123
```

---

## 📌 Catatan Desain

- **Server-side DataTables** dipilih dibanding client-side agar performa tetap baik meskipun jumlah data siswa besar, dan agar pencarian/filter/export bisa dikontrol penuh dari sisi server (konsisten dengan requirement "hasil pencarian = hasil ekspor").
- **Export Excel** menggunakan pendekatan HTML table dengan header `Content-Type: application/vnd.ms-excel` — ringan, tidak butuh library tambahan, dan file tetap terbuka normal di Microsoft Excel/Google Sheets.
- Struktur MVC mengikuti konvensi bawaan CodeIgniter 3 agar mudah dibaca dan dikembangkan lebih lanjut.

---

## 👤 Dibuat oleh

Budi Cahyono.