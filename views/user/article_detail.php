<?php
require_once __DIR__ . '/../../app/bootstrap.php';
require_login();
if (current_user()['role'] !== 'user') {
    redirect('/views/auth/login.php');
}
require_once __DIR__ . '/../layouts/header.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$article = $conn->query("SELECT * FROM articles WHERE id=$id")->fetch_assoc();
if (!$article) {
    echo '<div class="container py-5"><div class="alert alert-danger d-flex align-items-center"><i class="fas fa-exclamation-triangle me-2"></i>Artikel tidak ditemukan.</div></div>';
    require_once '../../views/layouts/footer.php';
    exit;
}
?>
<div class="container-fluid bg-light min-vh-100 py-5">
    <div class="container" style="max-width: 800px;">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/views/user/dashboard.php" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Artikel</li>
            </ol>
        </nav>

        <div class="card border-0 shadow-sm overflow-hidden">
            <?php if ($article['gambar']): ?>
                <div class="position-relative" style="height: 400px;">
                    <img src="../../assets/images/<?= htmlspecialchars($article['gambar']) ?>" class="w-100 h-100 object-fit-cover" alt="<?= htmlspecialchars($article['judul']) ?>">
                    <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                        <!-- Overlay gradient for text readability if needed -->
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="card-body p-4 p-md-5">
                <div class="mb-3">
                    <span class="badge bg-primary text-white rounded-pill px-3 py-2">Info Kesehatan</span>
                </div>
                
                <h1 class="fw-bold mb-3 text-dark display-6"><?= htmlspecialchars($article['judul']) ?></h1>
                
                <div class="d-flex align-items-center text-muted mb-4 pb-4 border-bottom">
                    <div class="d-flex align-items-center me-4">
                        <i class="far fa-calendar-alt me-2"></i>
                        <?= date('d M Y', strtotime($article['created_at'])) ?>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="far fa-user me-2"></i>
                        Admin
                    </div>
                </div>
                
                <div class="article-content text-dark opacity-75" style="line-height: 1.8; font-size: 1.1rem; white-space: pre-line;">
                    <?= nl2br(htmlspecialchars($article['isi'])) ?>
                </div>
            </div>
            
            <div class="card-footer bg-white border-top p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <button onclick="window.history.back()" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </button>
                    <div class="text-muted small">
                        Bagikan: 
                        <a href="#" class="text-secondary mx-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-secondary mx-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-secondary mx-2"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../../views/layouts/footer.php'; ?>
