<?php
// Start session
session_start();

// Include configuration and database connection
require_once 'config/config.php';
require_once 'database/db_connect.php';
require_once 'includes/functions.php';

// Initialize database connection
$db = Database::getInstance();

// Get featured rooms
$featuredRooms = $db->fetchAll("SELECT * FROM rooms WHERE is_featured = 1 LIMIT 6");

// Get latest reviews
$latestReviews = $db->fetchAll("
    SELECT r.*, u.name as user_name, rm.room_number 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    JOIN rooms rm ON r.room_id = rm.id 
    ORDER BY r.created_at DESC 
    LIMIT 3
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nepal Hotel Booking - Your Home Away From Home</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="nav">
                <div class="logo">
                    <a href="index.php">
                        <img src="assets/images/logo.png" alt="Hotel Logo">
                    </a>
                </div>
                <ul class="nav-links">
                    <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="rooms.php">Rooms</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="profile.php">My Profile</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php" class="btn-primary">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Welcome to Your Perfect Stay in Nepal</h1>
                <p>Experience luxury and comfort in the heart of Nepal</p>
                <a href="rooms.php" class="btn-primary">Book Now</a>
            </div>
        </div>
    </section>

    <!-- Featured Rooms -->
    <section class="featured-rooms">
        <div class="container">
            <h2>Featured Rooms</h2>
            <div class="room-grid">
                <?php foreach ($featuredRooms as $room): ?>
                    <div class="room-card">
                        <img src="<?php echo htmlspecialchars($room['image_url']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?>">
                        <div class="room-info">
                            <h3><?php echo htmlspecialchars($room['name']); ?></h3>
                            <p class="price"><?php echo formatPrice($room['price_per_night']); ?> per night</p>
                            <p class="description"><?php echo htmlspecialchars($room['description']); ?></p>
                            <a href="room-single.php?id=<?php echo $room['id']; ?>" class="btn-secondary">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Latest Reviews -->
    <section class="reviews">
        <div class="container">
            <h2>What Our Guests Say</h2>
            <div class="review-grid">
                <?php foreach ($latestReviews as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="user-info">
                                <h4><?php echo htmlspecialchars($review['user_name']); ?></h4>
                                <p>Room <?php echo htmlspecialchars($review['room_number']); ?></p>
                            </div>
                            <div class="rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'active' : ''; ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p class="review-text"><?php echo htmlspecialchars($review['comment']); ?></p>
                        <p class="review-date"><?php echo date('F j, Y', strtotime($review['created_at'])); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Contact Us</h3>
                    <p><i class="fas fa-map-marker-alt"></i> Kathmandu, Nepal</p>
                    <p><i class="fas fa-phone"></i> +977-XXXXXXXXXX</p>
                    <p><i class="fas fa-envelope"></i> info@hotel.com</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="rooms.php">Rooms</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="privacy.php">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Follow Us</h3>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Nepal Hotel Booking. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html> 