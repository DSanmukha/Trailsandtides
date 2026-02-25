-- Run this in phpMyAdmin to add hotels table and ensure everything is set up

USE trailsandtides;

-- Hotels Table
CREATE TABLE IF NOT EXISTS hotels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    destination VARCHAR(100),
    description TEXT,
    price_per_night DECIMAL(10,2),
    image_url VARCHAR(500),
    rating DECIMAL(2,1) DEFAULT 4.5,
    amenities TEXT,
    stars INT DEFAULT 4,
    active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add active flag to tours and destinations
ALTER TABLE tours ADD COLUMN IF NOT EXISTS active TINYINT(1) DEFAULT 1;
ALTER TABLE destinations ADD COLUMN IF NOT EXISTS active TINYINT(1) DEFAULT 1;

-- Ensure role column exists on users
ALTER TABLE users ADD COLUMN IF NOT EXISTS role ENUM('user','admin') DEFAULT 'user';

-- Sample Hotels (INR pricing)
INSERT INTO hotels (name, destination, description, price_per_night, image_url, rating, amenities, stars) VALUES
('The Bali Beach Resort', 'Bali, Indonesia', 'Luxury beachfront resort with infinity pool overlooking the Indian Ocean', 12999, 'https://images.unsplash.com/photo-1537225228614-b3fb3d625cb0?w=600', 4.8, 'Pool,Spa,WiFi,Restaurant,Bar,Gym', 5),
('Le Grand Paris Hotel', 'Paris, France', 'Elegant Haussmann-style hotel steps from the Eiffel Tower with rooftop views', 18999, 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?w=600', 4.9, 'WiFi,Restaurant,Concierge,Rooftop Bar,Spa', 5),
('Tokyo Sky Hotel', 'Tokyo, Japan', 'Modern high-rise hotel in Shibuya with panoramic city views and Japanese hospitality', 9999, 'https://images.unsplash.com/photo-1540959375944-7049f642e9a0?w=600', 4.7, 'WiFi,Gym,Restaurant,Onsen,Business Center', 4);

-- Make sure the admin user exists
INSERT IGNORE INTO users (name, email, password, role) VALUES
('Admin', 'admin@trailsandtides.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Update existing admin by email
UPDATE users SET role='admin' WHERE email='admin@trailsandtides.com';
