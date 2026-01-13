<?php 
require_once 'api/connection.php';
include 'includes/header.php'; 

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
  echo '<div class="container mt-5"><div class="alert alert-warning">ID motor tidak valid. Kembali ke <a href="index.php">Beranda</a>.</div></div>';
  include 'includes/footer.php';
  exit;
}

// Query motor dari database
$motor = null;
try {
  // Cek dulu apakah kolom features ada
  $describeStmt = $pdo->query("DESCRIBE models");
  $columns = $describeStmt->fetchAll(PDO::FETCH_COLUMN, 0);
  $hasFeatures = in_array('features', $columns);
  
  // Query berdasarkan apakah kolom features ada
  if ($hasFeatures) {
    $stmt = $pdo->prepare("SELECT id, code, title, price, description, img, features FROM models WHERE id = ?");
  } else {
    $stmt = $pdo->prepare("SELECT id, code, title, price, description, img FROM models WHERE id = ?");
  }
  
  $stmt->execute([$id]);
  $motor = $stmt->fetch();
  
  // Jika features tidak ada di hasil, set null
  if ($motor && !$hasFeatures) {
    $motor['features'] = null;
  }
} catch (Exception $e) {
  error_log("Error fetching motor: " . $e->getMessage());
}

if (!$motor) {
  echo '<div class="container mt-5"><div class="alert alert-warning">Model tidak ditemukan. Kembali ke <a href="index.php">Beranda</a>.</div></div>';
  include 'includes/footer.php';
  exit;
}

$m = $motor;
?>

<div class="container mt-5">
  <div class="row mb-4">
    <div class="col-md-6">
      <?php if (!empty($m['img']) && file_exists($m['img'])): ?>
        <img src="<?php echo htmlspecialchars($m['img']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($m['title']); ?>">
      <?php else: ?>
        <div class="card p-4 text-center bg-dark text-light">[Gambar tidak tersedia]</div>
      <?php endif; ?>
    </div>

    <div class="col-md-6">
      <h2 class="mb-2"><?php echo htmlspecialchars($m['title']); ?></h2>
      <div class="product-code mb-3" style="font-size: 0.9rem; color: #a01e1e;">Code: <?php echo htmlspecialchars($m['code']); ?></div>
      <div class="product-price mb-4" style="font-size: 1.5rem; font-weight: bold; color: #a01e1e;"><?php echo htmlspecialchars($m['price']); ?></div>

      <h4 class="mt-4 mb-3">Deskripsi</h4>
      <p class="fs-5"><?php echo nl2br(htmlspecialchars($m['description'])); ?></p>

      <h4 class="mt-4 mb-3">Fitur Unggulan</h4>
      <ul class="list-unstyled">
        <?php
        if (!empty($m['features'])) {
          $features = json_decode($m['features'], true);
          if (is_array($features)) {
            foreach ($features as $feature) {
              echo '<li class="mb-2">' . htmlspecialchars($feature) . '</li>';
            }
          }
        } else {
          echo '<li class="mb-2 text-muted">Fitur unggulan tidak tersedia</li>';
        }
        ?>
      </ul>

      <div class="mt-5">
        <a href="index.php" class="btn btn-outline-light me-2">← Kembali ke Beranda</a>
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
          <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#orderModal" data-motor-id="<?php echo $m['id']; ?>" data-motor-title="<?php echo htmlspecialchars($m['title']); ?>">Pesan Sekarang</button>
        <?php else: ?>
          <a href="login.php" class="btn btn-danger">Login untuk Pesan</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Order Modal -->
<?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-bottom border-secondary">
        <h5 class="modal-title" id="orderModalLabel">Konfirmasi Pesanan</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-4">
          <h6 class="text-muted mb-2">Motor yang dipilih:</h6>
          <p class="mb-0" id="motorTitle" style="font-size: 1.1rem; font-weight: 600;"></p>
          <p class="mb-0 mt-2"><strong>Harga:</strong> <span id="motorPrice" style="color: #a01e1e;"></span></p>
        </div>
        <form id="orderForm">
          <input type="hidden" id="motorId" name="motor_id">
          <div class="mb-3">
            <label for="customerName" class="form-label">Nama Anda</label>
            <input type="text" class="form-control form-control-dark" id="customerName" readonly value="<?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?>">
          </div>
          <div class="mb-3">
            <label for="phoneNumber" class="form-label">Nomor Telepon *</label>
            <input type="tel" class="form-control form-control-dark" id="phoneNumber" name="phone" placeholder="Contoh: 08123456789" required pattern="[0-9]{10,15}">
            <small class="text-muted">Minimal 10 digit, tanpa karakter khusus</small>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-secondary">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmOrderBtn">Konfirmasi Pesanan</button>
      </div>
    </div>
  </div>
</div>

<style>
  .form-control-dark {
    background-color: #1a1a1a;
    border-color: #444;
    color: #fff;
  }
  .form-control-dark:focus {
    background-color: #222;
    border-color: #a01e1e;
    color: #fff;
    box-shadow: 0 0 0 0.2rem rgba(160, 30, 30, 0.25);
  }
  .form-control-dark::placeholder {
    color: #999;
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const orderModal = document.getElementById('orderModal');
  
  orderModal.addEventListener('show.bs.modal', function(e) {
    const button = e.relatedTarget;
    const motorId = button.getAttribute('data-motor-id');
    const motorTitle = button.getAttribute('data-motor-title');
    
    // Set values in modal
    document.getElementById('motorId').value = motorId;
    document.getElementById('motorTitle').textContent = motorTitle;
    
    // Get motor price from the page (from detail.php display)
    const priceText = document.querySelector('.product-price').textContent;
    document.getElementById('motorPrice').textContent = priceText;
  });
  
  // Handle order submission
  document.getElementById('confirmOrderBtn').addEventListener('click', function() {
    const motorId = document.getElementById('motorId').value;
    const phone = document.getElementById('phoneNumber').value;
    
    if (!phone.match(/^[0-9]{10,15}$/)) {
      alert('Nomor telepon harus 10-15 digit');
      return;
    }
    
    // Send order to API
    fetch('api/add_order.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        motor_id: motorId,
        phone: phone
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert(data.message);
        // Close modal and redirect to my orders page
        bootstrap.Modal.getInstance(document.getElementById('orderModal')).hide();
        window.location.href = 'my_orders.php?new_order=' + data.order_id;
      } else {
        alert('Error: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Terjadi kesalahan. Silakan coba lagi.');
    });
  });
});
</script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
