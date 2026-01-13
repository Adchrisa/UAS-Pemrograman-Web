<?php
// API untuk update status order
header('Content-Type: application/json');
require "connection.php";

$input = json_decode(file_get_contents("php://input"), true);

$id = $input["id"] ?? null;
$status = $input["status"] ?? null;

if (!$id || !$status) {
    echo json_encode(["success" => false, "message" => "ID dan status required"]);
    exit;
}

if (!in_array($status, ['pending', 'completed', 'cancelled'])) {
    echo json_encode(["success" => false, "message" => "Status tidak valid"]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Status berhasil diupdate"]);
    } else {
        echo json_encode(["success" => false, "message" => "Order tidak ditemukan"]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
