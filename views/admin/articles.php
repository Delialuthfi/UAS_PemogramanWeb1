<?php
require_once __DIR__ . '/../../app/bootstrap.php';
require_admin();

$page_title = 'Manajemen Artikel - ' . APP_NAME;
$articles = $conn->query('SELECT id, judul, isi, gambar, created_at FROM articles ORDER BY created_at DESC');
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
                        <a href="<?= BASE_URL ?>/views/admin/schedules.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1">
                            <i class="fas fa-clock me-3" style="width: 20px;"></i>Jadwal
                        </a>
                        <a href="<?= BASE_URL ?>/views/admin/articles.php" class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-2 mb-1 active-menu">
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
                <h2 class="fw-bold text-dark m-0"><i class="fas fa-newspaper text-primary me-2"></i>Manajemen Artikel</h2>
                <button class="btn btn-primary shadow-sm" onclick="toggleForm()">
                    <i class="fa fa-plus me-2"></i>Tambah Artikel Baru
                </button>
            </div>
            
            <!-- Form Tambah Artikel -->
            <div id="formAddArticle" class="mb-4" style="display: none;">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0 fw-bold text-primary">Form Tambah Artikel</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="../../controllers/ArticleController.php?action=create" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="judul" class="form-label text-muted small fw-bold text-uppercase">Judul Artikel</label>
                                        <input type="text" class="form-control" id="judul" name="judul" required placeholder="Masukkan judul artikel...">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="isi" class="form-label text-muted small fw-bold text-uppercase">Isi Artikel</label>
                                        <textarea class="form-control" id="isi" name="isi" rows="8" required placeholder="Tulis isi artikel disini..."></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="gambar" class="form-label text-muted small fw-bold text-uppercase">Gambar Artikel</label>
                                        <div class="card bg-light border-0 p-3 text-center mb-2">
                                            <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                            <small class="text-muted d-block">Preview Gambar</small>
                                        </div>
                                        <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                                        <small class="text-muted">Format: JPG, PNG, GIF</small>
                                    </div>
                                    
                                    <div class="d-grid gap-2 mt-4">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save me-2"></i>Simpan Artikel</button>
                                        <button type="button" class="btn btn-light text-muted" onclick="toggleForm()">Batal</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Daftar Artikel -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 fw-bold">Daftar Artikel Publikasi</h5>
                </div>
                <div class="card-body p-0">
                    <?php if($articles->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 border-0 text-muted small text-uppercase fw-bold" width="5%">No</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold" width="25%">Judul</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold" width="35%">Isi (Preview)</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold text-center" width="10%">Gambar</th>
                                    <th class="py-3 border-0 text-muted small text-uppercase fw-bold" width="15%">Tanggal</th>
                                    <th class="pe-4 py-3 border-0 text-muted small text-uppercase fw-bold text-center" width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                while($a = $articles->fetch_assoc()): 
                                ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted"><?= $no++ ?></td>
                                    <td><div class="fw-bold text-dark"><?= htmlspecialchars($a['judul']) ?></div></td>
                                    <td>
                                        <small class="text-muted"><?= htmlspecialchars(substr($a['isi'], 0, 100)) ?>...</small>
                                    </td>
                                    <td class="text-center">
                                        <?php if($a['gambar']): ?>
                                        <img src="../../assets/images/<?= htmlspecialchars($a['gambar']) ?>" class="rounded shadow-sm" alt="Thumbnail" style="width: 60px; height: 40px; object-fit: cover;">
                                        <?php else: ?>
                                        <span class="badge bg-light text-muted border">No Img</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="small fw-bold text-dark"><?= date('d M Y', strtotime($a['created_at'])) ?></div>
                                        <div class="small text-muted"><?= date('H:i', strtotime($a['created_at'])) ?> WIB</div>
                                    </td>
                                    <td class="pe-4 text-center">
                                        <a href="../../controllers/ArticleController.php?action=delete&id=<?= $a['id'] ?>" 
                                           class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Hapus artikel ini?')">
                                            <i class="fa fa-trash-alt me-1"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="p-5 text-center">
                        <img src="../../assets/images/empty-state.svg" alt="No Data" class="mb-3" style="max-width: 150px; opacity: 0.5;">
                        <div class="text-muted"><i class="fa fa-info-circle me-2"></i>Belum ada artikel yang dipublikasikan.</div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleForm() {
    const form = document.getElementById('formAddArticle');
    if(form.style.display === 'none') {
        form.style.display = 'block';
        // Auto scroll to form
        form.scrollIntoView({behavior: 'smooth'});
    } else {
        form.style.display = 'none';
    }
}
</script>
<?php require_once '../../views/layouts/footer.php'; ?>

