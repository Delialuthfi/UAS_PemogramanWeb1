<?php
/**
 * Homepage - Modern Blue Theme
 */
require_once __DIR__ . '/app/bootstrap.php';

$page_title = APP_NAME . ' - Layanan Kesehatan Digital';

// Get stats
$total_dokter = $conn->query("SELECT COUNT(*) FROM doctors")->fetch_row()[0];
$total_pasien = $conn->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetch_row()[0];
$total_booking = $conn->query("SELECT COUNT(*) FROM bookings")->fetch_row()[0];
$total_artikel = $conn->query("SELECT COUNT(*) FROM articles")->fetch_row()[0];

require_once __DIR__ . '/views/layouts/header.php';
?>

<main>
    <!-- Hero Section -->
    <section class='hero-section bg-primary text-white'>
        <div class='container'>
            <div class='row align-items-center'>
                <div class='col-lg-6'>
                    <h1 class='display-4 fw-bold mb-4 text-white'>Layanan Kesehatan Digital Terpercaya</h1>
                    <p class='lead mb-4 text-white opacity-75'>Solusi modern untuk konsultasi dan booking dokter secara online. Mudah, cepat, dan terpercaya!</p>
                    <div class='d-flex gap-3'>
                        <?php if (is_logged_in()): ?>
                            <a href='<?= BASE_URL ?>/views/<?= is_admin() ? 'admin' : 'user' ?>/dashboard.php' class='btn btn-light btn-lg'>
                                <i class='fas fa-tachometer-alt me-2'></i>Dashboard
                            </a>
                        <?php else: ?>
                            <a href='<?= BASE_URL ?>/views/auth/register.php' class='btn btn-light btn-lg'>
                                <i class='fas fa-user-plus me-2'></i>Daftar Gratis
                            </a>
                            <a href='<?= BASE_URL ?>/views/auth/login.php' class='btn btn-outline-light btn-lg'>
                                <i class='fas fa-sign-in-alt me-2'></i>Login
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class='col-lg-6 text-center mt-5 mt-lg-0'>
                    <i class='fas fa-heartbeat text-white' style='font-size: 200px; opacity: 0.2;'></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class='py-5 bg-white text-dark'>
        <div class='container py-5'>
            <div class='text-center mb-5'>
                <h2 class='section-title text-dark'>Layanan Utama</h2>
                <p class='text-secondary'>Kami menyediakan layanan kesehatan terbaik untuk Anda dan keluarga.</p>
            </div>
            
            <div class='row g-4'>
                <div class='col-md-4'>
                    <div class='card h-100 p-4 text-center'>
                        <div class='card-body'>
                            <div class='d-flex justify-content-center'>
                                <div class='icon-box'>
                                    <i class='fas fa-calendar-check'></i>
                                </div>
                            </div>
                            <h3>Booking Online</h3>
                            <p>Booking dokter spesialis sesuai kebutuhan Anda tanpa perlu antre lama di rumah sakit.</p>
                        </div>
                    </div>
                </div>
                <div class='col-md-4'>
                    <div class='card h-100 p-4 text-center'>
                        <div class='card-body'>
                            <div class='d-flex justify-content-center'>
                                <div class='icon-box'>
                                    <i class='fas fa-user-md'></i>
                                </div>
                            </div>
                            <h3>Konsultasi Dokter</h3>
                            <p>Konsultasi langsung dengan dokter berpengalaman melalui platform digital kami.</p>
                        </div>
                    </div>
                </div>
                <div class='col-md-4'>
                    <div class='card h-100 p-4 text-center'>
                        <div class='card-body'>
                            <div class='d-flex justify-content-center'>
                                <div class='icon-box'>
                                    <i class='fas fa-newspaper'></i>
                                </div>
                            </div>
                            <h3>Info Kesehatan</h3>
                            <p>Dapatkan tips dan artikel kesehatan terupdate untuk menjaga pola hidup sehat Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class='py-5 bg-light'>
        <div class='container'>
            <div class='row text-center'>
                <div class='col-md-3 mb-4'>
                    <div class='card h-100 p-3'>
                        <div class='card-body'>
                            <h2 class='display-4 fw-bold text-primary mb-0'><?= $total_dokter ?></h2>
                            <p class='text-muted'>Dokter Tersedia</p>
                        </div>
                    </div>
                </div>
                <div class='col-md-3 mb-4'>
                    <div class='card h-100 p-3'>
                        <div class='card-body'>
                            <h2 class='display-4 fw-bold text-primary mb-0'><?= $total_pasien ?></h2>
                            <p class='text-muted'>Pasien Terdaftar</p>
                        </div>
                    </div>
                </div>
                <div class='col-md-3 mb-4'>
                    <div class='card h-100 p-3'>
                        <div class='card-body'>
                            <h2 class='display-4 fw-bold text-primary mb-0'><?= $total_booking ?></h2>
                            <p class='text-muted'>Total Booking</p>
                        </div>
                    </div>
                </div>
                <div class='col-md-3 mb-4'>
                    <div class='card h-100 p-3'>
                        <div class='card-body'>
                            <h2 class='display-4 fw-bold text-primary mb-0'><?= $total_artikel ?></h2>
                            <p class='text-muted'>Artikel Kesehatan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <?php if (!is_logged_in()): ?>
    <section class='py-5 position-relative overflow-hidden'>
        <div class='container'>
            <div class='row justify-content-center'>
                <div class='col-lg-10'>
                    <div class='card bg-primary text-white text-center p-5 border-0'>
                        <div class='card-body position-relative z-1'>
                            <h2 class='text-white mb-4'>Siap Untuk Memulai?</h2>
                            <p class='lead text-white-50 mb-4'>Bergabunglah dengan ribuan pengguna lain untuk mendapatkan layanan kesehatan terbaik dari kami.</p>
                            <a href='<?= BASE_URL ?>/views/auth/register.php' class='btn btn-light btn-lg px-5'>
                                Daftar Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/views/layouts/footer.php'; ?>
