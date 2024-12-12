<?php
session_start();
include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: prj.php");
    exit();
}

$room_id = $_GET['id'];

// Fetch room details
$stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();

if (!$room) {
    header("Location: prj.php");
    exit();
}

// Fetch available timeslots
$timeslots = $conn->query("SELECT * FROM timeslots ORDER BY start_time")->fetch_all(MYSQLI_ASSOC);

// Fetch existing bookings for this room
$stmt = $conn->prepare("SELECT date, timeslot_id FROM bookings WHERE room_id = ? AND date >= CURDATE()");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$bookings_result = $stmt->get_result();
$bookings = [];
while ($booking = $bookings_result->fetch_assoc()) {
    $bookings[$booking['date']][] = $booking['timeslot_id'];
}

// Get the current date
$currentDate = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details - <?= htmlspecialchars($room['name']) ?></title>
    <link rel="stylesheet" href="prj.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><?= htmlspecialchars($room['name']) ?></h1>
            <a href="prj.php" class="back-button">Back to Rooms</a>
        </header>

        <div class="room-details">
    <?php if (!empty($room['image_url'])): ?>
        <img src="rooms_pics/room<?= htmlspecialchars($room['image_url']) ?>" 
             alt="<?= htmlspecialchars($room['name']) ?>" 
             onerror="this.onerror=null; this.src='rooms_pics/room'; console.log('Error loading image: ' + this.src);">
        <p>Image URL: rooms_pics/<?= htmlspecialchars($room['image_url']) ?></p>
    <?php else: ?>
        <img src="rooms_pics/room" alt="No image available">
        <p>No image URL provided</p>
    <?php endif; ?>
            <p><strong>Department:</strong> <?= htmlspecialchars($room['department']) ?></p>
            <p><strong>Capacity:</strong> <?= htmlspecialchars($room['capacity']) ?> People</p>
            <p><strong>Size:</strong> <?= htmlspecialchars($room['size']) ?></p>
            <p><strong>Equipment:</strong> <?= htmlspecialchars($room['equipment']) ?></p>
        </div>

        <section class="booking-section">
            <h2>Book this room</h2>
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="booking.php" method="post" id="bookingForm">
                    <input type="hidden" name="room_id" value="<?= $room_id ?>">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" required min="<?= $currentDate ?>">
                    <label for="timeslot">Time Slot:</label>
                    <select id="timeslot" name="timeslot_id" required>
                        <option value="">Select a time slot</option>
                        <?php foreach ($timeslots as $timeslot): ?>
                            <option value="<?= $timeslot['id'] ?>"><?= htmlspecialchars($timeslot['start_time']) ?> - <?= htmlspecialchars($timeslot['end_time']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Book Now</button>
                </form>
            <?php else: ?>
                <p>Please <a href="login.php">log in</a> to book this room.</p>
            <?php endif; ?>
        </section>
    </div>

    <script>
        const bookings = <?= json_encode($bookings) ?>;

        // Update available timeslots based on selected date
        document.getElementById('date').addEventListener('change', function() {
            const selectedDate = this.value;
            const timeslotSelect = document.getElementById('timeslot');
            const bookedTimeslots = bookings[selectedDate] || [];

            Array.from(timeslotSelect.options).forEach(option => {
                if (option.value !== "") { 
                    option.disabled = bookedTimeslots.includes(option.value);
                }
            });
        });

        // Form validation
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            const date = document.getElementById('date').value;
            const timeslot = document.getElementById('timeslot').value;
            if (!date || !timeslot) {
                e.preventDefault();
                alert('Please select both a date and a time slot.');
            }
        });
    </script>
</body>
</html>