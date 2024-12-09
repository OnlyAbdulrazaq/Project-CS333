<?php
include 'db_connection.php';

$booking_id = $_GET['id'];

// Retrieve timeslot ID
$timeslot_query = $conn->prepare("
    SELECT timeslot_id FROM bookings WHERE id = ?
");
$timeslot_query->execute([$booking_id]);
$timeslot_id = $timeslot_query->fetchColumn();

// Delete booking
$delete_booking = $conn->prepare("
    DELETE FROM bookings WHERE id = ?
");
$delete_booking->execute([$booking_id]);

// Mark timeslot as available
$update_timeslot = $conn->prepare("
    UPDATE timeslots SET is_available = TRUE WHERE id = ?
");
$update_timeslot->execute([$timeslot_id]);

echo "Booking canceled.";
header("Location: profile.php");
exit();
?>
