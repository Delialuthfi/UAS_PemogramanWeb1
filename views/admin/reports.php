<?php
require_once __DIR__ . '/../../app/bootstrap.php';
require_admin();

$page_title = 'Laporan - ' . APP_NAME;

// Get statistics
$total_booking = $conn->query("SELECT COUNT(*) FROM bookings")->fetch_row()[0];
$booking_pending = $conn->query("SELECT COUNT(*) FROM bookings WHERE status='pending'")->fetch_row()[0];
$booking_approved = $conn->query("SELECT COUNT(*) FROM bookings WHERE status='approved'")->fetch_row()[0];
$booking_rejected = $conn->query("SELECT COUNT(*) FROM bookings WHERE status='rejected'")->fetch_row()[0];
$total_pasien = $conn->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetch_row()[0];
require_once __DIR__ . '/../layouts/header.php';
$total_dokter = $conn->query("SELECT COUNT(*) FROM doctors")->fetch_row()[0];
$total_artikel = $conn->query("SELECT COUNT(*) FROM articles")->fetch_row()[0];

// Get booking data
$bookings = $conn->query('SELECT b.*, u.nama as user_nama, u.email as user_email, d.nama as doctor_nama, s.hari, s.jam_mulai, s.jam_selesai FROM bookings b JOIN users u ON b.user_id = u.id JOIN doctors d ON b.doctor_id = d.id JOIN schedules s ON b.schedule_id = s.id ORDER BY b.created_at DESC LIMIT 50');

// Get patient data
$patients = $conn->query('SELECT id, nama, email, no_hp, created_at FROM users WHERE role="user" ORDER BY created_at DESC LIMIT 50');

// Get doctor data
$doctors = $conn->query('SELECT id, nama, spesialis, tarif, telepon, created_at FROM doctors ORDER BY created_at DESC LIMIT 50');
?>
<div class="container-fluid py-4 min-vh-100 bg-light">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <div class="card border-0 shadow-sm sticky-top" style="top: 2rem; z-index: 1;">
                <div class="card-body p-2">
                    <div class="list-group list-group-flush border-0">
                        <a href="<?= BASE_URL ?>/views/admin/dashboard.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1">
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
                        <a href="<?= BASE_URL ?>/views/admin/reports.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1 active-menu">
                            <i class="fas fa-chart-bar me-3" style="width: 20px;"></i>Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark m-0"><i class="fas fa-chart-line text-primary me-2"></i>Laporan & Statistik</h2>
                <div class="text-muted small"><?= date('l, d F Y') ?></div>
            </div>
    
            <!-- Statistik Ringkas -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            <div class="icon-shape bg-primary-subtle text-primary rounded-circle mx-auto mb-3" style="width: 48px; height: 48px;">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Booking</h6>
                            <h3 class="fw-bold text-dark mb-0"><?= $total_booking ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            <div class="icon-shape bg-warning-subtle text-warning rounded-circle mx-auto mb-3" style="width: 48px; height: 48px;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h6 class="text-muted small text-uppercase fw-bold mb-1">Pending</h6>
                            <h3 class="fw-bold text-dark mb-0"><?= $booking_pending ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            <div class="icon-shape bg-success-subtle text-success rounded-circle mx-auto mb-3" style="width: 48px; height: 48px;">
                                <i class="fas fa-check"></i>
                            </div>
                            <h6 class="text-muted small text-uppercase fw-bold mb-1">Approved</h6>
                            <h3 class="fw-bold text-dark mb-0"><?= $booking_approved ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            <div class="icon-shape bg-danger-subtle text-danger rounded-circle mx-auto mb-3" style="width: 48px; height: 48px;">
                                <i class="fas fa-times"></i>
                            </div>
                            <h6 class="text-muted small text-uppercase fw-bold mb-1">Rejected</h6>
                            <h3 class="fw-bold text-dark mb-0"><?= $booking_rejected ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="icon-shape bg-info-subtle text-info rounded-3 me-3" style="width: 48px; height: 48px;">
                                <i class="fas fa-users fs-5"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small text-uppercase fw-bold mb-0">Total Pasien</h6>
                                <h3 class="fw-bold text-dark mb-0"><?= $total_pasien ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="icon-shape bg-success-subtle text-success rounded-3 me-3" style="width: 48px; height: 48px;">
                                <i class="fas fa-user-md fs-5"></i>
                            </div>
                             <div>
                                <h6 class="text-muted small text-uppercase fw-bold mb-0">Total Dokter</h6>
                                <h3 class="fw-bold text-dark mb-0"><?= $total_dokter ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="icon-shape bg-warning-subtle text-warning rounded-3 me-3" style="width: 48px; height: 48px;">
                                <i class="fas fa-newspaper fs-5"></i>
                            </div>
                             <div>
                                <h6 class="text-muted small text-uppercase fw-bold mb-0">Total Artikel</h6>
                                <h3 class="fw-bold text-dark mb-0"><?= $total_artikel ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Export Options -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 fw-bold">Export Laporan</h5>
                </div>
                <div class="card-body p-4">
            <div class="row">
                <div class="col-md-4">
                    <h6>Data Booking</h6>
                    <a href="../../controllers/ReportController.php?export=excel&type=booking" class="btn btn-success btn-sm me-2">
                        <i class="fa fa-file-excel"></i> Export Excel
                    </a>
                    <a href="../../controllers/ReportController.php?export=pdf&type=booking" class="btn btn-danger btn-sm">
                        <i class="fa fa-file-pdf"></i> Export PDF
                    </a>
                </div>
                <div class="col-md-4">
                    <h6>Data Pasien</h6>
                    <a href="../../controllers/ReportController.php?export=excel&type=pasien" class="btn btn-success btn-sm me-2">
                        <i class="fa fa-file-excel"></i> Export Excel
                    </a>
                    <a href="../../controllers/ReportController.php?export=pdf&type=pasien" class="btn btn-danger btn-sm">
                        <i class="fa fa-file-pdf"></i> Export PDF
                    </a>
                </div>
                <div class="col-md-4">
                    <h6>Data Dokter</h6>
                    <a href="../../controllers/ReportController.php?export=excel&type=dokter" class="btn btn-success btn-sm me-2">
                        <i class="fa fa-file-excel"></i> Export Excel
                    </a>
                    <a href="../../controllers/ReportController.php?export=pdf&type=dokter" class="btn btn-danger btn-sm">
                        <i class="fa fa-file-pdf"></i> Export PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Data Booking -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Data Booking Terbaru</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while($b = $bookings->fetch_assoc()): 
                            $color = $b['status'] == 'pending' ? 'warning' : ($b['status'] == 'approved' ? 'success' : 'danger');
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($b['user_nama']) ?></td>
                            <td><?= htmlspecialchars($b['doctor_nama']) ?></td>
                            <td><?= htmlspecialchars($b['hari']) ?>, <?= htmlspecialchars($b['jam_mulai']) ?>-<?= htmlspecialchars($b['jam_selesai']) ?></td>
                            <td><span class="badge bg-<?= $color ?>"><?= ucfirst($b['status']) ?></span></td>
                            <td><?= date('d M Y H:i', strtotime($b['created_at'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Data Pasien -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Data Pasien Terbaru</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Tanggal Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while($p = $patients->fetch_assoc()): 
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($p['nama']) ?></td>
                            <td><?= htmlspecialchars($p['email']) ?></td>
                            <td><?= htmlspecialchars($p['no_hp'] ?? '-') ?></td>
                            <td><?= date('d M Y H:i', strtotime($p['created_at'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Data Dokter -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Data Dokter Terbaru</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Dokter</th>
                            <th>Spesialis</th>
                            <th>Telepon</th>
                            <th>Tarif</th>
                            <th>Tanggal Terdaftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while($d = $doctors->fetch_assoc()): 
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($d['nama']) ?></td>
                            <td><?= htmlspecialchars($d['spesialis']) ?></td>
                            <td><?= htmlspecialchars($d['telepon'] ?? '-') ?></td>
                            <td>Rp<?= number_format($d['tarif'] ?? 0, 0, ',', '.') ?></td>
                            <td><?= date('d M Y H:i', strtotime($d['created_at'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require_once '../../views/layouts/footer.php'; ?>
