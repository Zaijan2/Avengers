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


$user_id = $_SESSION['user_id']; // ID retrieved from session

// *** MODIFIED SELECT STATEMENT TO FETCH ALL BOOKING DETAILS ***
$stmt = $conn->prepare("SELECT 
    booking_id, 
    service_name, 
    price, 
    date, 
    time, 
    status, 
    created_at, 
    address, 
    phone, 
    email 
    FROM bookings 
    WHERE user_id = ? 
    ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings = $stmt->get_result();
$stmt->close();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Bookings</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;800&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

  <style>
    /* --- DESIGN STYLES (NAV BAR & HEADER) --- */
    :root{
      --brand-blue: #6DAFF2;
      --nav-yellow: #FFDB58;
      --text-blue: #2E89F0;
      --nav-link-color: #0b3b66;
      --header-height: 110px;
      --nav-height: 64px;
      --max-content-width: 1360px;
    }
    
    * { box-sizing: border-box; }
    body{ font-family:"Poppins",sans-serif; background:#f4f6f8; margin:0; color:#123; }

    /* HEADER DESIGN */
    .site-header{ background:var(--brand-blue); height:var(--header-height); display:flex; align-items:center; }
    
    /* Centering CSS for Tagline */
    .header-inner{ 
        width:100%; 
        max-width:var(--max-content-width); 
        margin:0 auto; 
        display:flex; 
        align-items:center; 
        justify-content:space-between; 
        padding:12px 24px; 
    }
    .logo-text{ 
        font-family:'Baloo 2'; 
        font-size: 32px; 
        color:white; 
        font-weight:800; 
        min-width: 150px; 
    }
    .tagline{ 
        font-family:"Baloo 2"; 
        color:#fff; 
        font-weight:600; 
        font-size:20px; 
        flex-grow: 1; 
        text-align: center; 
        padding: 0 20px;
    }
    .header-placeholder {
        min-width: 150px; 
    }
    
    /* YELLOW NAV BAR DESIGN */
    .nav-bar{ background:var(--nav-yellow); height:var(--nav-height); display:flex; align-items:center; }
    .nav-list{ display:flex; justify-content:center; gap:30px; list-style:none; width:100%; margin:0; padding:0; }
    .nav-link{ color:var(--nav-link-color); text-decoration:none; font-weight:600; font-size:18px; }
    .nav-link.active{ text-decoration:underline; text-underline-offset:6px; font-weight:800; }
    .nav-link:hover { color: var(--text-blue); }

    /* --- PAGE CONTENT STYLES (Adjusted) --- */
    .wrap{
        max-width:1100px; 
        margin:40px auto; 
        padding:20px 30px;
        background:#fff;
        border-radius:12px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.06);
    }
    h2 { font-family: "Baloo 2"; color: var(--text-blue); margin-top: 0; }
    table{width:100%;border-collapse:collapse; table-layout: fixed;}
    th,td{padding:12px;border-bottom:1px solid #eee;text-align:left; word-wrap: break-word;}
    th{background:var(--brand-blue); color: white; font-weight: 600; border-bottom: none;}
    
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
    <li><a href="user_page.php" class="nav-link">Dashboard</a></li>
    <li><a href="tracking.php" class="nav-link active">Track Services</a></li>
    <li><a href="messages.php" class="nav-link">Messages</a></li>
    <li><a href="logout.php" class="nav-link">Logout</a></li>
  </ul>
</nav>

<div class="wrap">
  <h2>My Bookings</h2>
  
  <?php if ($bookings->num_rows > 0): ?>
  <table>
    <tr>
        <th>Service</th>
        <th>Price</th>
        <th>Schedule</th>
        <th>Address</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Status</th>
        <th>Booked Date</th>
    </tr>
    <?php while ($b = $bookings->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($b['service_name']); ?></td>
        <td>₱<?php echo number_format($b['price'],2); ?></td>
        <td><?php echo htmlspecialchars($b['date'] . ' @ ' . $b['time']); ?></td>
        <td><?php echo nl2br(htmlspecialchars($b['address'])); ?></td>
        <td><?php echo htmlspecialchars($b['phone']); ?></td>
        <td><?php echo htmlspecialchars($b['email']); ?></td>
        
        <td><?php echo htmlspecialchars($b['status']); ?></td>
        <td><?php echo htmlspecialchars(date('M j, Y', strtotime($b['created_at']))); ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
  <?php else: ?>
    <p>No bookings yet.</p>
  <?php endif; ?>
</div>
</body>
</html>