<?php
/**
 * Header Layout
 */
if (!defined('APP_LOADED')) {
    require_once __DIR__ . '/../../app/bootstrap.php';
}

$current_user = current_user();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? APP_NAME ?></title>
    <link href="<?= BASE_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/modern-theme.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top navbar-light bg-white border-bottom shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="<?= BASE_URL ?>">
            <i class="fas fa-heartbeat me-2"></i><?= APP_NAME ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>"><i class="fas fa-home me-1"></i>Home</a>
                </li>
                
                <?php if (is_logged_in()): ?>
                    <?php if (is_admin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/views/admin/dashboard.php">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard Admin
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/views/user/dashboard.php">
                                <i class="fas fa-columns me-1"></i>Dashboard
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle btn btn-light rounded-pill px-3" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i><?= e($current_user['nama']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li class="px-3 py-2 border-bottom">
                                <div class="fw-bold text-dark"><?= e($current_user['nama']) ?></div>
                                <div class="text-muted small"><?= e($current_user['email']) ?></div>
                            </li>
                            <?php if (!is_admin()): ?>
                            <li>
                                <a class="dropdown-item py-2" href="<?= BASE_URL ?>/views/user/profile.php">
                                    <i class="fas fa-user-edit me-2 text-primary"></i> Edit Profil
                                </a>
                            </li>
                            <?php endif; ?>
                            <li>
                                <a class="dropdown-item py-2 text-danger" href="<?= BASE_URL ?>/controllers/AuthController.php?action=logout">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link fw-medium" href="<?= BASE_URL ?>/views/auth/login.php">Masuk</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="nav-link btn btn-primary text-white px-4 rounded-pill shadow-sm hover-lift" href="<?= BASE_URL ?>/views/auth/register.php">Daftar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
