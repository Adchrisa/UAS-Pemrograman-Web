<?php
/**
 * Database Initialization Script
 * Auto-setup database jika ada issues
 */

require_once 'api/connection.php';

function initializeDatabase() {
    global $pdo;
    
    try {
        // 1. Check dan add kolom features jika belum ada
        $describeStmt = $pdo->query("DESCRIBE models");
        $columns = $describeStmt->fetchAll(PDO::FETCH_COLUMN, 0);
        
        if (!in_array('features', $columns)) {
            $pdo->exec("ALTER TABLE models ADD COLUMN features LONGTEXT DEFAULT NULL");
        }
        
        // 2. Check apakah ada data motor
        $countStmt = $pdo->query("SELECT COUNT(*) as total FROM models");
        $result = $countStmt->fetch();
        $total = $result['total'];
        
        if ($total == 0) {
            // Insert default motors dengan fitur BERBEDA untuk setiap motor
            $r1mFeatures = json_encode([
                '✓ Mesin 1000cc bertenaga 205 HP',
                '✓ Chassis aluminium monocoque',
                '✓ Suspensi Öhlins elektronik adaptif',
                '✓ Cruise control dan speed limiter',
                '✓ Electronic throttle control',
                '✓ Traction control semi-active'
            ]);
            
            $r6Features = json_encode([
                '✓ Mesin 600cc bertenaga 125 HP',
                '✓ Frame perimeter steel yang ringan',
                '✓ Suspensi inverted fork depan',
                '✓ ABS dengan cornering sensitivity',
                '✓ Rem kaliper 310mm depan',
                '✓ Ban sport compound premium'
            ]);
            
            $zx6rFeatures = json_encode([
                '✓ Mesin 636cc bertenaga 130 HP',
                '✓ Fuel injection presisi tinggi',
                '✓ Suspensi full adjustable',
                '✓ Rem ABS dual channel',
                '✓ Traction control modes',
                '✓ Riding modes (Sport/Road/Rain)'
            ]);
            
            $motors = [
                ['YZF-R1M', 'Yamaha R1M', 'Rp 550.000.000', 'Superbike flagship Yamaha dengan teknologi MotoGP', 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc', $r1mFeatures],
                ['YZF-R6', 'Yamaha R6', 'Rp 320.000.000', 'Supersport 600cc legendaris', 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87', $r6Features],
                ['ZX-6R', 'Kawasaki Ninja ZX-6R', 'Rp 280.000.000', 'Ninja 636cc dengan performa optimal', 'https://images.unsplash.com/photo-1609630875171-b1321377ee65', $zx6rFeatures]
            ];
            
            $stmt = $pdo->prepare("INSERT INTO models (code, title, price, description, img, features) VALUES (?, ?, ?, ?, ?, ?)");
            foreach ($motors as $motor) {
                $stmt->execute($motor);
            }
        } else {
            // Update motors tanpa features - tapi setiap motor punya fitur BERBEDA
            $emptyStmt = $pdo->query("SELECT id, code FROM models WHERE features IS NULL");
            $emptyMotors = $emptyStmt->fetchAll();
            
            if (count($emptyMotors) > 0) {
                $updateStmt = $pdo->prepare("UPDATE models SET features = ? WHERE code = ?");
                
                $featuresMap = [
                    'YZF-R1M' => json_encode([
                        '✓ Mesin 1000cc bertenaga 205 HP',
                        '✓ Chassis aluminium monocoque',
                        '✓ Suspensi Öhlins elektronik adaptif',
                        '✓ Cruise control dan speed limiter',
                        '✓ Electronic throttle control',
                        '✓ Traction control semi-active'
                    ]),
                    'YZF-R6' => json_encode([
                        '✓ Mesin 600cc bertenaga 125 HP',
                        '✓ Frame perimeter steel yang ringan',
                        '✓ Suspensi inverted fork depan',
                        '✓ ABS dengan cornering sensitivity',
                        '✓ Rem kaliper 310mm depan',
                        '✓ Ban sport compound premium'
                    ]),
                    'ZX-6R' => json_encode([
                        '✓ Mesin 636cc bertenaga 130 HP',
                        '✓ Fuel injection presisi tinggi',
                        '✓ Suspensi full adjustable',
                        '✓ Rem ABS dual channel',
                        '✓ Traction control modes',
                        '✓ Riding modes (Sport/Road/Rain)'
                    ])
                ];
                
                foreach ($emptyMotors as $motor) {
                    $features = $featuresMap[$motor['code']] ?? json_encode([
                        '✓ Desain aerodinamis modern',
                        '✓ Mesin bertenaga tinggi dengan performa maksimal',
                        '✓ Suspensi high-performance',
                        '✓ Sistem pengereman canggih',
                        '✓ Traction Control & Riding Modes',
                        '✓ Rangka aluminium ringan'
                    ]);
                    $updateStmt->execute([$features, $motor['code']]);
                }
            }
        }
        
        return ['success' => true, 'message' => 'Database initialized successfully'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

// Auto-initialize saat script ini di-load
initializeDatabase();
?>
