<?php
header('Content-Type: application/json');

// Tentukan folder untuk menyimpan gambar
$uploadDir = __DIR__ . '/../images/';

// Pastikan folder ada, jika tidak buat folder
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "File tidak ditemukan atau terjadi error saat upload"
    ]);
    exit;
}

$file = $_FILES['image'];
$fileName = $file['name'];
$fileTmp = $file['tmp_name'];
$fileSize = $file['size'];
$fileError = $file['error'];

// Validasi ukuran file (max 5MB)
$maxSize = 5 * 1024 * 1024; // 5MB
if ($fileSize > $maxSize) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Ukuran file terlalu besar (max 5MB)"
    ]);
    exit;
}

// Validasi tipe file
$allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$fileMime = mime_content_type($fileTmp);

if (!in_array($fileMime, $allowedMimes)) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Tipe file tidak diizinkan. Hanya JPEG, PNG, GIF, WebP yang diizinkan"
    ]);
    exit;
}

// Generate nama file unik untuk menghindari konflik
$fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
$fileNameNew = 'motor_' . time() . '_' . uniqid() . '.' . $fileExt;
$filePath = $uploadDir . $fileNameNew;

// Upload file
if (move_uploaded_file($fileTmp, $filePath)) {
    // Return path yang bisa diakses dari web browser
    $relativePath = 'images/' . $fileNameNew;
    
    echo json_encode([
        "success" => true,
        "message" => "Gambar berhasil diupload",
        "path" => $relativePath
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Gagal menyimpan file ke folder"
    ]);
}
?>
