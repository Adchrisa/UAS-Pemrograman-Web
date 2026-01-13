<?php
// API untuk register user baru
header('Content-Type: application/json');
session_start();
require "connection.php";

$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    echo json_encode(["success" => false, "message" => "Invalid JSON"]);
    exit;
}

$name = trim($input["name"] ?? "");
$username = trim($input["username"] ?? "");
$email = trim($input["email"] ?? "");
$password = $input["password"] ?? "";

// Validasi input
if (empty($name) || empty($username) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Nama, username, dan password wajib diisi"]);
    exit;
}

// Validasi panjang password
if (strlen($password) < 6) {
    echo json_encode(["success" => false, "message" => "Password minimal 6 karakter"]);
    exit;
}

// Validasi username hanya alphanumeric
if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    echo json_encode(["success" => false, "message" => "Username hanya boleh huruf, angka, dan underscore"]);
    exit;
}

try {
    // Cek apakah username sudah ada
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    
    if ($stmt->fetch()) {
        echo json_encode(["success" => false, "message" => "Username sudah terdaftar"]);
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert user baru
    $stmt = $pdo->prepare(
        "INSERT INTO users (name, username, email, password, role) VALUES (?, ?, ?, ?, 'user')"
    );
    $stmt->execute([$name, $username, $email, $hashedPassword]);

    echo json_encode([
        "success" => true, 
        "message" => "Registrasi berhasil! Silakan login."
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false, 
        "message" => "Terjadi kesalahan: " . $e->getMessage()
    ]);
}
