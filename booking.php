<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = $_POST['room_id'];
    $timeslot_id = $_POST['timeslot_id'];
    $date = $_POST['date'];
    $user_id = $_SESSION['user_id'];

    // Validate inputs
    if (empty($room_id) || empty($timeslot_id) || empty($date)) {
        $error = "Please fill in all fields.";
    } else {
        // Check for booking conflicts
        $stmt = $conn->prepare("SELECT * FROM bookings WHERE room_id = ? AND timeslot_id = ? AND date = ?");
        $stmt->bind_param("iis", $room_id, $timeslot_id, $date);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "This timeslot is already booked.";
        } else {
            // Insert booking
            $stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, timeslot_id, date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiis", $user_id, $room_id, $timeslot_id, $date);
            if ($stmt->execute()) {
                $success = "Booking successful!";
            } else {
                $error = "Failed to book the room.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="prj.css">
</head>
<body>
    <div class="container">
        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php elseif (isset($success)): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>
        <a href="prj.php">Back to Room List</a>
    </div>
</body>
</html>