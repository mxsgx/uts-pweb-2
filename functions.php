<?php

session_start();

/**
 * @param string $message Pesan notifikasi yang akan ditampilkan.
 * @param string $type Jenis notifikasi, harus salah satu dari `success`, `info`, `warning`, `danger`.
 */
function set_notification($message = '', $type = 'success')
{
    if (!isset($_SESSION['notifications'])) {
        $_SESSION['notifications'] = [];
    }

    $_SESSION['notifications'][] = [
        'message' => $message,
        'type' => $type,
    ];
}

function flash_notification()
{
    if (!isset($_SESSION['notifications'])) {
        $_SESSION['notifications'] = [];
    }

    if (empty($_SESSION['notifications'])) {
        return;
    }

    foreach ($_SESSION['notifications'] as $notification) {
        echo "<div class=\"alert alert-{$notification['type']} alert-dismissible fade show\" role=\"alert\">";
        echo htmlspecialchars($notification['message']);
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
    }

    $_SESSION['notifications'] = [];
}

function url($path = '/')
{
    $dir = dirname(__FILE__);
    $root = $_SERVER['DOCUMENT_ROOT'];
    $base = str_replace($root, '', $dir);

    return (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $base . $path;
}