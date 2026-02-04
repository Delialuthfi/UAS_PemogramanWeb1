<?php
require_once __DIR__ . '/../../app/bootstrap.php';
require_admin();

$page_title = 'Manajemen Booking - ' . APP_NAME;
$bookings = $conn->query('SELECT b.*, u.nama as user_nama, u.email as user_email, d.nama as doctor_nama, s.hari, s.jam_mulai, s.jam_selesai FROM bookings b JOIN users u ON b.user_id = u.id JOIN doctors d ON b.doctor_id = d.id JOIN schedules s ON b.schedule_id = s.id ORDER BY b.created_at DESC');
require_once __DIR__ . '/../layouts/header.php';
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
                        <a href="<?= BASE_URL ?>/views/admin/bookings.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1 active-menu">
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
                <h2 class="fw-bold text-dark m-0"><i class="fas fa-calendar-check text-primary me-2"></i>Manajemen Booking</h2>
                <div class="text-muted small"><?= date('l, d F Y') ?></div>
            </div>
    
            <?php if(isset($_GET['approved'])): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <strong><i class="fa fa-check-circle me-2"></i>Sukses!</strong> Booking telah disetujui.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            
            <?php if(isset($_GET['rejected'])): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <strong><i class="fa fa-times-circle me-2"></i>Sukses!</strong> Booking telah ditolak.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 fw-bold">Daftar Booking User</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 border-0 text-muted small text-uppercase fw-bold">No</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Pasien</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Dokter</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Jadwal</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Keluhan</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Status</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Tanggal Booking</th>
                                    <th class="pe-4 py-3 border-0 text-muted small text-uppercase fw-bold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                while($b = $bookings->fetch_assoc()): 
                                    $status_color = $b['status'] == 'pending' ? 'bg-warning-subtle text-warning' : ($b['status'] == 'approved' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger');
                                ?>
                                <tr>
                                    <td class="ps-4 fw-medium"><?= $no++ ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($b['user_nama']) ?></div>
                                        <div class="text-muted small"><?= htmlspecialchars($b['user_email']) ?></div>
                                    </td>
                                    <td class="text-dark fw-medium"><?= htmlspecialchars($b['doctor_nama']) ?></td>
                                    <td>
                                        <div class="badge bg-light text-dark border fw-normal mb-1"><i class="far fa-calendar me-1"></i><?= htmlspecialchars($b['hari']) ?></div>
                                        <div class="small text-muted"><i class="far fa-clock me-1"></i><?= htmlspecialchars($b['jam_mulai']) ?> - <?= htmlspecialchars($b['jam_selesai']) ?></div>
                                    </td>
                                    <td class="text-muted small" style="max-width: 200px;"><?= htmlspecialchars($b['keluhan']) ?></td>
                                    <td>
                                        <span class="badge rounded-pill <?= $status_color ?> px-3 py-2">
                                            <?= ucfirst($b['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-muted small"><?= date('d M Y, H:i', strtotime($b['created_at'])) ?></td>
                                    <td class="pe-4">
                                        <?php if($b['status'] == 'pending'): ?>
                                        <div class="d-flex gap-1">
                                            <a href="../../controllers/BookingController.php?action=approve&id=<?= $b['id'] ?>" 
                                            class="btn btn-success btn-sm btn-icon" onclick="return confirm('Setujui booking ini?')" title="Approve">
                                                <i class="fa fa-check"></i>
                                            </a>
                                            <a href="../../controllers/BookingController.php?action=reject&id=<?= $b['id'] ?>" 
                                            class="btn btn-danger btn-sm btn-icon" onclick="return confirm('Tolak booking ini?')" title="Reject">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </div>
                                        <?php else: ?>
                                        <span class="text-muted small fst-italic">Selesai</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../../views/layouts/footer.php'; ?>
