<?php
require_once __DIR__ . '/../app/bootstrap.php';
require_admin();

$action = $_GET['action'] ?? '';

// CRUD Artikel
if ($action === 'create') {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $gambar = '';
    if (!empty($_FILES['gambar']['name'])) {
        $target = '../assets/images/' . basename($_FILES['gambar']['name']);
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
            $gambar = basename($_FILES['gambar']['name']);
        }
    }
    $stmt = $conn->prepare('INSERT INTO articles (judul, isi, gambar) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $judul, $isi, $gambar);
    $stmt->execute();
    header('Location: ../views/admin/articles.php');
    exit;
}
if ($action === 'update') {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $gambar = $_POST['gambar_lama'];
    if (!empty($_FILES['gambar']['name'])) {
        $target = '../assets/images/' . basename($_FILES['gambar']['name']);
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
            $gambar = basename($_FILES['gambar']['name']);
        }
    }
    $stmt = $conn->prepare('UPDATE articles SET judul=?, isi=?, gambar=? WHERE id=?');
    $stmt->bind_param('sssi', $judul, $isi, $gambar, $id);
    $stmt->execute();
    header('Location: ../views/admin/articles.php');
    exit;
}
if ($action === 'delete') {
    $id = $_GET['id'];
    $stmt = $conn->prepare('DELETE FROM articles WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: ../views/admin/articles.php');
    exit;
}
