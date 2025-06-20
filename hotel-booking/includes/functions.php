<?php
require_once __DIR__ . '/../database/db_connect.php';

// User related functions
function registerUser($userData) {
    $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
    return insert('users', $userData);
}

function loginUser($email, $password) {
    $sql = "SELECT * FROM users WHERE email = ?";
    $user = fetchOne($sql, [$email]);
    
    if ($user && password_verify($password, $user['password'])) {
        unset($user['password']);
        $_SESSION['user'] = $user;
        return true;
    }
    return false;
}

// Room related functions
function getAvailableRooms($checkIn, $checkOut) {
    $sql = "SELECT r.*, rc.category_name, rc.base_price, rc.description 
            FROM rooms r 
            JOIN room_categories rc ON r.category_id = rc.category_id 
            WHERE r.status = 'available' 
            AND r.room_id NOT IN (
                SELECT room_id FROM bookings 
                WHERE (check_in_date <= ? AND check_out_date >= ?) 
                OR (check_in_date <= ? AND check_out_date >= ?)
                OR (check_in_date >= ? AND check_out_date <= ?)
            )";
    
    return fetchAll($sql, [$checkOut, $checkIn, $checkIn, $checkIn, $checkIn, $checkOut]);
}

function getRoomDetails($roomId) {
    $sql = "SELECT r.*, rc.category_name, rc.base_price, rc.description, rc.capacity,
            GROUP_CONCAT(ra.amenity_name) as amenities
            FROM rooms r 
            JOIN room_categories rc ON r.category_id = rc.category_id 
            LEFT JOIN room_amenity_relation rar ON r.room_id = rar.room_id
            LEFT JOIN room_amenities ra ON rar.amenity_id = ra.amenity_id
            WHERE r.room_id = ?
            GROUP BY r.room_id";
    
    return fetchOne($sql, [$roomId]);
}

// Booking related functions
function createBooking($bookingData) {
    $bookingId = insert('bookings', $bookingData);
    if ($bookingId) {
        update('rooms', ['status' => 'occupied'], 'room_id = ?', [$bookingData['room_id']]);
    }
    return $bookingId;
}

function getBookingDetails($bookingId) {
    $sql = "SELECT b.*, u.full_name, u.email, u.phone_number,
            r.room_number, rc.category_name, rc.base_price
            FROM bookings b
            JOIN users u ON b.user_id = u.user_id
            JOIN rooms r ON b.room_id = r.room_id
            JOIN room_categories rc ON r.category_id = rc.category_id
            WHERE b.booking_id = ?";
    
    return fetchOne($sql, [$bookingId]);
}

// Payment related functions
function createPayment($paymentData) {
    return insert('payments', $paymentData);
}

function updatePaymentStatus($paymentId, $status, $transactionId = null) {
    $data = ['payment_status' => $status];
    if ($transactionId) {
        $data['transaction_id'] = $transactionId;
    }
    return update('payments', $data, 'payment_id = ?', [$paymentId]);
}

// Review related functions
function addReview($reviewData) {
    return insert('reviews', $reviewData);
}

function getRoomReviews($roomId) {
    $sql = "SELECT r.*, u.full_name
            FROM reviews r
            JOIN users u ON r.user_id = u.user_id
            JOIN bookings b ON r.booking_id = b.booking_id
            WHERE b.room_id = ?
            ORDER BY r.created_at DESC";
    
    return fetchAll($sql, [$roomId]);
}

// Admin related functions
function adminLogin($username, $password) {
    $sql = "SELECT * FROM admin_users WHERE username = ?";
    $admin = fetchOne($sql, [$username]);
    
    if ($admin && password_verify($password, $admin['password'])) {
        unset($admin['password']);
        $_SESSION['admin'] = $admin;
        return true;
    }
    return false;
}

function getBookingStats() {
    $sql = "SELECT 
            COUNT(*) as total_bookings,
            SUM(CASE WHEN booking_status = 'pending' THEN 1 ELSE 0 END) as pending_bookings,
            SUM(CASE WHEN booking_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
            SUM(CASE WHEN booking_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_bookings,
            SUM(CASE WHEN booking_status = 'completed' THEN 1 ELSE 0 END) as completed_bookings
            FROM bookings";
    
    return fetchOne($sql);
}

// Utility functions
function formatPrice($price) {
    return CURRENCY . ' ' . number_format($price, 2);
}

function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

function calculateTotalPrice($basePrice, $checkIn, $checkOut) {
    $start = new DateTime($checkIn);
    $end = new DateTime($checkOut);
    $interval = $start->diff($end);
    return $basePrice * $interval->days;
}
?> 