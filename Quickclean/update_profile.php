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

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$address = trim($_POST['address'] ?? '');
$contact = trim($_POST['contact_num'] ?? '');

// handle upload
$filename = null;
if (!empty($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['error'] == 0) {
    $tmp = $_FILES['profile_pic']['tmp_name'];
    // sanitize file name
    $namepart = time() . "_" . basename($_FILES['profile_pic']['name']);
    $target = "uploads/" . $namepart;
    if (move_uploaded_file($tmp, $target)) {
        $filename = $namepart;
    }
}

// update fields
if ($filename) {
    $stmt = $conn->prepare("UPDATE user SET name=?, email=?, address=?, contact_num=?, profile_pic=? WHERE user_id=?");
    $stmt->bind_param("sssssi", $name, $email, $address, $contact, $filename, $user_id);
} else {
    $stmt = $conn->prepare("UPDATE user SET name=?, email=?, address=?, contact_num=? WHERE user_id=?");
    $stmt->bind_param("ssssi", $name, $email, $address, $contact, $user_id);
}
$stmt->execute();
$stmt->close();

header("Location: user_page.php");
exit();
