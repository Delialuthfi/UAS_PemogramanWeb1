<?php
require_once __DIR__ . '/../../app/bootstrap.php';
require_login();

if (is_admin()) {
    redirect('/views/admin/dashboard.php');
}

$page_title = 'Daftar Dokter - ' . APP_NAME;
$filter = '';
$where = [];
if (!empty($_GET['spesialis'])) {
    $where[] = "spesialis='".$conn->real_escape_string($_GET['spesialis'])."'";
}
if (!empty($_GET['hari'])) {
    $where[] = "id IN (SELECT doctor_id FROM schedules WHERE hari='".$conn->real_escape_string($_GET['hari'])."')";
require_once __DIR__ . '/../layouts/header.php';;
}
if (!empty($_GET['tarif_min'])) {
    $where[] = "tarif >= ".intval($_GET['tarif_min']);
}
if (!empty($_GET['tarif_max'])) {
    $where[] = "tarif <= ".intval($_GET['tarif_max']);
}
if ($where) $filter = 'WHERE '.implode(' AND ', $where);
$doctors = $conn->query("SELECT * FROM doctors $filter ORDER BY nama");
$spesialis = $conn->query("SELECT DISTINCT spesialis FROM doctors");
$hari = $conn->query("SELECT DISTINCT hari FROM schedules");

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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark m-0"><i class="fas fa-user-md text-primary me-2"></i>Cari Dokter</h2>
            </div>
    
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <form class="row g-3" method="GET">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Spesialis</label>
                            <select name="spesialis" class="form-select border-0 bg-light">
                                <option value="">Semua Spesialis</option>
                                <?php foreach($spesialis as $s): ?>
                                <option value="<?= htmlspecialchars($s['spesialis']) ?>" <?= (isset($_GET['spesialis']) && $_GET['spesialis']==$s['spesialis'])?'selected':'' ?>><?= htmlspecialchars($s['spesialis']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Hari</label>
                            <select name="hari" class="form-select border-0 bg-light">
                                <option value="">Semua Hari</option>
                                <?php foreach($hari as $h): ?>
                                <option value="<?= htmlspecialchars($h['hari']) ?>" <?= (isset($_GET['hari']) && $_GET['hari']==$h['hari'])?'selected':'' ?>><?= htmlspecialchars($h['hari']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted text-uppercase">Min. Tarif</label>
                            <input type="number" name="tarif_min" class="form-control border-0 bg-light" placeholder="Rp 0" value="<?= htmlspecialchars($_GET['tarif_min']??'') ?>">
                        </div>
                        <div class="col-md-2">
                             <label class="form-label small fw-bold text-muted text-uppercase">Max. Tarif</label>
                            <input type="number" name="tarif_max" class="form-control border-0 bg-light" placeholder="Rp Max" value="<?= htmlspecialchars($_GET['tarif_max']??'') ?>">
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fas fa-search me-2"></i>Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-4">
                <?php while($d = $doctors->fetch_assoc()): ?>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="position-relative">
                            <?php if($d['foto']): ?>
                                <img src="../../assets/images/<?= htmlspecialchars($d['foto']) ?>" class="card-img-top" style="height:220px; object-fit:cover" alt="<?= htmlspecialchars($d['nama']) ?>">
                            <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height:220px;">
                                    <i class="fas fa-user-md text-muted opacity-25 fa-4x"></i>
                                </div>
                            <?php endif; ?>
                            <div class="position-absolute bottom-0 start-0 w-100 p-2 bg-gradient-dark opacity-75"></div>
                        </div>
                        <div class="card-body text-center p-3">
                            <h6 class="card-title fw-bold text-dark mb-1"><?= htmlspecialchars($d['nama']) ?></h6>
                            <p class="card-text text-muted small mb-2"><?= htmlspecialchars($d['spesialis']) ?></p>
                            <p class="fw-bold text-primary mb-3">Rp <?= number_format($d['tarif'],0,',','.') ?></p>
                            <a href="doctor_detail.php?id=<?= $d['id'] ?>" class="btn btn-outline-primary btn-sm w-100 rounded-pill">Lihat Jadwal</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once '../../views/layouts/footer.php'; ?>
