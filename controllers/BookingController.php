<?php
require_once __DIR__ . '/../app/bootstrap.php';

$action = $_GET['action'] ?? '';

// Booking oleh user
if ($action === 'create' && isset($_SESSION['user']) && $_SESSION['user']['role'] === 'user') {
    $user_id = $_SESSION['user']['id'];
    $doctor_id = $_POST['doctor_id'];
    $schedule_id = $_POST['schedule_id'];
    $keluhan = $_POST['keluhan'];
    $stmt = $conn->prepare('INSERT INTO bookings (user_id, doctor_id, schedule_id, keluhan) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('iiis', $user_id, $doctor_id, $schedule_id, $keluhan);
    $stmt->execute();
    header('Location: ../views/user/booking_history.php');
    exit;
}
// Admin update status booking
if ($action === 'update_status' && isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $stmt = $conn->prepare('UPDATE bookings SET status=? WHERE id=?');
    $stmt->bind_param('si', $status, $id);
    $stmt->execute();
    header('Location: ../views/admin/bookings.php');
    exit;
}
// Admin hapus booking
if ($action === 'delete' && isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
    $id = $_GET['id'];
    $stmt = $conn->prepare('DELETE FROM bookings WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: ../views/admin/bookings.php');
    exit;
}

// Admin approve booking
if ($action === 'approve' && isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare('UPDATE bookings SET status=? WHERE id=?');
    $status = 'approved';
    $stmt->bind_param('si', $status, $id);
    $stmt->execute();
    header('Location: ../views/admin/bookings.php?approved=1');
    exit;
}

// Admin reject booking
if ($action === 'reject' && isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare('UPDATE bookings SET status=? WHERE id=?');
    $status = 'rejected';
    $stmt->bind_param('si', $status, $id);
    $stmt->execute();
    header('Location: ../views/admin/bookings.php?rejected=1');
    exit;
}
