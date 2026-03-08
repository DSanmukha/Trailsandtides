-- =============================================
-- Trails & Tides - Database Schema
-- =============================================

CREATE DATABASE IF NOT EXISTS trailsandtides;
USE trailsandtides;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    role ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add role column if it doesn't exist (for existing DBs)
ALTER TABLE users ADD COLUMN IF NOT EXISTS role ENUM('user','admin') DEFAULT 'user';

-- Tours Table
CREATE TABLE IF NOT EXISTS tours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    destination VARCHAR(100),
    duration INT DEFAULT 5,
    price DECIMAL(12,2),
    original_price DECIMAL(12,2),
    image_url VARCHAR(500),
    rating DECIMAL(2,1) DEFAULT 4.5,
    reviews INT DEFAULT 0,
    tag VARCHAR(50),
    group_type VARCHAR(50),
    inclusions TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Destinations Table
CREATE TABLE IF NOT EXISTS destinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    country VARCHAR(100),
    region VARCHAR(50),
    description TEXT,
    weather VARCHAR(20),
    temperature INT,
    rating DECIMAL(2,1),
    tour_count INT DEFAULT 0,
    image_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Hotels Table
CREATE TABLE IF NOT EXISTS hotels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    destination VARCHAR(100),
    description TEXT,
    price_per_night DECIMAL(12,2),
    original_price DECIMAL(12,2),
    rating DECIMAL(2,1) DEFAULT 4.5,
    image_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings Table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tour_id INT DEFAULT NULL,
    hotel_name VARCHAR(200),
    check_in DATE,
    check_out DATE DEFAULT NULL,
    guests INT DEFAULT 1,
    total_price DECIMAL(12,2),
    notes TEXT DEFAULT NULL,
    status ENUM('pending','confirmed','cancelled','completed') DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Journal Entries Table
CREATE TABLE IF NOT EXISTS journal_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) DEFAULT 'Untitled',
    content TEXT NOT NULL,
    mood VARCHAR(30) DEFAULT 'happy',
    location VARCHAR(200) DEFAULT NULL,
    image_url VARCHAR(500) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- =============================================
-- SAMPLE DATA
-- =============================================

-- Sample Users (password: 'password')
INSERT IGNORE INTO users (name, email, password, role) VALUES
('Admin', 'admin@trailsandtides.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('Priya Sharma', 'priya@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- Sample Tours (INR)
INSERT IGNORE INTO tours (title, destination, duration, price, original_price, rating, reviews, tag, group_type) VALUES
('Bali Paradise Experience', 'Bali, Indonesia', 7, 149999, 199999, 4.8, 284, 'POPULAR', 'Group Tours'),
('Swiss Alps Adventure', 'Switzerland', 5, 219999, 259999, 4.9, 156, 'LUXURY', 'Small Groups'),
('Tokyo Lights & Culture', 'Tokyo, Japan', 4, 119999, 149999, 4.7, 342, 'FAMILY', 'All Ages'),
('Maldives Romantic Getaway', 'Maldives', 6, 299999, 349999, 5.0, 89, 'HONEYMOON', 'Couples'),
('Iceland Explorer', 'Iceland', 8, 239999, 289999, 4.6, 201, 'ADVENTURE', 'Adventure Seekers'),
('Egypt Historical Journey', 'Egypt', 9, 189999, 229999, 4.8, 127, 'CULTURAL', 'History Enthusiasts');

-- Sample Destinations
INSERT IGNORE INTO destinations (name, country, region, description, weather, temperature, rating, tour_count, image_url) VALUES
('Bali', 'Indonesia', 'Southeast Asia', 'Tropical beaches, ancient temples, rice terraces, and vibrant nightlife', 'Sunny', 28, 4.8, 12, 'assets/images/destinations/bali.jpg'),
('Paris', 'France', 'Europe', 'City of love — iconic landmarks, world-class museums, and exquisite cuisine', 'Cloudy', 18, 4.9, 8, 'assets/images/destinations/paris.jpg'),
('Tokyo', 'Japan', 'East Asia', 'Neon-lit streets, ancient temples, and the finest cuisine in the world', 'Clear', 20, 4.7, 6, 'assets/images/destinations/tokyo.jpg'),
('Maldives', 'Maldives', 'South Asia', 'Crystal clear waters, overwater bungalows, and pristine coral reefs', 'Sunny', 29, 5.0, 4, 'assets/images/destinations/maldives.jpg'),
('Swiss Alps', 'Switzerland', 'Europe', 'Majestic peaks, alpine meadows, chocolate, and world-class skiing', 'Snowy', 5, 4.8, 5, 'assets/images/destinations/swiss-alps.jpg'),
('New York', 'USA', 'North America', 'The city that never sleeps — Broadway, Central Park, and iconic skyline', 'Clear', 22, 4.6, 7, 'assets/images/destinations/newyork.jpg'),
('Egypt', 'Egypt', 'Africa', 'Ancient pyramids, pharaohs tombs, the Nile, and desert adventures', 'Hot', 35, 4.5, 3, 'assets/images/destinations/bali.jpg'),
('Costa Rica', 'Costa Rica', 'Central America', 'Rainforests, volcanoes, wildlife, and pristine Pacific beaches', 'Tropical', 27, 4.7, 4, 'assets/images/destinations/beach.jpg');
