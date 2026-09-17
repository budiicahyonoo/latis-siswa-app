<?php $is_edit = !empty($siswa['id']); ?>

<h4 class="mb-3"><?= $is_edit ? 'Edit Siswa' : 'Tambah Siswa' ?></h4>

<div class="card">
    <div class="card-body">
        <form action="<?= $is_edit ? base_url('siswa/update/' . $siswa['id']) : base_url('siswa/store') ?>"
              method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label">Lembaga</label>
                <select name="lembaga_id" class="form-select <?= isset($errors['lembaga_id']) ? 'is-invalid' : '' ?>">
                    <option value="">-- Pilih Lembaga --</option>
                    <?php foreach ($lembaga as $l): ?>
                        <option value="<?= $l['id'] ?>"
                            <?= (isset($siswa['lembaga_id']) && $siswa['lembaga_id'] == $l['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($l['nama_lembaga']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['lembaga_id'])): ?>
                    <div class="invalid-feedback"><?= $errors['lembaga_id'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">NIS</label>
                <input type="text" name="nis" class="form-control <?= isset($errors['nis']) ? 'is-invalid' : '' ?>"
                       value="<?= isset($siswa['nis']) ? htmlspecialchars($siswa['nis']) : '' ?>">
                <?php if (isset($errors['nis'])): ?>
                    <div class="invalid-feedback"><?= $errors['nis'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Siswa</label>
                <input type="text" name="nama_siswa" class="form-control <?= isset($errors['nama_siswa']) ? 'is-invalid' : '' ?>"
                       value="<?= isset($siswa['nama_siswa']) ? htmlspecialchars($siswa['nama_siswa']) : '' ?>">
                <?php if (isset($errors['nama_siswa'])): ?>
                    <div class="invalid-feedback"><?= $errors['nama_siswa'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                       value="<?= isset($siswa['email']) ? htmlspecialchars($siswa['email']) : '' ?>">
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Foto <small class="text-muted">(JPG/PNG, maksimal 100KB<?= $is_edit ? ', kosongkan jika tidak ingin mengubah foto' : '' ?>)</small>
                </label>
                <input type="file" name="foto" class="form-control <?= isset($errors['foto']) ? 'is-invalid' : '' ?>" accept=".jpg,.jpeg,.png">
                <?php if (isset($errors['foto'])): ?>
                    <div class="invalid-feedback d-block"><?= $errors['foto'] ?></div>
                <?php endif; ?>

                <?php if ($is_edit && !empty($siswa['foto'])): ?>
                    <div class="mt-2">
                        <img src="<?= base_url('uploads/siswa/' . $siswa['foto']) ?>" style="width:80px;height:80px;object-fit:cover;border-radius:6px;">
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= base_url('siswa') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
