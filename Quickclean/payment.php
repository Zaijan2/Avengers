<?php
session_start();
$conn = new mysqli("localhost", "root", "", "quickclean");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get booking_id from session
if(!isset($_SESSION['booking_id'])) die("Booking not found.");
$booking_id = $_SESSION['booking_id'];
$user_id = 1; // replace with actual logged-in user ID

// Fetch booking details
$stmtBooking = $conn->prepare("SELECT price FROM bookings WHERE booking_id=?");
$stmtBooking->bind_param("i",$booking_id);
$stmtBooking->execute();
$booking = $stmtBooking->get_result()->fetch_assoc();
$stmtBooking->close();

// On Done, insert payment if not exists and redirect to receipt
if(isset($_POST['done'])){
    $stmtCheck = $conn->prepare("SELECT * FROM payments WHERE booking_id=?");
    $stmtCheck->bind_param("i",$booking_id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    if($resultCheck->num_rows==0){
        $amount = $booking['price'];
        $payment_method = 'qrcode';
        $payment_status = 'paid';
        $transaction_id = 'TXN'.time();
        $stmtInsert = $conn->prepare("INSERT INTO payments (booking_id,user_id,amount,payment_method,payment_status,transaction_id) VALUES (?,?,?,?,?,?)");
        $stmtInsert->bind_param("iissss",$booking_id,$user_id,$amount,$payment_method,$payment_status,$transaction_id);
        $stmtInsert->execute();
        $stmtInsert->close();
    }
    $stmtCheck->close();
    header("Location: receipt.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>QR Code Payment</title>
<style>
body{font-family:"Poppins",sans-serif;background:#F0F4F5;text-align:center;padding:40px;}
.box{background:white;max-width:450px;margin:auto;padding:25px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.1);}
img.qr{width:240px;margin-top:15px;border:8px solid #eaeaea;border-radius:10px;}
h2{color:#2E89F0;}
.btn{background:#2E89F0;color:white;padding:12px 25px;border:none;border-radius:8px;cursor:pointer;font-size:16px;font-weight:600;margin-top:20px;}
</style>
</head>
<body>
<div class="box">
<h2>Scan to Pay</h2>
<img src="qrcode.webp" class="qr" alt="QR Code">
<p>After completing the payment, click the button below:</p>
<form method="POST">
<button type="submit" name="done" class="btn">Done</button>
</form>
</div>
</body>
</html>
