<?php
header('Content-Type: application/json');
require "connection.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Anda harus login terlebih dahulu']);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    echo json_encode(["success" => false, "message" => "Invalid JSON"]);
    exit;
}

$motor_id = isset($input["motor_id"]) ? intval($input["motor_id"]) : 0;
$phone = trim($input["phone"] ?? "");

// Validation
if (!$motor_id) {
    echo json_encode(["success" => false, "message" => "Motor ID tidak valid"]);
    exit;
}

if (empty($phone)) {
    echo json_encode(["success" => false, "message" => "Nomor telepon harus diisi"]);
    exit;
}

if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
    echo json_encode(["success" => false, "message" => "Nomor telepon harus 10-15 digit"]);
    exit;
}

// Get user info from session
$user_id = $_SESSION['user_id'] ?? 0;
$customer_name = $_SESSION['name'] ?? 'User';

if (!$user_id) {
    echo json_encode(["success" => false, "message" => "User session tidak valid"]);
    exit;
}

try {
    // Verify motor exists and get price
    $stmt = $pdo->prepare("SELECT id, title, price FROM models WHERE id = ?");
    $stmt->execute([$motor_id]);
    $motor = $stmt->fetch();
    
    if (!$motor) {
        echo json_encode(["success" => false, "message" => "Motor tidak ditemukan"]);
        exit;
    }
    
    // Parse price - remove "Rp" dan titik/koma, ambil angka saja
    $price_raw = $motor['price'];
    $total_price = intval(preg_replace('/[^0-9]/', '', $price_raw));
    
    if ($total_price <= 0) {
        echo json_encode(["success" => false, "message" => "Harga motor tidak valid"]);
        exit;
    }
    
    // Insert order into database
    $stmt = $pdo->prepare(
        "INSERT INTO orders (motor_id, customer_name, customer_phone, total_price, status)
         VALUES (?, ?, ?, ?, 'pending')"
    );
    $stmt->execute([$motor_id, $customer_name, $phone, $total_price]);
    
    echo json_encode([
        "success" => true,
        "message" => "Pesanan berhasil dibuat! Admin akan menghubungi Anda segera.",
        "order_id" => $pdo->lastInsertId()
    ]);
    
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Terjadi kesalahan: " . $e->getMessage()]);
}
