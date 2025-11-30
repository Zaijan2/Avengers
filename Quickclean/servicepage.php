<?php
session_start();

// --- CHECK LOGIN ---
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Only allow customers
if ($_SESSION['role'] !== 'customer') {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin-dashboard.php");
    } else {
        header("Location: login.php");
    }
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];

// --- DATABASE CONNECTION ---
$host = "localhost";
$user = "root";
$pass = "";
$db   = "quickclean";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch services
$query = "SELECT * FROM services ORDER BY service_id DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>QuickClean - Services</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
/* same styles as your previous code */
:root{
  --brand-blue: #6DAFF2;
  --nav-yellow: #FFDB58;
  --text-blue: #2E89F0;
  --muted-bg: #F0F4F5;
}
* { box-sizing:border-box; }
body { margin:0; font-family:"Poppins",sans-serif; background:#fff; color:#123; }
.site-header { background:var(--brand-blue); height:110px; display:flex; align-items:center; justify-content:center; }
.header-inner { width:90%; max-width:1200px; display:flex; justify-content:space-between; align-items:center; }
.logo { height:75px; }
.tagline { color:#fff; font-weight:600; font-size:20px; }
.profile { width:50px; height:50px; border-radius:50%; background:#fff; display:flex; align-items:center; justify-content:center; font-weight:bold; }
/* Nav */
.nav-bar { background:var(--nav-yellow); height:64px; display:flex; align-items:center; }
.nav-list { display:flex; justify-content:center; gap:30px; list-style:none; width:100%; padding:0; margin:0; }
.nav-link { text-decoration:none; color:#0b3b66; font-weight:600; font-size:18px; }
.nav-link.active { text-decoration:underline; font-weight:700; }
/* Services */
.services-section { padding:50px 20px; text-align:center; }
.services-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:20px; max-width:1200px; margin:0 auto; }
.service-card { background:#fff; border-radius:12px; padding:20px; box-shadow:0 6px 18px rgba(0,0,0,0.06); transition:0.25s; }
.service-card:hover { transform:translateY(-6px); box-shadow:0 18px 40px rgba(0,0,0,0.12); }
.service-card img { width:100%; height:180px; object-fit:cover; border-radius:8px; margin-bottom:10px; }
.service-card h3 { color:var(--text-blue); margin-bottom:6px; }
.service-card p { color:#555; font-size:0.95rem; min-height:60px; }
.service-price { color:#e6b93a; font-weight:700; margin-top:10px; font-size:1.1rem; }
.btn-book { background:var(--nav-yellow); border:none; padding:12px 22px; border-radius:28px; font-weight:700; cursor:pointer; transition:0.3s; }
.btn-book:hover { background:#e6c73b; transform:translateY(-2px); }
</style>
</head>
<body>

<header class="site-header">
  <div class="header-inner">
    <img src="logo.png" alt="QuickClean logo" class="logo">
    <span class="tagline">QuickClean: Clean Spaces, Happy Faces.</span>
    <div class="profile"><?php echo htmlspecialchars(substr($user_name,0,1)); ?></div>
  </div>
</header>

<nav class="nav-bar">
  <ul class="nav-list">
    <li><a href="customer-home.php" class="nav-link">Home</a></li>
    <li><a href="servicepage.php" class="nav-link active">Services</a></li>
    <li><a href="testimonial.php" class="nav-link">Testimonies</a></li>
    <li><a href="aboutus.php" class="nav-link">About Us</a></li>
    <li><a href="contactus.php" class="nav-link">Contact Us</a></li>
    <li><a href="logout.php" class="nav-link">Logout</a></li>
  </ul>
</nav>

<section class="services-section">
  <h2>Our Services</h2>
  <div class="services-grid">
    <?php if ($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="service-card">
          <img src="Admin/uploads/<?php echo htmlspecialchars($row['image'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($row['service_name']); ?>">
          <h3><?php echo htmlspecialchars($row['service_name']); ?></h3>
          <p><?php echo htmlspecialchars($row['description']); ?></p>
          <div class="service-price">₱<?php echo number_format($row['price'],2); ?></div>
          <a href="customer-booking.php?service_id=<?php echo $row['service_id']; ?>">
            <button class="btn-book">BOOK NOW</button>
          </a>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No services available at the moment.</p>
    <?php endif; ?>
  </div>
</section>

<footer>© 2025 QuickClean. All Rights Reserved.</footer>

<?php $conn->close(); ?>
</body>
</html>
