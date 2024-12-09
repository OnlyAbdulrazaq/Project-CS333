<?php
include 'db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Validate the room ID
if (!isset($_GET['id'])) {
    echo "Invalid Room ID.";
    exit();
}

$room_id = $_GET['id'];

// Allow numeric or specific string formats (e.g., fallback_1)
if (!preg_match('/^(\d+|fallback_\d+)$/', $room_id)) {
    echo "Invalid Room ID format.";
    exit();
}

if (str_starts_with($room_id, 'fallback_')) {
    // Handle fallback room details
    $fallback_number = explode('_', $room_id)[1];
    $room = [
        'department' => 'Fallback Department',
        'capacity' => rand(10, 300),
        'area' => rand(50, 300) . ' m²',
        'equipment' => 'Basic Equipment',
        'image_url' => "rooms_pics/room{$fallback_number}.jpg",
    ];
} else {
    // Fetch room details from the database
    $stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();

    if (!$room) {
        echo "Room not found.";
        exit();
    }
}

// Fetch available timeslots
$timeslots_result = $conn->query("SELECT * FROM timeslots");
$timeslots = [];
if ($timeslots_result) {
    while ($row = $timeslots_result->fetch_assoc()) {
        $timeslots[] = $row;
    }
}

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $timeslot_id = $_POST['timeslot_id'];
    $date = $_POST['date'];
    $user_id = $_SESSION['user_id'];

    // Check for empty fields
    if (empty($timeslot_id) || empty($date)) {
        $error = "Please select a timeslot and a date.";
    } else {
        // Check for conflicts
        $conflict = $conn->prepare("SELECT * FROM bookings WHERE room_id = ? AND timeslot_id = ? AND date = ?");
        $conflict->bind_param("iis", $room_id, $timeslot_id, $date);
        $conflict->execute();
        $conflict_result = $conflict->get_result();

        if ($conflict_result->num_rows > 0) {
            $error = "This timeslot is already booked.";
        } else {
            // Insert booking
            $stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, timeslot_id, date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiis", $user_id, $room_id, $timeslot_id, $date);
            $stmt->execute();
            $success = "Booking successful!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>
    <link rel="stylesheet" href="prj.css">
</head>
<body>

    <main class="room-details">
        <h1>Room <?= htmlspecialchars($room_id) ?></h1>
        <p><strong>Department:</strong> <?= htmlspecialchars($room['department']) ?></p>
        <p><strong>Capacity:</strong> <?= htmlspecialchars($room['capacity']) ?> people</p>
        <p><strong>Area:</strong> <?= htmlspecialchars($room['area']) ?></p>
        <p><strong>Equipment:</strong> <?= htmlspecialchars($room['equipment']) ?></p>
        <img src="<?= htmlspecialchars($room['image_url']) ?>" alt="Room Image">

        <h2>Book This Room</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p style="color: green;"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="date">Date:</label>
            <input type="date" name="date" required>
            
            <label for="timeslot">Timeslot:</label>
            <select name="timeslot_id" required>
                <?php foreach ($timeslots as $timeslot): ?>
                    <option value="<?= htmlspecialchars($timeslot['id']) ?>">
                        <?= htmlspecialchars($timeslot['start_time'] . ' - ' . $timeslot['end_time']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Book Now</button>
        </form>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
