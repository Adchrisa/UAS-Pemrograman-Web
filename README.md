# Cigadung Motor Works - CRUD & Sistem Transaksi

Aplikasi manajemen motor dan transaksi dengan dashboard admin, autentikasi user, dan export laporan.

## 📋 Fitur Utama

- **Autentikasi**: Login, register, logout dengan session dan cookie
- **CRUD Motor**: Tambah, lihat, edit, hapus data motor dengan fitur unik setiap model
- **Manajemen Transaksi**: Admin bisa lihat dan ubah status order
- **User Profile**: User bisa lihat riwayat order mereka
- **Export Laporan**: Download transaksi dalam format PDF atau Excel (CSV)
- **Role-Based Access**: Admin dashboard dan user area terpisah


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


