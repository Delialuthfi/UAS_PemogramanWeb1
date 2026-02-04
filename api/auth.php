<?php
/**
 * Auth API
 * ========
 * Endpoints: login, register, logout
 * 
 * Usage:
 *   POST /api/auth.php?action=login
 *   POST /api/auth.php?action=register  
 *   GET  /api/auth.php?action=logout
 */

require_once __DIR__ . '/../app/bootstrap.php';

$action = get('action', '');

switch ($action) {
    case 'login':
        handleLogin();
        break;
    case 'register':
        handleRegister();
        break;
    case 'logout':
        handleLogout();
        break;
    default:
        redirect('/views/auth/login.php');
}

/**
 * Handle Login
 */
function handleLogin(): void {
    global $conn;
    
    if (request_method() !== 'POST') {
        redirect('/views/auth/login.php');
    }
    
    $email = post('email', '');
    $password = post('password', '');
    
    // Validate
    if (empty($email) || empty($password)) {
        set_flash('error', 'Email dan password harus diisi');
        redirect('/views/auth/login.php');
    }
    
    // Query user
    $stmt = $conn->prepare('SELECT id, nama, email, no_hp, password, role FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    // Verify
    if (!$user || !password_verify($password, $user['password'])) {
        set_flash('error', 'Email atau password salah');
        redirect('/views/auth/login.php');
    }
    
    // Set session
    $_SESSION['user'] = [
        'id' => (int) $user['id'],
        'nama' => $user['nama'],
        'email' => $user['email'],
        'no_hp' => $user['no_hp'],
        'role' => $user['role']
    ];
    
    session_regenerate_id(true);
    
    // Redirect based on role
    if ($user['role'] === 'admin') {
        redirect('/views/admin/dashboard.php');
    } else {
        redirect('/views/user/dashboard.php');
    }
}

/**
 * Handle Register
 */
function handleRegister(): void {
    global $conn;
    
    if (request_method() !== 'POST') {
        redirect('/views/auth/register.php');
    }
    
    $nama = post('nama', '');
    $email = post('email', '');
    $no_hp = post('no_hp', '');
    $password = post('password', '');
    $password_confirm = post('password_confirm', '');
    
    // Validate
    if (empty($nama) || empty($email) || empty($no_hp) || empty($password)) {
        set_flash('error', 'Semua field harus diisi');
        redirect('/views/auth/register.php');
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash('error', 'Format email tidak valid');
        redirect('/views/auth/register.php');
    }
    
    if ($password !== $password_confirm) {
        set_flash('error', 'Password tidak cocok');
        redirect('/views/auth/register.php');
    }
    
    if (strlen($password) < 6) {
        set_flash('error', 'Password minimal 6 karakter');
        redirect('/views/auth/register.php');
    }
    
    // Check existing email
    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        set_flash('error', 'Email sudah terdaftar');
        $stmt->close();
        redirect('/views/auth/register.php');
    }
    $stmt->close();
    
    // Insert user
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare('INSERT INTO users (nama, email, no_hp, password, role) VALUES (?, ?, ?, ?, "user")');
    $stmt->bind_param('ssss', $nama, $email, $no_hp, $password_hash);
    
    if ($stmt->execute()) {
        set_flash('success', 'Registrasi berhasil! Silakan login.');
        redirect('/views/auth/login.php');
    } else {
        set_flash('error', 'Registrasi gagal');
        redirect('/views/auth/register.php');
    }
}

/**
 * Handle Logout
 */
function handleLogout(): void {
    $_SESSION = [];
    
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    session_destroy();
    
    header('Location: ' . BASE_URL . '/views/auth/login.php?logout=1');
    exit;
}
