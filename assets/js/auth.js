// Login dengan API dan Cookies
function validateLogin(event) {
  event.preventDefault();
  console.log('validateLogin dipanggil');
  
  var user = document.getElementById('username').value.trim();
  var pass = document.getElementById('password').value.trim();

  console.log('Username:', user, 'Password length:', pass.length);

  if (!user || !pass) {
    alert('Username dan password wajib diisi');
    return;
  }

  console.log('Mengirim request ke API...');
  
  // Kirim ke API (pakai form-encoded agar kompatibel di shared hosting)
  fetch('api/auth_login.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    credentials: 'include',
    cache: 'no-store',
    body: new URLSearchParams({ username: user, password: pass }).toString()
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert(data.message);
      window.location.href = 'index.php';
    } else {
      alert(data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Terjadi kesalahan saat login');
  });
}

// Register dengan API
function handleRegister(event) {
  event.preventDefault();
  var name = document.getElementById('name').value.trim();
  var username = document.getElementById('r_username').value.trim();
  var password = document.getElementById('r_password').value.trim();
  var email = document.getElementById('email').value.trim();

  if (!name || !username || !password) {
    alert('Nama, username, dan password wajib diisi.');
    return;
  }

  // Kirim ke API (pakai form-encoded)
  fetch('api/auth_register.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    credentials: 'include',
    cache: 'no-store',
    body: new URLSearchParams({
      name: name,
      username: username,
      email: email,
      password: password
    }).toString()
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert(data.message);
      window.location.href = 'login.php';
    } else {
      alert(data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Terjadi kesalahan saat registrasi');
  });
}

// Fungsi Logout dengan API
function handleLogout() {
  if (!confirm('Yakin ingin logout?')) {
    return;
  }

  fetch('api/auth_logout.php', {
    method: 'POST',
    credentials: 'include',
    cache: 'no-store'
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert(data.message);
      window.location.href = 'login.php';
    }
  })
  .catch(error => {
    console.error('Error:', error);
    // Tetap redirect meskipun error
    window.location.href = 'login.php';
  });
}

document.addEventListener('DOMContentLoaded', function() {
  console.log('auth.js loaded, mencari form...');
  
  var loginForm = document.getElementById('loginForm');
  console.log('loginForm element:', loginForm);
  
  if (loginForm) {
    loginForm.addEventListener('submit', validateLogin);
    console.log('Event listener login terpasang');
  } else {
    console.error('Form login tidak ditemukan!');
  }

  var regForm = document.getElementById('registerForm');
  if (regForm) {
    regForm.addEventListener('submit', handleRegister);
    console.log('Event listener register terpasang');
  }

  var logoutBtn = document.getElementById('logoutBtn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', function(e) {
      e.preventDefault();
      handleLogout();
    });
    console.log('Event listener logout terpasang');
  }
});
