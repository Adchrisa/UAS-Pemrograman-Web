<?php
// API untuk get orders user yang sedang login
header('Content-Type: application/json');
require "connection.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Anda harus login terlebih dahulu']);
    exit;
}

$user_name = $_SESSION['name'] ?? '';

try {
    // Get orders by customer name (karena tidak ada user_id di table orders)
    $stmt = $pdo->prepare("
        SELECT o.id, o.motor_id, o.customer_name, o.customer_phone, o.total_price, o.status, o.order_date, m.title as motor_name, m.code as motor_code, m.img as motor_img
        FROM orders o 
        LEFT JOIN models m ON o.motor_id = m.id 
        WHERE o.customer_name = ?
        ORDER BY o.order_date DESC
    ");
    $stmt->execute([$user_name]);
    $rows = $stmt->fetchAll();

    echo json_encode([
        "success" => true,
        "data" => $rows
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>
