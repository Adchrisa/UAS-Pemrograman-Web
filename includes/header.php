<?php
// Check login status tanpa require (untuk index.php tidak perlu harus login)
// Start session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = null;
$isLoggedIn = false;

// Cek session
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $currentUser = [
        'id' => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? null,
        'name' => $_SESSION['name'] ?? null,
        'role' => $_SESSION['role'] ?? 'user'
    ];
    $isLoggedIn = true;
} 
// Jika tidak ada session, cek cookie
elseif (isset($_COOKIE['cmw_auth'])) {
    try {
        require_once __DIR__ . '/../api/connection.php';
        $cookieData = json_decode(base64_decode($_COOKIE['cmw_auth']), true);
        
        if ($cookieData && isset($cookieData['user_id'])) {
            $stmt = $pdo->prepare("SELECT id, username, name, role FROM users WHERE id = ?");
            $stmt->execute([$cookieData['user_id']]);
            $user = $stmt->fetch();
            
            if ($user) {
                $currentUser = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'name' => $user['name'],
                    'role' => $user['role']
                ];
                $isLoggedIn = true;
                
                // Restore session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['logged_in'] = true;
            }
        }
    } catch (Exception $e) {
        // Cookie invalid, abaikan
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Cigadung Motorworks</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark" style="background:linear-gradient(90deg,#2a0000,#a51515);">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Cigadung Motorworks</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navMenu" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#produk">Produk</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#about">About</a></li>
        
        <?php if ($isLoggedIn && $currentUser['role'] === 'admin'): ?>
          <!-- Dashboard Admin - hanya untuk admin di navbar utama -->
          <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">⚙️ Dashboard</a></li>
        <?php endif; ?>
        
        <?php if ($isLoggedIn): ?>
          <!-- Menu untuk user yang sudah login -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              👤 <?= htmlspecialchars($currentUser['name']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" style="background: linear-gradient(135deg, #2a0000, #1a0000); border: 1px solid rgba(255,90,90,0.3);">
              <li><a class="dropdown-item text-light" href="profile.php">👤 Profile</a></li>
              <li><a class="dropdown-item text-light" href="my_orders.php">📦 Pesanan Saya</a></li>
              <li><hr class="dropdown-divider" style="border-color: rgba(255,90,90,0.2);"></li>
              <li><a class="dropdown-item text-danger" onclick="logout()" href="#">🚪 Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <!-- Menu untuk user yang belum login -->
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="register.php">Registrasi</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container my-4">

<script>
function logout() {
  if (confirm('Yakin ingin logout?')) {
    fetch('api/auth_logout.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      }
    })
    .then(() => {
      window.location.href = 'index.php';
    });
  }
}
</script>
