-- Active: 1716015696405@@127.0.0.1@3306@room_booking_system
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    room_id INT,
    timeslot_id INT,
    date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES rooms(id),
    FOREIGN KEY (timeslot_id) REFERENCES timeslots(id),
    Foreign Key (user_id) REFERENCES users(id)
);

CREATE DATABASE room_booking_system;

-- Create the database
CREATE DATABASE IF NOT EXISTS room_booking_system;
USE room_booking_system;

-- Bookings table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    timeslot_id INT NOT NULL,
    date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id),
    FOREIGN KEY (timeslot_id) REFERENCES timeslots(id)
);

-- rooms
INSERT INTO rooms (name, department, capacity, size, equipment, image_url) VALUES
('Room 101', 'CS', 300, '270 sq m', 'Projector, Whiteboard , sound system , Quality Chairs', '/rooms_pics/room1.jpg'),
('Room 102', 'NE', 30, '74 sq m', 'WhiteBoard,chairs, TV', '/rooms_pics/room2.jpg'),
('Room 103', 'CS', 40, '110 sq m', 'Projector, Conference Phone', '/rooms_pics/room3.jpg'),
('Room 104', 'IS', 30, '74 sq m', 'SmartBoard, Projector ,Table, chairs', '/rooms_pics/room4.jpg'),
('Room 105', 'CS', 340, '290 sq m', 'Projector, Whiteboard , sound system , Quality Chairs, Conference Phone', '/rooms_pics/room5.jpg'),
('Room 106', 'NE', 30, '74 sq m', 'Projector, SmartBoard , computers', '/rooms_pics/room6.jpg'),
('Room 107', 'IS', 30, '74 sq m', 'chairtables, BlackBoard', '/rooms_pics/room7.jpg'),
('Room 201', 'CS', 40, '80 sq m', 'Projector, WhiteBoard , SmartBoard', '/rooms_pics/room8.jpg'),
('Room 202', 'NE', 30, '74 sq m', 'Projector, Conference Phone , SmartBoard , WhiteBoard', '/rooms_pics/room9.jpg'),
('Room 203', 'IS', 200, '205 sq m', 'Projector, Whiteboard , sound system , Quality Chairs, Conference Phone', '/rooms_pics/room10.jpg'),
('Room 204', 'CS', 300, '270 sq m', 'Projector, Whiteboard , sound system , Quality Chairs, Conference Phone', '/rooms_pics/room11.jpg'),
('Room 205', 'NE', 40, '80 sq m', 'Projector, BlackBoard', '/rooms_pics/room12.jpg'),
('Room 206', 'CS', 340, '290 sq m', 'Projector, Whiteboard , sound system , Quality Chairs, Conference Phone', '/rooms_pics/room13.jpg'),
('Room 207', 'CS', 30, '74 sq m', 'Projector, Tables , Chairs', '/rooms_pics/room14.jpg'),
('Room 301', 'IS', 20, '65 sq m', 'Chairs, Rounded Tables', '/rooms_pics/room15.jpg'),
('Room 302', 'CS', 100, '150 sq m', 'Projector, WhiteBoard', '/rooms_pics/room16.jpg'),
('Room 303', 'IS', 120, '170 sq m', 'Projector, Whiteboard , sound system , Quality Chairs, Conference Phone', '/rooms_pics/room17.jpg'),
('Room 304', 'CS', 25, '70 sq m', 'Projector, WhiteBoard , SmartBoard, Tbales , Chairs', '/rooms_pics/room18.jpg'),
('Room 305', 'NE', 25, '70 sq m', 'Projector, TVs , Round Tables , Chairs', '/rooms_pics/room19.jpg');