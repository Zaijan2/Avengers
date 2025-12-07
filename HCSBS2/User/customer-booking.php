<?php
session_start();

// --- ERROR DISPLAY ---
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- CHECK LOGIN ---
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// --- DATABASE CONNECTION ---
$conn = new mysqli("localhost", "root", "", "quickclean");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// --- GET SERVICE ID FROM URL ---
if (!isset($_GET['service_id'])) {
    die("No service selected.");
}
$service_id = intval($_GET['service_id']);

// --- GET SERVICE DETAILS ---
$stmtService = $conn->prepare("SELECT service_name, price FROM services WHERE service_id=? AND status='active'");
$stmtService->bind_param("i", $service_id);
$stmtService->execute();
$service = $stmtService->get_result()->fetch_assoc();
$stmtService->close();

if (!$service) {
    die("Selected service not found or inactive.");
}

// --- GET CUSTOMER DETAILS ---
$stmtUser = $conn->prepare("SELECT name, email, contact_num, address FROM user WHERE user_id=?");
$stmtUser->bind_param("i", $user_id);
$stmtUser->execute();
$user = $stmtUser->get_result()->fetch_assoc();
$stmtUser->close();

// --- HANDLE FORM SUBMISSION ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $date = $_POST['date'];
    $time = $_POST['time'];
    $notes = $_POST['notes'] ?? '';

    // FIX: ADD user_id TO INSERT STATEMENT
    $stmt = $conn->prepare("INSERT INTO bookings 
        (user_id, service_id, service_name, price, date, time, name, phone, email, address, notes, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        
    // FIX: ADD 'i' FOR user_id (integer) AND THE $user_id VARIABLE
    $stmt->bind_param(
        "iisssssssss", // 'i' for user_id, 'i' for service_id, 's' for the rest
        $user_id,
        $service_id,
        $service['service_name'],
        $service['price'],
        $date,
        $time,
        $user['name'],
        $user['contact_num'],
        $user['email'],
        $user['address'],
        $notes
    );
    $stmt->execute();

    $_SESSION['booking_id'] = $stmt->insert_id;
    $stmt->close();

    header("Location: payment.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Service - QuickClean</title>
<style>
body{font-family:"Poppins",sans-serif;background:#F0F4F5;}
.container{max-width:600px;margin:40px auto;background:#fff;padding:25px;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,0.1);}
h2{text-align:center;color:#2E89F0;margin-bottom:20px;}
label{font-weight:600;display:block;margin-bottom:5px;}
input,textarea{width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;margin-bottom:15px;}
button{background:#2E89F0;color:#fff;border:none;padding:12px;width:100%;font-size:16px;border-radius:6px;cursor:pointer;font-weight:600;}
button:hover{background:#1c6ed6;}
input[readonly] { background:#e9ecef; }
</style>
</head>
<body>
<div class="container">
<h2>Book Service: <?php echo htmlspecialchars($service['service_name']); ?></h2>
<form method="POST">
    <label>Full Name</label>
    <input type="text" value="<?php echo htmlspecialchars($user['name']); ?>" readonly>

    <label>Email</label>
    <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>

    <label>Phone Number</label>
    <input type="text" value="<?php echo htmlspecialchars($user['contact_num']); ?>" readonly>

    <label>Address</label>
    <textarea readonly><?php echo htmlspecialchars($user['address']); ?></textarea>

    <label>Date</label>
    <input type="date" name="date" required>

    <label>Time</label>
    <input type="time" name="time" required>

    <label>Additional Notes</label>
    <textarea name="notes" placeholder="Optional"></textarea>

    <button type="submit">Proceed to Payment</button>
</form>
</div>
</body>
</html>
<?php $conn->close(); ?>