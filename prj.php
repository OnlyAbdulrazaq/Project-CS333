<?php
session_start();
include 'db.php'; 

// Fetch all rooms from the database
$result = $conn->query("SELECT * FROM rooms");

if ($result === false) {
    die("Database query failed: " . $conn->error);
}

// Fetch data as an associative array
$rooms = [];
while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="prj.css">
  <title>RoomScheduler</title>
</head>
<body>
  <div class="container">
    <header class="header">
      <div class="menu-icon">☰</div>
      <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search By Department..." onkeyup="filterCards()" />
      </div>
      <div class="icons">
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="mybookings.php" class="bookings-icon">📅</a>
          <a href="logout.php" class="logout-icon">🚪</a>
        <?php else: ?>
          <a href="login.php" class="login-icon">🔑</a>
        <?php endif; ?>
      </div>
    </header>
    <main class="grid-container">
  <?php foreach ($rooms as $room): ?>
    <div class="card" data-department="<?= htmlspecialchars($room['department']) ?>">
      <a href="room_details.php?id=<?= $room['id'] ?>">
        <img src="rooms_pics/room<?= htmlspecialchars($room['image_url']) ?>" 
             alt="<?= htmlspecialchars($room['name']) ?>" 
             onerror="this.onerror=null; this.src='rooms_pics/default_room.jpg';">
      </a>
      <div class="card-info">
        <h3><?= htmlspecialchars($room['name']) ?></h3>
        <p>
          📍 <?= htmlspecialchars($room['department']) ?> Department | 
          👥 <?= htmlspecialchars($room['capacity']) ?> People | 
          <?= htmlspecialchars($room['size']) ?>
        </p>
        <p>Equipment: <?= htmlspecialchars($room['equipment']) ?></p>
      </div>
    </div>
  <?php endforeach; ?>
    </main>
  </div>
  <?php include 'footer.php'; ?>
  <script src="prj.js"></script>
</body>
</html>