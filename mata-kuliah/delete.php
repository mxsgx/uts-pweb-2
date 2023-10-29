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

try {
    $stmt = $db->prepare('DELETE FROM mata_kuliah WHERE id_mk = ?');

    $stmt->bind_param('i', $id);

    if (!$stmt->execute()) {
        set_notification("Gagal menghapus data mata kuliah: {$stmt->error}", 'danger');
        header("Location: index.php");
        exit;
    }

    set_notification('Berhasil menghapus data mata kuliah!');
    header("Location: index.php");
    exit;
} catch (Throwable $e) {
    set_notification("Gagal menghapus data mata kuliah: {$e->getMessage()}", 'danger');
    header("Location: index.php");
    exit;
} finally {
    $stmt->close();
}