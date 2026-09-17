<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Data Siswa</h4>
    <a href="<?= base_url('siswa/add') ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Tambah Siswa
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="row mb-3 g-2">
            <div class="col-md-4">
                <input type="text" id="search_box" class="form-control" placeholder="Cari NIS atau Nama Siswa...">
            </div>
            <div class="col-md-3">
                <select id="filter_lembaga" class="form-select">
                    <option value="">-- Semua Lembaga --</option>
                    <?php foreach ($lembaga as $l): ?>
                        <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['nama_lembaga']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-5 text-end">
                <a id="btn_export" href="#" class="btn btn-success">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </a>
            </div>
        </div>

        <table id="table_siswa" class="table table-bordered table-striped w-100">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Email</th>
                    <th>Lembaga</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/datatables.net@1.13.11/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.11/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function () {
    var table = $('#table_siswa').DataTable({
        processing: true,
        serverSide: true,
        searching: false, // pencarian pakai search_box custom, bukan search bawaan datatables
        ordering: false,
        ajax: {
            url: "<?= base_url('siswa/ajax_list') ?>",
            data: function (d) {
                d.search_value = $('#search_box').val();
                d.lembaga_id   = $('#filter_lembaga').val();
            }
        },
        columns: [
            { data: 'no', orderable: false, width: '5%' },
            { data: 'foto', orderable: false, render: function (data) {
                    return '<img src="' + data + '" class="foto-thumb">';
                } },
            { data: 'nis' },
            { data: 'nama_siswa' },
            { data: 'email' },
            { data: 'nama_lembaga' },
            { data: 'action', orderable: false }
        ]
    });

    var searchTimer;
    $('#search_box').on('keyup', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function () {
            table.ajax.reload();
        }, 400);
    });

    $('#filter_lembaga').on('change', function () {
        table.ajax.reload();
    });

    function buildExportUrl() {
        var params = new URLSearchParams();
        params.set('search_value', $('#search_box').val());
        params.set('lembaga_id', $('#filter_lembaga').val());
        return "<?= base_url('siswa/export_excel') ?>?" + params.toString();
    }

    $('#btn_export').on('click', function (e) {
        e.preventDefault();
        window.location.href = buildExportUrl();
    });

    $(document).on('click', '.btn-delete', function (e) {
        if (!confirm('Hapus data siswa "' + $(this).data('nama') + '"?')) {
            e.preventDefault();
        }
    });
});
</script>
