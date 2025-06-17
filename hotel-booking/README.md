# Nepal Hotel Booking System

A complete hotel booking system with Khalti payment integration, built for the Nepal market.

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (for PHP dependencies)
- Khalti merchant account

## Installation Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd hotel-booking
   ```

2. **Configure your web server**
   - Point your web server's document root to the `hotel-booking` directory
   - Ensure the web server has write permissions for the `uploads` and `logs` directories

3. **Configure database**
   - Open `config/config.php`
   - Update the database credentials:
     ```php
     define('DB_USER', 'your_username');
     define('DB_PASS', 'your_password');
     ```

4. **Configure Khalti**
   - Open `config/config.php`
   - Update the Khalti credentials:
     ```php
     define('KHALTI_PUBLIC_KEY', 'your_khalti_public_key');
     define('KHALTI_SECRET_KEY', 'your_khalti_secret_key');
     ```

5. **Run the setup script**
   ```bash
   php setup.php
   ```
   This will:
   - Create necessary directories
   - Create the database
   - Create all required tables
   - Create a default admin user

6. **Default Admin Credentials**
   - Username: admin
   - Password: admin123
   - Change these credentials after first login

## Directory Structure

```
hotel-booking/
├── admin-panel/         # Admin interface
├── config/             # Configuration files
├── database/           # Database files
├── includes/           # PHP includes
├── uploads/            # Uploaded files
│   ├── rooms/         # Room images
│   └── users/         # User uploads
├── logs/              # System logs
└── index.php          # Main entry point
```

## Usage

1. **Access the website**
   - Open your web browser
   - Navigate to `http://localhost/hotel-booking`

2. **Admin Panel**
   - Navigate to `http://localhost/hotel-booking/admin-panel`
   - Login with admin credentials
   - Manage rooms, bookings, and users

3. **User Features**
   - Browse available rooms
   - Make reservations
   - Pay using Khalti
   - View booking history
   - Leave reviews

## Security Considerations

1. **Change Default Credentials**
   - Change the default admin password immediately
   - Use strong passwords for all accounts

2. **File Permissions**
   - Ensure proper permissions on sensitive directories
   - Restrict access to configuration files

3. **Regular Updates**
   - Keep PHP and MySQL updated
   - Monitor security advisories

## Troubleshooting

1. **Database Connection Issues**
   - Verify database credentials in `config/config.php`
   - Ensure MySQL service is running
   - Check database user permissions

2. **Upload Issues**
   - Verify directory permissions
   - Check PHP upload limits in php.ini
   - Ensure sufficient disk space

3. **Payment Issues**
   - Verify Khalti credentials
   - Check Khalti API status
   - Verify SSL certificate

## Support

For support, please contact:
- Email: support@hotel.com
- Phone: +977-XXXXXXXXXX

## License

This project is licensed under the MIT License - see the LICENSE file for details. 