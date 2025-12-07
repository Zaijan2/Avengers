<?php
session_start();

// Check if user is logged in
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

// Fetch user data including profile picture
$stmt = $conn->prepare("SELECT name, profile_pic FROM user WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();
$stmt->close();

$current_page = basename($_SERVER['PHP_SELF']); // e.g., "customer-home.php"

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QuickClean - About & Contact</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Baloo+2&display=swap" rel="stylesheet">
<style>
:root{
  --brand-blue: #6DAFF2;
  --nav-yellow: #FFDB58;
  --cta-yellow: #FFD54A;
  --text-blue: #2E89F0;
  --muted-bg: #F0F4F5;
  --nav-link-color: #0b3b66;
  --header-height: 110px;
  --nav-height: 64px;
  --max-content-width: 1360px;
}

/* Reset & base */
* { box-sizing: border-box; }
html,body { height:100%; margin:0; font-family: "Poppins", sans-serif; color:#123; background:#fff; line-height:1.4; }
a:focus, button:focus { outline:3px solid rgba(46,137,240,0.18); outline-offset:3px; }

/* Header */
.site-header{ background: var(--brand-blue); height: var(--header-height); display:flex; align-items:center; width:100%; box-shadow:0 1px 0 rgba(0,0,0,0.04);}
.header-inner{ width:100%; max-width:var(--max-content-width); margin:0 auto; display:flex; align-items:center; justify-content:space-between; padding:12px 24px;}
.logo{ height:75px; display:block; }
.header-center{ flex:1; display:flex; align-items:center; justify-content:center; }
.tagline{ font-family:"Baloo 2", "Poppins", sans-serif; font-weight:600; font-size:20px; color:#fff; letter-spacing:0.3px; }

/* NAV */
.nav-bar{ background:var(--nav-yellow); height:var(--nav-height); display:flex; align-items:center; }
.nav-list{ display:flex; justify-content:center; gap:30px; list-style:none; width:100%; margin:0; padding:0; }
.nav-link{ color:var(--nav-link-color); text-decoration:none; font-weight:600; font-size:18px; }
.nav-link.active {
    font-weight: 800;
    border-bottom: 2px solid #000; /* subtle bottom border instead of underline */
}

/* About Section */
.about-section{ max-width: var(--max-content-width); margin: 40px auto; padding:0 20px; text-align:center;}
.about-section h2{ font-size:1.8rem; color: var(--text-blue); margin-bottom:20px; }
.about-section p{ color: var(--text-blue); margin-bottom:20px; }

/* Contact Section */
.contact-section{ max-width: var(--max-content-width); margin:50px auto; padding:0 20px; display:flex; flex-wrap:wrap; gap:40px; align-items:flex-start; justify-content:center;}
.contact-info{ flex:1 1 300px; text-align:center;}
.contact-info h2{ color: var(--text-blue); font-size:1.8rem; margin-bottom:20px;}
.contact-info p{ margin-bottom:20px; color: var(--text-blue);}
.contact-details{ display:flex; flex-direction:column; gap:12px; text-align:center; font-size:0.95rem; }
.contact-details p{ margin:0; }
.contact-form{ flex:1 1 350px; background:#4da3ff; padding:20px; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.05);}
.contact-form form{ display:flex; flex-direction:column; gap:15px;}
.contact-form input, .contact-form select{ padding:12px 15px; border-radius:6px; border:none; background:#fff; font-size:1rem; color: var(--text-blue);}
.contact-form button{ padding:8px 20px; border:none; background: var(--nav-yellow); color: var(--text-blue); font-weight:700; cursor:pointer; border-radius:25px; transition:background 0.3s ease; align-self:center; font-size:0.9rem; width:auto;}
.contact-form button:hover{ background:#ffe066; }

@media (max-width:768px){
  .tagline{ font-size:0.9rem;}
  .contact-section{ flex-direction:column; align-items:center;}
}
</style>
</head>
<body>

<!-- HEADER -->
<header class="site-header">
  <div class="header-inner">
    <img src="logo.png" alt="QuickClean logo" class="logo">

    <div class="tagline">QuickClean: Clean Spaces, Happy Faces.</div>

    <a href="user_page.php" class="profile-link">
      <div class="profile">
        <?php
          $pic = $userData['profile_pic'];
          $path = "uploads/" . $pic;

          if (!empty($pic) && file_exists($path)) {
              echo '<img src="'.$path.'" 
                        style="width:40px;height:40px;border-radius:50%;object-fit:cover;" 
                        alt="Profile">';
          } else {
              echo htmlspecialchars(substr($userData['name'], 0, 1));
          }
        ?>
      </div>
    </a>

  </div>
</header>

<nav class="nav-bar">
  <ul class="nav-list">
    <li><a href="customer-home.php" class="nav-link <?php if($current_page=='customer-home.php'){echo 'active';} ?>">Home</a></li>
    <li><a href="servicepage.php" class="nav-link <?php if($current_page=='servicepage.php'){echo 'active';} ?>">Services</a></li>
    <li><a href="testimonial.php" class="nav-link <?php if($current_page=='testimonial.php'){echo 'active';} ?>">Testimonies</a></li>
    <li><a href="aboutus.php" class="nav-link <?php if($current_page=='aboutus.php'){echo 'active';} ?>">About Us</a></li>
  </ul>
</nav>


<!-- ABOUT US SECTION -->
<section class="about-section">
  <h2>ABOUT US</h2>
  <p>At <strong>QuickClean</strong>, we believe that a clean home is the foundation of comfort and well-being. Our mission is to make cleaning easy, reliable, and accessible for every household. We specialize in professional home cleaning and post-construction cleaning services tailored to your needs.</p>
  <p>Our team is trained, trustworthy, and passionate about delivering spotless results every time. With just a few clicks, you can book our services online and enjoy hassle-free scheduling.</p>
  <p>At QuickClean, we don’t just clean—we create fresh, welcoming spaces where you can truly relax and feel at home.</p>

  <h2>HISTORY</h2>
  <p>QuickClean was founded with a simple goal: to make home cleaning faster, easier, and more reliable for busy households. What started as a small idea to help families maintain clean and comfortable homes has grown into a trusted service platform, offering professional cleaning solutions for every need.</p>
  <p><strong>QuickClean: Clean Spaces, Happy Faces.</strong></p>
</section>

<!-- CONTACT SECTION -->
<section class="contact-section">
  <div class="contact-info">
    <h2>CONTACT US</h2>
    <p>Need a spotless home? Connect with QuickClean today. Cleanliness is our promise, and we’re always ready to serve you.</p>
    <div class="contact-details">
      <p>📞 +63 923456789</p>
      <p>📧 support@quickclean.com</p>
      <p>📍 123 Clean Street, Quezon City, Philippines</p>
    </div>
  </div>

  <div class="contact-form">
    <form>
      <input type="text" placeholder="Full Name" required>
      <input type="email" placeholder="Email" required>
      <input type="text" placeholder="Phone Number" required>
      <select required>
        <option value="">Services</option>
        <option value="Deep Cleaning">Deep Cleaning</option>
        <option value="Regular Cleaning">Regular Cleaning</option>
        <option value="Post-Construction Cleaning">Post-Construction Cleaning</option>
        <option value="Move-in/Move-out Cleaning">Move-in/Move-out Cleaning</option>
        <option value="Upholstery Cleaning">Upholstery Cleaning</option>
      </select>
      <button type="submit">Get Quote</button>
    </form>
  </div>
</section>

</body>
</html>
