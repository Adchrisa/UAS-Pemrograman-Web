<?php 
// Halaman Kelola Transaksi - Admin Only
require_once 'includes/auth_check.php';
requireAdmin();
$user = getLoggedInUser();
include 'includes/header.php'; 
?>

<div class="row mb-3">
  <div class="col-12">
    <a href="admin_dashboard.php" class="btn btn-sm btn-secondary">← Kembali ke Dashboard</a>
    <a href="index.php" class="btn btn-sm btn-outline-secondary">🏠 Kembali ke Beranda</a>
    <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#exportModal">⬇️ Export Transaksi</button>
  </div>
</div>

<div class="card p-4 mt-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>📋 Kelola Transaksi</h3>
    <button class="btn btn-success" onclick="openAddOrderModal()">
      ➕ Tambah Transaksi Baru
    </button>
  </div>

  <div class="table-responsive">
    <table class="table table-dark table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Motor</th>
          <th>Customer</th>
          <th>Phone</th>
          <th>Qty</th>
          <th>Total Harga</th>
          <th>Status</th>
          <th>Tanggal</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="ordersTableBody">
        <tr>
          <td colspan="9" class="text-center">Loading...</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal untuk Add Order -->
<div class="modal fade" id="orderModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Transaksi Baru</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="orderForm">
          <div class="mb-3">
            <label class="form-label">Pilih Motor *</label>
            <select class="form-control" id="motorId" required>
              <option value="">-- Pilih Motor --</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Nama Customer *</label>
            <input type="text" class="form-control" id="customerName" required>
          </div>

          <div class="mb-3">
            <label class="form-label">No. Telepon</label>
            <input type="text" class="form-control" id="customerPhone" placeholder="08xxx">
          </div>

          <div class="mb-3">
            <label class="form-label">Quantity *</label>
            <input type="number" class="form-control" id="quantity" value="1" min="1" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Total Harga *</label>
            <input type="number" class="form-control" id="totalPrice" required placeholder="550000000">
            <small class="text-muted">Masukkan angka tanpa titik/koma</small>
          </div>

          <div class="mb-3">
            <label class="form-label">Status *</label>
            <select class="form-control" id="status" required>
              <option value="pending">Pending</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="saveOrder()">Simpan</button>
      </div>
    </div>
  </div>
</div>

<script>
// Load orders saat halaman dibuka
document.addEventListener('DOMContentLoaded', function() {
  loadOrders();
  loadMotorOptions();
});

// Load semua orders
function loadOrders() {
  fetch('api/get_orders.php')
    .then(response => response.json())
    .then(data => {
      if (data.success && data.data.length > 0) {
        displayOrders(data.data);
      } else {
        document.getElementById('ordersTableBody').innerHTML = 
          '<tr><td colspan="9" class="text-center">Belum ada transaksi</td></tr>';
      }
    })
    .catch(error => {
      console.error('Error:', error);
      document.getElementById('ordersTableBody').innerHTML = 
        '<tr><td colspan="9" class="text-center text-danger">Error loading data</td></tr>';
    });
}

// Display orders di tabel
function displayOrders(orders) {
  let html = '';
  orders.forEach(order => {
    let badge = 'secondary';
    if (order.status == 'completed') badge = 'success';
    if (order.status == 'pending') badge = 'warning';
    if (order.status == 'cancelled') badge = 'danger';

    html += `
      <tr>
        <td>#${order.id}</td>
        <td>${order.motor_name || 'N/A'}</td>
        <td>${order.customer_name}</td>
        <td>${order.customer_phone || '-'}</td>
        <td>1</td>
        <td>Rp ${Number(order.total_price).toLocaleString('id-ID')}</td>
        <td><span class="badge bg-${badge}">${order.status}</span></td>
        <td>${formatDate(order.order_date)}</td>
        <td>
          <button class="btn btn-sm btn-warning" onclick="updateStatus(${order.id})">📝 Status</button>
          <button class="btn btn-sm btn-danger" onclick="deleteOrder(${order.id})">🗑️</button>
        </td>
      </tr>
    `;
  });
  document.getElementById('ordersTableBody').innerHTML = html;
}

// Load motor options untuk dropdown
function loadMotorOptions() {
  fetch('api/get_data.php')
    .then(response => response.json())
    .then(data => {
      if (data.success && data.data) {
        const select = document.getElementById('motorId');
        data.data.forEach(motor => {
          const option = document.createElement('option');
          option.value = motor.id;
          option.textContent = `${motor.title} (${motor.code})`;
          select.appendChild(option);
        });
      }
    });
}

// Open modal
function openAddOrderModal() {
  document.getElementById('orderForm').reset();
  var modal = new bootstrap.Modal(document.getElementById('orderModal'));
  modal.show();
}

// Save order
function saveOrder() {
  const data = {
    motor_id: document.getElementById('motorId').value,
    customer_name: document.getElementById('customerName').value.trim(),
    customer_phone: document.getElementById('customerPhone').value.trim(),
    quantity: document.getElementById('quantity').value,
    total_price: document.getElementById('totalPrice').value,
    status: document.getElementById('status').value
  };

  if (!data.motor_id || !data.customer_name || !data.total_price) {
    alert('Motor, Nama Customer, dan Total Harga wajib diisi');
    return;
  }

  fetch('api/add_order.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  })
  .then(response => response.json())
  .then(result => {
    if (result.success) {
      alert(result.message);
      bootstrap.Modal.getInstance(document.getElementById('orderModal')).hide();
      loadOrders();
    } else {
      alert('Error: ' + result.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Terjadi kesalahan saat menyimpan transaksi');
  });
}

// Update status order
function updateStatus(id) {
  const newStatus = prompt('Update status (pending/completed/cancelled):');
  if (!newStatus) return;

  if (!['pending', 'completed', 'cancelled'].includes(newStatus)) {
    alert('Status tidak valid');
    return;
  }

  fetch('api/update_order_status.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id: id, status: newStatus })
  })
  .then(response => response.json())
  .then(result => {
    if (result.success) {
      alert(result.message);
      loadOrders();
    } else {
      alert('Error: ' + result.message);
    }
  });
}

// Delete order
function deleteOrder(id) {
  if (!confirm('Yakin ingin menghapus transaksi ini?')) return;

  fetch('api/delete_order.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id: id })
  })
  .then(response => response.json())
  .then(result => {
    if (result.success) {
      alert(result.message);
      loadOrders();
    } else {
      alert('Error: ' + result.message);
    }
  });
}

// Format date
function formatDate(dateString) {
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

<!-- Modal Export Transaksi -->
<div class="modal fade" id="exportModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">📥 Export Transaksi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted mb-4">Pilih format file untuk export:</p>
        <div class="d-grid gap-3">
          <a href="api/export_orders_pdf.php" class="btn btn-lg btn-outline-danger" target="_blank">
            📄 PDF
            <small class="d-block text-muted">Download dalam format PDF</small>
          </a>
          <a href="api/export_orders_excel.php" class="btn btn-lg btn-outline-success" target="_blank">
            📊 Excel
            <small class="d-block text-muted">Download dalam format Excel (.xlsx)</small>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
