<?php

require_once __DIR__ . '/../../database.php';
require_once __DIR__ . '/../../functions.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header('Location: ' . url('/login.php'));
    exit;
}

if (!isset($_GET['id'])) {
    set_notification('Id mata kuliah tidak ada', 'danger');
    header('Location: ../index.php');
    exit;
}

$id = (int) $_GET['id'];

// Validasi apakah data nama, kode, dan deskripsi dikirim.
if (!isset($_POST['judul'], $_POST['deskripsi'], $_FILES['file'])) {
    set_notification('Data yang dikirim tidak sesuai format', 'danger');
    header("Location: ./create.php?id={$id}");
    exit;
}

$judul = $_POST['judul'];
$deskripsi = $_POST['deskripsi'];

// Data nama dan kode tidak boleh kosong
if (empty($judul)) {
    set_notification('Judul materi tidak boleh kosong', 'danger');
    header("Location: ./create.php?id={$id}");
    exit;
}

$file = $_FILES['file'];
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = md5(random_bytes(32)) . ".{$ext}";
$path = __DIR__ . "/files/{$filename}";

mkdir(dirname($path));

if (!move_uploaded_file($file['tmp_name'], $path)) {
    set_notification('Gagal menyimpan file materi', 'danger');
    header("Location: ./create.php?id={$id}");
    exit;
}

try {
    $stmt = $db->prepare('INSERT INTO materi (id_mk, judul_materi, deskripsi_materi, file_materi) VALUE (?, ?, ?, ?)');

    $stmt->bind_param('isss', $id, $judul, $deskripsi, $filename);

    if (!$stmt->execute()) {
        set_notification("Gagal menambahkan data materi mata kuliah: {$stmt->error}", 'danger');
        header("Location: ./create.php?id={$id}");
        exit;
    }

    set_notification('Berhasil menambahkan data materi mata kuliah!');
    header("Location: ./edit.php?id={$stmt->insert_id}&id_mk={$id}");
    exit;
} catch (Throwable $e) {
    set_notification("Gagal menambahkan data materi mata kuliah: {$e->getMessage()}", 'danger');
    header("Location: ./create.php?id={$id}");
    exit;
} finally {
    $stmt->close();
}