<?php
header('Content-Type: application/json');
require "connection.php";

try {
    if (isset($_GET["id"])) {
        $stmt = $pdo->prepare("SELECT * FROM models WHERE id = ?");
        $stmt->execute([$_GET["id"]]);
        $row = $stmt->fetch();

        echo json_encode([
            "success" => $row ? true : false,
            "data" => $row
        ]);
        exit;
    }

    $stmt = $pdo->query("SELECT * FROM models ORDER BY id ASC");
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
