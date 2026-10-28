-- ============================================
-- College Girls Hostel Management System
-- Database Setup SQL File
-- Run this in phpMyAdmin or MySQL terminal
-- ============================================

-- Step 1: Create the database
CREATE DATABASE IF NOT EXISTS hostel_db;

-- Step 2: Select the database
USE hostel_db;

-- ============================================
-- Table 1: Students
-- ============================================
CREATE TABLE IF NOT EXISTS students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    gender VARCHAR(10) DEFAULT 'Female',
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- Table 2: Rooms
-- ============================================
CREATE TABLE IF NOT EXISTS rooms (
    room_id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(20) NOT NULL UNIQUE,
    room_type ENUM('Single', 'Double', 'Triple') NOT NULL,
    capacity INT NOT NULL,
    occupied INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- Table 3: Room Allocations
-- ============================================
CREATE TABLE IF NOT EXISTS allocations (
    allocation_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE DEFAULT NULL,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(room_id) ON DELETE CASCADE
);

-- ============================================
-- Table 4: Fees
-- ============================================
CREATE TABLE IF NOT EXISTS fees (
    fee_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    status ENUM('Paid', 'Pending') DEFAULT 'Pending',
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- ============================================
-- Table 5: Complaints
-- ============================================
CREATE TABLE IF NOT EXISTS complaints (
    complaint_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    complaint TEXT NOT NULL,
    status ENUM('Pending', 'Resolved') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- ============================================
-- Table 6: Admin Login
-- ============================================
CREATE TABLE IF NOT EXISTS admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- ============================================
-- Default Admin Account
-- Username: admin | Password: admin123
-- ============================================
INSERT INTO admin (username, password)
VALUES ('admin', MD5('admin123'));

-- ============================================
-- Sample Data (Optional - for testing)
-- ============================================
INSERT INTO students (name, gender, phone, email, address) VALUES
('Priya Sharma', 'Female', '9876543210', 'priya@example.com', 'Mumbai, Maharashtra'),
('Anjali Singh', 'Female', '9123456789', 'anjali@example.com', 'Delhi, India'),
('Kavita Patel', 'Female', '9988776655', 'kavita@example.com', 'Ahmedabad, Gujarat');

INSERT INTO rooms (room_number, room_type, capacity, occupied) VALUES
('R101', 'Single', 1, 0),
('R102', 'Double', 2, 0),
('R103', 'Triple', 3, 0),
('R104', 'Double', 2, 0);

INSERT INTO fees (student_id, amount, payment_date, status) VALUES
(1, 5000.00, '2025-01-01', 'Paid'),
(2, 5000.00, '2025-01-01', 'Pending'),
(3, 5000.00, '2025-01-01', 'Pending');
