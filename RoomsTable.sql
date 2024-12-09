-- Active: 1716015696405@@127.0.0.1@3306@room_booking_system
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    department VARCHAR(50),
    capacity INT,
    size VARCHAR(50),
    equipment TEXT,
    image_url VARCHAR(255)
);
