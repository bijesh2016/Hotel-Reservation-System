-- Create the database
CREATE DATABASE IF NOT EXISTS hotel_reservation;
USE hotel_reservation;

-- Users table
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    id_type ENUM('citizenship', 'passport', 'driving_license') NOT NULL,
    id_number VARCHAR(50) NOT NULL,
    address TEXT,
    city VARCHAR(50),
    country VARCHAR(50) DEFAULT 'Nepal',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Room categories table
CREATE TABLE room_categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(50) NOT NULL,
    description TEXT,
    base_price DECIMAL(10,2) NOT NULL,
    capacity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Rooms table
CREATE TABLE rooms (
    room_id INT PRIMARY KEY AUTO_INCREMENT,
    room_number VARCHAR(10) UNIQUE NOT NULL,
    category_id INT,
    floor_number INT NOT NULL,
    status ENUM('available', 'occupied', 'maintenance') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES room_categories(category_id)
);

-- Room amenities table
CREATE TABLE room_amenities (
    amenity_id INT PRIMARY KEY AUTO_INCREMENT,
    amenity_name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Room-amenity relationship table
CREATE TABLE room_amenity_relation (
    room_id INT,
    amenity_id INT,
    PRIMARY KEY (room_id, amenity_id),
    FOREIGN KEY (room_id) REFERENCES rooms(room_id),
    FOREIGN KEY (amenity_id) REFERENCES room_amenities(amenity_id)
);

-- Bookings table
CREATE TABLE bookings (
    booking_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    room_id INT,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    total_guests INT NOT NULL,
    booking_status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    total_amount DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (room_id) REFERENCES rooms(room_id)
);

-- Payments table
CREATE TABLE payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('khalti', 'cash', 'bank_transfer') NOT NULL,
    transaction_id VARCHAR(100),
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id)
);

-- Reviews table
CREATE TABLE reviews (
    review_id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT,
    user_id INT,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Room images table
CREATE TABLE room_images (
    image_id INT PRIMARY KEY AUTO_INCREMENT,
    room_id INT,
    image_url VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES rooms(room_id)
);

-- Admin users table
CREATE TABLE admin_users (
    admin_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('super_admin', 'manager', 'staff') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user
INSERT INTO admin_users (username, password, full_name, email, role)
VALUES ('admin', 'root', 'System Admin', 'admin@hotel.com', 'super_admin');

-- Insert some default room categories
INSERT INTO room_categories (category_name, description, base_price, capacity) VALUES
('Standard Room', 'Comfortable room with basic amenities', 3000.00, 2),
('Deluxe Room', 'Spacious room with premium amenities', 5000.00, 2),
('Suite', 'Luxury suite with separate living area', 8000.00, 4),
('Family Room', 'Large room perfect for families', 6000.00, 4);

-- Insert some default amenities
INSERT INTO room_amenities (amenity_name, description) VALUES
('Wi-Fi', 'Free high-speed internet access'),
('Air Conditioning', 'Climate control system'),
('TV', 'Flat-screen television with cable channels'),
('Mini Bar', 'Refrigerator with drinks and snacks'),
('Room Service', '24/7 room service available'),
('Safe', 'In-room safe for valuables'),
('Balcony', 'Private balcony with view'),
('Bathroom', 'Private bathroom with shower');

-- Create indexes for better performance
CREATE INDEX idx_room_status ON rooms(status);
CREATE INDEX idx_booking_dates ON bookings(check_in_date, check_out_date);
CREATE INDEX idx_payment_status ON payments(payment_status);
CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_booking_status ON bookings(booking_status); 