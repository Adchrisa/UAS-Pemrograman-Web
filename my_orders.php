<?php 
// Halaman Pesanan Saya - User Only
require_once 'includes/auth_check.php';
requireLogin(); // Harus login
$user = getLoggedInUser();

// Jika admin, redirect ke orders.php
if ($user['role'] === 'admin') {
    header('Location: orders.php');
    exit;
}

include 'includes/header.php'; 
?>

<div class="row mb-3">
  <div class="col-12">
    <a href="index.php" class="btn btn-sm btn-secondary">← Kembali ke Beranda</a>
  </div>
</div>

<div class="card p-4 mt-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3>📦 Pesanan Saya</h3>
      <p class="text-muted mb-0">Halo, <strong><?= htmlspecialchars($user['name']) ?></strong></p>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-dark table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Motor</th>
          <th>Gambar</th>
          <th>No. Telepon</th>
          <th>Total Harga</th>
          <th>Status</th>
          <th>Tanggal Pesan</th>
        </tr>
      </thead>
      <tbody id="myOrdersTableBody">
        <tr>
          <td colspan="7" class="text-center">Loading...</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<script>
// Load orders saat halaman dibuka
document.addEventListener('DOMContentLoaded', function() {
  loadMyOrders();
});

// Load orders user
function loadMyOrders() {
  fetch('api/get_my_orders.php')
    .then(response => response.json())
    .then(data => {
      if (data.success && data.data.length > 0) {
        displayMyOrders(data.data);
      } else {
        document.getElementById('myOrdersTableBody').innerHTML = 
          '<tr><td colspan="7" class="text-center">Anda belum memiliki pesanan</td></tr>';
      }
    })
    .catch(error => {
      console.error('Error:', error);
      document.getElementById('myOrdersTableBody').innerHTML = 
        '<tr><td colspan="7" class="text-center text-danger">Error loading data</td></tr>';
    });
}

// Display orders di tabel
function displayMyOrders(orders) {
  let html = '';
  orders.forEach(order => {
    let badge = 'secondary';
    let statusText = order.status;
    
    if (order.status == 'completed') {
      badge = 'success';
      statusText = 'Selesai';
    }
    if (order.status == 'pending') {
      badge = 'warning';
      statusText = 'Menunggu';
    }
    if (order.status == 'cancelled') {
      badge = 'danger';
      statusText = 'Dibatalkan';
    }

    // Handle image
    let imgHtml = '-';
    if (order.motor_img) {
      imgHtml = `<img src="${order.motor_img}" alt="${order.motor_name}" style="width: 60px; height: 45px; object-fit: cover; border-radius: 5px;">`;
    }

    html += `
      <tr>
        <td>#${order.id}</td>
        <td>
          <strong>${order.motor_name || 'N/A'}</strong><br>
          <small class="text-muted">${order.motor_code || ''}</small>
        </td>
        <td>${imgHtml}</td>
        <td>${order.customer_phone || '-'}</td>
        <td><strong>Rp ${Number(order.total_price).toLocaleString('id-ID')}</strong></td>
        <td><span class="badge bg-${badge}">${statusText}</span></td>
        <td>${formatDate(order.order_date)}</td>
      </tr>
    `;
  });
  document.getElementById('myOrdersTableBody').innerHTML = html;
}

// Format date
function formatDate(dateString) {
  if (!dateString) return '-';
  const date = new Date(dateString);
  return date.toLocaleDateString('id-ID', { 
    day: '2-digit', 
    month: '2-digit', 
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
}
</script>

<style>
.table td {
  vertical-align: middle;
}

.badge {
  padding: 6px 12px;
  font-size: 0.85rem;
}
</style>

<?php include 'includes/footer.php'; ?>
