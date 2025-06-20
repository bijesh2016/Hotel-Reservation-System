<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'hotel_reservation');
define('DB_USER', 'root');  // Change this to your database username
define('DB_PASS', '');      // Change this to your database password

// Application Configuration
define('SITE_NAME', 'Nepal Hotel Booking');
define('SITE_URL', 'http://localhost/hotel-booking');
define('CURRENCY', 'NPR');
define('TIMEZONE', 'Asia/Kathmandu');

// Khalti Configuration
define('KHALTI_PUBLIC_KEY', 'YOUR_KHALTI_PUBLIC_KEY');
define('KHALTI_SECRET_KEY', 'YOUR_KHALTI_SECRET_KEY');

// File Upload Configuration
define('UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'] . '/hotel-booking/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif']);

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));

// Error Reporting
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Set timezone
date_default_timezone_set(TIMEZONE);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?> 