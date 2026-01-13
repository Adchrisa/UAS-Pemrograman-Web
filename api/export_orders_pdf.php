<?php
// Export semua transaksi ke PDF (Admin Only)
session_start();
require_once __DIR__ . '/../includes/auth_check.php';
requireAdmin();
require_once __DIR__ . '/connection.php';

// Ambil data orders join models
$stmt = $pdo->query("SELECT o.id, o.customer_name, o.customer_phone, o.total_price, o.status, o.order_date, m.title AS motor_name, m.code AS motor_code FROM orders o LEFT JOIN models m ON o.motor_id = m.id ORDER BY o.order_date DESC");
$orders = $stmt->fetchAll();

// Helper: escape text untuk PDF
function pdf_escape($text) {
    $text = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    // PDF text is Latin-1; fallback remove non-ASCII
    $text = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text);
    return $text;
}

// Bangun konten stream
$stream = "";
$titleY = 780;
$stream .= "BT /F1 16 Tf 1 0 0 1 40 $titleY Tm (Laporan Transaksi) Tj ET\n";
$date = date('d-m-Y H:i');
$stream .= "BT /F1 10 Tf 1 0 0 1 40 " . ($titleY - 18) . " Tm (Diunduh: $date) Tj ET\n";

// Header tabel
$startY = 730;
$lineHeight = 14;
$headers = ["ID", "Motor", "Customer", "Phone", "Harga", "Status", "Tanggal"];
$headerText = implode(" | ", $headers);
$stream .= "BT /F1 11 Tf 1 0 0 1 40 $startY Tm (" . pdf_escape($headerText) . ") Tj ET\n";
$stream .= "BT /F1 11 Tf 1 0 0 1 40 " . ($startY - 2) . " Tm () Tj ET\n";

$y = $startY - $lineHeight;
foreach ($orders as $order) {
    if ($y < 60) {
        // Stop if page would overflow (simple single-page export)
        break;
    }
    $line = sprintf(
        "#%s | %s | %s | %s | Rp %s | %s | %s",
        $order['id'],
        $order['motor_name'] ?: 'N/A',
        $order['customer_name'],
        $order['customer_phone'] ?: '-',
        number_format($order['total_price'], 0, ',', '.'),
        strtoupper($order['status']),
        date('d-m-Y', strtotime($order['order_date']))
    );
    $stream .= "BT /F1 10 Tf 1 0 0 1 40 $y Tm (" . pdf_escape($line) . ") Tj ET\n";
    $y -= $lineHeight;
}

// Bangun objek PDF manual
$objects = [];
$objects[] = "1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj";
$objects[] = "2 0 obj << /Type /Pages /Count 1 /Kids [3 0 R] >> endobj";
$objects[] = "3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 5 0 R /Resources << /Font << /F1 4 0 R >> >> >> endobj";
$objects[] = "4 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj";
$objects[] = "5 0 obj << /Length " . strlen($stream) . " >> stream\n$stream" . "endstream endobj";

$pdf = "%PDF-1.4\n";
$offsets = [];
foreach ($objects as $obj) {
    $offsets[] = strlen($pdf);
    $pdf .= $obj . "\n";
}
$startxref = strlen($pdf);
$pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
$pdf .= "0000000000 65535 f \n";
foreach ($offsets as $off) {
    $pdf .= sprintf("%010d 00000 n \n", $off);
}
$pdf .= "trailer << /Size " . (count($objects) + 1) . " /Root 1 0 R >>\n";
$pdf .= "startxref\n$startxref\n%%EOF";

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="laporan-transaksi.pdf"');
echo $pdf;
exit;
