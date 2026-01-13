<?php
// Load environment variables dari .env jika ada
if (file_exists(__DIR__ . '/../.env')) {
    $envLines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envLines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($value));
        }
    }
}

// Database credentials - dari environment atau default
$DB_HOST = getenv('DB_HOST') ?: "localhost";
$DB_NAME = getenv('DB_NAME') ?: "uts_api";
$DB_USER = getenv('DB_USER') ?: "root";
$DB_PASS = getenv('DB_PASS') ?: "";
$DB_CHARSET = getenv('DB_CHARSET') ?: "utf8mb4";

// Debug: uncomment untuk lihat nilai (HAPUS setelah selesai debug!)
// die(json_encode(['host' => $DB_HOST, 'name' => $DB_NAME, 'user' => $DB_USER]));

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=$DB_CHARSET",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Database Connection Failed: " . $e->getMessage(),
        "host" => $DB_HOST, // Debug info
        "database" => $DB_NAME // Debug info
    ]);
    exit;
}
