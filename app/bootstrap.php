<?php
/**
 * Bootstrap Application
 * =====================
 * Include this file at the top of every page.
 */

// Prevent multiple includes
if (defined('APP_LOADED')) {
    return;
}
define('APP_LOADED', true);
define('APP_ROOT', dirname(__DIR__));

// =====================
// SESSION
// =====================
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_lifetime', 86400 * 7);
    ini_set('session.cookie_path', '/');
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Lax');
    
    session_name('LKSESSID');
    session_start();
}

// =====================
// LOAD CORE FILES
// =====================
require_once APP_ROOT . '/app/config.php';
require_once APP_ROOT . '/app/helpers.php';
require_once APP_ROOT . '/app/middleware.php';
