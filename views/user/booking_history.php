<?php
require_once __DIR__ . '/../../app/bootstrap.php';
require_login();
if (current_user()['role'] !== 'user') {
    redirect('/views/auth/login.php');
}
require_once __DIR__ . '/../layouts/header.php';
$user_id = $_SESSION['user']['id'];
$bookings = $conn->query("SELECT bookings.*, doctors.nama as doctor_nama, schedules.hari, schedules.jam_mulai, schedules.jam_selesai FROM bookings JOIN doctors ON bookings.doctor_id = doctors.id JOIN schedules ON bookings.schedule_id = schedules.id WHERE bookings.user_id = $user_id ORDER BY bookings.id DESC");
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
                        <a href="<?= BASE_URL ?>/views/user/booking_history.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1 active-menu">
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark m-0"><i class="fas fa-history text-primary me-2"></i>Riwayat Booking</h2>
                <button class="btn btn-light text-primary shadow-sm fw-bold" onclick="location.reload()"><i class="fas fa-sync-alt me-2"></i>Refresh</button>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 fw-bold">Daftar Kunjungan Anda</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 border-0 text-muted small text-uppercase fw-bold">Dokter</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Jadwal Kunjungan</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Keluhan</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Status</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Tanggal Buat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($b = $bookings->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4 py-3 fw-bold text-dark"><?= htmlspecialchars($b['doctor_nama']) ?></td>
                                    <td class="text-muted">
                                        <div class="fw-medium text-dark"><?= htmlspecialchars($b['hari']) ?></div>
                                        <small><i class="far fa-clock me-1"></i><?= htmlspecialchars($b['jam_mulai']) ?>-<?= htmlspecialchars($b['jam_selesai']) ?></small>
                                    </td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 150px;" title="<?= htmlspecialchars($b['keluhan']) ?>">
                                            <?= htmlspecialchars($b['keluhan']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <?php 
                                            $color = 'secondary';
                                            $icon = 'circle';
                                            if($b['status'] == 'pending') { $color = 'warning'; $icon = 'clock'; }
                                            elseif($b['status'] == 'approved') { $color = 'info'; $icon = 'thumbs-up'; }
                                            elseif($b['status'] == 'confirmed') { $color = 'primary'; $icon = 'check-circle'; }
                                            elseif($b['status'] == 'done') { $color = 'success'; $icon = 'check-double'; }
                                            elseif($b['status'] == 'rejected') { $color = 'danger'; $icon = 'times-circle'; }
                                            elseif($b['status'] == 'cancelled') { $color = 'secondary'; $icon = 'ban'; }
                                        ?>
                                        <span class="badge bg-<?= $color ?>-subtle text-<?= $color ?> border border-<?= $color ?>-subtle px-3 py-2 rounded-pill fw-bold">
                                            <i class="fas fa-<?= $icon ?> me-1"></i><?= ucfirst($b['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-muted small"><?= date('d M Y H:i', strtotime($b['created_at'])) ?></td>
                                </tr>
                                <?php endwhile; ?>
                                <?php if($bookings->num_rows == 0): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-clipboard-list fa-3x mb-3 opacity-25"></i>
                                        <p class="mb-0">Belum ada riwayat booking.</p>
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
