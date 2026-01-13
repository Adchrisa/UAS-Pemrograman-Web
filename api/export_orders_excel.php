<?php
// Export transaksi ke Excel (CSV format)
session_start();
require_once 'connection.php';
require_once __DIR__ . '/../includes/auth_check.php';

requireAdmin();

try {
    // Fetch semua orders dengan info customer dan motor
    $sql = "
    SELECT 
        o.id as order_id,
        o.customer_name,
        o.customer_phone,
        o.total_price,
        o.status,
        o.order_date,
        m.title as motor_name,
        m.code as motor_code
    FROM orders o
    LEFT JOIN models m ON o.motor_id = m.id
    ORDER BY o.order_date DESC
    ";
    
    $stmt = $pdo->query($sql);
    $orders = $stmt->fetchAll();
    
    // Buat konten CSV
    $output = "No,Tanggal Order,Motor,Kode Motor,Nama Customer,Telepon,Total Harga,Status\n";
    
    $no = 1;
    foreach ($orders as $order) {
        $date = date('d/m/Y H:i', strtotime($order['order_date']));
        $motor_name = $order['motor_name'] ?? 'N/A';
        $motor_code = $order['motor_code'] ?? '-';
        $customer_name = $order['customer_name'] ?? '-';
        $phone = $order['customer_phone'] ?? '-';
        $total = number_format($order['total_price'], 0, ',', '.');
        $status = strtoupper($order['status']);
        
        // Escape data untuk CSV
        $output .= sprintf(
            "%d,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"Rp %s\",\"%s\"\n",
            $no++,
            $date,
            $motor_name,
            $motor_code,
            $customer_name,
            $phone,
            $total,
            $status
        );
    }
    
    // Clear output buffer
    if (ob_get_level()) ob_end_clean();
    
    // Headers untuk CSV (Excel compatible)
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="Laporan-Transaksi-' . date('Y-m-d-His') . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    
    // Kirim BOM untuk UTF-8 agar Excel baca dengan benar
    echo "\xEF\xBB\xBF";
    echo $output;
    exit;
    
} catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'Database Error',
        'message' => $e->getMessage()
    ]);
    exit;
}

