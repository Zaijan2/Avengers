<?php
// --- DATABASE CONNECTION ---
$conn = new mysqli("localhost", "root", "", "quickclean");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book a Service - QuickClean</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: "Poppins", sans-serif; background: #F0F4F5; }
    .container {
      max-width: 600px; margin: 40px auto; background: #fff; padding: 25px;
      border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    h2 { text-align:center; color:#2E89F0; margin-bottom:20px; }
    label { font-weight:600; display:block; margin-bottom:5px; }
    input, textarea, select {
      width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; margin-bottom:15px;
    }
    button {
      background:#2E89F0; color:#fff; border:none; padding:12px; width:100%;
      font-size:16px; border-radius:6px; cursor:pointer; font-weight:600;
    }
    button:hover { background:#1c6ed6; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Book a Cleaning Service</h2>
    <form action="confirm-booking.php" method="POST">
      <label>Full Name</label>
      <input type="text" name="name" required>

      <label>Email</label>
      <input type="email" name="email" required>

      <label>Phone Number</label>
      <input type="text" name="phone" required>

      <label>Address</label>
      <textarea name="address" required></textarea>

      <label>Select Service</label>
      <select name="service_name" required>
        <option value="">-- Select a Service --</option>
        <?php
        $services = $conn->query("SELECT service_id, service_name FROM services WHERE status='active'");
        while ($row = $services->fetch_assoc()) {
          echo "<option value='{$row['service_id']}'>{$row['service_name']}</option>";
        }
        ?>
      </select>

      <label>Date</label>
      <input type="date" name="date" required>

      <label>Time</label>
      <input type="time" name="time" required>

      <label>Additional Notes</label>
      <textarea name="notes" placeholder="Optional"></textarea>

      <button type="submit">Proceed to Confirmation</button>
    </form>
  </div>
</body>
</html>
<?php $conn->close(); ?>
