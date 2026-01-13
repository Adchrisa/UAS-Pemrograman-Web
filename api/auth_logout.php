<?php
// API untuk logout user
header('Content-Type: application/json');
session_start();

// Hapus semua session
$_SESSION = array();

// Hapus session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Hapus auth cookie
if (isset($_COOKIE['cmw_auth'])) {
    setcookie('cmw_auth', '', time() - 3600, '/');
}

// Destroy session
session_destroy();

echo json_encode([
    "success" => true,
    "message" => "Logout berhasil"
]);
