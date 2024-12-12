CREATE TABLE timeslots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT,
    start_time TIME,
    end_time TIME,
    is_available BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);
INSERT INTO timeslots (start_time, end_time) VALUES 
('09:00:00', '10:00:00'),
('10:00:00', '11:00:00'),
('11:00:00', '12:00:00'),
('13:00:00', '14:00:00'),
('15:00:00', '16:00:00'),
('16:30:00', '20:00:00');
