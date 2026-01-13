<?php
// API untuk login user
header('Content-Type: application/json');
session_start();
require "connection.php";

$raw = file_get_contents("php://input");
$input = json_decode($raw, true);
// Fallback ke POST jika JSON diblokir hosting
if (!$input || !is_array($input)) {
    $input = $_POST;
}
if (!$input || !is_array($input)) {
    echo json_encode(["success" => false, "message" => "Permintaan tidak valid"]);
    exit;
}

$username = trim($input["username"] ?? "");
$password = $input["password"] ?? "";
$isDefaultAdmin = ($username === 'admin' && ($password === 'admin' || $password === 'admin123'));

// Validasi input
if (empty($username) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Username dan password wajib diisi"]);
    exit;
}

try {
    // Cari user berdasarkan username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Auto-create admin jika belum ada dan pakai kredensial default
    if (!$user && $isDefaultAdmin) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $insert = $pdo->prepare("INSERT INTO users (name, username, email, password, role) VALUES (?,?,?,?,?)");
        $insert->execute(['Administrator', 'admin', 'admin@cigadungmotorworks.id', $hashed, 'admin']);
        $stmt->execute([$username]);
        $user = $stmt->fetch();
    }

    if (!$user) {
        echo json_encode(["success" => false, "message" => "Username tidak ditemukan"]);
        exit;
    }

    // Jika admin pakai kredensial default tapi hash mismatch, reset hash ke password yang dimasukkan
    if ($isDefaultAdmin && !password_verify($password, $user['password'])) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $upd = $pdo->prepare("UPDATE users SET password = ?, role = 'admin' WHERE id = ?");
        $upd->execute([$hashed, $user['id']]);
        $user['password'] = $hashed;
        $user['role'] = 'admin';
    }

    // Verifikasi password
    if (!password_verify($password, $user['password'])) {
        echo json_encode(["success" => false, "message" => "Password salah"]);
        exit;
    }

    // Set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['logged_in'] = true;

    // Set cookie (cookie akan bertahan 7 hari)
    $cookie_name = "cmw_auth";
    $cookie_value = base64_encode(json_encode([
        'user_id' => $user['id'],
        'username' => $user['username'],
        'role' => $user['role'],
        'token' => bin2hex(random_bytes(16))
    ]));
    
    // Set cookie dengan security flags
    setcookie($cookie_name, $cookie_value, [
        'expires' => time() + (86400 * 7), // 7 days
        'path' => '/',
        'secure' => false, // set true jika pakai HTTPS
        'httponly' => true, // Protect against XSS
        'samesite' => 'Lax'
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Login berhasil",
        "data" => [
            "name" => $user['name'],
            "username" => $user['username']
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Terjadi kesalahan: " . $e->getMessage()
    ]);
}
