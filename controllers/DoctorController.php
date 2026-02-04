<?php
require_once __DIR__ . '/../app/bootstrap.php';
require_admin();

$action = $_GET['action'] ?? '';

// CRUD Dokter
if ($action === 'create') {
    $nama = $_POST['nama'];
    $spesialis = $_POST['spesialis'];
    $tarif = $_POST['tarif'];
    $telepon = $_POST['telepon'] ?? '';
    $deskripsi = $_POST['deskripsi'];
    $foto = '';
    if (!empty($_FILES['foto']['name'])) {
        $target = '../assets/images/' . basename($_FILES['foto']['name']);
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
            $foto = basename($_FILES['foto']['name']);
        }
    }
    $stmt = $conn->prepare('INSERT INTO doctors (nama, spesialis, tarif, telepon, foto, deskripsi) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ssisss', $nama, $spesialis, $tarif, $telepon, $foto, $deskripsi);
    $stmt->execute();
    header('Location: ../views/admin/doctors.php');
    exit;
}
if ($action === 'update') {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $spesialis = $_POST['spesialis'];
    $tarif = $_POST['tarif'];
    $telepon = $_POST['telepon'] ?? '';
    $deskripsi = $_POST['deskripsi'];
    $foto = $_POST['foto_lama'];
    if (!empty($_FILES['foto']['name'])) {
        $target = '../assets/images/' . basename($_FILES['foto']['name']);
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
            $foto = basename($_FILES['foto']['name']);
        }
    }
    $stmt = $conn->prepare('UPDATE doctors SET nama=?, spesialis=?, tarif=?, telepon=?, foto=?, deskripsi=? WHERE id=?');
    $stmt->bind_param('ssisssi', $nama, $spesialis, $tarif, $telepon, $foto, $deskripsi, $id);
    $stmt->execute();
    header('Location: ../views/admin/doctors.php');
    exit;
}
if ($action === 'delete') {
    $id = $_GET['id'];
    $stmt = $conn->prepare('DELETE FROM doctors WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: ../views/admin/doctors.php');
    exit;
}
