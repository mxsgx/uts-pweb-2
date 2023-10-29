<?php

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../functions.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header('Location: ' . url('/login.php'));
    exit;
}

if (!isset($_GET['id'])) {
    set_notification('Id mata kuliah tidak ada', 'danger');
    header('Location: index.php');
    exit;
}

$id = (int) $_GET['id'];

// Validasi apakah data nama, kode, dan deskripsi dikirim.
if (!isset($_POST['nama'], $_POST['kode'], $_POST['deskripsi'])) {
    set_notification('Data yang dikirim tidak sesuai format', 'danger');
    header("Location: edit.php?id={$id}");
    exit;
}

$nama = $_POST['nama'];
$kode = $_POST['kode'];
$deskripsi = $_POST['deskripsi'];

// Data nama dan kode tidak boleh kosong
if (empty($nama) || empty($kode)) {
    set_notification('Nama dan kode tidak boleh kosong', 'danger');
    header("Location: edit.php?id={$id}");
    exit;
}

try {
    $stmt = $db->prepare('UPDATE mata_kuliah SET nama_mk = ?, kode_mk = ?, deskripsi_mk = ? WHERE id_mk = ?');

    $stmt->bind_param('sssi', $nama, $kode, $deskripsi, $id);

    if (!$stmt->execute()) {
        set_notification("Gagal mengubah data mata kuliah: {$stmt->error}", 'danger');
        header("Location: edit.php?id={$id}");
        exit;
    }

    set_notification('Berhasil mengubah data mata kuliah!');
    header("Location: edit.php?id={$id}");
    exit;
} catch (Throwable $e) {
    set_notification("Gagal mengubah data mata kuliah: {$e->getMessage()}", 'danger');
    header("Location: edit.php?id={$id}");
    exit;
} finally {
    $stmt->close();
}