<?php
header('Content-Type: application/json');
require_once 'connection.php';
require_once '../includes/auth_check.php';

// Protect - hanya admin
if (!isAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    // Update fitur untuk setiap motor
    $updateStmt = $pdo->prepare("UPDATE models SET features = ? WHERE code = ?");
    
    // Yamaha R1M
    $r1mFeatures = json_encode([
        '✓ Mesin 1000cc bertenaga 205 HP',
        '✓ Chassis aluminium monocoque',
        '✓ Suspensi Öhlins elektronik adaptif',
        '✓ Cruise control dan speed limiter',
        '✓ Electronic throttle control',
        '✓ Traction control semi-active'
    ]);
    $updateStmt->execute([$r1mFeatures, 'YZF-R1M']);
    
    // Yamaha R6
    $r6Features = json_encode([
        '✓ Mesin 600cc bertenaga 125 HP',
        '✓ Frame perimeter steel yang ringan',
        '✓ Suspensi inverted fork depan',
        '✓ ABS dengan cornering sensitivity',
        '✓ Rem kaliper 310mm depan',
        '✓ Ban sport compound premium'
    ]);
    $updateStmt->execute([$r6Features, 'YZF-R6']);
    
    // Kawasaki Ninja ZX-6R
    $zx6rFeatures = json_encode([
        '✓ Mesin 636cc bertenaga 130 HP',
        '✓ Fuel injection presisi tinggi',
        '✓ Suspensi full adjustable',
        '✓ Rem ABS dual channel',
        '✓ Traction control modes',
        '✓ Riding modes (Sport/Road/Rain)'
    ]);
    $updateStmt->execute([$zx6rFeatures, 'ZX-6R']);
    
    echo json_encode([
        'success' => true,
        'message' => 'Fitur motor berhasil diupdate! Setiap motor sekarang punya fitur uniknya sendiri.'
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
