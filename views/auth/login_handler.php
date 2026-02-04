<?php
// ULTRA SIMPLE LOGIN - NO FRILLS
require_once __DIR__ . '/../../app/bootstrap.php';

// Hanya proses jika POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Query
    $stmt = $conn->prepare("SELECT id, nama, email, no_hp, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($password, $user['password'])) {
        // Login berhasil
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nama' => $user['nama'],
            'email' => $user['email'],
            'no_hp' => $user['no_hp'],
            'role' => $user['role']
        ];
        
        // Redirect
        if ($user['role'] === 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../user/dashboard.php");
        }
        die();
    } else {
        // Login gagal
        $_SESSION['error'] = 'Email atau password salah';
        header("Location: ./login.php");
        die();
    }
}

// Jika bukan POST, redirect ke login
header("Location: ./login.php");
die();
?>
