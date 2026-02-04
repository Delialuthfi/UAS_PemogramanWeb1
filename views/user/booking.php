<?php
require_once __DIR__ . '/../../app/bootstrap.php';
require_login();

if (is_admin()) {
    redirect('/views/admin/dashboard.php');
}

$page_title = 'Booking Dokter - ' . APP_NAME;
$doctor_id = intval($_GET['doctor_id'] ?? 0);
$schedule_id = intval($_GET['schedule_id'] ?? 0);
$doctor = $doctor_id > 0 ? $conn->query("SELECT * FROM doctors WHERE id=$doctor_id")->fetch_assoc() : null;
$schedule = $schedule_id > 0 ? $conn->query("SELECT * FROM schedules WHERE id=$schedule_id")->fetch_assoc() : null;
$schedules_result = $doctor_id > 0 ? $conn->query("SELECT * FROM schedules WHERE doctor_id=$doctor_id ORDER BY hari, jam_mulai") : null;
$schedules_count = $schedules_result ? $schedules_result->num_rows : 0;
require_once __DIR__ . '/../layouts/header.php';

// Redirect jika dokter tidak ditemukan
if (!$doctor) {
    header('Location: doctors.php');
    exit;
}
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
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/views/user/doctor_detail.php?id=<?= $doctor_id ?>" class="text-decoration-none"><?= htmlspecialchars($doctor['nama']) ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Booking</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-calendar-plus me-2"></i>Booking Janji Konsultasi</h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info d-flex align-items-center shadow-sm mb-4">
                        <i class="fas fa-info-circle me-3 fs-3"></i>
                        <div>
                            <strong>Informasi Booking</strong>
                            <div class="small">Anda akan melakukan janji temu dengan dokter <strong><?= htmlspecialchars($doctor['nama']) ?></strong> (<?= htmlspecialchars($doctor['spesialis']) ?>).<br>Biaya konsultasi: <strong>Rp<?= number_format($doctor['tarif'],0,',','.') ?></strong></div>
                        </div>
                    </div>
            
                    <?php if($schedules_count == 0): ?>
                    <div class="text-center py-5">
                        <div class="mb-3 text-warning">
                             <i class="fas fa-calendar-times fa-4x"></i>
                        </div>
                        <h5 class="fw-bold">Jadwal tidak tersedia</h5>
                        <p class="text-muted">Dokter ini belum memiliki jadwal yang terdaftar. Silakan hubungi admin atau cari dokter lain.</p>
                        <a href="doctor_detail.php?id=<?= $doctor_id ?>" class="btn btn-secondary mt-2">Kembali ke Detail Dokter</a>
                    </div>
                    <?php else: ?>
                    <form method="POST" action="../../controllers/BookingController.php?action=create" class="row g-4">
                        <input type="hidden" name="doctor_id" value="<?= $doctor_id ?>">
                        
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Pilih Jadwal Konsultasi <span class="text-danger">*</span></label>
                            <select name="schedule_id" class="form-select form-select-lg bg-light border-0" required onchange="document.querySelector('#submitBtn').disabled = this.value == ''">
                                <option value="">-- Pilih Hari & Jam --</option>
                                <?php 
                                // Reset pointer ke awal
                                $schedules_result->data_seek(0);
                                while($s = $schedules_result->fetch_assoc()): ?>
                                <option value="<?= $s['id'] ?>" <?= ($schedule_id == $s['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($s['hari']) ?> - Pukul <?= htmlspecialchars($s['jam_mulai']) ?> s/d <?= htmlspecialchars($s['jam_selesai']) ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                            <div class="form-text">Pilih waktu yang sesuai dengan ketersediaan Anda.</div>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Keluhan / Alasan Konsultasi <span class="text-danger">*</span></label>
                            <textarea name="keluhan" class="form-control bg-light border-0" rows="5" placeholder="Contoh: Saya mengalami demam tinggi sejak 2 hari yang lalu..." required></textarea>
                            <div class="form-text">Jelaskan gejala yang Anda rasakan secara singkat namun jelas.</div>
                        </div>

                        <div class="col-12 mt-4">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-light btn-lg" onclick="window.history.back()">Batal</button>
                                <button type="submit" id="submitBtn" class="btn btn-primary btn-lg px-5 shadow-sm fw-bold">Konfirmasi Booking</button>
                            </div>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../../views/layouts/footer.php'; ?>
