<?php

require_once __DIR__ . '/functions.php';

session_start();
session_destroy();

header('Location: ' . url('/'));
exit;