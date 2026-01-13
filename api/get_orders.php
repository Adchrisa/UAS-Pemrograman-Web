<?php
// API untuk get orders dengan join ke models
header('Content-Type: application/json');
require "connection.php";

try {
    $stmt = $pdo->query("
        SELECT o.id, o.motor_id, o.customer_name, o.customer_phone, o.total_price, o.status, o.order_date, m.title as motor_name, m.code as motor_code
        FROM orders o 
        LEFT JOIN models m ON o.motor_id = m.id 
        ORDER BY o.order_date DESC
    ");
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
