<?php
$conn = mysqli_connect("localhost", "root", "", "quickclean");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$booking_id = (int)$_GET['id']; // Sanitize input

// Check if transaction already exists for this booking
$check = mysqli_query($conn, "SELECT * FROM transactions WHERE booking_id = $booking_id");

if (mysqli_num_rows($check) > 0) {
    // Transaction exists, just update status
    $sql = "UPDATE transactions SET status='on the way' WHERE booking_id = $booking_id";
} else {
    // No transaction exists, create new one
    $sql = "INSERT INTO transactions (booking_id, status) VALUES ($booking_id, 'on the way')";
}

mysqli_query($conn, $sql);

// Also update booking status to 'accepted' if it was pending
mysqli_query($conn, "UPDATE bookings SET status='accepted' WHERE booking_id=$booking_id");

mysqli_close($conn);

header("Location: assigned.php");
exit();
?>