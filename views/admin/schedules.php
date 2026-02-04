<?php
require_once __DIR__ . '/../../app/bootstrap.php';
require_admin();

$page_title = 'Manajemen Jadwal - ' . APP_NAME;

// Put doctors in array to avoid result pointer issues
$doctors_result = $conn->query('SELECT * FROM doctors');
$doctors_list = [];
while($d = $doctors_result->fetch_assoc()) {
    $doctors_list[] = $d;
}

$schedules_result = $conn->query('SELECT schedules.*, doctors.nama as doctor_nama FROM schedules JOIN doctors ON schedules.doctor_id = doctors.id ORDER BY schedules.id DESC');
$schedules_list = [];
while($s = $schedules_result->fetch_assoc()) {
    $schedules_list[] = $s;
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
                        <a href="<?= BASE_URL ?>/views/admin/schedules.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1 active-menu">
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
                <h2 class="fw-bold text-dark m-0"><i class="fas fa-clock text-primary me-2"></i>Manajemen Jadwal</h2>
                <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="fas fa-plus me-2"></i>Tambah Jadwal
                </button>
            </div>
    
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 fw-bold">Jadwal Praktik Dokter</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 border-0 text-muted small text-uppercase fw-bold">Dokter</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Hari Praktik</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Jam Praktik</th>
                                    <th class="pe-4 py-3 border-0 text-muted small text-uppercase fw-bold text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($schedules_list as $s): ?>
                                <tr>
                                    <td class="ps-4 py-3 fw-bold text-dark"><?= htmlspecialchars($s['doctor_nama']) ?></td>
                                    <td><span class="badge bg-light text-dark border px-3 py-2 fw-normal"><?= htmlspecialchars($s['hari']) ?></span></td>
                                    <td class="text-muted"><i class="far fa-clock me-1 text-primary"></i> <?= htmlspecialchars($s['jam_mulai']) ?> - <?= htmlspecialchars($s['jam_selesai']) ?></td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $s['id'] ?>">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="../../controllers/ScheduleController.php?action=delete&id=<?= $s['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Loop - Placed Outside Table -->
<?php foreach($schedules_list as $s): ?>
<!-- Modal Edit: <?= $s['id'] ?> -->
<div class="modal fade" id="modalEdit<?= $s['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content border-0 shadow" method="POST" action="../../controllers/ScheduleController.php?action=update">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Edit Jadwal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                <div class="mb-3">
                    <label class="form-label small text-muted text-uppercase fw-bold">Dokter</label>
                    <select name="doctor_id" class="form-select" required>
                        <?php foreach($doctors_list as $d): ?>
                        <option value="<?= $d['id'] ?>" <?= $d['id']==$s['doctor_id']?'selected':'' ?>><?= htmlspecialchars($d['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small text-muted text-uppercase fw-bold">Hari</label>
                    <select name="hari" class="form-select" required>
                        <?php foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $day): ?>
                            <option value="<?= $day ?>" <?= $s['hari'] == $day ? 'selected' : '' ?>><?= $day ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label small text-muted text-uppercase fw-bold">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control" value="<?= $s['jam_mulai'] ?>" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label small text-muted text-uppercase fw-bold">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control" value="<?= $s['jam_selesai'] ?>" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content border-0 shadow" method="POST" action="../../controllers/ScheduleController.php?action=create">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Tambah Jadwal Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small text-muted text-uppercase fw-bold">Dokter</label>
                    <select name="doctor_id" class="form-select" required>
                        <option value="" disabled selected>Pilih Dokter...</option>
                        <?php foreach($doctors_list as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small text-muted text-uppercase fw-bold">Hari</label>
                    <select name="hari" class="form-select" required>
                        <option value="" disabled selected>Pilih Hari...</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label small text-muted text-uppercase fw-bold">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label small text-muted text-uppercase fw-bold">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
      </div>
    </form>
  </div>
</div>
<?php require_once '../../views/layouts/footer.php'; ?>
