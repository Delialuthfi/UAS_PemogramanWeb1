<?php
/**
 * User Dashboard
 */
require_once __DIR__ . '/../../app/bootstrap.php';
require_login();

// Redirect admin to admin dashboard
if (is_admin()) {
    redirect('/views/admin/dashboard.php');
}

$page_title = 'Dashboard - ' . APP_NAME;
$userData = current_user();
$user_id = $userData['id'];

// Booking aktif (pending/confirmed)
$aktif = $conn->query("
    SELECT b.*, d.nama as doctor_nama, s.hari, s.jam_mulai, s.jam_selesai 
    FROM bookings b 
    JOIN doctors d ON b.doctor_id=d.id 
    JOIN schedules s ON b.schedule_id=s.id 
    WHERE b.user_id=$user_id AND (b.status='pending' OR b.status='confirmed') 
    ORDER BY b.id DESC LIMIT 1
")->fetch_assoc();

// Riwayat booking terbaru (semua status)
$riwayat = $conn->query("
    SELECT b.*, d.nama as doctor_nama, s.hari, s.jam_mulai, s.jam_selesai 
    FROM bookings b 
    JOIN doctors d ON b.doctor_id=d.id 
    JOIN schedules s ON b.schedule_id=s.id 
    WHERE b.user_id=$user_id 
    ORDER BY b.id DESC LIMIT 5
");

// Artikel kesehatan terbaru
$articles = $conn->query("SELECT * FROM articles ORDER BY created_at DESC LIMIT 3");

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid py-4 min-vh-100 bg-light">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <div class="card border-0 shadow-sm sticky-top" style="top: 2rem; z-index: 1;">
                <div class="card-body p-2">
                    <div class="list-group list-group-flush border-0">
                        <a href="<?= BASE_URL ?>/views/user/dashboard.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1 active-menu">
                            <i class="fas fa-tachometer-alt me-3" style="width: 20px;"></i>Dashboard
                        </a>
                        <a href="<?= BASE_URL ?>/views/user/doctors.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1">
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
            <!-- Welcome Header -->
            <div class="card bg-primary text-white border-0 shadow-sm mb-4 overflow-hidden position-relative animate-fade-in-up">
                <div class="card-body p-4 position-relative" style="z-index: 2;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-2 text-white">Halo, <?= htmlspecialchars($userData['nama']) ?>!</h2>
                            <p class="mb-0 text-white-50">Selamat datang kembali. Jangan lupa jaga kesehatan Anda hari ini.</p>
                        </div>
                        <div class="col-md-4 text-end d-none d-md-block">
                             <a href="<?= BASE_URL ?>/views/user/doctors.php" class="btn btn-light text-primary fw-bold px-4 hover-lift shadow-sm">
                                <i class="fas fa-plus me-2"></i>Booking Baru
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Decorative Circles -->
                <div class="position-absolute rounded-circle bg-white opacity-10" style="width: 200px; height: 200px; top: -50px; right: -50px; z-index: 1;"></div>
                <div class="position-absolute rounded-circle bg-white opacity-10" style="width: 100px; height: 100px; bottom: -20px; left: 10%; z-index: 1;"></div>
            </div>

            <div class="row g-4 mb-4">
                <!-- Booking Aktif Card -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-calendar-check me-2"></i>Booking Aktif</h5>
                        </div>
                        <div class="card-body">
                            <?php if ($aktif): ?>
                                <div class="alert alert-info border-0 d-flex align-items-center mb-3">
                                    <i class="fas fa-info-circle me-2 fs-5"></i>
                                    <div>Status: <strong><?= ucfirst($aktif['status']) ?></strong></div>
                                </div>
                                <div class="d-flex align-items-start mb-3">
                                    <div class="icon-shape bg-primary-subtle text-primary rounded-3 me-3">
                                        <i class="fas fa-user-md fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1"><?= htmlspecialchars($aktif['doctor_nama']) ?></h5>
                                        <p class="text-muted small mb-0">Dokter Spesialis</p>
                                    </div>
                                </div>
                                <hr class="border-light">
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Hari/Tanggal</small>
                                        <div class="fw-medium"><i class="far fa-calendar me-1 text-primary"></i> <?= htmlspecialchars($aktif['hari']) ?></div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Jam Konusltasi</small>
                                        <div class="fw-medium"><i class="far fa-clock me-1 text-primary"></i> <?= htmlspecialchars($aktif['jam_mulai']) ?> - <?= htmlspecialchars($aktif['jam_selesai']) ?></div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <div class="icon-shape bg-light text-muted rounded-circle mx-auto mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-calendar-times fs-3"></i>
                                    </div>
                                    <p class="text-muted mb-3">Tidak ada booking aktif saat ini.</p>
                                    <a href="<?= BASE_URL ?>/views/user/doctors.php" class="btn btn-primary btn-sm rounded-pill px-4">
                                        Cari Dokter
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats / Profile Summary -->
                <div class="col-md-6">
                     <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-chart-pie me-2"></i>Ringkasan Saya</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-3 text-center h-100 hover-lift">
                                        <h3 class="fw-bold text-dark mb-0"><?= $riwayat ? $riwayat->num_rows : 0 ?></h3>
                                        <p class="small text-muted mb-0">Total Kunjungan</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-3 text-center h-100 hover-lift">
                                        <h3 class="fw-bold text-dark mb-0"><?= $articles ? $articles->num_rows : 0 ?></h3>
                                        <p class="small text-muted mb-0">Artikel Dibaca</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="d-flex align-items-center p-3 border rounded-3">
                                    <div class="flex-shrink-0">
                                        <div class="icon-shape bg-success-subtle text-success rounded-circle">
                                            <i class="fas fa-user-circle fs-5"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0 fw-bold">Profil Lengkap</h6>
                                        <p class="small text-muted mb-0">Email: <?= htmlspecialchars($userData['email']) ?></p>
                                    </div>
                                    <a href="profile.php" class="btn btn-light btn-sm rounded-pill"><i class="fas fa-pen small"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Recent Articles -->
                <div class="col-lg-8">
                     <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-newspaper me-2"></i>Artikel Terbaru</h5>
                        </div>
                        <div class="card-body">
                             <?php if ($articles && $articles->num_rows > 0): ?>
                                <div class="row g-3">
                                    <?php while($article = $articles->fetch_assoc()): ?>
                                    <div class="col-md-4">
                                        <a href="article_detail.php?id=<?= $article['id'] ?>" class="text-decoration-none text-dark">
                                            <div class="card h-100 border hover-lift">
                                                <?php if($article['gambar']): ?>
                                                    <img src="../../assets/images/<?= htmlspecialchars($article['gambar']) ?>" class="card-img-top" alt="..." style="height: 120px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 120px;">
                                                        <i class="fas fa-image text-muted opacity-25 fa-2x"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="card-body p-3">
                                                    <h6 class="card-title fw-bold mb-2 text-truncate"><?= htmlspecialchars($article['judul']) ?></h6>
                                                    <p class="card-text small text-muted"><?= htmlspecialchars(date('d M Y', strtotime($article['created_at']))) ?></p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center py-3">Belum ada artikel.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Recent History list (Small) -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-history me-2"></i>Riwayat</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <?php if ($riwayat && $riwayat->num_rows > 0): ?>
                                    <?php while($hist = $riwayat->fetch_assoc()): 
                                        $badgeClass = 'secondary';
                                        if($hist['status'] == 'pending') $badgeClass = 'warning';
                                        elseif($hist['status'] == 'confirmed') $badgeClass = 'primary';
                                        elseif($hist['status'] == 'done') $badgeClass = 'success';
                                        elseif($hist['status'] == 'cancelled') $badgeClass = 'danger';
                                    ?>
                                        <div class="list-group-item px-3 py-3">
                                            <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                                <h6 class="mb-0 fw-bold small"><?= htmlspecialchars($hist['doctor_nama']) ?></h6>
                                                <span class="badge bg-<?= $badgeClass ?>-subtle text-<?= $badgeClass === 'primary' ? 'primary' : $badgeClass ?>-emphasis rounded-pill" style="font-size: 0.65rem;">
                                                    <?= strtoupper($hist['status']) ?>
                                                </span>
                                            </div>
                                            <small class="text-muted"><i class="far fa-clock me-1"></i> <?= htmlspecialchars(date('d M Y', strtotime($hist['created_at']))) ?></small>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="p-4 text-center text-muted small">Belum ada riwayat.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0 text-center py-3">
                            <a href="booking_history.php" class="text-decoration-none small fw-bold">Lihat Semua History</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../../views/layouts/footer.php'; ?>
