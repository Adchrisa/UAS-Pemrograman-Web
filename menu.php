<?php 
// Proteksi halaman - harus login
require_once 'includes/auth_check.php';
$user = getLoggedInUser();

// Redirect admin ke dashboard admin
if (isAdmin()) {
    header("Location: admin_dashboard.php");
    exit;
}

include 'includes/header.php'; 
?>

<div class="row mb-4">
  <div class="col-12">
    <h2>👋 Selamat Datang, <?= htmlspecialchars($user['name']) ?>!</h2>
    <p class="text-muted">Member Dashboard - Cigadung Motorworks</p>
  </div>
</div>

<div class="card p-4">
  <h4>Menu Pengguna</h4>
  <div class="list-group mb-4">
    <a class="list-group-item list-group-item-action bg-transparent text-light" href="index.php">
      🔥 Lihat Model Motor Cigadung Motorworks
    </a>
    <a class="list-group-item list-group-item-action bg-transparent text-light" href="#">
      📄 Artikel: Cara Memilih Sportbike
    </a>
    <a class="list-group-item list-group-item-action bg-transparent text-light" href="#">
      🛠 Tips Maintenance Motor Sport
    </a>
    <a class="list-group-item list-group-item-action bg-transparent text-light" href="#">
      📞 Hubungi Dealer Resmi
    </a>
    <a class="list-group-item list-group-item-action bg-transparent text-danger" 
       id="logoutBtn" href="#">
      🚪 Logout
    </a>
  </div>

  <div class="card p-3" style="background:#1c1c1c;">
    <h5>Informasi Pengguna</h5>
    <p><strong>Nama:</strong> <?= htmlspecialchars($user['name']) ?><br>
       <strong>Username:</strong> <?= htmlspecialchars($user['username']) ?><br>
       <small class="text-muted">Anda login menggunakan sistem authentication dengan cookies & session.</small>
    </p>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
