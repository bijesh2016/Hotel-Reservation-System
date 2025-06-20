<?php
// Display all errors during setup
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting setup process...\n";

// Create necessary directories
$directories = [
    'uploads',
    'uploads/rooms',
    'uploads/users',
    'logs'
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        if (mkdir($dir, 0777, true)) {
            echo "Created directory: $dir\n";
        } else {
            echo "Failed to create directory: $dir\n";
        }
    }
}

// Database setup
try {
    // Read the SQL file
    $sql = file_get_contents('database/hotel_db.sql');
    
    // Create connection without database
    $pdo = new PDO("mysql:host=localhost", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS hotel_reservation");
    echo "Database created successfully\n";
    
    // Select the database
    $pdo->exec("USE hotel_reservation");
    
    // Execute the SQL
    $pdo->exec($sql);
    echo "Tables created successfully\n";
    
    // Create default admin user
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO admin_users (username, password, full_name, email, role) 
                          VALUES ('admin', ?, 'System Admin', 'admin@hotel.com', 'super_admin')");
    $stmt->execute([$adminPassword]);
    echo "Default admin user created\n";
    echo "Username: admin\n";
    echo "Password: admin123\n";
    
    echo "Setup completed successfully!\n";
    
} catch(PDOException $e) {
    die("Setup failed: " . $e->getMessage() . "\n");
}
?> 