<?php

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../functions.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header('Location: ' . url('/login.php'));
    exit;
}

// Validasi apakah data nama, kode, dan deskripsi dikirim.
if (!isset($_POST['nama'], $_POST['kode'], $_POST['deskripsi'])) {
    set_notification('Data yang dikirim tidak sesuai format', 'danger');
    header('Location: create.php');
    exit;
}

$nama = $_POST['nama'];
$kode = $_POST['kode'];
$deskripsi = $_POST['deskripsi'];

// Data nama dan kode tidak boleh kosong
if (empty($nama) || empty($kode)) {
    set_notification('Nama dan kode tidak boleh kosong', 'danger');
    header('Location: create.php');
    exit;
}

try {
    $stmt = $db->prepare('INSERT INTO mata_kuliah (kode_mk, nama_mk, deskripsi_mk) VALUE (?, ?, ?)');

    $stmt->bind_param('sss', $nama, $kode, $deskripsi);

    if (!$stmt->execute()) {
        set_notification("Gagal menambahkan data mata kuliah: {$stmt->error}", 'danger');
        header('Location: create.php');
        exit;
    }

    set_notification('Berhasil menambahkan data mata kuliah!');
    header("Location: edit.php?id={$stmt->insert_id}");
    exit;
} catch (Throwable $e) {
    set_notification("Gagal menambahkan data mata kuliah: {$e->getMessage()}", 'danger');
    header('Location: create.php');
    exit;
} finally {
    $stmt->close();
}