// Mobile Navigation Toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navLinks = document.querySelector('.nav-links');

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            navLinks.classList.toggle('active');
        });
    }

    // Date Picker Initialization
    const dateInputs = document.querySelectorAll('.date-picker');
    if (dateInputs.length > 0) {
        dateInputs.forEach(input => {
            input.addEventListener('change', function() {
                validateDates();
            });
        });
    }

    // Form Validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(form)) {
                e.preventDefault();
            }
        });
    });
});

// Date Validation
function validateDates() {
    const checkIn = document.querySelector('#check-in');
    const checkOut = document.querySelector('#check-out');
    
    if (checkIn && checkOut) {
        const checkInDate = new Date(checkIn.value);
        const checkOutDate = new Date(checkOut.value);
        const today = new Date();
        
        // Reset time part for accurate comparison
        today.setHours(0, 0, 0, 0);
        
        if (checkInDate < today) {
            alert('Check-in date cannot be in the past');
            checkIn.value = '';
            return false;
        }
        
        if (checkOutDate <= checkInDate) {
            alert('Check-out date must be after check-in date');
            checkOut.value = '';
            return false;
        }
        
        return true;
    }
}

// Form Validation
function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('error');
            isValid = false;
        } else {
            field.classList.remove('error');
        }
    });
    
    // Email validation
    const emailField = form.querySelector('input[type="email"]');
    if (emailField && emailField.value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailField.value)) {
            emailField.classList.add('error');
            isValid = false;
        }
    }
    
    // Password validation
    const passwordField = form.querySelector('input[type="password"]');
    if (passwordField && passwordField.value) {
        if (passwordField.value.length < 6) {
            passwordField.classList.add('error');
            isValid = false;
        }
    }
    
    return isValid;
}

// Room Search
function searchRooms() {
    const checkIn = document.querySelector('#check-in').value;
    const checkOut = document.querySelector('#check-out').value;
    const guests = document.querySelector('#guests').value;
    
    if (validateDates()) {
        window.location.href = `rooms.php?check-in=${checkIn}&check-out=${checkOut}&guests=${guests}`;
    }
}

// Khalti Payment Integration
function initiateKhaltiPayment(amount, bookingId) {
    const config = {
        publicKey: KHALTI_PUBLIC_KEY,
        productIdentity: bookingId,
        productName: "Hotel Booking",
        productUrl: window.location.href,
        eventHandler: {
            onSuccess(payload) {
                // Handle successful payment
                verifyPayment(payload, bookingId);
            },
            onError(error) {
                // Handle error
                console.error('Payment failed:', error);
                alert('Payment failed. Please try again.');
            },
            onClose() {
                // Handle payment window close
                console.log('Payment window closed');
            }
        }
    };

    const checkout = new KhaltiCheckout(config);
    checkout.show({ amount: amount * 100 }); // Convert to paisa
}

// Verify Payment
function verifyPayment(payload, bookingId) {
    fetch('verify-payment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            token: payload.token,
            amount: payload.amount,
            booking_id: bookingId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = `booking-confirmation.php?id=${bookingId}`;
        } else {
            alert('Payment verification failed. Please contact support.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

// Image Preview
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.querySelector('#image-preview');
            if (preview) {
                preview.src = e.target.result;
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Rating System
function setRating(rating) {
    const stars = document.querySelectorAll('.rating-star');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.add('active');
        } else {
            star.classList.remove('active');
        }
    });
    document.querySelector('#rating-value').value = rating;
}

// Notification System
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
} 