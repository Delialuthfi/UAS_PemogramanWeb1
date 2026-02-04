<?php
require_once __DIR__ . '/../../app/bootstrap.php';
require_login();
if (current_user()['role'] !== 'user') {
    redirect('/views/auth/login.php');
}
require_once __DIR__ . '/../layouts/header.php';
$id = intval($_GET['id'] ?? 0);
$doctor = $conn->query("SELECT * FROM doctors WHERE id=$id")->fetch_assoc();
$schedules = $conn->query("SELECT * FROM schedules WHERE doctor_id=$id ORDER BY hari, jam_mulai");
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
                        <a href="<?= BASE_URL ?>/views/user/doctors.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1 active-menu">
                            <i class="fas fa-user-md me-3" style="width: 20px;"></i>Cari Dokter
                        </a>
                        <a href="<?= BASE_URL ?>/views/user/booking_history.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1">
                            <i class="fas fa-history me-3" style="width: 20px;"></i>Riwayat Booking
                        </a>
                        <a href="<?= BASE_URL ?>/views/user/profile.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1">
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
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/views/user/doctors.php" class="text-decoration-none">Cari Dokter</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($doctor['nama']) ?></li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-3 text-center mb-4 mb-md-0">
                            <?php if($doctor['foto']): ?>
                                <img src="../../assets/images/<?= htmlspecialchars($doctor['foto']) ?>" class="img-fluid rounded-circle shadow-sm mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            <?php else: ?>
                                <div class="d-inline-flex align-items-center justify-content-center bg-light text-secondary rounded-circle mb-3" style="width: 150px; height: 150px;">
                                    <i class="fas fa-user-md fa-4x"></i>
                                </div>
                            <?php endif; ?>
                            <h4 class="fw-bold mb-1"><?= htmlspecialchars($doctor['nama']) ?></h4>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                <?= htmlspecialchars($doctor['spesialis']) ?>
                            </span>
                        </div>
                        <div class="col-md-9 border-start-md ps-md-4">
                            <h5 class="fw-bold text-dark mb-3">Tentang Dokter</h5>
                            <p class="text-muted mb-4"><?= htmlspecialchars($doctor['deskripsi']) ?></p>
                            
                            <h5 class="fw-bold text-dark mb-3">Biaya Konsultasi</h5>
                            <h3 class="text-primary fw-bold mb-4">Rp <?= number_format($doctor['tarif'], 0, ',', '.') ?></h3>
                            
                            <div class="d-flex gap-2">
                                <a href="booking.php?doctor_id=<?= $doctor['id'] ?>" class="btn btn-success btn-lg px-4 shadow-sm hover-lift">
                                    <i class="fas fa-calendar-check me-2"></i>Booking Sekarang
                                </a>
                                <button onclick="window.history.back()" class="btn btn-outline-secondary btn-lg px-4">
                                    Kembali
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-3">
                    <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-clock me-2"></i>Jadwal Praktik Tersedia</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Hari</th>
                                    <th>Jam Praktik</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if($schedules->num_rows > 0):
                                    while($s = $schedules->fetch_assoc()): 
                                ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-dark"><?= htmlspecialchars($s['hari']) ?></td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info-emphasis rounded-pill">
                                            <i class="far fa-clock me-1"></i>
                                            <?= htmlspecialchars($s['jam_mulai']) ?> - <?= htmlspecialchars($s['jam_selesai']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="booking.php?doctor_id=<?= $doctor['id'] ?>&schedule_id=<?= $s['id'] ?>" class="btn btn-primary btn-sm px-3 rounded-pill">
                                            Pilih Jadwal <i class="fas fa-chevron-right ms-1"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    endwhile;
                                else:
                                ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        <i class="far fa-calendar-times fa-2x mb-2 d-block"></i>
                                        Belum ada jadwal tersedia.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../../views/layouts/footer.php'; ?>
