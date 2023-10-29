<?php

require_once __DIR__ . '/../../database.php';
require_once __DIR__ . '/../../functions.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header('Location: ' . url('/login.php'));
    exit;
}

if (!isset($_GET['id'], $_GET['id_mk'])) {
    set_notification('Id materi atau Id mata kuliah tidak ada', 'danger');
    header('Location: ../index.php');
    exit;
}

$id = (int) $_GET['id'];
$id_mk = (int) $_GET['id_mk'];

try {
    $stmt_get = $db->prepare('SELECT file_materi FROM materi WHERE id = ?');
    $stmt_get->bind_param('i', $id);

    if (!$stmt_get->execute()) {
        set_notification("Gagal mengambil data materi mata kuliah: {$stmt->error}", 'danger');
        header("Location: ../edit.php?id={$id_mk}");
        exit;
    }

    $materi = $stmt_get->get_result()->fetch_assoc();

    $stmt_delete = $db->prepare('DELETE FROM materi WHERE id = ?');

    $stmt_delete->bind_param('i', $id);

    if (!$stmt_delete->execute()) {
        set_notification("Gagal menghapus data materi mata kuliah: {$stmt->error}", 'danger');
        header("Location: ../edit.php?id={$id_mk}");
        exit;
    }

    $path = __DIR__ . "/files/{$materi['file_materi']}";
    unlink($path);

    set_notification('Berhasil menghapus data materi mata kuliah!');
    header("Location: ../edit.php?id={$id_mk}");
    exit;
} catch (Throwable $e) {
    set_notification("Gagal menghapus data materi mata kuliah: {$e->getMessage()}", 'danger');
    header("Location: ../edit.php?id={$id_mk}");
    exit;
} finally {
    $stmt_get->close();
    $stmt_delete->close();
}