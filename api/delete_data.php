<?php
header('Content-Type: application/json');
require "connection.php";

$input = json_decode(file_get_contents("php://input"), true);
$id = $input["id"] ?? null;

if (!$id) {
    echo json_encode(["success" => false, "message" => "id required"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM models WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Deleted"]);
    } else {
        echo json_encode(["success" => false, "message" => "ID not found"]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
