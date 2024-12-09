-- Active: 1716015696405@@127.0.0.1@3306@room_booking_system
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    room_id INT,
    timeslot_id INT,
    date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES rooms(id),
    FOREIGN KEY (timeslot_id) REFERENCES timeslots(id)
);
CREATE DATABASE room_booking_system;
