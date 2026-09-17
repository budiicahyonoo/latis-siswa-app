<?php $current = $this->uri->segment(1); ?>
<div class="sidebar">
    <div class="brand"><i class="fa-solid fa-graduation-cap"></i> Latis Siswa</div>
    <a href="<?= base_url('siswa') ?>" class="<?= $current === 'siswa' ? 'active' : '' ?>">
        <i class="fa-solid fa-users"></i> Siswa
    </a>
    <a href="<?= base_url('profile') ?>" class="<?= $current === 'profile' ? 'active' : '' ?>">
        <i class="fa-solid fa-user"></i> Profile
    </a>
    <a href="<?= base_url('auth/logout') ?>" onclick="return confirm('Yakin ingin logout?');">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
</div>
<div class="main-content">
    <?php
        $success = $this->session->flashdata('success');
        $error   = $this->session->flashdata('error');
    ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
