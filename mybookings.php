<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT b.id, r.name AS room_name, t.start_time, t.end_time, b.date 
                        FROM bookings b 
                        JOIN rooms r ON b.room_id = r.id 
                        JOIN timeslots t ON b.timeslot_id = t.id 
                        WHERE b.user_id = ? 
                        ORDER BY b.date, t.start_time");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet" href="prj.css">
</head>
<body>
    <div class="container">
        <h1>My Bookings</h1>
        <?php if (empty($bookings)): ?>
            <p>You have no bookings.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Room</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking['room_name']) ?></td>
                            <td><?= htmlspecialchars($booking['date']) ?></td>
                            <td><?= htmlspecialchars($booking['start_time']) ?> - <?= htmlspecialchars($booking['end_time']) ?></td>
                            <td>
                                <form action="cancel_booking.php" method="post">
                                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                    <button type="submit">Cancel</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <a href="prj.php">Back to Room List</a>
    </div>
</body>
</html>