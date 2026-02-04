<?php
/**
 * Helper Functions
 * =================
 */

/**
 * Escape HTML output
 */
function e(string $string): string {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Get and clear flash message
 */
function flash(string $key): ?string {
    $message = $_SESSION[$key] ?? null;
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
    }
    return $message;
}

/**
 * Set flash message
 */
function set_flash(string $key, string $message): void {
    $_SESSION[$key] = $message;
}

/**
 * Redirect to URL
 */
function redirect(string $path): void {
    header('Location: ' . BASE_URL . $path);
    exit;
}

/**
 * Return JSON response
 */
function json_response(array $data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Check if request is AJAX
 */
function is_ajax(): bool {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Get request method
 */
function request_method(): string {
    return $_SERVER['REQUEST_METHOD'];
}

/**
 * Get POST data safely
 */
function post(string $key, $default = null) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
}

/**
 * Get GET data safely
 */
function get(string $key, $default = null) {
    return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
}
