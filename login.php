<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card p-4">
      <h3 class="card-title">Login Cigadung Motorworks</h3>
      <form id="loginForm">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input id="username" class="form-control" type="text" autocomplete="username" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input id="password" class="form-control" type="password" autocomplete="current-password" required>
        </div>
        <button class="btn btn-primary w-100" type="submit">Login</button>
        <div class="text-center mt-3">
          <a href="register.php" class="btn btn-link">Belum punya akun? Registrasi</a>
        </div>
      </form>

      <hr>
      <div class="alert alert-info small mb-0">
        <strong>📌 Akun Testing:</strong><br>
        Username: <code>admin</code> | Password: <code>admin123</code>
        <br><small class="text-muted">Atau buat akun baru melalui menu registrasi</small>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
