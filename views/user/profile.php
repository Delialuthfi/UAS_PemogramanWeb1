<?php
require_once __DIR__ . '/../../app/bootstrap.php';
require_login();

if (is_admin()) {
    redirect('/views/admin/dashboard.php');
}

$page_title = 'Profil Saya - ' . APP_NAME;
$user = current_user();

// Update profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $no_hp = trim($_POST['no_hp']);
    $id = $user['id'];

    $stmt = $conn->prepare('UPDATE users SET nama=?, email=?, no_hp=? WHERE id=?');
    $stmt->bind_param('sssi', $nama, $email, $no_hp, $id);
    if ($stmt->execute()) {
        $_SESSION['user']['nama'] = $nama;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['no_hp'] = $no_hp;
        $success = 'Profil berhasil diperbarui!';
    } else {
        $error = 'Gagal memperbarui profil.';
    }
}

require_once __DIR__ . '/../layouts/header.php';
?>
<div class="container-fluid py-4 min-vh-100 bg-light">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <div class="card border-0 shadow-sm sticky-top" style="top: 2rem; z-index: 1;">
                <div class="card-body p-2">
                    <div class="list-group list-group-flush border-0">
                        <a href="<?= BASE_URL ?>/views/user/dashboard.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1">
                            <i class="fas fa-tachometer-alt me-3" style="width: 20px;"></i>Dashboard
                        </a>
                        <a href="<?= BASE_URL ?>/views/user/doctors.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1">
                            <i class="fas fa-user-md me-3" style="width: 20px;"></i>Cari Dokter
                        </a>
                        <a href="<?= BASE_URL ?>/views/user/booking_history.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1">
                            <i class="fas fa-history me-3" style="width: 20px;"></i>Riwayat Booking
                        </a>
                        <a href="<?= BASE_URL ?>/views/user/profile.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1 active-menu">
                            <i class="fas fa-user-circle me-3" style="width: 20px;"></i>Profil Saya
                        </a>
                        <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=logout" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1 text-danger">
                            <i class="fas fa-sign-out-alt me-3" style="width: 20px;"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark m-0"><i class="fas fa-user-circle text-primary me-2"></i>Profil Saya</h2>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom p-4">
                            <h5 class="mb-0 fw-bold">Edit Informasi Pribadi</h5>
                        </div>
                        <div class="card-body p-4">
                            <?php if (!empty($success)): ?>
                                <div class="alert alert-success d-flex align-items-center shadow-sm">
                                    <i class="fas fa-check-circle me-2 fs-5"></i><?= $success ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger d-flex align-items-center shadow-sm">
                                    <i class="fas fa-exclamation-circle me-2 fs-5"></i><?= $error ?>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST">
                                <div class="mb-4">
                                    <label class="form-label text-muted small text-uppercase fw-bold">Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control form-control-lg bg-light border-0" value="<?= htmlspecialchars($_SESSION['user']['nama']) ?>" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-muted small text-uppercase fw-bold">Email Address</label>
                                    <input type="email" name="email" class="form-control form-control-lg bg-light border-0" value="<?= htmlspecialchars($_SESSION['user']['email']) ?>" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-muted small text-uppercase fw-bold">Nomor Handphone</label>
                                    <input type="text" name="no_hp" class="form-control form-control-lg bg-light border-0" value="<?= htmlspecialchars($_SESSION['user']['no_hp']) ?>" required>
                                </div>
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <button type="button" class="btn btn-light" onclick="window.history.back()">Batal</button>
                                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center mb-4">
                        <div class="card-body p-5">
                            <div class="mb-4">
                                <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle" style="width: 100px; height: 100px;">
                                    <i class="fas fa-user fa-3x"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-1"><?= htmlspecialchars($_SESSION['user']['nama']) ?></h4>
                            <p class="text-muted"><?= htmlspecialchars($_SESSION['user']['role']) ?></p>
                            <div class="badge bg-success-subtle text-success px-3 py-2 rounded-pill mb-3">
                                <i class="fas fa-check-circle me-1"></i> Akun Aktif
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border-0 shadow-sm bg-primary text-white overflow-hidden">
                        <div class="card-body p-4 position-relative">
                            <h5 class="fw-bold mb-3 z-1 position-relative">Butuh Bantuan?</h5>
                            <p class="mb-3 opacity-75 z-1 position-relative">Hubungi customer service kami jika Anda mengalami kendala.</p>
                            <a href="#" class="btn btn-light text-primary fw-bold w-100 shadow-sm hover-lift z-1 position-relative">Hubungi Sekarang</a>
                            
                            <div class="position-absolute rounded-circle bg-white opacity-10" style="width: 150px; height: 150px; top: -50px; right: -50px; z-index: 0;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../../views/layouts/footer.php'; ?>
