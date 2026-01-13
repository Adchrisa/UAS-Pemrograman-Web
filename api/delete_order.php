<?php
// API untuk delete order
header('Content-Type: application/json');
require "connection.php";

$input = json_decode(file_get_contents("php://input"), true);
$id = $input["id"] ?? null;

if (!$id) {
    echo json_encode(["success" => false, "message" => "ID required"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Transaksi berhasil dihapus"]);
    } else {
        echo json_encode(["success" => false, "message" => "Order tidak ditemukan"]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
