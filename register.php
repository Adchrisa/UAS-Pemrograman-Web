<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card p-4">
      <h3 class="card-title">Registrasi Akun</h3>
      <p class="text-muted small mb-3">Buat akun baru untuk mengakses sistem CRUD Cigadung Motorworks</p>
      <form id="registerForm">
        <div class="mb-3">
          <label class="form-label">Nama Lengkap *</label>
          <input id="name" class="form-control" type="text" required placeholder="Contoh: John Doe">
        </div>
        <div class="mb-3">
          <label class="form-label">Username *</label>
          <input id="r_username" class="form-control" type="text" required placeholder="Hanya huruf, angka, dan underscore">
          <small class="text-muted">Username untuk login, harus unique</small>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input id="email" class="form-control" type="email" placeholder="email@example.com">
          <small class="text-muted">Opsional</small>
        </div>
        <div class="mb-3">
          <label class="form-label">Password *</label>
          <input id="r_password" class="form-control" type="password" required placeholder="Minimal 6 karakter">
        </div>
        <button class="btn btn-primary w-100" type="submit">Daftar Sekarang</button>
        <div class="text-center mt-3">
          <a href="login.php" class="btn btn-link">Sudah punya akun? Login</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
