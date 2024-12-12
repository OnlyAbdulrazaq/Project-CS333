<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $booking_id, $user_id);
    
    if ($stmt->execute()) {
        $success = "Booking cancelled successfully.";
    } else {
        $error = "Failed to cancel the booking.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Booking</title>
    <link rel="stylesheet" href="prj.css">
</head>
<body>
    <div class="container">
        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php elseif (isset($success)): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>
        <a href="my_bookings.php">Back to My Bookings</a>
    </div>
</body>
</html>