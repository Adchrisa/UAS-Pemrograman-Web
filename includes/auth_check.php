<?php
// File untuk cek apakah user sudah login
// Include file ini di halaman yang perlu proteksi

session_start();

// Cek apakah ada session login
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// Jika tidak ada session, cek cookie
if (!$isLoggedIn && isset($_COOKIE['cmw_auth'])) {
    try {
        $cookieData = json_decode(base64_decode($_COOKIE['cmw_auth']), true);
        
        if ($cookieData && isset($cookieData['user_id'])) {
            // Restore session dari cookie
            require_once __DIR__ . '/../api/connection.php';
            
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$cookieData['user_id']]);
            $user = $stmt->fetch();
            
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['name'] = $user['name'];
                    $_SESSION['role'] = $user['role'];
                $_SESSION['logged_in'] = true;
                $isLoggedIn = true;
            }
        }
    } catch (Exception $e) {
        // Cookie invalid, abaikan
    }
}

// Jika tetap tidak login, redirect ke login page
if (!$isLoggedIn) {
    header("Location: login.php");
    exit;
}

// Fungsi helper untuk mendapatkan data user
function getLoggedInUser() {
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? null,
        'name' => $_SESSION['name'] ?? null,
        'role' => $_SESSION['role'] ?? 'user'
    ];
}

// Fungsi cek apakah user adalah admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Fungsi untuk proteksi halaman yang harus login (admin atau user biasa)
function requireLogin() {
    // Auth check sudah dilakukan di atas file ini
    // Function ini hanya placeholder untuk explicit requirement
}

// Fungsi untuk proteksi halaman admin only
function requireAdmin() {
    if (!isAdmin()) {
        header("Location: index.php");
        exit;
    }
}
