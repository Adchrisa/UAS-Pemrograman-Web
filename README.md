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


## Screenshots
1. Tampilan beranda saat pertama kali buka website
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/1%20(beranda).PNG)

2. Tampilan login
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/2%20(login).PNG)

3. Tampilan regist
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/3%20(regist).PNG)

4. Login berhasil
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/4%20(login%20berhasil).PNG)

5. Tampilan login sebagai admin, terdapat fitur dashboard khusus untuk admin
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/5%20(login%20admin).PNG)

6. Tampilan dashboard admin
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/6%20(dashboard%20admin).PNG)

7. Fitur export laporan, disini bisa memilih PDF atau Excel (CSV)
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/7%20(fitur%20export%20laporan).PNG)

8. File pdf dan excel yang sudah di download, isinya ini berupa isi transaksi baik itu tanggal, motor, nama, dll
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/8%20(hasil%20export).PNG)

9. Tampilan database mysql (produk motor)
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/9%20(database%20sebelum%20ditambah).PNG)

10. Mencoba menambahkan produk motor, disini bacaan berhasil
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/10%20(nambah%20motor%2C%20create).PNG)

11. Data bertambah setelah tadi diinput oleh admin, tadi diinput supra
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/11%20(create%20tadi%20ada%20di%20database).PNG)

12. Di dashboard juga berubah total produk jadi 5 yang asalnya 4 (di gambar ke 6 masih 4 sekarang 5)
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/last.PNG)

13. Fitur Edit
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/12%20(edit).PNG)

14. Edit berhasil, di database mysql berubah yang asalnya 13 juta menjadi 10 juta, deskripsi juga berubah
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/13%20(edit%20berhasil).PNG)

15. Tampilan produk yang sudah ditambahkan dan di edit
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/14%20(read).PNG)

16. Fitur delete, disini mencoba delete produk supra
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/15%20(supra%20delete).PNG)

17. di beranda sudah terhapus
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/16%20(produk%20terhapus).PNG)

18. di mysql langsung terhapus
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/17%20(di%20database%20terhapus).PNG)

19. Fitur pesan, pesan ini akan masuk ke database yang mana admin bisa mengeceknya di dashboard dan juga pesanan ini tentunya masuk ke database mysql
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/18%20(fitur%20pesan).PNG)

20. Pesanan masuk di kelola transaksi
![image alt](https://github.com/Adchrisa/UAS-Pemrograman-Web/blob/015065d3abd65f501f6121ff07a180c04886553a/Screenshots/19%20(pesanan%20masuk).PNG)


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


