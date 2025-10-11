<?php
$conn = new mysqli("localhost", "root", "", "quickclean");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['id']) && isset($_POST['action'])) {
  $id = intval($_POST['id']);
  $action = $_POST['action'];

  if ($action == 'accept') {
    $conn->query("UPDATE bookings SET status='accepted' WHERE booking_id=$id");
  } elseif ($action == 'decline') {
    $conn->query("UPDATE bookings SET status='declined' WHERE booking_id=$id");
  }
}
$conn->close();
?>
