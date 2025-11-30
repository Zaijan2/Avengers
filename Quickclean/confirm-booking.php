<?php
$conn = new mysqli("localhost", "root", "", "quickclean");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Step 1: if user clicked "Confirm", insert into database
if (isset($_POST['confirm'])) {
  $service_id = $_POST['service_id'];
  $service_name = $_POST['service_name'];
  $price = $_POST['price'];
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];
  $date = $_POST['date'];
  $time = $_POST['time'];
  $notes = $_POST['notes'];

  $sql = "INSERT INTO bookings (service_id, service_name, date, time, name, phone, email, address, notes, status, price)
          VALUES ('$service_id', '$service_name', '$date', '$time', '$name', '$phone', '$email', '$address', '$notes', 'pending', '$price')";

  if ($conn->query($sql)) {
    $inserted = true;
  } else {
    $error = "Error saving booking: " . $conn->error;
  }
}

// Step 2: if this is first visit, show confirmation preview
elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
  // fetch service details using service_id
  $sid = $_POST['service_name']; // this contains the ID from dropdown
  $serviceData = $conn->query("SELECT * FROM services WHERE service_id='$sid' LIMIT 1");
  $service = $serviceData->fetch_assoc();
  $service_name = $service['service_name'];
  $price = $service['price']; // get price
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirm Booking - QuickClean</title>
  <style>
    body { font-family:"Poppins",sans-serif; background:#F0F4F5; padding:50px; color:#123; text-align:center; }
    .box { max-width:500px; margin:auto; background:#fff; padding:30px; border-radius:10px; box-shadow:0 3px 10px rgba(0,0,0,0.1); }
    h2 { color:#2E89F0; }
    .detail { text-align:left; margin:15px 0; }
    .btn {
      background:#2E89F0; color:#fff; padding:10px 20px; border:none;
      border-radius:6px; cursor:pointer; font-weight:600; margin:10px;
    }
    .btn.cancel { background:#bbb; }
  </style>
</head>
<body>
<div class="box">
<?php if (!empty($inserted)): ?>
  <h2>Booking Confirmed!</h2>
  <p>✅ Your booking has been saved and is pending admin approval.</p>
  <a href="servicepage.php" class="btn">Back</a>

<?php elseif (!empty($error)): ?>
  <h2>Booking Failed</h2>
  <p style="color:red;"><?= $error ?></p>
  <a href="customer-booking.php" class="btn cancel">Go Back</a>

<?php else: ?>
  <h2>Confirm Your Booking</h2>
  <div class="detail">
    <strong>Service:</strong> <?= htmlspecialchars($service_name) ?><br>
    <strong>Price:</strong> ₱<?= number_format($price, 2) ?><br>
    <strong>Date:</strong> <?= htmlspecialchars($_POST['date']) ?><br>
    <strong>Time:</strong> <?= htmlspecialchars($_POST['time']) ?><br>
    <strong>Name:</strong> <?= htmlspecialchars($_POST['name']) ?><br>
    <strong>Email:</strong> <?= htmlspecialchars($_POST['email']) ?><br>
    <strong>Phone:</strong> <?= htmlspecialchars($_POST['phone']) ?><br>
    <strong>Address:</strong> <?= htmlspecialchars($_POST['address']) ?><br>
    <strong>Notes:</strong> <?= htmlspecialchars($_POST['notes'] ?: 'None') ?><br>
  </div>

  <form method="POST">
    <input type="hidden" name="service_id" value="<?= htmlspecialchars($sid) ?>">
    <input type="hidden" name="service_name" value="<?= htmlspecialchars($service_name) ?>">
    <input type="hidden" name="price" value="<?= htmlspecialchars($price) ?>">
    <input type="hidden" name="name" value="<?= htmlspecialchars($_POST['name']) ?>">
    <input type="hidden" name="email" value="<?= htmlspecialchars($_POST['email']) ?>">
    <input type="hidden" name="phone" value="<?= htmlspecialchars($_POST['phone']) ?>">
    <input type="hidden" name="address" value="<?= htmlspecialchars($_POST['address']) ?>">
    <input type="hidden" name="date" value="<?= htmlspecialchars($_POST['date']) ?>">
    <input type="hidden" name="time" value="<?= htmlspecialchars($_POST['time']) ?>">
    <input type="hidden" name="notes" value="<?= htmlspecialchars($_POST['notes']) ?>">

    <button type="submit" name="confirm" class="btn">Confirm Booking</button>
    <a href="customer-booking.php" class="btn cancel">Cancel</a>
  </form>
<?php endif; ?>
</div>
</body>
</html>
<?php $conn->close(); ?>
