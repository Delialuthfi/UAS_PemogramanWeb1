<?php
require_once __DIR__ . '/../../app/bootstrap.php';
require_admin();

$page_title = 'Manajemen Dokter - ' . APP_NAME;
$doctors = $conn->query('SELECT * FROM doctors ORDER BY id DESC');
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
                        <a href="<?= BASE_URL ?>/views/admin/doctors.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1 active-menu">
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
                <h2 class="fw-bold text-dark m-0"><i class="fas fa-user-md text-primary me-2"></i>Manajemen Dokter</h2>
            </div>
            
            <div class="row g-4">
                <!-- Form Tambah Dokter -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom p-4">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-plus-circle text-primary me-2"></i>Tambah Dokter Baru</h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="../../controllers/DoctorController.php?action=create" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label small text-uppercase fw-bold text-muted">Nama Lengkap *</label>
                                    <input type="text" name="nama" class="form-control" placeholder="Contoh: dr. Budi Santoso, Sp.PD" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-uppercase fw-bold text-muted">Spesialis *</label>
                                    <input type="text" name="spesialis" class="form-control" placeholder="Contoh: Penyakit Dalam" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-uppercase fw-bold text-muted">Tarif Konsultasi (Rp) *</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">Rp</span>
                                        <input type="number" name="tarif" class="form-control border-start-0 ps-0" placeholder="0" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-uppercase fw-bold text-muted">No. Telepon</label>
                                    <input type="text" name="telepon" class="form-control" placeholder="0812...">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-uppercase fw-bold text-muted">Deskripsi *</label>
                                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi singkat dokter..." required></textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label small text-uppercase fw-bold text-muted">Foto Profil</label>
                                    <input type="file" name="foto" class="form-control">
                                    <div class="form-text small">Format: JPG, PNG. Maks 2MB.</div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                                    <i class="fas fa-save me-2"></i>Simpan Data Dokter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Daftar Dokter -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom p-4">
                            <h5 class="mb-0 fw-bold">Daftar Dokter Tersedia</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4 py-3 border-0 text-muted small text-uppercase fw-bold">Info Dokter</th>
                                            <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Tarif</th>
                                            <th class="py-3 border-0 text-muted small text-uppercase fw-bold">Kontak</th>
                                            <th class="pe-4 py-3 border-0 text-muted small text-uppercase fw-bold text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $no = 1;
                                        while($d = $doctors->fetch_assoc()): 
                                        ?>
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <?php if($d['foto']): ?>
                                                    <img src="../../assets/images/<?= htmlspecialchars($d['foto']) ?>" class="rounded-circle me-3 object-fit-cover shadow-sm" width="48" height="48">
                                                    <?php else: ?>
                                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3 text-secondary" style="width: 48px; height: 48px;">
                                                        <i class="fas fa-user-md"></i>
                                                    </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <div class="fw-bold text-dark"><?= htmlspecialchars($d['nama']) ?></div>
                                                        <div class="text-muted small"><?= htmlspecialchars($d['spesialis']) ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-medium text-primary">Rp<?= number_format($d['tarif'],0,',','.') ?></td>
                                            <td class="text-muted small">
                                                <div class="mb-1"><i class="fas fa-phone me-1"></i><?= htmlspecialchars($d['telepon'] ?? '-') ?></div>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <div class="btn-group">
                                                    <a href="edit_doctor.php?id=<?= $d['id'] ?>" class="btn btn-outline-primary btn-sm" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="../../controllers/DoctorController.php?action=delete&id=<?= $d['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus dokter ini?')" title="Hapus">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </div>
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
    </div>
</div>

<!-- Single Modal Edit untuk Semua Dokter -->
<div class="modal fade" id="editDoctorModal" tabindex="-1" aria-labelledby="editDoctorLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="../../controllers/DoctorController.php?action=update" enctype="multipart/form-data" id="editDoctorForm">
            <div class="modal-header">
                <h5 class="modal-title" id="editDoctorLabel">Edit Dokter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="doctorId">
                <div class="mb-3">
                    <label for="editNama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="editNama" name="nama" required>
                </div>
                <div class="mb-3">
                    <label for="editSpesialis" class="form-label">Spesialis</label>
                    <input type="text" class="form-control" id="editSpesialis" name="spesialis" required>
                </div>
                <div class="mb-3">
                    <label for="editTarif" class="form-label">Tarif</label>
                    <input type="number" class="form-control" id="editTarif" name="tarif" required>
                </div>
                <div class="mb-3">
                    <label for="editTelepon" class="form-label">No. Telepon</label>
                    <input type="text" class="form-control" id="editTelepon" name="telepon">
                </div>
                <div class="mb-3">
                    <label for="editDeskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="editDeskripsi" name="deskripsi" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="editFoto" class="form-label">Foto</label>
                    <input type="file" class="form-control" id="editFoto" name="foto">
                    <input type="hidden" name="foto_lama" id="fotoLama">
                    <div id="currentFoto" class="mt-2"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Form Tambah Jadwal Dokter -->
<div class="card mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Tambah Jadwal Dokter</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="../../controllers/ScheduleController.php?action=create">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Pilih Dokter *</label>
                    <select name="doctor_id" class="form-select" required>
                        <option value="">-- Pilih Dokter --</option>
                        <?php 
                        $doctors_for_schedule = $conn->query('SELECT id, nama FROM doctors ORDER BY nama');
                        while($doc = $doctors_for_schedule->fetch_assoc()): ?>
                        <option value="<?= $doc['id'] ?>"><?= htmlspecialchars($doc['nama']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Hari (Tanggal) *</label>
                    <input type="date" name="hari" class="form-control" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label>Jam Mulai *</label>
                    <input type="time" name="jam_mulai" class="form-control" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label>Jam Selesai *</label>
                    <input type="time" name="jam_selesai" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-info">
                <i class="fa fa-plus"></i> Tambah Jadwal
            </button>
        </form>
    </div>
</div>

<?php require_once '../../views/layouts/footer.php'; ?>
