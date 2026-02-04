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
define('BASE_URL', '/layanan_kesehatan');

// =====================
// ERROR HANDLING
// =====================
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', APP_ROOT . '/logs/error.log');

// =====================
// DATABASE
// =====================
class Database {
    private static $instance = null;
    private $conn;
    
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $name = 'layanan_kesehatan';
    
    private function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);
        if ($this->conn->connect_error) {
            error_log('Database connection failed: ' . $this->conn->connect_error);
            die('Database connection failed');
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
