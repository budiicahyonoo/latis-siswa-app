<h4 class="mb-3">Profile</h4>

<div class="card" style="max-width: 500px;">
    <div class="card-body text-center">
        <img src="<?= !empty($user['photo']) ? base_url('uploads/profile/' . $user['photo']) : base_url('assets/img/no-image.png') ?>"
             class="rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover;">

        <h5 class="mb-0"><?= htmlspecialchars($user['nama']) ?></h5>
        <p class="text-muted"><?= htmlspecialchars($user['position']) ?></p>

        <hr>

        <form action="<?= base_url('profile/update_photo') ?>" method="post" enctype="multipart/form-data" class="text-start">
            <label class="form-label">Ganti Foto <small class="text-muted">(JPG/PNG, maksimal 100KB)</small></label>
            <input type="file" name="photo" class="form-control mb-2" accept=".jpg,.jpeg,.png">
            <button type="submit" class="btn btn-primary w-100">Update Foto</button>
        </form>
    </div>
</div>
