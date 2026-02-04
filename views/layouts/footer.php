
<?php if (defined('APP_NAME')): ?>
<footer class='text-white py-5 mt-auto' style='background: var(--dark-bg);'>
    <div class='container'>
        <div class='row g-4'>
            <div class='col-lg-4 mb-4 mb-lg-0'>
                <h5 class='mb-3 text-white fw-bold'><i class='fas fa-heartbeat me-2'></i><?= APP_NAME ?></h5>
                <p class='text-white-50 small'>Platform layanan kesehatan digital terpadu. Konsultasi dokter dan booking jadwal dengan mudah dan cepat.</p>
            </div>
            <div class='col-lg-4 mb-4 mb-lg-0'>
                <h5 class='mb-3 text-white fw-bold'>Tautan Cepat</h5>
                <ul class='list-unstyled'>
                    <li class='mb-2'><a href='<?= BASE_URL ?>' class='text-white-50 text-decoration-none transition-all hover-white'>Beranda</a></li>
                    <?php if (!is_logged_in()): ?>
                    <li class='mb-2'><a href='<?= BASE_URL ?>/views/auth/login.php' class='text-white-50 text-decoration-none transition-all hover-white'>Login</a></li>
                    <li class='mb-2'><a href='<?= BASE_URL ?>/views/auth/register.php' class='text-white-50 text-decoration-none transition-all hover-white'>Daftar</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class='col-lg-4'>
                <h5 class='mb-3 text-white fw-bold'>Hubungi Kami</h5>
                <ul class='list-unstyled text-white-50 small'>
                    <li class='mb-2'><i class='fas fa-envelope me-2'></i>support@layanankesehatan.com</li>
                    <li class='mb-2'><i class='fas fa-phone me-2'></i>+62 123 4567 890</li>
                    <li class='mb-2'><i class='fas fa-map-marker-alt me-2'></i>Jakarta, Indonesia</li>
                </ul>
            </div>
        </div>
        <hr class='my-4 border-light opacity-25'>
        <div class='text-center text-white-50 small'>
            &copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.
        </div>
        <div class='text-center text-white-50 small mt-2'>
            <strong>23552011317_Ardelia Luthfiani_TIF RP-23 CNS B_UASWEB1</strong>
        </div>
    </div>
</footer>
<?php endif; ?>

<script src='<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js'></script>
</body>
</html>
