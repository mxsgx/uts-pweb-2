<?php

require_once __DIR__ . '/../../database.php';
require_once __DIR__ . '/../../functions.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header('Location: ' . url('/login.php'));
    exit;
}

if (!isset($_GET['id'], $_GET['id_mk'])) {
    set_notification('Id mata kuliah atau Id materi tidak ada', 'danger');
    header('Location: ../index.php');
    exit;
}

$id = (int) $_GET['id'];
$id_mk = (int) $_GET['id_mk'];

// Validasi apakah data nama, kode, dan deskripsi dikirim.
if (!isset($_POST['judul'], $_POST['deskripsi'])) {
    set_notification('Data yang dikirim tidak sesuai format', 'danger');
    header("Location: ./edit.php?id={$id}id_mk={$id_mk}");
    exit;
}

$judul = $_POST['judul'];
$deskripsi = $_POST['deskripsi'];

// Data nama dan kode tidak boleh kosong
if (empty($judul)) {
    set_notification('Judul materi tidak boleh kosong', 'danger');
    header("Location: ./edit.php?id={$id}id_mk={$id_mk}");
    exit;
}

$stmt_materi = $db->prepare('SELECT * FROM materi WHERE id_materi = ? AND id_mk = ? LIMIT 1');
$stmt_materi->bind_param('ii', $id, $id_mk);
if (!$stmt_materi->execute()) {
    set_notification('Gagal mengambil data materi', 'danger');
    header("Location: ./edit.php?id={$id}id_mk={$id_mk}");
    exit;
}

$materi_result = $stmt_materi->get_result();
$materi = $materi_result->fetch_assoc();

$stmt_materi->close();

$filename = $materi['file_materi'];

if (isset($_FILES['file']) && !empty($_FILES['file']['tmp_name'])) {
    $file = $_FILES['file'];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = md5(random_bytes(32)) . ".{$ext}";
    $path = __DIR__ . "/files/{$filename}";

    mkdir(dirname($path));

    if (!move_uploaded_file($file['tmp_name'], $path)) {
        set_notification('Gagal menyimpan file materi', 'danger');
        header("Location: ./edit.php?id={$id}&id_mk={$id_mk}");
        exit;
    }

    unlink(__DIR__ . "/files/{$materi['file_materi']}");
}

try {
    $stmt = $db->prepare('UPDATE materi SET judul_materi = ?, deskripsi_materi = ?, file_materi = ? WHERE id_materi = ?');

    $stmt->bind_param('sssi', $judul, $deskripsi, $filename, $id);

    if (!$stmt->execute()) {
        set_notification("Gagal mengubah data materi mata kuliah: {$stmt->error}", 'danger');
        header("Location: ./edit.php?id={$id}&id_mk={$id_mk}");
        exit;
    }

    set_notification('Berhasil mengubah data materi mata kuliah!');
    header("Location: ./edit.php?id={$id}&id_mk={$id_mk}");
    exit;
} catch (Throwable $e) {
    set_notification("Gagal mengubah data materi mata kuliah: {$e->getMessage()}", 'danger');
    header("Location: ./edit.php?id={$id}&id_mk={$id_mk}");
    exit;
} finally {
    $stmt->close();
}