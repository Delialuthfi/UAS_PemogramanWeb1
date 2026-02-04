<?php
require_once __DIR__ . '/../app/bootstrap.php';
require_admin();

$action = $_GET['action'] ?? '';

// CRUD Jadwal
if ($action === 'create') {
    $doctor_id = intval($_POST['doctor_id']);
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    
    // Validasi
    if ($doctor_id > 0 && $hari && $jam_mulai && $jam_selesai) {
        $stmt = $conn->prepare('INSERT INTO schedules (doctor_id, hari, jam_mulai, jam_selesai) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('isss', $doctor_id, $hari, $jam_mulai, $jam_selesai);
        if ($stmt->execute()) {
            header('Location: ../views/admin/schedules.php?success=jadwal_ditambah');
        } else {
            header('Location: ../views/admin/schedules.php?error=jadwal_gagal');
        }
    } else {
        header('Location: ../views/admin/schedules.php?error=data_tidak_lengkap');
    }
    exit;
}
if ($action === 'update') {
    $id = $_POST['id'];
    $doctor_id = $_POST['doctor_id'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $stmt = $conn->prepare('UPDATE schedules SET doctor_id=?, hari=?, jam_mulai=?, jam_selesai=? WHERE id=?');
    $stmt->bind_param('isssi', $doctor_id, $hari, $jam_mulai, $jam_selesai, $id);
    $stmt->execute();
    header('Location: ../views/admin/schedules.php');
    exit;
}
if ($action === 'delete') {
    $id = $_GET['id'];
    $stmt = $conn->prepare('DELETE FROM schedules WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: ../views/admin/schedules.php');
    exit;
}
