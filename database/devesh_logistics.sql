-- Database setup for Devesh Logistics
-- Create database
CREATE DATABASE IF NOT EXISTS devesh_logistics;
USE devesh_logistics;

-- Table for booking details
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pickup_address VARCHAR(255) NOT NULL,
    drop_address VARCHAR(255) NOT NULL,
    pickup_date DATE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    vehicle_type VARCHAR(50) NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    booking_status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for contact messages
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create indexes for better performance
CREATE INDEX idx_bookings_email ON bookings(email);
CREATE INDEX idx_bookings_phone ON bookings(phone);
CREATE INDEX idx_bookings_date ON bookings(pickup_date);
CREATE INDEX idx_contact_email ON contact_messages(email);
CREATE INDEX idx_contact_status ON contact_messages(status);

-- Insert sample data (optional)
INSERT INTO bookings (pickup_address, drop_address, pickup_date, phone, vehicle_type, fullname, email) VALUES
('Mumbai, Maharashtra', 'Delhi, India', '2025-10-15', '919876543210', 'mini_truck', 'John Doe', 'john@example.com'),
('Bangalore, Karnataka', 'Chennai, Tamil Nadu', '2025-10-20', '918765432109', 'large_truck', 'Jane Smith', 'jane@example.com');

INSERT INTO contact_messages (name, email, subject, message) VALUES
('Rajesh Kumar', 'rajesh@example.com', 'Pricing Inquiry', 'I need a quote for regular shipments from Mumbai to Pune'),
('Priya Sharma', 'priya@example.com', 'Service Feedback', 'Excellent service! Very professional team.');