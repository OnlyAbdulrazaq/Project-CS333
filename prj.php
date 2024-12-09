<?php
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
        <div class="cart-icon">🛒</div>
        <div class="user-icon">👤</div>
      </div>
    </header>
    <main class="grid-container">
      <?php foreach ($rooms as $index => $room): ?>
        <div class="card" data-department="<?= htmlspecialchars($room['department']) ?>">
        <a href="room_details.php?id=<?= $index + 1 ?>">  <!-- Change here: Room ID is now index + 1 -->
          <img src="<?= htmlspecialchars($room['image_url']) ?>" alt="Room <?= $index + 1 ?>">  <!-- Display Room number -->
        </a>
          <div class="card-info">
            <h3>Room <?= $index + 1 ?></h3>  <!-- Display Room number -->
            <p>
              📍 <?= htmlspecialchars($room['department']) ?> Department | 
              👥 <?= htmlspecialchars($room['capacity']) ?> People | 
              <?= htmlspecialchars($room['size']) ?>
            </p>
            <p>Equipment: <?= htmlspecialchars($room['equipment']) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
      
      <?php 
        $departments = ['CS', 'NE', 'IS'];
        for ($i = 1; $i <= 19; $i++):
          // Get random department for fallback cards
          $department = $departments[array_rand($departments)];
      ?>
        <div class="card" data-department="<?= $department ?>">
          <a href="room_details.php?id=fallback_<?= $i ?>">
            <img src="rooms_pics/room<?= $i ?>.jpg" alt="Fallback Room <?= $i ?>">
          </a>
          <div class="card-info">
            <h3>Room <?= $i ?></h3>
            <p>
              📍 <?= $department ?> Department | 
              👥 <?= rand(10, 300) ?> People | 
              <?= rand(50, 300) ?> m²
            </p>
          </div>
        </div>
      <?php endfor; ?>
    </main>
  </div>
  <footer class="simple-footer">
    <div class="footer-content">
      <h2 class="website-name">Room<span class="highlight">Scheduler</span></h2>
      <p>Email: <a href="mailto:support@roomscheduler.com">Support@RoomScheduler.com</a></p>
      <p>Phone: <a href="tel:+9733323">+973 39733323</a></p>
      <p class="copyright">©2024 RoomScheduler. All Rights Reserved.</p>
    </div>
  </footer>
  <script src="prj.js"></script>
</body>
</html>
