# Cigadung Motor Works - CRUD & Sistem Transaksi

Aplikasi manajemen motor dan transaksi dengan dashboard admin, autentikasi user, dan export laporan.

## 📋 Fitur Utama

- **Autentikasi**: Login, register, logout dengan session dan cookie
- **CRUD Motor**: Tambah, lihat, edit, hapus data motor dengan fitur unik setiap model
- **Manajemen Transaksi**: Admin bisa lihat dan ubah status order
- **User Profile**: User bisa lihat riwayat order mereka
- **Export Laporan**: Download transaksi dalam format PDF atau Excel (CSV)
- **Role-Based Access**: Admin dashboard dan user area terpisah

## 🚀 Setup Lokal (XAMPP/Ubuntu)

### Prasyarat
- PHP 7.4+
- MySQL/MariaDB
- Apache dengan mod_rewrite
- Browser modern

### Langkah-Langkah

1. **Clone atau Extract project**
   ```bash
   # Jika dari GitHub
   git clone <repo-url>
   cd projek1
   
   # atau extract ZIP ke xampp/htdocs/projek1
   ```

2. **Setup Database**
   - Buat database baru di MySQL:
     ```sql
     CREATE DATABASE projek1;
     ```
   - Import file `database.sql`:
     ```bash
     mysql -u root -p projek1 < database.sql
     ```

3. **Konfigurasi Environment**
   - Copy `.env.example` menjadi `.env`:
     ```bash
     cp .env.example .env
     ```
   - Edit `.env` dengan kredensial database lokal Anda:
     ```dotenv
     DB_HOST=localhost
     DB_NAME=projek1
     DB_USER=root
     DB_PASS=your_password
     ```

4. **Jalankan**
   - **XAMPP**: Buka `http://localhost/projek1`
   - **Ubuntu**: Pastikan project di `/var/www/projek1`, akses via domain atau IP

5. **Login Default**
   - Email: `admin@cms.id`
   - Password: `admin123`

---

## 🌐 Deploy ke InfinityFree

### Prasyarat
- Akun InfinityFree (gratis)
- FTP Client (FileZilla) atau File Manager web

### Langkah-Langkah

1. **Setup Database di InfinityFree**
   - Login ke vPanel InfinityFree
   - Buat database baru (catat nama database dan credentials)

2. **Siapkan Project**
   - Copy `.env.infinityfree` menjadi `.env`:
     ```bash
     cp .env.infinityfree .env
     ```
   - Edit `.env` dengan credentials InfinityFree:
     ```dotenv
     DB_HOST=sql100.infinityfree.com
     DB_NAME=if0_xxxxxx_yourdatabase
     DB_USER=if0_xxxxxx
     DB_PASS=your_vpanel_password
     ```
   - Rename `.htaccess.infinityfree` menjadi `.htaccess` (opsional, untuk shared hosting compatibility)

3. **Upload via FTP atau File Manager**
   - Upload semua file project ke folder public_html via FileZilla atau web File Manager
   - Pastikan folder `images/` dan `includes/` terupload

4. **Import Database**
   - Di vPanel → phpMyAdmin
   - Select database Anda
   - Import file `database.sql`

5. **Akses**
   - Buka `https://yourdomain.infinityfree.com`
   - Login dengan kredensial default di atas

---

## 📝 File & Folder Penting

```
.
├── api/                    # API endpoints untuk CRUD & auth
│   ├── auth_*.php         # Login, register, logout
│   ├── connection.php     # Database connection
│   ├── export_*.php       # Export PDF & Excel
│   └── ...
├── includes/             # Header & footer (layout template)
├── assets/               # CSS & JS custom
├── bootstrap-5.3.8-dist/ # Bootstrap CSS/JS
├── images/               # Folder untuk gambar motor & uploads
├── database.sql          # Schema & seed data
├── .env.example          # Template environment untuk lokal
├── .env.infinityfree     # Template environment untuk InfinityFree
├── .htaccess             # Rewrite rules untuk production
├── .htaccess.infinityfree# Rewrite rules untuk shared hosting (opsional)
└── *.php                 # Halaman utama (login, dashboard, crud, dll)
```

---

## ⚙️ Environment Variables

Edit `.env` sesuai kebutuhan:

| Variable | Keterangan |
|----------|-----------|
| `DB_HOST` | Host MySQL (localhost untuk lokal) |
| `DB_NAME` | Nama database |
| `DB_USER` | Username MySQL |
| `DB_PASS` | Password MySQL |
| `APP_ENV` | `development` atau `production` |
| `APP_DEBUG` | `true` atau `false` |

---

## 🔐 Security Notes

- **Jangan commit `.env`** - Sudah di `.gitignore`
- Ubah password admin default setelah setup
- Gunakan HTTPS jika hosting support
- Session timeout default 7 hari (bisa diatur di `.env`)

---

## 🐛 Troubleshooting

### "Database Connection Failed"
- Cek kredensial di `.env` sesuai database Anda
- Pastikan MySQL service berjalan
- Untuk InfinityFree, gunakan `sql100.infinityfree.com` sebagai host

### "Login Page Acak-acakan / Assets Tidak Load"
- Pastikan mod_rewrite aktif di Apache
- Cek `.htaccess` tidak block folder `/includes/`

### "Export PDF/Excel Error"
- Pastikan folder `images/` dan `vendor/` ada
- Cek permission folder write-able

---

## 📞 Support

- **Database Schema**: Lihat `database.sql` untuk struktur tabel
- **API Endpoints**: Lihat file di folder `api/`
- **Styling**: Edit `assets/css/style.css`

---

**Dibuat untuk UAS Kelas XII**
