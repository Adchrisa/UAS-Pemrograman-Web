-- Database untuk Project UAS
-- Cigadung Motorworks - CRUD dengan Auth Cookies

-- CATATAN: Database sudah harus dibuat lewat control panel InfinityFree
-- Jangan uncomment baris CREATE DATABASE di bawah (akan error di shared hosting)
-- CREATE DATABASE IF NOT EXISTS uts_api CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE uts_api;

-- Drop tabel jika mau re-seed bersih (opsional, uncomment jika perlu)
-- DROP TABLE IF EXISTS orders;
-- DROP TABLE IF EXISTS models;
-- DROP TABLE IF EXISTS users;

-- Tabel users untuk authentication
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel models untuk CRUD Motor
CREATE TABLE IF NOT EXISTS models (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    price VARCHAR(100),
    description TEXT,
    img VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel orders untuk transaksi jual beli
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    motor_id INT NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50),
    quantity INT DEFAULT 1,
    total_price DECIMAL(15,2) NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (motor_id) REFERENCES models(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed admin user (password: admin123)
INSERT INTO users (name, username, email, password, role) VALUES 
('Administrator', 'admin', 'admin@cigadungmotorworks.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    email = VALUES(email),
    password = VALUES(password),
    role = VALUES(role);

-- Seed data motor (gunakan code unik, aman di-run ulang)
INSERT INTO models (code, title, price, description, img) VALUES
('YZF-R1M', 'Yamaha R1M', 'Rp 550.000.000', 'Superbike flagship Yamaha dengan teknologi MotoGP', 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc'),
('YZF-R6', 'Yamaha R6', 'Rp 320.000.000', 'Supersport 600cc legendaris', 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87'),
('ZX-6R', 'Kawasaki Ninja ZX-6R', 'Rp 280.000.000', 'Ninja 636cc dengan performa optimal', 'https://images.unsplash.com/photo-1609630875171-b1321377ee65')
ON DUPLICATE KEY UPDATE
    title = VALUES(title),
    price = VALUES(price),
    description = VALUES(description),
    img = VALUES(img);

-- Seed sample orders (pakai subquery supaya aman walau id berbeda)
INSERT INTO orders (motor_id, customer_name, customer_phone, quantity, total_price, status) VALUES
((SELECT id FROM models WHERE code = 'YZF-R1M'), 'Budi Santoso', '081234567890', 1, 550000000, 'completed'),
((SELECT id FROM models WHERE code = 'YZF-R6'),  'Andi Wijaya', '081234567891', 1, 320000000, 'completed'),
((SELECT id FROM models WHERE code = 'ZX-6R'),  'Doni Pratama', '081234567892', 1, 280000000, 'pending');
