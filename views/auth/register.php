<?php
/**
 * Register Page
 */
require_once __DIR__ . '/../../app/bootstrap.php';

// Redirect if already logged in
if (is_logged_in()) {
    redirect(is_admin() ? '/views/admin/dashboard.php' : '/views/user/dashboard.php');
}

$error = flash('error');
?>
<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Daftar - <?= APP_NAME ?></title>
    <link href='<?= BASE_URL ?>/assets/css/bootstrap.min.css' rel='stylesheet'>
    <link href='<?= BASE_URL ?>/assets/css/modern-theme.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css'>
</head>
<body class='bg-light'>
    <div class='min-vh-100 d-flex align-items-center justify-content-center py-5'>
        <div class='container'>
            <div class='row justify-content-center'>
                <div class='col-md-6 col-lg-5'>
                    <div class='card shadow-lg border-0'>
                        <div class='card-body p-5'>
                            <div class='text-center mb-4'>
                                <a href='<?= BASE_URL ?>' class='text-decoration-none'>
                                    <i class='fas fa-heartbeat fa-3x text-primary mb-3'></i>
                                </a>
                                <h2 class='fw-bold text-dark'>Buat Akun Baru</h2>
                                <p class='text-muted'>Isi form di bawah untuk mendaftar</p>
                            </div>

                            <?php if ($error): ?>
                                <div class='alert alert-danger d-flex align-items-center fade show'><i class='fas fa-exclamation-circle me-2'></i><?= e($error) ?></div>
                            <?php endif; ?>

                            <form action='<?= BASE_URL ?>/api/auth.php?action=register' method='POST'>
                                <div class='mb-3'>
                                    <label for='nama' class='form-label'>Nama Lengkap</label>
                                    <div class='input-group'>
                                        <span class='input-group-text bg-light'><i class='fas fa-user text-muted'></i></span>
                                        <input type='text' class='form-control' id='nama' name='nama' required placeholder='Nama Anda'>
                                    </div>
                                </div>

                                <div class='mb-3'>
                                    <label for='email' class='form-label'>Email Address</label>
                                    <div class='input-group'>
                                        <span class='input-group-text bg-light'><i class='fas fa-envelope text-muted'></i></span>
                                        <input type='email' class='form-control' id='email' name='email' required placeholder='nama@email.com'>
                                    </div>
                                </div>
                                
                                <div class='mb-4'>
                                    <label for='password' class='form-label'>Password</label>
                                    <div class='input-group'>
                                        <span class='input-group-text bg-light'><i class='fas fa-lock text-muted'></i></span>
                                        <input type='password' class='form-control' id='password' name='password' required placeholder='Min. 6 karakter'>
                                    </div>
                                </div>
                                
                                <button type='submit' class='btn btn-primary w-100 mb-3 fs-5'>Daftar</button>
                                
                                <div class='text-center'>
                                    <p class='text-muted mb-0'>Sudah punya akun? <a href='<?= BASE_URL ?>/views/auth/login.php' class='text-primary fw-bold text-decoration-none'>Login di sini</a></p>
                                    <div class='mt-3'>
                                        <a href='<?= BASE_URL ?>' class='text-muted small text-decoration-none'><i class='fas fa-arrow-left me-1'></i> Kembali ke Beranda</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src='<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js'></script>
</body>
</html>
