<?php
session_start();
require_once 'api/connection.php';

// Check authentication
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

// Get user data
$userId = $_SESSION['user_id'];
$query = $pdo->prepare("SELECT id, username, name, email, role, created_at FROM users WHERE id = ?");
$query->execute([$userId]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
  header("Location: login.php");
  exit;
}

$userName = htmlspecialchars($user['name']);
$userEmail = htmlspecialchars($user['email']);
$userUsername = htmlspecialchars($user['username']);
$userRole = htmlspecialchars($user['role']);
$joinDate = date('d M Y', strtotime($user['created_at']));

// Get order stats
$statsQuery = $pdo->prepare("SELECT COUNT(*) as total_orders, SUM(total_price) as total_spent FROM orders WHERE customer_name = ?");
$statsQuery->execute([$user['name']]);
$stats = $statsQuery->fetch(PDO::FETCH_ASSOC);
$totalOrders = $stats['total_orders'] ?? 0;
$totalSpent = $stats['total_spent'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Member - Cigadung Motorworks</title>
  <link rel="stylesheet" href="bootstrap-5.3.8-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    .profile-header {
      background: linear-gradient(135deg, rgba(255,90,90,0.15), rgba(160,30,30,0.15));
      border: 1.5px solid rgba(160,30,30,0.3);
      border-radius: 15px;
      padding: 40px 20px;
      text-align: center;
      margin-bottom: 30px;
    }

    .profile-avatar {
      width: 120px;
      height: 120px;
      background: linear-gradient(135deg, #ff5a5a, #a01e1e);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 50px;
      margin: 0 auto 20px;
      box-shadow: 0 0 30px rgba(255,90,90,0.3);
    }

    .info-card {
      background: linear-gradient(135deg, rgba(160, 30, 30, 0.15), rgba(200, 40, 40, 0.08));
      border: 1.5px solid rgba(160,30,30,0.3);
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      transition: all 0.3s ease;
    }

    .info-card:hover {
      border-color: rgba(255,90,90,0.5);
      box-shadow: 0 5px 20px rgba(255,90,90,0.2);
    }

    .info-label {
      color: #ff5a5a;
      font-weight: 600;
      margin-bottom: 5px;
      font-size: 0.9rem;
    }

    .info-value {
      color: #ddd;
      font-size: 1.1rem;
      margin-bottom: 15px;
    }

    .stat-box {
      background: linear-gradient(135deg, rgba(160, 30, 30, 0.15), rgba(200, 40, 40, 0.08));
      border: 1.5px solid rgba(160,30,30,0.3);
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      transition: all 0.3s ease;
    }

    .stat-box:hover {
      border-color: rgba(255,90,90,0.5);
      box-shadow: 0 5px 20px rgba(255,90,90,0.2);
    }

    .stat-number {
      font-size: 2rem;
      color: #ff5a5a;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .stat-label {
      color: #aaa;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .member-benefits {
      background: linear-gradient(135deg, rgba(160, 30, 30, 0.15), rgba(200, 40, 40, 0.08));
      border: 1.5px solid rgba(160,30,30,0.3);
      border-radius: 12px;
      padding: 25px;
    }

    .benefit-item {
      padding: 12px 0;
      border-bottom: 1px solid rgba(160,30,30,0.2);
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .benefit-item:last-child {
      border-bottom: none;
    }

    .benefit-icon {
      font-size: 1.3rem;
    }

    .page-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: 
        radial-gradient(circle at 20% 50%, rgba(255,90,90,0.05) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(160,30,30,0.05) 0%, transparent 50%),
        linear-gradient(180deg, #000 0%, #0a0000 100%);
      z-index: -1;
      pointer-events: none;
    }
  </style>
</head>
<body class="bg-dark">
  <div class="page-background"></div>
  
  <?php include 'includes/header.php'; ?>

  <div class="container py-5">
    <!-- Profile Header -->
    <div class="profile-header">
      <div class="profile-avatar">👤</div>
      <h1 class="text-light mb-2"><?= $userName ?></h1>
      <p class="text-muted mb-0">Member sejak <?= $joinDate ?></p>
    </div>

    <!-- Main Content -->
    <div class="row">
      <!-- Left Column: User Info -->
      <div class="col-lg-6 mb-4">
        <h4 class="text-danger mb-4">📋 Informasi Akun</h4>
        
        <div class="info-card">
          <div class="info-label">👤 Nama Lengkap</div>
          <div class="info-value"><?= $userName ?></div>

          <div class="info-label">@️ Username</div>
          <div class="info-value">@<?= $userUsername ?></div>

          <div class="info-label">📧 Email</div>
          <div class="info-value"><?= $userEmail ?></div>

          <div class="info-label">🎖️ Status Membership</div>
          <div>
            <span class="badge bg-success px-3 py-2">
              <span style="font-size: 0.9rem;">✓ Active Member</span>
            </span>
          </div>
        </div>
      </div>

      <!-- Right Column: Stats & Benefits -->
      <div class="col-lg-6 mb-4">
        <h4 class="text-danger mb-4">📊 Statistik Pembelian</h4>
        
        <div class="row g-3 mb-4">
          <div class="col-6">
            <div class="stat-box">
              <div class="stat-number"><?= $totalOrders ?></div>
              <div class="stat-label">Total Pesanan</div>
            </div>
          </div>
          <div class="col-6">
            <div class="stat-box">
              <div class="stat-number">Rp <?= number_format($totalSpent ?? 0, 0, ',', '.') ?></div>
              <div class="stat-label">Total Belanja</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Member Benefits Section -->
    <div class="row mt-5">
      <div class="col-lg-12">
        <h4 class="text-danger mb-4">🎁 Benefit Member Anda</h4>
        
        <div class="member-benefits">
          <div class="benefit-item">
            <span class="benefit-icon">🔥</span>
            <span class="text-light"><strong>Diskon Khusus</strong> - Dapatkan diskon eksklusif untuk setiap pembelian unit motor</span>
          </div>
          <div class="benefit-item">
            <span class="benefit-icon">👨‍💼</span>
            <span class="text-light"><strong>Konsultasi Gratis</strong> - Akses unlimited konsultasi dengan expert kami untuk memilih motor yang tepat</span>
          </div>
          <div class="benefit-item">
            <span class="benefit-icon">⭐</span>
            <span class="text-light"><strong>Priority Booking</strong> - Prioritas booking untuk test ride dan servis maintenance</span>
          </div>
          <div class="benefit-item">
            <span class="benefit-icon">🚀</span>
            <span class="text-light"><strong>Early Bird Access</strong> - Akses pertama untuk unit limited edition dan model terbaru</span>
          </div>
          <div class="benefit-item">
            <span class="benefit-icon">📦</span>
            <span class="text-light"><strong>Gratis Aksesori</strong> - Paket aksesori gratis untuk setiap pembelian unit</span>
          </div>
          <div class="benefit-item">
            <span class="benefit-icon">🛡️</span>
            <span class="text-light"><strong>Extended Warranty</strong> - Perpanjangan garansi dengan harga spesial member</span>
          </div>
        </div>
      </div>
    </div>

  </div>

  <?php include 'includes/footer.php'; ?>

  <script src="bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
