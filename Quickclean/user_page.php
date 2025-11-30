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

// fetch user
$stmt = $conn->prepare("SELECT user_id, name, email, address, contact_num, profile_pic FROM user WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>User Dashboard</title>
  <style>
    body{font-family:Arial; background:#f4f6f8; margin:0}
    header{background:#2E89F0;color:#fff;padding:12px 20px;display:flex;justify-content:space-between;align-items:center;}
    nav a{color:#fff;margin-left:12px;text-decoration:none;font-weight:600}
    .wrap{max-width:1000px;margin:24px auto;padding:20px;background:#fff;border-radius:8px;box-shadow:0 3px 8px rgba(0,0,0,0.06)}
    .profile{display:flex;gap:16px;align-items:center}
    .avatar{width:60px;height:60px;border-radius:50%;background:#ddd;display:flex;justify-content:center;align-items:center;font-weight:700}
    .menu{margin-top:10px}
    .menu a{display:inline-block;margin-right:10px;padding:8px 12px;background:#2E89F0;color:#fff;border-radius:6px;text-decoration:none}
    <style>
/* Sidebar Container */
.sidebar {
    width: 250px;
    height: 100vh;
    background: #63A9E6;
    padding: 20px;
    position: fixed;
    top: 0;
    left: 0;
    color: white;
    font-family: "Poppins", sans-serif;
}

/* Branding */
.sidebar h2 {
    text-align: center;
    font-size: 28px;
    margin-bottom: 30px;
    font-weight: 700;
}

/* Nav Links */
.sidebar a {
    display: block;
    padding: 12px 15px;
    color: white;
    text-decoration: none;
    margin-bottom: 10px;
    font-size: 17px;
    border-radius: 8px;
    transition: 0.2s;
}

/* Hover */
.sidebar a:hover {
    background: #3D7FDA;
}

/* Active Page */
.sidebar a.active {
    background: #246CD6;
}

/* Page Content */
.content {
    margin-left: 270px; 
    padding: 20px;
    font-family: "Poppins", sans-serif;
}
</style>

  </style>
</head>
<body>
<header>
  <div>QuickClean</div>
  <div>
    <a href="customer-home.php" style="color:#fff">Home</a>
    <a href="user_page.php" style="color:#fff">Dashboard</a>
    <a href="edit_profile.php" style="color:#fff">Edit Profile</a>
    <a href="tracking.php" style="color:#fff">Track Services</a>
    <a href="messages.php" style="color:#fff">Messages</a>
    <a href="logout.php" style="color:#fff">Logout</a>
  </div>
</header>

<div class="wrap">
  <div class="profile">
    <div class="avatar">
      <?php
        if (!empty($user['profile_pic']) && file_exists("uploads/".$user['profile_pic'])) {
            echo '<img src="uploads/'.htmlspecialchars($user['profile_pic']).'" style="width:60px;height:60px;border-radius:50%;object-fit:cover">';
        } else {
            echo strtoupper(substr($user['name'] ?? 'U',0,1));
        }
      ?>
    </div>
    <div>
      <h2><?php echo htmlspecialchars($user['name']); ?></h2>
      <div><?php echo htmlspecialchars($user['email']); ?></div>
      <div><?php echo htmlspecialchars($user['contact_num']); ?></div>
      <div style="margin-top:10px" class="menu">
        <a href="edit_profile.php">Edit Profile</a>
        <a href="tracking.php">My Bookings</a>
        <a href="messages.php">Messages</a>
      </div>
    </div>
  </div>
</div>
</body>
</html>
