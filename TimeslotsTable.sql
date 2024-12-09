CREATE TABLE timeslots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT,
    start_time TIME,
    end_time TIME,
    is_available BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);
