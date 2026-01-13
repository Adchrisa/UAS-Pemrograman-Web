<?php 
require_once 'api/connection.php';
require_once 'api/init_db.php'; // Auto-initialize database

// Fetch semua products dari database
$products = [];
try {
  $stmt = $pdo->query("SELECT id, code, title, price, description, img FROM models ORDER BY id DESC");
  $products = $stmt->fetchAll();
} catch (Exception $e) {
  error_log("Error fetching products: " . $e->getMessage());
}

include 'includes/header.php'; 

// Check if user is logged in (for personalized greeting)
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$userName = $isLoggedIn ? ($_SESSION['name'] ?? 'User') : null;
?>

<!-- Professional Hero Section -->
<div class="hero-section">
  <div class="hero-background">
    <div class="hero-glow hero-glow-1"></div>
    <div class="hero-glow hero-glow-2"></div>
    <div class="hero-grid"></div>
  </div>
  
  <div class="container hero-container">
    <div class="hero-content-wrapper">
      <div class="hero-main">
        <div class="hero-top-badge">
          <span class="pulse-dot"></span>
          <span>Premium Sportbike Dealer</span>
        </div>
        
        <h1 class="hero-main-title">
          <span class="title-word">Cigadung</span>
          <span class="title-word highlight">Motorworks</span>
        </h1>
        
        <p class="hero-main-subtitle">
          Experience the ultimate fusion of performance, luxury, and engineering excellence.
        </p>
        
        <div class="hero-stats-row">
          <div class="hero-stat">
            <div class="stat-value"><?= count($products) ?>+</div>
            <div class="stat-label">Premium Units</div>
          </div>
          <div class="hero-stat-divider"></div>
          <div class="hero-stat">
            <div class="stat-value">500+</div>
            <div class="stat-label">Satisfied Riders</div>
          </div>
          <div class="hero-stat-divider"></div>
          <div class="hero-stat">
            <div class="stat-value">24/7</div>
            <div class="stat-label">Expert Support</div>
          </div>
        </div>
        
        <div class="hero-actions">
          <a href="#produk" class="btn-hero btn-hero-primary">
            <span class="btn-text">Explore Collection</span>
            <span class="btn-icon">→</span>
          </a>
          <a href="#about" class="btn-hero btn-hero-secondary">
            <span class="btn-text">Learn More</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Why Choose Us Section -->
<section id="why-us" class="mb-5 py-5" style="background: linear-gradient(180deg, rgba(0,0,0,0.8) 0%, rgba(26,0,0,0.8) 100%);">
  <div class="container">
    <h2 class="section-title text-center mb-5">Mengapa Memilih Cigadung Motorworks</h2>
    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <span class="feature-icon">✓</span>
          <h5 class="feature-title">Unit Original 100%</h5>
          <p class="text-muted">Semua motor dijamin asli dengan dokumentasi lengkap, STNK resmi, dan garansi pabrik.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <span class="feature-icon">👨‍💼</span>
          <h5 class="feature-title">Konsultasi Expert</h5>
          <p class="text-muted">Tim sales berpengalaman siap membantu Anda memilih motor sesuai kebutuhan dan budget.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <span class="feature-icon">🔒</span>
          <h5 class="feature-title">Transaksi Aman</h5>
          <p class="text-muted">Proses pembelian transparan, legal, dan dilindungi kontrak resmi untuk keamanan Anda.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Section Produk Motor -->
<section id="produk" class="mb-5">
  <div class="container">
    <h2 class="section-title mb-4">Koleksi Motor Sport Premium</h2>
    <div class="row g-4">
  <?php
    if (count($products) > 0) {
      foreach($products as $motor) {
        echo '<div class="col-lg-4 col-md-6">';
        echo '<div class="product-card">';
        
        // Display gambar jika ada
        echo '<div class="product-image-wrapper">';
        if (!empty($motor['img'])) {
          echo '<img src="' . htmlspecialchars($motor['img']) . '" 
                    alt="' . htmlspecialchars($motor['title']) . '" 
                    onerror="this.src=\'data:image/svg+xml,%3Csvg xmlns=\\\'http://www.w3.org/2000/svg\\\' viewBox=\\\'0 0 400 300\\\'%3E%3Crect fill=\\\'%23000\\\'/%3E%3Ctext x=\\\'50%25\\\' y=\\\'50%25\\\' dominant-baseline=\\\'middle\\\' text-anchor=\\\'middle\\\' font-size=\\\'60\\\' fill=\\\'%23333\\\'%3E🏍️%3C/text%3E%3C/svg%3E\'">';
        } else {
          echo '<img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 400 300\'%3E%3Crect fill=\'%23000\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'60\' fill=\'%23333\'%3E🏍️%3C/text%3E%3C/svg%3E" alt="Product">';
        }
        echo '<span class="product-badge">NEW</span>';
        echo '</div>';
        
        echo '<div class="product-info">';
        echo '<div class="product-code">' . htmlspecialchars($motor['code']) . '</div>';
        echo '<h5 class="product-title">' . htmlspecialchars($motor['title']) . '</h5>';
        echo '<div class="product-price mb-3">' . htmlspecialchars($motor['price']) . '</div>';
        echo '<a href="detail.php?id=' . urlencode($motor['id']) . '" 
                  class="btn btn-primary w-100">Lihat Detail</a>';
        echo '</div></div></div>';
      }
    } else {
      echo '<div class="col-12"><div class="card p-5 text-center">';
      echo '<h5 class="text-muted">Belum ada produk tersedia</h5>';
      echo '</div></div>';
    }
  ?>
    </div>
  </div>
</section>

<!-- About Section -->
<section id="about" class="mb-5">
  <div class="container">
    <h2 class="section-title text-center mb-5">Tentang Cigadung Motorworks</h2>
    <div class="row align-items-center">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <h4 class="text-danger mb-3">Visi Kami</h4>
        <p style="color: #ddd; line-height: 1.8;">
          Menjadi showroom motor sport premium terpercaya di Indonesia dengan unit original, konsultasi profesional, dan layanan berkualitas tinggi.
        </p>
        <h4 class="text-danger mt-4 mb-3">Misi Kami</h4>
        <ul class="list-unstyled" style="color: #ccc;">
          <li class="mb-2">✓ Menyediakan motor 100% original dengan garansi resmi</li>
          <li class="mb-2">✓ Memberikan konsultasi profesional</li>
          <li class="mb-2">✓ Proses transaksi yang transparan</li>
          <li class="mb-2">✓ Layanan after-sales terbaik</li>
        </ul>
      </div>
      <div class="col-lg-6">
        <div class="row g-3">
          <div class="col-6">
            <div class="text-center">
              <div style="font-size: 3rem; margin-bottom: 10px;">🏍️</div>
              <p class="text-muted small">Original</p>
            </div>
          </div>
          <div class="col-6">
            <div class="text-center">
              <div style="font-size: 3rem; margin-bottom: 10px;">👥</div>
              <p class="text-muted small">Expert</p>
            </div>
          </div>
          <div class="col-6">
            <div class="text-center">
              <div style="font-size: 3rem; margin-bottom: 10px;">🛡️</div>
              <p class="text-muted small">Safe</p>
            </div>
          </div>
          <div class="col-6">
            <div class="text-center">
              <div style="font-size: 3rem; margin-bottom: 10px;">⚡</div>
              <p class="text-muted small">Fast</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- Section Layanan -->
<section id="layanan" class="mb-5">
  <div class="container">
    <h2 class="section-title mb-4">Layanan Premium Kami</h2>
    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <span class="feature-icon">💰</span>
          <h5 class="feature-title">Trade-In & Tukar Tambah</h5>
          <p class="text-muted">Tukar motor lama Anda dengan unit baru. Kami menerima berbagai jenis motor dengan harga kompetitif dan proses cepat.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <span class="feature-icon">🔧</span>
          <h5 class="feature-title">Service & Maintenance</h5>
          <p class="text-muted">Layanan perawatan rutin dan perbaikan oleh mekanik berpengalaman. Tersedia booking online untuk kemudahan Anda.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <span class="feature-icon">📋</span>
          <h5 class="feature-title">Konsultasi Pembelian</h5>
          <p class="text-muted">Gratis konsultasi dengan sales expert kami untuk memilih motor yang sesuai dengan kebutuhan dan budget Anda.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <span class="feature-icon">💳</span>
          <h5 class="feature-title">Kredit & Leasing</h5>
          <p class="text-muted">Solusi pembiayaan fleksibel dengan DP ringan, tenor hingga 5 tahun, dan proses approval cepat (1-2 hari).</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <span class="feature-icon">📦</span>
          <h5 class="feature-title">Accessories & Spareparts</h5>
          <p class="text-muted">Lengkapi motor Anda dengan aksesoris original dan spareparts genuine. Stok lengkap untuk berbagai model.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="feature-box">
          <span class="feature-icon">🏁</span>
          <h5 class="feature-title">Test Ride</h5>
          <p class="text-muted">Coba langsung motor impian Anda sebelum membeli. Jadwalkan test ride dengan safety gear lengkap dari kami.</p>
        </div>
      </div>
    </div>


  </div>
</section>

<!-- Section Artikel & Tips -->
<section id="artikel" class="mb-5">
  <div class="container">
    <h2 class="section-title mb-4">Artikel & Tips Berkendara</h2>
    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <div class="card h-100" style="overflow: hidden;">
          <div style="height: 180px; background: linear-gradient(135deg, #2a0000, #4d0000); display: flex; align-items: center; justify-content: center;">
            <span style="font-size: 4rem;">📖</span>
          </div>
          <div class="p-4">
            <h5 class="text-danger mb-3">Cara Memilih Sportbike yang Tepat</h5>
            <p class="text-muted small mb-3">Tips memilih motor sport sesuai tinggi badan, pengalaman riding, dan budget Anda.</p>
            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#articleModal1">Baca Selengkapnya →</button>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="card h-100" style="overflow: hidden;">
          <div style="height: 180px; background: linear-gradient(135deg, #2a0000, #4d0000); display: flex; align-items: center; justify-content: center;">
            <span style="font-size: 4rem;">🔧</span>
          </div>
          <div class="p-4">
            <h5 class="text-danger mb-3">Maintenance Motor Sport: Panduan Lengkap</h5>
            <p class="text-muted small mb-3">Perawatan rutin, oli terbaik, dan tips agar performa motor tetap optimal.</p>
            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#articleModal2">Baca Selengkapnya →</button>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="card h-100" style="overflow: hidden;">
          <div style="height: 180px; background: linear-gradient(135deg, #2a0000, #4d0000); display: flex; align-items: center; justify-content: center;">
            <span style="font-size: 4rem;">🏍️</span>
          </div>
          <div class="p-4">
            <h5 class="text-danger mb-3">Perbedaan Supersport vs Superbike</h5>
            <p class="text-muted small mb-3">Memahami karakter, performa, dan perbedaan antara kedua kategori motor sport ini.</p>
            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#articleModal3">Baca Selengkapnya →</button>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="card h-100" style="overflow: hidden;">
          <div style="height: 180px; background: linear-gradient(135deg, #2a0000, #4d0000); display: flex; align-items: center; justify-content: center;">
            <span style="font-size: 4rem;">⚙️</span>
          </div>
          <div class="p-4">
            <h5 class="text-danger mb-3">Modifikasi Motor Sport: Do's & Don'ts</h5>
            <p class="text-muted small mb-3">Panduan modifikasi aman yang tidak merusak performa dan garansi motor Anda.</p>
            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#articleModal4">Baca Selengkapnya →</button>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="card h-100" style="overflow: hidden;">
          <div style="height: 180px; background: linear-gradient(135deg, #2a0000, #4d0000); display: flex; align-items: center; justify-content: center;">
            <span style="font-size: 4rem;">🏁</span>
          </div>
          <div class="p-4">
            <h5 class="text-danger mb-3">Track Day untuk Pemula</h5>
            <p class="text-muted small mb-3">Persiapan, equipment, dan tips mengikuti track day pertama Anda dengan aman.</p>
            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#articleModal5">Baca Selengkapnya →</button>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="card h-100" style="overflow: hidden;">
          <div style="height: 180px; background: linear-gradient(135deg, #2a0000, #4d0000); display: flex; align-items: center; justify-content: center;">
            <span style="font-size: 4rem;">🛡️</span>
          </div>
          <div class="p-4">
            <h5 class="text-danger mb-3">Safety Riding: Wajib Tahu untuk Rider</h5>
            <p class="text-muted small mb-3">Teknik berkendara aman, posisi riding yang benar, dan tips defensive riding.</p>
            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#articleModal6">Baca Selengkapnya →</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Article Modals -->
<!-- Modal 1: Cara Memilih Sportbike yang Tepat -->
<div class="modal fade" id="articleModal1" tabindex="-1" aria-labelledby="articleModal1Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-bottom border-secondary">
        <h5 class="modal-title" id="articleModal1Label">📖 Cara Memilih Sportbike yang Tepat</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="article-content">
          <p class="text-muted mb-4"><em>Ditulis oleh Tim CMW Sportbike | 10 Januari 2026</em></p>
          
          <p>Memilih sportbike yang tepat adalah keputusan penting yang harus mempertimbangkan berbagai faktor. Berikut panduan lengkapnya:</p>
          
          <h6 class="text-danger mt-4 mb-3">1. Sesuaikan dengan Tinggi Badan</h6>
          <p>Tinggi sadel motor sport bervariasi dari 780mm hingga 850mm. Pastikan kaki Anda bisa menapak tanah dengan nyaman saat berhenti. Untuk rider dengan tinggi di bawah 165cm, pilih motor dengan sadel rendah seperti Ninja 250 atau CBR250RR.</p>
          
          <h6 class="text-danger mt-4 mb-3">2. Pertimbangkan Pengalaman Riding</h6>
          <ul class="mb-3">
            <li><strong>Pemula:</strong> Mulai dengan 250-300cc seperti Ninja 250, CBR250RR, atau R25</li>
            <li><strong>Intermediate:</strong> 600-650cc seperti Ninja 650, CBR650R</li>
            <li><strong>Advanced:</strong> 1000cc+ seperti Ninja H2, CBR1000RR-R</li>
          </ul>
          
          <h6 class="text-danger mt-4 mb-3">3. Budget dan Biaya Kepemilikan</h6>
          <p>Hitung tidak hanya harga pembelian, tapi juga:</p>
          <ul class="mb-3">
            <li>Biaya service berkala (Rp 500.000 - 2.000.000 per service)</li>
            <li>Konsumsi bahan bakar (sportbike 15-25 km/liter)</li>
            <li>Asuransi (3-5% dari harga motor per tahun)</li>
            <li>Ban racing (Rp 2-4 juta per set, tahan 5000-8000km)</li>
          </ul>
          
          <h6 class="text-danger mt-4 mb-3">4. Tujuan Penggunaan</h6>
          <p><strong>Daily Commute:</strong> Pilih yang ergonomis seperti Ninja 650 atau CBR650R dengan posisi riding lebih upright.</p>
          <p><strong>Track Day:</strong> Pilih full supersport seperti R1, CBR1000RR-R, atau Ninja ZX-10R.</p>
          <p><strong>Weekend Ride:</strong> Sport touring seperti Ninja 1000SX cocok untuk jarak jauh.</p>
          
          <h6 class="text-danger mt-4 mb-3">5. Test Ride Wajib!</h6>
          <p>Jangan membeli tanpa test ride. Rasakan:</p>
          <ul>
            <li>Posisi riding dan kenyamanan ergonomi</li>
            <li>Responsivitas throttle dan handling</li>
            <li>Kualitas suspensi dan pengereman</li>
            <li>Berat motor saat bermanuver di kecepatan rendah</li>
          </ul>
          
          <div class="alert alert-danger mt-4" role="alert">
            <strong>💡 Tips Pro:</strong> Jangan tergoda membeli motor terlalu powerful untuk level skill Anda. Motor yang "terlalu banyak" bisa berbahaya dan menghambat learning curve Anda sebagai rider.
          </div>
        </div>
      </div>
      <div class="modal-footer border-top border-secondary">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal 2: Maintenance Motor Sport -->
<div class="modal fade" id="articleModal2" tabindex="-1" aria-labelledby="articleModal2Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-bottom border-secondary">
        <h5 class="modal-title" id="articleModal2Label">🔧 Maintenance Motor Sport: Panduan Lengkap</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="article-content">
          <p class="text-muted mb-4"><em>Ditulis oleh Tim CMW Sportbike | 8 Januari 2026</em></p>
          
          <p>Perawatan rutin adalah kunci untuk menjaga performa optimal motor sport Anda. Berikut panduan maintenance lengkap:</p>
          
          <h6 class="text-danger mt-4 mb-3">Jadwal Service Berkala</h6>
          <table class="table table-dark table-striped table-sm">
            <thead>
              <tr>
                <th>Kilometer</th>
                <th>Maintenance</th>
                <th>Estimasi Biaya</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1.000 km</td>
                <td>Service awal, ganti oli mesin</td>
                <td>Rp 300.000</td>
              </tr>
              <tr>
                <td>6.000 km</td>
                <td>Ganti oli, filter, cek rem & rantai</td>
                <td>Rp 500.000</td>
              </tr>
              <tr>
                <td>12.000 km</td>
                <td>Service besar, ganti busi, air filter</td>
                <td>Rp 1.200.000</td>
              </tr>
              <tr>
                <td>24.000 km</td>
                <td>Overhaul valve, ganti coolant</td>
                <td>Rp 2.500.000</td>
              </tr>
            </tbody>
          </table>
          
          <h6 class="text-danger mt-4 mb-3">Pemilihan Oli Terbaik</h6>
          <p><strong>Untuk Penggunaan Harian:</strong></p>
          <ul>
            <li>Motul 7100 10W-40 (Rp 150.000/liter)</li>
            <li>Shell Advance Ultra 10W-40 (Rp 120.000/liter)</li>
            <li>Castrol Power1 Racing 10W-40 (Rp 140.000/liter)</li>
          </ul>
          
          <p><strong>Untuk Track Day / Racing:</strong></p>
          <ul>
            <li>Motul 300V 15W-50 (Rp 400.000/liter)</li>
            <li>Castrol Power1 Racing 4T 10W-50 (Rp 350.000/liter)</li>
            <li>Liqui Moly Racing 10W-60 (Rp 380.000/liter)</li>
          </ul>
          
          <h6 class="text-danger mt-4 mb-3">Perawatan Rantai</h6>
          <ol>
            <li><strong>Bersihkan setiap 500-600km:</strong> Gunakan chain cleaner dan sikat khusus</li>
            <li><strong>Lumasi setelah dibersihkan:</strong> Pakai chain lube berkualitas (DID, RK, EK)</li>
            <li><strong>Cek ketegangan:</strong> Slack 20-30mm (lihat manual motor)</li>
            <li><strong>Ganti setiap 15.000-20.000km:</strong> Ganti chain dan sprocket bersamaan</li>
          </ol>
          
          <h6 class="text-danger mt-4 mb-3">Sistem Pengereman</h6>
          <p>Cek kampas rem setiap 3.000km. Ganti jika ketebalan di bawah 2mm. Ganti brake fluid DOT 4 setiap 1-2 tahun untuk performa optimal.</p>
          
          <h6 class="text-danger mt-4 mb-3">Ban</h6>
          <ul>
            <li>Cek tekanan angin setiap minggu (depan: 32-33 psi, belakang: 36-38 psi)</li>
            <li>Ganti jika kedalaman alur < 2mm atau ada retak</li>
            <li>Umur pakai: Sport touring 8000-12000km, Racing 3000-5000km</li>
          </ul>
          
          <div class="alert alert-danger mt-4" role="alert">
            <strong>⚠️ Penting:</strong> Jangan skip service berkala meski motor jarang dipakai. Oli tetap terdegradasi meski motor diam. Ganti oli minimal 1x per tahun.
          </div>
        </div>
      </div>
      <div class="modal-footer border-top border-secondary">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal 3: Perbedaan Supersport vs Superbike -->
<div class="modal fade" id="articleModal3" tabindex="-1" aria-labelledby="articleModal3Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-bottom border-secondary">
        <h5 class="modal-title" id="articleModal3Label">🏍️ Perbedaan Supersport vs Superbike</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="article-content">
          <p class="text-muted mb-4"><em>Ditulis oleh Tim CMW Sportbike | 5 Januari 2026</em></p>
          
          <p>Banyak rider bingung membedakan supersport dan superbike. Mari kita bahas perbedaan mendasar kedua kategori ini:</p>
          
          <div class="row mt-4">
            <div class="col-md-6">
              <div class="card bg-secondary mb-3">
                <div class="card-body">
                  <h6 class="text-danger mb-3">🏁 SUPERSPORT (600cc)</h6>
                  <p><strong>Contoh Motor:</strong></p>
                  <ul class="small">
                    <li>Yamaha R6</li>
                    <li>Kawasaki Ninja ZX-6R</li>
                    <li>Honda CBR600RR</li>
                    <li>Suzuki GSX-R600</li>
                  </ul>
                  <p><strong>Karakteristik:</strong></p>
                  <ul class="small">
                    <li>Kapasitas: 599-636cc</li>
                    <li>Power: 110-130 HP</li>
                    <li>Berat: 185-195 kg</li>
                    <li>Redline: 15.000-16.500 RPM</li>
                    <li>Top Speed: 250-270 km/jam</li>
                  </ul>
                  <p class="small"><strong>Cocok untuk:</strong> Track day pemula-intermediate, daily riding yang sporty, rider dengan body lebih kecil</p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card bg-secondary mb-3">
                <div class="card-body">
                  <h6 class="text-danger mb-3">🚀 SUPERBIKE (1000cc+)</h6>
                  <p><strong>Contoh Motor:</strong></p>
                  <ul class="small">
                    <li>Yamaha R1 / R1M</li>
                    <li>Kawasaki Ninja ZX-10R / H2</li>
                    <li>Honda CBR1000RR-R Fireblade</li>
                    <li>Suzuki GSX-R1000</li>
                  </ul>
                  <p><strong>Karakteristik:</strong></p>
                  <ul class="small">
                    <li>Kapasitas: 998-1441cc</li>
                    <li>Power: 180-230+ HP</li>
                    <li>Berat: 195-205 kg</li>
                    <li>Redline: 13.000-14.500 RPM</li>
                    <li>Top Speed: 280-320+ km/jam</li>
                  </ul>
                  <p class="small"><strong>Cocok untuk:</strong> Track day advanced, racing, rider expert dengan skill tinggi, straight line performance</p>
                </div>
              </div>
            </div>
          </div>
          
          <h6 class="text-danger mt-4 mb-3">Perbedaan Karakter Riding</h6>
          <p><strong>Supersport (600cc):</strong></p>
          <ul>
            <li>Perlu diputar tinggi untuk maksimal power (9000+ RPM)</li>
            <li>Lebih flick-able dan nimble di tikungan</li>
            <li>Corner speed lebih cepat dari superbike</li>
            <li>Lebih mudah dikontrol untuk rider intermediate</li>
            <li>Konsumsi BBM lebih irit (18-22 km/liter)</li>
          </ul>
          
          <p><strong>Superbike (1000cc+):</strong></p>
          <ul>
            <li>Massive torque dari low-mid RPM</li>
            <li>Akselerasi brutal, wheelie prone</li>
            <li>Straight line speed unggul jauh</li>
            <li>Butuh skill tinggi untuk extract full potential</li>
            <li>Electronic aids lengkap (TC, ABS, QS, DTC)</li>
            <li>Konsumsi BBM boros (12-16 km/liter)</li>
          </ul>
          
          <h6 class="text-danger mt-4 mb-3">Mana yang Lebih Cepat di Track?</h6>
          <p>Untuk rider expert: Superbike 1-2 detik/lap lebih cepat.</p>
          <p>Untuk rider intermediate: Supersport bisa sama cepat atau lebih cepat karena lebih mudah dikontrol di corner.</p>
          
          <div class="alert alert-danger mt-4" role="alert">
            <strong>📝 Kesimpulan:</strong> Jika Anda rider intermediate dan fokus di track, supersport adalah pilihan tepat. Jika Anda expert dan butuh bragging rights + power maksimal, pilih superbike. Untuk daily riding? Jangan keduanya 😄 - terlalu uncomfortable!
          </div>
        </div>
      </div>
      <div class="modal-footer border-top border-secondary">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal 4: Modifikasi Motor Sport -->
<div class="modal fade" id="articleModal4" tabindex="-1" aria-labelledby="articleModal4Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-bottom border-secondary">
        <h5 class="modal-title" id="articleModal4Label">⚙️ Modifikasi Motor Sport: Do's & Don'ts</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="article-content">
          <p class="text-muted mb-4"><em>Ditulis oleh Tim CMW Sportbike | 3 Januari 2026</em></p>
          
          <p>Modifikasi motor sport bisa meningkatkan performa, tapi salah langkah bisa merusak handling dan bahkan void warranty. Berikut panduannya:</p>
          
          <h6 class="text-danger mt-4 mb-3">✅ DO's - Modifikasi yang Aman</h6>
          
          <p><strong>1. Exhaust System (Knalpot Racing)</strong></p>
          <ul>
            <li>✅ Pilih brand ternama: Akrapovic, Yoshimura, SC Project, Arrow</li>
            <li>✅ Full system lebih optimal dari slip-on, tapi lebih mahal</li>
            <li>✅ Dyno tuning setelah ganti knalpot untuk AFR optimal</li>
            <li>⚠️ Cek regulasi: beberapa daerah larang knalpot racing di jalan</li>
            <li>Budget: Rp 5-35 juta (slip-on Rp 5-15 juta, full system Rp 15-35 juta)</li>
          </ul>
          
          <p><strong>2. ECU Flash / Power Commander</strong></p>
          <ul>
            <li>✅ Unlock performa tersembunyi ECU standar</li>
            <li>✅ Hilangkan limiter, perbaiki throttle response</li>
            <li>✅ Custom fuel mapping sesuai modifikasi</li>
            <li>Budget: Rp 2-8 juta</li>
          </ul>
          
          <p><strong>3. Suspension Upgrade</strong></p>
          <ul>
            <li>✅ Ohlins, Nitron, KTech untuk performa track</li>
            <li>✅ Set sag sesuai berat rider (25-30mm front, 30-40mm rear)</li>
            <li>✅ Professional setup sangat penting</li>
            <li>Budget: Rp 15-60 juta (fork + shock)</li>
          </ul>
          
          <p><strong>4. Ban Performance</strong></p>
          <ul>
            <li>✅ Pirelli Diablo Rosso IV / Supercorsa</li>
            <li>✅ Michelin Power 5 / Power Cup 2</li>
            <li>✅ Bridgestone S22 / V02</li>
            <li>✅ Dunlop Q4 untuk track</li>
            <li>Budget: Rp 3-6 juta per set</li>
          </ul>
          
          <h6 class="text-danger mt-4 mb-3">❌ DON'Ts - Jangan Lakukan Ini!</h6>
          
          <div class="alert alert-warning text-dark" role="alert">
            <p><strong>1. ❌ Porting Polish Ekstrem</strong></p>
            <p>Porting polish berlebihan bisa merusak karakteristik mesin. Pabrik sudah R&D bertahun-tahun. Trust them!</p>
            
            <p><strong>2. ❌ Ganti Piston/Bore Up</strong></p>
            <p>Void warranty, durability turun drastis. Untuk street bike, TIDAK WORTH IT.</p>
            
            <p><strong>3. ❌ Potong Stang / Ubah Geometri</strong></p>
            <p>Handling jadi unpredictable dan berbahaya. Geometri motor hasil perhitungan kompleks.</p>
            
            <p><strong>4. ❌ Ban Murah / Tidak Branded</strong></p>
            <p>Ini literally nyawa Anda di jalan. NEVER compromise on tires!</p>
            
            <p><strong>5. ❌ Air Filter Racing Tanpa Tune ECU</strong></p>
            <p>AFR jadi lean, mesin bisa overheat dan rusak.</p>
          </div>
          
          <h6 class="text-danger mt-4 mb-3">Prioritas Modifikasi (Urutan Budget Wise)</h6>
          <ol>
            <li><strong>Ban (Rp 3-6 juta):</strong> ROI tertinggi untuk safety & performa</li>
            <li><strong>Brake Pads Racing (Rp 800rb-2juta):</strong> Stopping power lebih baik</li>
            <li><strong>Knalpot (Rp 5-15 juta):</strong> Estetika + sedikit power gain</li>
            <li><strong>ECU Flash (Rp 2-5 juta):</strong> Unlock performa tersembunyi</li>
            <li><strong>Suspension (Rp 15-60 juta):</strong> Untuk yang serius track day</li>
          </ol>
          
          <div class="alert alert-danger mt-4" role="alert">
            <strong>💰 Budget Reality Check:</strong> Modifikasi bisa menelan 30-100% dari harga motor. Pertimbangkan: apakah lebih baik upgrade ke motor lebih tinggi? R25 + 50 juta modif = masih kalah dari R6 standar.
          </div>
        </div>
      </div>
      <div class="modal-footer border-top border-secondary">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal 5: Track Day untuk Pemula -->
<div class="modal fade" id="articleModal5" tabindex="-1" aria-labelledby="articleModal5Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-bottom border-secondary">
        <h5 class="modal-title" id="articleModal5Label">🏁 Track Day untuk Pemula</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="article-content">
          <p class="text-muted mb-4"><em>Ditulis oleh Tim CMW Sportbike | 1 Januari 2026</em></p>
          
          <p>Track day adalah experience yang mengubah cara Anda berkendara. Berikut panduan lengkap untuk pertama kali ke circuit:</p>
          
          <h6 class="text-danger mt-4 mb-3">Persiapan Motor</h6>
          <ol>
            <li><strong>Tape semua lampu kaca:</strong> Gunakan duct tape untuk cover headlight, tail light, sein. Jika pecah tidak berserakan di track.</li>
            <li><strong>Lepas spion:</strong> Wajib dilepas untuk safety.</li>
            <li><strong>Coolant diganti air:</strong> Jika motor crash, coolant licin di track. Pakai air biasa.</li>
            <li><strong>Cek tekanan ban:</strong> Hot: 32/34 psi (depan/belakang). Cold: 28/30 psi.</li>
            <li><strong>Cek brake fluid & brake pad:</strong> Pastikan masih tebal, rem akan bekerja keras.</li>
            <li><strong>Isi bensin cukup:</strong> 1 sesi (20-30 menit) bisa habis 4-6 liter.</li>
          </ol>
          
          <h6 class="text-danger mt-4 mb-3">Equipment Wajib</h6>
          <table class="table table-dark table-striped table-sm">
            <thead>
              <tr>
                <th>Item</th>
                <th>Spesifikasi</th>
                <th>Budget</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Helmet</td>
                <td>Full face, SNI/DOT/ECE</td>
                <td>Rp 1-10 juta</td>
              </tr>
              <tr>
                <td>Leather Suit</td>
                <td>1-piece or 2-piece dengan zip</td>
                <td>Rp 3-30 juta</td>
              </tr>
              <tr>
                <td>Gloves</td>
                <td>Racing gloves dengan knuckle guard</td>
                <td>Rp 500rb-3 juta</td>
              </tr>
              <tr>
                <td>Boots</td>
                <td>Racing boots, ankle protection</td>
                <td>Rp 1-8 juta</td>
              </tr>
              <tr>
                <td>Back Protector</td>
                <td>CE Level 2 (optional tapi recommended)</td>
                <td>Rp 500rb-2 juta</td>
              </tr>
            </tbody>
          </table>
          
          <h6 class="text-danger mt-4 mb-3">Biaya Track Day</h6>
          <ul>
            <li><strong>Entry fee:</strong> Rp 500.000 - 1.500.000 (tergantung circuit & organizer)</li>
            <li><strong>Bensin:</strong> Rp 200.000 - 400.000 (full day 3-4 sesi)</li>
            <li><strong>Ban (jika perlu ganti):</strong> Rp 3.000.000 - 6.000.000</li>
            <li><strong>Brake pad (jika habis):</strong> Rp 800.000 - 2.000.000</li>
            <li><strong>Crash damage:</strong> Rp 0 - 50 juta (pray you don't crash!)</li>
          </ul>
          
          <h6 class="text-danger mt-4 mb-3">Etika di Track</h6>
          <ol>
            <li><strong>Respect flag marshal:</strong> Red flag = stop immediately. Yellow = slow down, no overtake. Checkered = session end.</li>
            <li><strong>Overtake hanya di straight:</strong> Jangan pernah overtake di corner, berbahaya!</li>
            <li><strong>Signal dengan tangan:</strong> Angkat tangan kiri jika slow down atau ada masalah.</li>
            <li><strong>Jangan ego:</strong> Ini bukan race. Fokus improve lap time Anda sendiri.</li>
            <li><strong>Stay di group level Anda:</strong> Biasanya dibagi pemula, intermediate, advanced.</li>
          </ol>
          
          <h6 class="text-danger mt-4 mb-3">Tips Riding di Track</h6>
          <ul>
            <li>🏁 <strong>Smooth is fast:</strong> Fokus smooth throttle, brake, steering. Aggressive ≠ fast.</li>
            <li>👀 <strong>Look ahead:</strong> Mata harus selalu lihat 2-3 corner ahead.</li>
            <li>🎯 <strong>Nail the apex:</strong> Late apex lebih aman untuk pemula.</li>
            <li>🔥 <strong>Warm up tires:</strong> 1-2 lap pertama pelan untuk panaskan ban.</li>
            <li>📹 <strong>Record & analyze:</strong> Pasang camera, review video untuk improve.</li>
          </ul>
          
          <h6 class="text-danger mt-4 mb-3">Sirkuit di Indonesia</h6>
          <ul>
            <li><strong>Sentul International Circuit (Bogor):</strong> 4.012 km, best untuk pemula</li>
            <li><strong>Mandalika Circuit (Lombok):</strong> 4.318 km, MotoGP grade</li>
            <li><strong>Kanjuruhan Circuit (Malang):</strong> 2.4 km, teknikal</li>
          </ul>
          
          <div class="alert alert-danger mt-4" role="alert">
            <strong>🚨 Warning:</strong> Track day ADDICTIVE! Setelah first track day, Anda akan kecanduan dan dompet langsung tipis. Budget minimal Rp 30-50 juta untuk gear + motor upgrade + track day fees per tahun. Worth it? ABSOLUTELY! 🔥
          </div>
        </div>
      </div>
      <div class="modal-footer border-top border-secondary">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal 6: Safety Riding -->
<div class="modal fade" id="articleModal6" tabindex="-1" aria-labelledby="articleModal6Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-bottom border-secondary">
        <h5 class="modal-title" id="articleModal6Label">🛡️ Safety Riding: Wajib Tahu untuk Rider</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="article-content">
          <p class="text-muted mb-4"><em>Ditulis oleh Tim CMW Sportbike | 28 Desember 2025</em></p>
          
          <p>Riding motor sport bukan hanya soal speed, tapi survival. Berikut panduan safety riding yang WAJIB dipahami setiap rider:</p>
          
          <h6 class="text-danger mt-4 mb-3">Posisi Riding yang Benar</h6>
          
          <p><strong>Posisi Duduk Standar:</strong></p>
          <ul>
            <li>Kepala sejajar dengan stang, pandangan jauh ke depan</li>
            <li>Siku sedikit bengkok, rileks - jangan kaku</li>
            <li>Grip stang dengan 3 jari (pinky & ring finger light grip)</li>
            <li>Lutut menjepit tangki untuk stabilitas</li>
            <li>Kaki di footpeg ball of foot, tumit sejajar footpeg</li>
          </ul>
          
          <p><strong>Posisi Cornering:</strong></p>
          <ul>
            <li>Bodyweight ke dalam (inside lean)</li>
            <li>Head over inside mirror (kepala miring ke arah belokan)</li>
            <li>Outside leg grip tangki, inside leg rileks</li>
            <li>Look through the corner (mata ke apex & exit)</li>
          </ul>
          
          <h6 class="text-danger mt-4 mb-3">Teknik Pengereman</h6>
          
          <div class="row">
            <div class="col-md-6">
              <div class="card bg-secondary mb-3">
                <div class="card-body">
                  <h6 class="text-warning mb-3">Rem Depan (70-80% power)</h6>
                  <ul class="small">
                    <li>Squeeze progressively, jangan grab</li>
                    <li>2-3 jari cukup (index + middle)</li>
                    <li>Trail braking untuk advance rider</li>
                    <li>Max braking di upright position</li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card bg-secondary mb-3">
                <div class="card-body">
                  <h6 class="text-warning mb-3">Rem Belakang (20-30% power)</h6>
                  <ul class="small">
                    <li>Stabilitas saat pengereman</li>
                    <li>Hindari lock rear wheel</li>
                    <li>Useful di corner untuk adjust line</li>
                    <li>Bisa di-combine dengan front</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          
          <p><strong>Emergency Braking:</strong></p>
          <ol>
            <li>Squeeze clutch (cut power)</li>
            <li>Apply front brake progressively (squeeze, don't grab)</li>
            <li>Apply rear brake (prevent rear lift)</li>
            <li>Shift body weight ke belakang</li>
            <li>Keep eyes UP, jangan lihat ke tanah</li>
          </ol>
          
          <h6 class="text-danger mt-4 mb-3">Defensive Riding: SIPDE Method</h6>
          
          <p><strong>S - SCAN:</strong> Mata harus aktif scan 12-15 detik ahead. Cek mirrors setiap 3-5 detik.</p>
          
          <p><strong>I - IDENTIFY:</strong> Identify hazard: mobil slow, pedestrian, oil spill, pothole, gravel.</p>
          
          <p><strong>P - PREDICT:</strong> Predict what stupid things car driver might do. Assume mereka TIDAK lihat Anda.</p>
          
          <p><strong>D - DECIDE:</strong> Decide action: slow down, change lane, increase following distance.</p>
          
          <p><strong>E - EXECUTE:</strong> Execute smoothly, tidak panic.</p>
          
          <h6 class="text-danger mt-4 mb-3">Gear & Equipment ATGATT</h6>
          <p><strong>ATGATT = All The Gear, All The Time</strong></p>
          
          <table class="table table-dark table-striped table-sm">
            <thead>
              <tr>
                <th>Gear</th>
                <th>Protection</th>
                <th>Injury Reduction</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Helmet</td>
                <td>Kepala, otak</td>
                <td>37% fatality ↓</td>
              </tr>
              <tr>
                <td>Jacket (armor)</td>
                <td>Shoulder, elbow, back</td>
                <td>Protection from road rash</td>
              </tr>
              <tr>
                <td>Gloves</td>
                <td>Tangan, jari</td>
                <td>Instinct protect hands</td>
              </tr>
              <tr>
                <td>Riding Pants</td>
                <td>Hip, knee</td>
                <td>Serious injury ↓ 50%</td>
              </tr>
              <tr>
                <td>Boots</td>
                <td>Ankle, kaki</td>
                <td>Fracture prevention</td>
              </tr>
            </tbody>
          </table>
          
          <h6 class="text-danger mt-4 mb-3">Kondisi Berbahaya</h6>
          
          <p><strong>❌ JANGAN Riding Saat:</strong></p>
          <ul>
            <li>Hujan deras (visibility rendah, grip minimal)</li>
            <li>Ban sudah tipis (< 2mm tread depth)</li>
            <li>Kurang tidur / mengantuk</li>
            <li>Emotional (marah, sedih, stress)</li>
            <li>Setelah minum alkohol (ZERO tolerance!)</li>
          </ul>
          
          <p><strong>⚠️ Extra Hati-hati Saat:</strong></p>
          <ul>
            <li>Jam sibuk traffic (banyak mobil unpredictable)</li>
            <li>Blind corner / crest hill (tidak tahu apa di depan)</li>
            <li>Malam hari (visibility terbatas, drunk driver)</li>
            <li>Intersection (90% accident terjadi di sini)</li>
          </ul>
          
          <h6 class="text-danger mt-4 mb-3">Mental & Mindset</h6>
          <blockquote class="blockquote">
            <p class="mb-0">"Ride like everyone is trying to kill you, because they are."</p>
            <footer class="blockquote-footer mt-2">Anonymous Rider</footer>
          </blockquote>
          
          <ul class="mt-3">
            <li>🧠 <strong>Ego kills:</strong> Slow rider yang pulang = winner. Fast rider di rumah sakit = loser.</li>
            <li>🎯 <strong>Focus:</strong> One mistake, one distraction = bisa fatal.</li>
            <li>🙏 <strong>Respect road:</strong> Road tidak care seberapa bagus motor Anda atau seberapa jago skill Anda.</li>
            <li>📚 <strong>Never stop learning:</strong> Ikut safety riding course, track day, coaching.</li>
          </ul>
          
          <div class="alert alert-danger mt-4" role="alert">
            <strong>🚨 Reality Check:</strong> Statistik menunjukkan motor sport 27x lebih berbahaya dari mobil. TAPI dengan proper skill, gear, dan mindset - Anda bisa minimize risk drastis. Ride smart, ride safe, ride long! 🏍️💪
          </div>
        </div>
      </div>
      <div class="modal-footer border-top border-secondary">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Article Modal Styles -->
<style>
.article-content h6 {
  font-weight: 600;
  letter-spacing: 0.3px;
}
.article-content p {
  line-height: 1.8;
  color: #e3e3e3;
}
.article-content ul, .article-content ol {
  line-height: 1.9;
  color: #e3e3e3;
}
.article-content strong {
  color: #fff;
  font-weight: 600;
}
.article-content .alert {
  border-radius: 8px;
  padding: 1rem 1.25rem;
}
.article-content .card {
  border-radius: 8px;
  border: 1px solid rgba(255,90,90,0.2);
}
.article-content blockquote {
  border-left: 4px solid #c41e3a;
  padding-left: 1rem;
  font-style: italic;
  color: #cfcfcf;
}
.modal-lg {
  max-width: 900px;
}
.modal-body {
  max-height: 70vh;
}
</style>

<?php include 'includes/footer.php'; ?>

