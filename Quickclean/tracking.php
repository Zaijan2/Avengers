<?php
session_start();

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

require_login();


$host = "localhost";
$user = "root";
$pass = "";
$db   = "quickclean";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT booking_id, service_name, price, date, time, status, created_at FROM bookings WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings = $stmt->get_result();
$stmt->close();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>My Bookings</title>
  <style>
    body{font-family:Arial;background:#f4f6f8;margin:0}
    .wrap{max-width:900px;margin:24px auto;padding:20px;background:#fff;border-radius:8px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid #eee;text-align:left}
    th{background:#fafafa}
  </style>
</head>
<body>
<div class="wrap">
  <h2>My Bookings</h2>
  <?php if ($bookings->num_rows > 0): ?>
  <table>
    <tr><th>Service</th><th>Price</th><th>Schedule</th><th>Status</th><th>Booked</th></tr>
    <?php while ($b = $bookings->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($b['service_name']); ?></td>
        <td>₱<?php echo number_format($b['price'],2); ?></td>
        <td><?php echo htmlspecialchars($b['date'] . ' ' . $b['time']); ?></td>
        <td><?php echo htmlspecialchars($b['status']); ?></td>
        <td><?php echo htmlspecialchars($b['created_at']); ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
  <?php else: ?>
    <p>No bookings yet.</p>
  <?php endif; ?>
</div>
</body>
</html>
