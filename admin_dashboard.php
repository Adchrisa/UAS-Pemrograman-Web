<?php 
// Dashboard Admin - Hanya bisa diakses oleh admin
require_once 'includes/auth_check.php';
requireAdmin(); // Hanya admin yang bisa akses
$user = getLoggedInUser();

// Load statistics
require_once 'api/connection.php';

// Total produk motor
$stmt = $pdo->query("SELECT COUNT(*) as total FROM models");
$totalProducts = $stmt->fetch()['total'];

// Total orders
$stmt = $pdo->query("SELECT COUNT(*) as total FROM orders");
$totalOrders = $stmt->fetch()['total'];

// Total revenue (completed orders)
$stmt = $pdo->query("SELECT SUM(total_price) as total FROM orders WHERE status = 'completed'");
$totalRevenue = $stmt->fetch()['total'] ?? 0;

// Pending orders
$stmt = $pdo->query("SELECT COUNT(*) as total FROM orders WHERE status = 'pending'");
$pendingOrders = $stmt->fetch()['total'];

// Recent orders
$stmt = $pdo->query("
    SELECT o.id, o.motor_id, o.customer_name, o.customer_phone, o.total_price, o.status, o.order_date, m.title as motor_name 
    FROM orders o 
    LEFT JOIN models m ON o.motor_id = m.id 
    ORDER BY o.order_date DESC 
    LIMIT 5
");
$recentOrders = $stmt->fetchAll();

include 'includes/header.php'; 
?>

<div class="row mb-4">
  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h2>🎯 Dashboard Admin</h2>
        <p class="text-muted">Selamat datang, <strong><?= htmlspecialchars($user['name']) ?></strong> (Admin)</p>
      </div>
      <div>
        <a href="index.php" class="btn btn-secondary btn-sm">← Kembali ke Beranda</a>
      </div>
    </div>
  </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
  <div class="col-md-3 mb-3">
    <div class="card p-3 bg-gradient-primary">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="text-muted mb-1">Total Produk</h6>
          <h2 class="mb-0"><?= $totalProducts ?></h2>
        </div>
        <div class="stat-icon">
          🏍️
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3 mb-3">
    <div class="card p-3 bg-gradient-success">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="text-muted mb-1">Total Transaksi</h6>
          <h2 class="mb-0"><?= $totalOrders ?></h2>
        </div>
        <div class="stat-icon">
          📊
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3 mb-3">
    <div class="card p-3 bg-gradient-warning">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="text-muted mb-1">Revenue</h6>
          <h3 class="mb-0">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></h3>
        </div>
        <div class="stat-icon">
          💰
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3 mb-3">
    <div class="card p-3 bg-gradient-danger">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="text-muted mb-1">Pending Orders</h6>
          <h2 class="mb-0"><?= $pendingOrders ?></h2>
        </div>
        <div class="stat-icon">
          ⏳
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card p-3">
      <h5>⚡ Quick Actions</h5>
      <div class="btn-group" role="group">
        <a href="crud.php" class="btn btn-primary">
          ➕ Tambah Produk Motor
        </a>
        <a href="orders.php" class="btn btn-success">
          📋 Kelola Transaksi
        </a>
        <a href="index.php" class="btn btn-info">
          👁️ Lihat Katalog
        </a>
        <a href="#" id="logoutBtn" class="btn btn-danger">
          🚪 Logout
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Recent Orders -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card p-4">
      <h5 class="mb-3">📦 Transaksi Terbaru</h5>
      <div class="table-responsive">
        <table class="table table-dark table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Motor</th>
              <th>Customer</th>
              <th>Phone</th>
              <th>Qty</th>
              <th>Total</th>
              <th>Status</th>
              <th>Tanggal</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($recentOrders) > 0): ?>
              <?php foreach ($recentOrders as $order): ?>
                <tr>
                  <td>#<?= $order['id'] ?></td>
                  <td><?= htmlspecialchars($order['motor_name'] ?? 'Motor Dihapus') ?></td>
                  <td><?= htmlspecialchars($order['customer_name']) ?></td>
                  <td><?= htmlspecialchars($order['customer_phone']) ?></td>
                  <td>1</td>
                  <td>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></td>
                  <td>
                    <?php
                    $badge = 'secondary';
                    if ($order['status'] == 'completed') $badge = 'success';
                    if ($order['status'] == 'pending') $badge = 'warning';
                    if ($order['status'] == 'cancelled') $badge = 'danger';
                    ?>
                    <span class="badge bg-<?= $badge ?>"><?= $order['status'] ?></span>
                  </td>
                  <td><?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center text-muted">Belum ada transaksi</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div class="text-end mt-2">
        <a href="orders.php" class="btn btn-sm btn-outline-light">Lihat Semua →</a>
      </div>
    </div>
  </div>
</div>

<style>
.stat-icon {
  font-size: 3rem;
  opacity: 0.6;
}

.bg-gradient-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.bg-gradient-success {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
}

.bg-gradient-warning {
  background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%) !important;
}

.bg-gradient-danger {
  background: linear-gradient(135deg, #e01b1b 0%, #8B0000 100%) !important;
}

.card h2, .card h3 {
  color: #fff !important;
  font-weight: bold;
}

.card h6 {
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
</style>

<?php include 'includes/footer.php'; ?>
