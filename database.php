<?php

try {
    $db = new mysqli("127.0.0.1", "root", "2wsx1qaz", "mhs");
} catch (Exception $e) {
    die('Database error: ' . $e->getMessage());
}