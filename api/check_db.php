<?php
header('Content-Type: application/json');
require_once 'api/connection.php';

try {
    // 1. Check kolom features
    $stmt = $pdo->query("DESCRIBE models");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    $hasFeatures = in_array('features', $columns);
    
    // 2. Check data
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM models");
    $result = $stmt->fetch();
    $motorCount = $result['total'];
    
    // 3. Get motors
    $stmt = $pdo->query("SELECT id, code, title FROM models LIMIT 5");
    $motors = $stmt->fetchAll();
    
    echo json_encode([
        'hasFeatures' => $hasFeatures,
        'motorCount' => $motorCount,
        'motors' => $motors,
        'status' => 'ok'
    ], JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
