<?php
/**
 * Config.php - Centralized Configuration
 * Untuk memudahkan deployment ke different servers
 */

// ===== ENVIRONMENT DETECTION =====
define('ENV', getenv('APP_ENV') ?: 'production');
define('DEBUG', ENV === 'development');

// ===== DATABASE CONFIG =====
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'uts_api');
define('DB_PORT', getenv('DB_PORT') ?: 3306);
define('DB_CHARSET', 'utf8mb4');

// ===== APPLICATION CONFIG =====
define('APP_NAME', 'Cigadung Motorworks');
define('APP_URL', getenv('APP_URL') ?: 'http://localhost/projek1');
define('APP_PATH', __DIR__);

// ===== SECURITY CONFIG =====
define('SESSION_LIFETIME', 3600); // 1 hour
define('COOKIE_LIFETIME', 86400 * 7); // 7 days
define('COOKIE_SECURE', (ENV === 'production')); // HTTPS only in production
define('COOKIE_HTTPONLY', true);
define('BCRYPT_COST', 10);

// ===== FILE UPLOAD CONFIG =====
define('UPLOAD_DIR', APP_PATH . '/images/');
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_MIME_TYPES', [
    'image/jpeg',
    'image/png',
    'image/gif',
    'image/webp'
]);

// ===== LOGGING CONFIG =====
define('LOG_DIR', APP_PATH . '/logs/');
define('LOG_FILE', LOG_DIR . 'app.log');
define('ERROR_LOG_FILE', LOG_DIR . 'error.log');

// ===== EMAIL CONFIG (Optional) =====
define('MAIL_FROM', getenv('MAIL_FROM') ?: 'noreply@cigadungmotorworks.id');
define('MAIL_FROM_NAME', APP_NAME);

// ===== API CONFIG =====
define('API_RATE_LIMIT', getenv('API_RATE_LIMIT') ?: 100); // requests per hour
define('API_TIMEOUT', 30); // seconds

// ===== TIMEZONE =====
date_default_timezone_set(getenv('TIMEZONE') ?: 'Asia/Jakarta');

// ===== ERROR HANDLING =====
if (!DEBUG) {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
} else {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

// ===== CREATE REQUIRED DIRECTORIES =====
if (!is_dir(LOG_DIR)) {
    mkdir(LOG_DIR, 0755, true);
}

if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

// ===== HELPER FUNCTIONS =====
function config($key, $default = null) {
    $key = strtoupper($key);
    return defined($key) ? constant($key) : $default;
}

function isProduction() {
    return ENV === 'production';
}

function isDevelopment() {
    return ENV === 'development';
}

function isDebugEnabled() {
    return DEBUG;
}
?>
