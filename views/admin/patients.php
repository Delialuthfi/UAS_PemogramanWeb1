<?php
require_once __DIR__ . '/../../app/bootstrap.php';
require_admin();

$page_title = 'Manajemen Pasien - ' . APP_NAME;
$patients = $conn->query('SELECT id, nama, email, no_hp, created_at FROM users WHERE role="user" ORDER BY created_at DESC');
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
                        <a href="<?= BASE_URL ?>/views/admin/bookings.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1">
                            <i class="fas fa-calendar-check me-3" style="width: 20px;"></i>Booking
                        </a>
                        <a href="<?= BASE_URL ?>/views/admin/patients.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1 active-menu">
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
                <h2 class="fw-bold text-dark m-0"><i class="fas fa-users text-primary me-2"></i>Manajemen Pasien</h2>
                <div class="text-muted small"><?= date('l, d F Y') ?></div>
            </div>
    
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 fw-bold">Daftar Pasien Terdaftar</h5>
                </div>
                <div class="card-body p-0">
                    <?php if($patients->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 border-0 text-muted small text-uppercase fw-bold">No</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Nama Pasien</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Email</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Telepon</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Tanggal Daftar</th>
                                    <th class="pe-4 py-3 border-0 text-muted small text-uppercase fw-bold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                while($p = $patients->fetch_assoc()): 
                                ?>
                                <tr>
                                    <td class="ps-4 fw-medium"><?= $no++ ?></td>
                                    <td class="fw-bold text-dark"><?= htmlspecialchars($p['nama']) ?></td>
                                    <td class="text-muted"><?= htmlspecialchars($p['email']) ?></td>
                                    <td class="text-muted"><?= htmlspecialchars($p['no_hp'] ?? '-') ?></td>
                                    <td class="text-muted small"><?= date('d M Y, H:i', strtotime($p['created_at'])) ?></td>
                                    <td class="pe-4">
                                        <a href="../../controllers/AuthController.php?action=delete_patient&id=<?= $p['id'] ?>" 
                                        class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Hapus pasien ini? Data booking akan tetap tersimpan.')">
                                            <i class="fa fa-trash me-1"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="p-5 text-center">
                        <div class="icon-shape bg-light text-muted rounded-circle mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-users-slash fs-3"></i>
                        </div>
                        <p class="text-muted mb-0">Belum ada pasien yang terdaftar.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../../views/layouts/footer.php'; ?>
