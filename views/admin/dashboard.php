<?php
/**
 * Admin Dashboard
 */
require_once __DIR__ . '/../../app/bootstrap.php';
require_admin();

$page_title = 'Dashboard Admin - ' . APP_NAME;

// Statistics
$total_booking = $conn->query("SELECT COUNT(*) FROM bookings")->fetch_row()[0];
$total_pasien = $conn->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetch_row()[0];
$total_dokter = $conn->query("SELECT COUNT(*) FROM doctors")->fetch_row()[0];
$total_artikel = $conn->query("SELECT COUNT(*) FROM articles")->fetch_row()[0];

// Recent bookings
$recent_bookings = $conn->query("
    SELECT b.*, u.nama as patient_name, d.nama as doctor_name 
    FROM bookings b 
    LEFT JOIN users u ON b.user_id = u.id 
    LEFT JOIN doctors d ON b.doctor_id = d.id 
    ORDER BY b.created_at DESC LIMIT 5
");

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid py-4 min-vh-100 bg-light">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <div class="card border-0 shadow-sm sticky-top" style="top: 2rem; z-index: 1;">
                <div class="card-body p-2">
                    <div class="list-group list-group-flush border-0">
                        <a href="<?= BASE_URL ?>/views/admin/dashboard.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1 active-menu">
                            <i class="fas fa-tachometer-alt me-3" style="width: 20px;"></i>Dashboard
                        </a>
                        <a href="<?= BASE_URL ?>/views/admin/bookings.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1">
                            <i class="fas fa-calendar-check me-3" style="width: 20px;"></i>Booking
                        </a>
                        <a href="<?= BASE_URL ?>/views/admin/patients.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1">
                            <i class="fas fa-users me-3" style="width: 20px;"></i>Pasien
                        </a>
                        <a href="<?= BASE_URL ?>/views/admin/doctors.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1">
                            <i class="fas fa-user-md me-3" style="width: 20px;"></i>Dokter
                        </a>
                        <a href="<?= BASE_URL ?>/views/admin/schedules.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1">
                            <i class="fas fa-clock me-3" style="width: 20px;"></i>Jadwal
                        </a>
                        <a href="<?= BASE_URL ?>/views/admin/articles.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1">
                            <i class="fas fa-newspaper me-3" style="width: 20px;"></i>Artikel
                        </a>
                        <a href="<?= BASE_URL ?>/views/admin/reports.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1">
                            <i class="fas fa-chart-bar me-3" style="width: 20px;"></i>Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark m-0"><i class="fas fa-tachometer-alt text-primary me-2"></i>Dashboard Admin</h2>
                <div class="text-muted small"><?= date('l, d F Y') ?></div>
            </div>
            
            <!-- Stats Cards -->
            <div class="row g-4 mb-5">
                <!-- Card 1 -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Booking</h6>
                                    <h2 class="display-6 fw-bold text-dark mb-0"><?= $total_booking ?></h2>
                                </div>
                                <div class="icon-shape bg-primary-subtle text-primary rounded-3 p-3">
                                    <i class="fas fa-calendar-check fa-lg"></i>
                                </div>
                            </div>
                            <a href="<?= BASE_URL ?>/views/admin/bookings.php" class="text-decoration-none small fw-bold text-primary">
                                Lihat detail <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Card 2 -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Pasien</h6>
                                    <h2 class="display-6 fw-bold text-dark mb-0"><?= $total_pasien ?></h2>
                                </div>
                                <div class="icon-shape bg-success-subtle text-success rounded-3 p-3">
                                    <i class="fas fa-users fa-lg"></i>
                                </div>
                            </div>
                            <a href="<?= BASE_URL ?>/views/admin/patients.php" class="text-decoration-none small fw-bold text-success">
                                Lihat detail <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Card 3 -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Dokter</h6>
                                    <h2 class="display-6 fw-bold text-dark mb-0"><?= $total_dokter ?></h2>
                                </div>
                                <div class="icon-shape bg-info-subtle text-info rounded-3 p-3">
                                    <i class="fas fa-user-md fa-lg"></i>
                                </div>
                            </div>
                            <a href="<?= BASE_URL ?>/views/admin/doctors.php" class="text-decoration-none small fw-bold text-info">
                                Lihat detail <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Card 4 -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Artikel</h6>
                                    <h2 class="display-6 fw-bold text-dark mb-0"><?= $total_artikel ?></h2>
                                </div>
                                <div class="icon-shape bg-warning-subtle text-warning rounded-3 p-3">
                                    <i class="fas fa-newspaper fa-lg"></i>
                                </div>
                            </div>
                            <a href="<?= BASE_URL ?>/views/admin/articles.php" class="text-decoration-none small fw-bold text-warning">
                                Lihat detail <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Bookings -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-clock text-primary me-2"></i>Booking Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <?php if ($recent_bookings && $recent_bookings->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3 border-0 text-muted small text-uppercase fw-bold">Pasien</th>
                                        <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Dokter</th>
                                        <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Tanggal</th>
                                        <th class="pe-4 py-3 border-0 text-muted small text-uppercase fw-bold">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($booking = $recent_bookings->fetch_assoc()): ?>
                                    <tr>
                                        <td class="ps-4 py-3 fw-medium text-dark"><?= e($booking['patient_name'] ?? 'N/A') ?></td>
                                        <td class="py-3 text-muted"><?= e($booking['doctor_name'] ?? 'N/A') ?></td>
                                        <td class="py-3 text-muted"><?= date('d M Y', strtotime($booking['tanggal'] ?? $booking['created_at'])) ?></td>
                                        <td class="pe-4 py-3">
                                            <?php 
                                            $status = $booking['status'] ?? 'pending';
                                            $badge_class = match($status) {
                                                'approved', 'confirmed' => 'bg-success-subtle text-success',
                                                'cancelled', 'rejected' => 'bg-danger-subtle text-danger',
                                                default => 'bg-warning-subtle text-warning'
                                            };
                                            ?>
                                            <span class="badge rounded-pill <?= $badge_class ?> px-3 py-2"><?= ucfirst($status) ?></span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="p-5 text-center">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-25"></i>
                            <p class="text-muted mb-0">Belum ada booking terbaru.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
