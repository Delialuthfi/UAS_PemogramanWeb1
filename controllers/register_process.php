<?php
// Register processor
require_once __DIR__ . '/../app/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/auth/register.php');
    exit;
}

$nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$no_hp = isset($_POST['no_hp']) ? trim($_POST['no_hp']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$password_confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '';

// Validasi
if (empty($nama) || empty($email) || empty($no_hp) || empty($password)) {
    $_SESSION['error'] = 'Semua field harus diisi';
    header('Location: ../views/auth/register.php');
    exit;
}

if ($password !== $password_confirm) {
    $_SESSION['error'] = 'Password tidak cocok';
    header('Location: ../views/auth/register.php');
    exit;
}

// Cek email exist
$stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['error'] = 'Email sudah terdaftar';
    $stmt->close();
    header('Location: ../views/auth/register.php');
    exit;
}
$stmt->close();

// Insert user
$password_hash = password_hash($password, PASSWORD_BCRYPT);
$role = 'user';

$stmt = $conn->prepare('INSERT INTO users (nama, email, no_hp, password, role) VALUES (?, ?, ?, ?, ?)');
$stmt->bind_param('sssss', $nama, $email, $no_hp, $password_hash, $role);

if ($stmt->execute()) {
    $_SESSION['success'] = 'Registrasi berhasil! Silakan login';
    header('Location: ../views/auth/login.php');
} else {
    $_SESSION['error'] = 'Registrasi gagal: ' . $stmt->error;
    header('Location: ../views/auth/register.php');
}
$stmt->close();
exit;
?>
