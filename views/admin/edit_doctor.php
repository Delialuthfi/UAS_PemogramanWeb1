<?php
require_once __DIR__ . '/../../app/bootstrap.php';
require_admin();

require_once __DIR__ . '/../layouts/header.php';

$id = $_GET['id'] ?? 0;
$doctor = $conn->query("SELECT * FROM doctors WHERE id = $id")->fetch_assoc();

if (!$doctor) {
    header('Location: doctors.php');
    exit;
}
?>
<div class="container mt-5">
    <div class="d-flex align-items-center mb-3">
        <a href="doctors.php" class="btn btn-secondary me-2"><i class="fa fa-arrow-left"></i> Back</a>
        <h2 class="mb-0">Edit Dokter: <?= htmlspecialchars($doctor['nama']) ?></h2>
    </div>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Form Edit Dokter</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="../../controllers/DoctorController.php?action=update" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $doctor['id'] ?>">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama *</label>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($doctor['nama']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Spesialis *</label>
                        <input type="text" name="spesialis" class="form-control" value="<?= htmlspecialchars($doctor['spesialis']) ?>" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tarif (Rp) *</label>
                        <input type="number" name="tarif" class="form-control" value="<?= $doctor['tarif'] ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="telepon" class="form-control" value="<?= htmlspecialchars($doctor['telepon'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Deskripsi *</label>
                    <textarea name="deskripsi" class="form-control" rows="4" required><?= htmlspecialchars($doctor['deskripsi']) ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Foto</label>
                    <input type="file" name="foto" class="form-control">
                    <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($doctor['foto']) ?>">
                    <?php if($doctor['foto']): ?>
                    <div class="mt-3">
                        <small class="text-muted">Foto saat ini:</small><br>
                        <img src="../../assets/images/<?= htmlspecialchars($doctor['foto']) ?>" style="max-width: 150px; margin-top: 10px; border-radius: 4px; border: 1px solid #ddd; padding: 5px;">
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="doctors.php" class="btn btn-secondary">
                        <i class="fa fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../../views/layouts/footer.php'; ?>
