<?php
// Login Processor
require_once __DIR__ . '/../app/bootstrap.php';

// Only process POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/auth/login.php');
    exit;
}

// Get form data
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Validate
if (empty($email) || empty($password)) {
    $_SESSION['error'] = 'Email dan password harus diisi';
    header('Location: ../views/auth/login.php');
    exit;
}

// Query user
$stmt = $conn->prepare('SELECT id, nama, email, no_hp, password, role FROM users WHERE email = ?');
if (!$stmt) {
    $_SESSION['error'] = 'Database error';
    header('Location: ../views/auth/login.php');
    exit;
}

$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Check user exists
if (!$user) {
    $_SESSION['error'] = 'Email atau password salah';
    header('Location: ../views/auth/login.php');
    exit;
}

// Check password
if (!password_verify($password, $user['password'])) {
    $_SESSION['error'] = 'Email atau password salah';
    header('Location: ../views/auth/login.php');
    exit;
}

// LOGIN SUCCESS - SET SESSION
$_SESSION['user'] = array(
    'id' => (int)$user['id'],
    'nama' => $user['nama'],
    'email' => $user['email'],
    'no_hp' => $user['no_hp'],
    'role' => $user['role']
);

// Clear error
if (isset($_SESSION['error'])) {
    unset($_SESSION['error']);
}

// Save session to custom file
$sid = save_session_to_custom_file();

// Redirect based on role
$redirect = ($user['role'] === 'admin') 
    ? '/layanan_kesehatan/views/admin/dashboard.php'
    : '/layanan_kesehatan/views/user/dashboard.php';

header('Location: ' . $redirect);
exit;
?>
