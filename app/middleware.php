<?php
/**
 * Authentication Middleware
 * =========================
 */

/**
 * Check if user is logged in
 */
function is_logged_in(): bool {
    return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
}

/**
 * Check if user is admin
 */
function is_admin(): bool {
    return is_logged_in() && $_SESSION['user']['role'] === 'admin';
}

/**
 * Get current user data
 */
function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

/**
 * Require login - redirect to login if not authenticated
 */
function require_login(): void {
    if (!is_logged_in()) {
        set_flash('error', 'Silakan login terlebih dahulu');
        redirect('/views/auth/login.php');
    }
}

/**
 * Require admin role
 */
function require_admin(): void {
    require_login();
    if (!is_admin()) {
        set_flash('error', 'Akses ditolak');
        redirect('/views/auth/login.php');
    }
}

/**
 * Require user role (non-admin)
 */
function require_user(): void {
    require_login();
    if (is_admin()) {
        redirect('/views/admin/dashboard.php');
    }
}
