<?php
/**
 * Application Configuration
 * =========================
 * Database, constants, and core settings
 */

// Prevent direct access
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

// =====================
// CONSTANTS
// =====================
define('APP_NAME', 'Layanan Kesehatan');
define('APP_VERSION', '2.0.0');
// BASE_URL is now defined dynamically below based on environment

// =====================
// ERROR HANDLING
// =====================
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', APP_ROOT . '/logs/error.log');

// =====================
// DATABASE CONFIG
// =====================
// Default Localhost
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'layanan_kesehatan';

// Production (AnymHost) - Silakan ganti ini nanti di server
if ($_SERVER['HTTP_HOST'] === 'ardeliaweb.my.id' || $_SERVER['HTTP_HOST'] === 'www.ardeliaweb.my.id') {
    $db_host = 'localhost'; 
    $db_user = 'dasvtkap_adel'; // Updated based on your screenshot
    $db_pass = 'DB_PASSWORD_ANDA';   // ganti dengan password database yg anda buat di cpanel
    $db_name = 'dasvtkap_adel';    // Updated based on your screenshot
    
    // Update Base URL untuk production
    if (!defined('BASE_URL_FIXED')) {
         define('BASE_URL_FIXED', 'https://ardeliaweb.my.id'); 
    }
}

// Ensure BASE_URL is set correctly if not fixed
if (!defined('BASE_URL')) {
    if (defined('BASE_URL_FIXED')) {
        define('BASE_URL', BASE_URL_FIXED);
    } else {
        // Auto-detect for localhost
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $script_dir = dirname($_SERVER['SCRIPT_NAME']);
        // Strip out any trailing slash
        $script_dir = rtrim($script_dir, '/');
        // Handle root directory case
        if ($script_dir === '\\' || $script_dir === '/') {
            $script_dir = '';
        }
        define('BASE_URL', $protocol . $_SERVER['HTTP_HOST'] . $script_dir);
    }
}

// =====================
// DATABASE CLASS
// =====================
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        global $db_host, $db_user, $db_pass, $db_name;
        
        $this->conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if ($this->conn->connect_error) {
            error_log('Database connection failed: ' . $this->conn->connect_error);
            // Hide detailed error in production
            if (strpos($_SERVER['HTTP_HOST'], 'localhost') === false) {
                 die('Maaf, sedang ada gangguan koneksi ke database. Silakan coba beberapa saat lagi.');
            } else {
                 die('Database connection failed: ' . $this->conn->connect_error);
            }
        }
        $this->conn->set_charset('utf8mb4');
    }
    
    public static function getInstance(): mysqli {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->conn;
    }
}

// Global database connection
$conn = Database::getInstance();
