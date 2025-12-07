<?php
// Visitor homepage — no login required

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "quickclean";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) die("Connection failed: " . mysqli_connect_error());

// Fetch services ordered by number of bookings (most availed first)
$services = $conn->query("
    SELECT s.service_id, s.service_name, s.description, s.price, s.image, 
           COUNT(b.booking_id) AS bookings_count
    FROM services s
    LEFT JOIN bookings b ON s.service_id = b.service_id
    WHERE s.status='active'
    GROUP BY s.service_id
    ORDER BY bookings_count DESC
");

$current_page = basename($_SERVER['PHP_SELF']); // get current page name
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QuickClean - Visitor Home</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
body{margin:0;font-family:"Poppins",sans-serif;background:#fff;color:#123;}
a{text-decoration:none;}

/* Header */
.site-header {
  background:#6DAFF2;
  height:110px;
  display:flex;
  align-items:center;
}
.header-inner {
  width:100%;
  max-width:1360px;
  margin:0 auto;
  display:flex;
  align-items:center;
  justify-content:space-between; /* Align logo left, tagline center/right */
  padding:12px 24px;
}
.logo {
  height:75px;
}

.tagline {
  font-family:"Baloo 2";
  color:#fff;
  font-weight:600;
  font-size:20px;
}

/* Nav */
.nav-bar{background:#FFDB58;height:64px;display:flex;align-items:center;}
.nav-list{display:flex;justify-content:center;gap:30px;list-style:none;width:100%;margin:0;padding:0;}
.nav-link{
    color:#0b3b66;
    text-decoration:none;
    font-weight:600;
    font-size:18px;
}
.nav-link.active{
    font-weight:800;
    text-decoration:underline;
    text-underline-offset:6px; /* same as customer page */
}

/* Hero */
.hero{text-align:center;padding:80px 24px;background:#F0F4F5;}
.hero h1{font-size:60px;color:#2E89F0;margin-bottom:20px;}
.hero p{font-size:18px;color:#1b4d7a;margin-bottom:30px;}
.cta-btn{background:#FFD54A;border:none;padding:12px 24px;border-radius:25px;font-weight:700;cursor:pointer;}

/* Services Section */
.services-section{padding:60px 40px;background:#f9fafb;}
.services-section h2{text-align:center;color:#2E89F0;margin-bottom:40px;font-size:32px;}
.services-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:30px;max-width:1360px;margin:0 auto;}
.service-card{background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 3px 10px rgba(0,0,0,0.08);transition:transform 0.2s;}
.service-card:hover{transform:translateY(-4px);}
.service-card img{width:100%;height:200px;object-fit:cover;}
.service-card-content{padding:20px;}
.service-card-content h3{color:#2E89F0;margin-bottom:10px;}
.service-card-content p{font-size:15px;color:#333;min-height:60px;}
.price{font-weight:600;color:#2E89F0;margin-top:10px;}
</style>
</head>
<body>

<!-- HEADER -->
<header class="site-header">
  <div class="header-inner">
    <img src="logo.png" alt="QuickClean logo" class="logo">

    <div class="tagline">QuickClean: Clean Spaces, Happy Faces.</div>
  </div>
</header>


<!-- NAV -->
<nav class="nav-bar">
  <ul class="nav-list">
    <li><a href="home.php" class="nav-link <?= ($current_page=='home.php') ? 'active' : '' ?>">Home</a></li>
    <li><a href="servicepage.php" class="nav-link <?= ($current_page=='servicepage.php') ? 'active' : '' ?>">Services</a></li>
    <li><a href="testimonial.php" class="nav-link <?= ($current_page=='testimonial.php') ? 'active' : '' ?>">Testimonies</a></li>
    <li><a href="aboutus.php" class="nav-link <?= ($current_page=='aboutus.php') ? 'active' : '' ?>">About Us</a></li>
    <li><a href="login.php" class="nav-link <?= ($current_page=='login.php') ? 'active' : '' ?>">Login</a></li>
  </ul>
</nav>

<!-- HERO -->
<section class="hero">
  <h1>Welcome to QuickClean</h1>
  <div style="margin-top: 40px;">
    <video width="70%" autoplay loop muted playsinline style="border-radius: 12px;">
      <source src="ads.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>
  </div>
</section>

<!-- SERVICES -->
<section class="services-section">
  <h2>Most Popular Services</h2>
  <div class="services-grid">
    <?php if($services && $services->num_rows > 0): ?>
      <?php while($row = $services->fetch_assoc()): ?>
        <div class="service-card">
          <img src="../Admin/uploads/<?= htmlspecialchars($row['image'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($row['service_name']) ?>">
          <div class="service-card-content">
            <h3><?= htmlspecialchars($row['service_name']) ?></h3>
            <p><?= htmlspecialchars($row['description']) ?></p>
            <div class="price">₱<?= number_format($row['price'],2) ?></div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="text-align:center;">No services available at the moment.</p>
    <?php endif; ?>
  </div>
</section>

<?php $conn->close(); ?>
</body>
</html>
