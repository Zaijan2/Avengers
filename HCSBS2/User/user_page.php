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
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>User Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;800&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
:root{
  --brand-blue: #6DAFF2;
  --nav-yellow: #FFDB58;
  --text-blue: #2E89F0;
  --header-height: 110px;
  --nav-height: 64px;
  --max-content-width: 1360px;
}
* { box-sizing: border-box; }
body { font-family:"Poppins",sans-serif; background:#f4f6f8; margin:0; color:#123; }

/* HEADER & NAV */
.site-header{ background:var(--brand-blue); height:var(--header-height); display:flex; align-items:center; }
.header-inner{ width:100%; max-width:var(--max-content-width); margin:0 auto; display:flex; align-items:center; justify-content:space-between; padding:12px 24px; }
.logo-text{ font-family:'Baloo 2'; font-size:32px; color:white; font-weight:800; min-width:150px; }
.tagline{ font-family:'Baloo 2'; color:#fff; font-weight:600; font-size:20px; flex-grow:1; text-align:center; padding:0 20px; }
.header-placeholder { min-width:150px; }
.nav-bar{ background:var(--nav-yellow); height:var(--nav-height); display:flex; align-items:center; }
.nav-list{ display:flex; justify-content:center; gap:30px; list-style:none; width:100%; margin:0; padding:0; }
.nav-link{ color:#0b3b66; text-decoration:none; font-weight:600; font-size:18px; }
.nav-link.active{ text-decoration:underline; text-underline-offset:6px; font-weight:800; }
.nav-link:hover{ color:var(--text-blue); }

/* DASHBOARD CONTENT */
.wrap{max-width:600px; margin:40px auto; padding:30px; background:#fff; border-radius:12px; box-shadow:0 3px 8px rgba(0,0,0,0.06);}
.profile{display:flex; gap:24px; align-items:flex-start;}
.avatar{width:80px; height:80px; border-radius:50%; background:#ddd; display:flex; justify-content:center; align-items:center; font-weight:700; font-size:24px; overflow:hidden;}
.dashboard-title{ font-family:"Baloo 2"; color:var(--text-blue); margin:0 0 10px 0; }
.edit-btn{margin-top:20px; padding:10px 20px; background:var(--text-blue); color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:600; text-decoration:none; display:inline-block; transition: background 0.2s;}
.edit-btn:hover{background:#1a6cb3;}
</style>
</head>
<body>

<header class="site-header">
  <div class="header-inner">
    <div class="logo-text">QuickClean</div>
    <div class="tagline">Clean Spaces, Happy Faces.</div>
    <span class="header-placeholder"></span>
  </div>
</header>

<nav class="nav-bar">
  <ul class="nav-list">
    <li><a href="customer-home.php" class="nav-link">Home</a></li>
    <li><a href="user_page.php" class="nav-link active">Dashboard</a></li>
    <li><a href="tracking.php" class="nav-link">Track Services</a></li>
    <li><a href="messages.php" class="nav-link">Messages</a></li>
    <li><a href="logout.php" class="nav-link">Logout</a></li>
  </ul>
</nav>

<div class="wrap">
  <div class="profile">
    <div class="avatar">
      <?php
        if (!empty($user['profile_pic']) && file_exists("uploads/".$user['profile_pic'])) {
            echo '<img src="uploads/'.htmlspecialchars($user['profile_pic']).'" style="width:100%;height:100%;object-fit:cover;">';
        } else {
            echo strtoupper(substr($user['name'] ?? 'U',0,1));
        }
      ?>
    </div>
    <div>
      <h2 class="dashboard-title"><?php echo htmlspecialchars($user['name']); ?></h2>
      <div><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></div>
      <div><strong>Contact No.:</strong> <?php echo htmlspecialchars($user['contact_num']); ?></div>
      <div><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></div>

      <!-- EDIT PROFILE BUTTON -->
      <a href="edit_profile.php" class="edit-btn">Edit Profile</a>
    </div>
  </div>
</div>

</body>
</html>
