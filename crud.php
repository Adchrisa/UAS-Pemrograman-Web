<?php 
// Proteksi halaman - harus login
require_once 'includes/auth_check.php';
requireAdmin(); // Hanya admin yang bisa akses
$user = getLoggedInUser();
include 'includes/header.php'; 
?>

<div class="row mb-3">
  <div class="col-12">
    <a href="admin_dashboard.php" class="btn btn-sm btn-secondary">← Kembali ke Dashboard</a>
    <a href="index.php" class="btn btn-sm btn-outline-secondary">🏠 Kembali ke Beranda</a>
  </div>
</div>

<div class="card p-4 mt-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>🏍️ Manajemen Produk Motor</h3>
    <button class="btn btn-success" onclick="openAddModal()">
      ➕ Tambah Motor Baru
    </button>
  </div>

  <div class="table-responsive">
    <table class="table table-dark table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Kode</th>
          <th>Nama Motor</th>
          <th>Harga</th>
          <th>Deskripsi</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="motorTableBody">
        <tr>
          <td colspan="6" class="text-center">Loading...</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal untuk Add/Edit -->
<div class="modal fade" id="motorModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Tambah Motor</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="motorForm">
          <input type="hidden" id="motorId">
          
          <div class="mb-3">
            <label class="form-label">Kode Motor *</label>
            <input type="text" class="form-control" id="motorCode" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Nama Motor *</label>
            <input type="text" class="form-control" id="motorTitle" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="text" class="form-control" id="motorPrice" placeholder="Rp 100.000.000">
          </div>

          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea class="form-control" id="motorDescription" rows="3"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Fitur Unggulan</label>
            <small class="d-block text-muted mb-2">Masukkan setiap fitur di baris terpisah (tanpa perlu ✓, akan ditambahkan otomatis)</small>
            <textarea class="form-control" id="motorFeatures" rows="5" placeholder="Desain aerodinamis modern&#10;Mesin bertenaga tinggi dengan performa maksimal&#10;Suspensi high-performance&#10;Sistem pengereman canggih&#10;Traction Control & Riding Modes&#10;Rangka aluminium ringan"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Gambar Motor</label>
            <input type="file" class="form-control" id="motorImgFile" accept="image/*">
            <small class="text-muted d-block mt-2">atau masukkan URL:</small>
            <input type="text" class="form-control mt-2" id="motorImg" placeholder="https://...">
            <div id="imgPreview" class="mt-2"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="saveMotor()">Simpan</button>
      </div>
    </div>
  </div>
</div>

<script>
// Load data saat halaman dibuka
document.addEventListener('DOMContentLoaded', function() {
  loadMotors();
});

// Fungsi untuk load semua data motor
function loadMotors() {
  fetch('api/get_data.php')
    .then(response => response.json())
    .then(data => {
      if (data.success && data.data.length > 0) {
        displayMotors(data.data);
      } else {
        document.getElementById('motorTableBody').innerHTML = 
          '<tr><td colspan="6" class="text-center">Belum ada data motor</td></tr>';
      }
    })
    .catch(error => {
      console.error('Error:', error);
      document.getElementById('motorTableBody').innerHTML = 
        '<tr><td colspan="6" class="text-center text-danger">Error loading data</td></tr>';
    });
}

// Fungsi untuk display data di table
function displayMotors(motors) {
  let html = '';
  motors.forEach(motor => {
    html += `
      <tr>
        <td>${motor.id}</td>
        <td>${motor.code}</td>
        <td>${motor.title}</td>
        <td>${motor.price || '-'}</td>
        <td>${motor.description ? motor.description.substring(0, 50) + '...' : '-'}</td>
        <td>
          <button class="btn btn-sm btn-warning" onclick="editMotor(${motor.id})">✏️ Edit</button>
          <button class="btn btn-sm btn-danger" onclick="deleteMotor(${motor.id})">🗑️ Hapus</button>
        </td>
      </tr>
    `;
  });
  document.getElementById('motorTableBody').innerHTML = html;
}

// Preview gambar saat file dipilih
document.addEventListener('DOMContentLoaded', function() {
  loadMotors();
  
  const imgFileInput = document.getElementById('motorImgFile');
  if (imgFileInput) {
    imgFileInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
          document.getElementById('imgPreview').innerHTML = 
            `<img src="${event.target.result}" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">`;
        };
        reader.readAsDataURL(file);
      }
    });
  }
});

// Fungsi untuk buka modal tambah
function openAddModal() {
  document.getElementById('modalTitle').textContent = 'Tambah Motor Baru';
  document.getElementById('motorForm').reset();
  document.getElementById('motorId').value = '';
  document.getElementById('motorFeatures').value = '';
  document.getElementById('imgPreview').innerHTML = '';
  
  var modal = new bootstrap.Modal(document.getElementById('motorModal'));
  modal.show();
}

// Fungsi untuk edit motor
function editMotor(id) {
  fetch(`api/get_data.php?id=${id}`)
    .then(response => response.json())
    .then(result => {
      if (result.success && result.data) {
        const motor = result.data;
        document.getElementById('modalTitle').textContent = 'Edit Motor';
        document.getElementById('motorId').value = motor.id;
        document.getElementById('motorCode').value = motor.code;
        document.getElementById('motorTitle').value = motor.title;
        document.getElementById('motorPrice').value = motor.price || '';
        document.getElementById('motorDescription').value = motor.description || '';
        
        // Parse features dari JSON dan tampilkan di textarea
        if (motor.features) {
          try {
            const features = JSON.parse(motor.features);
            if (Array.isArray(features)) {
              document.getElementById('motorFeatures').value = features.map(f => {
                // Hapus simbol ✓ jika ada
                return f.replace(/^✓\s*/, '').trim();
              }).join('\n');
            }
          } catch (e) {
            document.getElementById('motorFeatures').value = motor.features || '';
          }
        }
        
        document.getElementById('motorImg').value = motor.img || '';
        
        var modal = new bootstrap.Modal(document.getElementById('motorModal'));
        modal.show();
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Gagal memuat data motor');
    });
}

// Fungsi untuk save (insert/update)
function saveMotor() {
  const id = document.getElementById('motorId').value;
  const code = document.getElementById('motorCode').value.trim();
  const title = document.getElementById('motorTitle').value.trim();
  const price = document.getElementById('motorPrice').value.trim();
  const description = document.getElementById('motorDescription').value.trim();
  const featuresText = document.getElementById('motorFeatures').value.trim();
  const imgFile = document.getElementById('motorImgFile').files[0];
  let imgValue = document.getElementById('motorImg').value.trim();

  if (!code || !title) {
    alert('Kode dan Nama Motor wajib diisi');
    return;
  }

  // Konversi fitur text ke JSON array
  let features = null;
  if (featuresText) {
    features = JSON.stringify(
      featuresText.split('\n')
        .map(line => {
          let trimmed = line.trim();
          if (trimmed) {
            // Tambahkan ✓ jika belum ada
            return trimmed.startsWith('✓') ? trimmed : '✓ ' + trimmed;
          }
          return null;
        })
        .filter(item => item !== null)
    );
  }

  // Jika ada file yang di-upload, upload terlebih dahulu
  if (imgFile) {
    const formData = new FormData();
    formData.append('image', imgFile);

    fetch('api/upload_image.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(result => {
      if (result.success) {
        imgValue = result.path;
        // Setelah upload berhasil, simpan data motor
        saveMotorData(id, code, title, price, description, imgValue, features);
      } else {
        alert('Error upload gambar: ' + result.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Terjadi kesalahan saat upload gambar');
    });
  } else {
    // Tidak ada file upload, langsung simpan
    saveMotorData(id, code, title, price, description, imgValue, features);
  }
}

// Helper function untuk simpan data motor
function saveMotorData(id, code, title, price, description, img, features) {
  const data = {
    code: code,
    title: title,
    price: price,
    description: description,
    img: img,
    features: features
  };

  if (id) {
    data.id = id;
  }

  fetch('api/insup_data.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
  })
  .then(response => response.json())
  .then(result => {
    if (result.success) {
      alert(result.message);
      bootstrap.Modal.getInstance(document.getElementById('motorModal')).hide();
      loadMotors();
    } else {
      alert('Error: ' + result.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Terjadi kesalahan saat menyimpan data');
  });
}

// Fungsi untuk delete motor
function deleteMotor(id) {
  if (!confirm('Yakin ingin menghapus data motor ini?')) {
    return;
  }

  fetch('api/delete_data.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ id: id })
  })
  .then(response => response.json())
  .then(result => {
    if (result.success) {
      alert(result.message);
      loadMotors();
    } else {
      alert('Error: ' + result.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Terjadi kesalahan saat menghapus data');
  });
}
</script>

<?php include 'includes/footer.php'; ?>
