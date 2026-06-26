-- MilkWise database schema
-- This matches the schema that config/db.php creates automatically.
-- Use this file only if you want to set up the DB manually via phpMyAdmin
-- instead of letting the app create it on first run.

CREATE DATABASE IF NOT EXISTS milk_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE milk_db;

DROP TABLE IF EXISTS daily_updates, milk_plans, users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE milk_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    milk_type ENUM('cow', 'buffalo') DEFAULT 'cow',
    default_quantity DECIMAL(4,2) DEFAULT 1.00,
    UNIQUE KEY unique_plan (user_id, milk_type),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE daily_updates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    milk_type ENUM('cow', 'buffalo') DEFAULT 'cow',
    update_date DATE NOT NULL,
    quantity DECIMAL(4,2) DEFAULT NULL,
    status ENUM('skip', 'reduce', 'increase', 'normal') DEFAULT 'normal',
    notes TEXT,
    request_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_update (user_id, milk_type, update_date),
    INDEX idx_date (update_date)
) ENGINE=InnoDB;

-- NOTE: There is no plaintext-password admin INSERT here on purpose.
-- The app (config/db.php) creates the admin account automatically using
-- the email/password you set in config/secrets.php - that's the
-- recommended way, since it hashes the password correctly.
--
-- If you really want to insert an admin row manually instead, generate a
-- hash first with PHP:
--
--   php -r "echo password_hash('your-password-here', PASSWORD_DEFAULT);"
--
-- INSERT INTO users (name, email, password, role, status) VALUES
-- ('Admin', 'your-email@example.com', '<paste hash here>', 'admin', 'approved');
