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
    // Cek apakah kolom features sudah ada
    $stmt = $pdo->query("DESCRIBE models");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    
    if (!in_array('features', $columns)) {
        $pdo->exec("ALTER TABLE models ADD COLUMN features LONGTEXT DEFAULT NULL");
        
        // Update data default untuk motor yang sudah ada
        $defaultFeatures = json_encode([
            '✓ Desain aerodinamis modern',
            '✓ Mesin bertenaga tinggi dengan performa maksimal',
            '✓ Suspensi high-performance',
            '✓ Sistem pengereman canggih',
            '✓ Traction Control & Riding Modes',
            '✓ Rangka aluminium ringan'
        ]);
        
        $stmt = $pdo->prepare("UPDATE models SET features = ? WHERE features IS NULL");
        $stmt->execute([$defaultFeatures]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Kolom features berhasil ditambahkan ke tabel models. Fitur default telah diterapkan ke semua motor yang ada.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Kolom features sudah ada di database. Lanjut ke Manajemen Motor untuk mengedit fitur.'
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
