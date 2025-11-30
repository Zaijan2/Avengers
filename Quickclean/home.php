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
.site-header{background:#6DAFF2;height:110px;display:flex;align-items:center;justify-content:space-between;padding:0 24px;}
.logo{height:75px;}
.tagline{color:#fff;font-weight:600;font-size:20px;}

/* Nav */
.nav-bar{background:#FFDB58;height:64px;display:flex;align-items:center;}
.nav-list{display:flex;justify-content:center;gap:30px;list-style:none;width:100%;margin:0;padding:0;}
.nav-link{text-decoration:none;color:#0b3b66;font-weight:600;}
.nav-link.active{text-decoration:underline;font-weight:700;}

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
.book-btn{margin-top:10px;padding:10px 20px;background:#FFD54A;border:none;border-radius:25px;cursor:pointer;font-weight:600;display:inline-block;}

/* Testimonials */
.testimonials{padding:60px 40px;background:#fff;}
.testimonials h2{text-align:center;color:#2E89F0;margin-bottom:40px;font-size:32px;}
.testimonial{background:#f0f4f5;padding:20px;margin-bottom:20px;border-radius:8px;}
.testimonial p{font-style:italic;margin-bottom:10px;}
.testimonial .author{font-weight:600;}

/* Contact Section */
.contact-section{padding:60px 40px;background:#F9F9F9;}
.contact-section h2{text-align:center;color:#2E89F0;margin-bottom:20px;font-size:32px;}
.contact-details{max-width:500px;margin:0 auto;text-align:center;margin-bottom:30px;}
.contact-details p{margin:6px 0;font-weight:500;}
.contact-form{max-width:500px;margin:0 auto;}
.contact-form input, .contact-form select, .contact-form button{width:100%;padding:10px;margin-bottom:10px;border-radius:6px;border:1px solid #ccc;}
.contact-form button{background:#2E89F0;color:#fff;border:none;cursor:pointer;font-weight:600;}
.contact-form button:hover{background:#1c6ed6;}

/* About Section */
.about-section{padding:60px 40px;background:#fff;}
.about-section h2{text-align:center;color:#2E89F0;margin-bottom:20px;font-size:32px;}
.about-section p{max-width:800px;margin:0 auto 15px;line-height:1.6;text-align:center;}
</style>
</head>
<body>

<!-- HEADER -->
<header class="site-header">
  <img src="logo.png" alt="QuickClean logo" class="logo">
  <div class="tagline">QuickClean: Clean Spaces, Happy Faces.</div>
</header>

<!-- NAV -->
<nav class="nav-bar">
  <ul class="nav-list">
    <li><a href="index.php" class="nav-link active">Home</a></li>
    <li><a href="servicepage.php" class="nav-link">Services</a></li>
    <li><a href="testimonial.php" class="nav-link">Testimonies</a></li>
    <li><a href="aboutus.php" class="nav-link">About Us</a></li>
    <li><a href="contactus.php" class="nav-link">Contact Us</a></li>
    <li><a href="login.php" class="nav-link">Login</a></li>
  </ul>
</nav>

<!-- HERO -->
<section class="hero">
  <h1>Welcome to QuickClean</h1>
  <p>Turn your home or office into a spotless haven of comfort and cleanliness.</p>
  <button class="cta-btn">Book Now</button>
</section>

<!-- SERVICES -->
<section class="services-section">
  <h2>Most Popular Services</h2>
  <div class="services-grid">
    <?php if($services && $services->num_rows > 0): ?>
      <?php while($row = $services->fetch_assoc()): ?>
        <div class="service-card">
          <img src="Admin/uploads/<?= htmlspecialchars($row['image'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($row['service_name']) ?>">
          <div class="service-card-content">
            <h3><?= htmlspecialchars($row['service_name']) ?></h3>
            <p><?= htmlspecialchars($row['description']) ?></p>
            <div class="price">₱<?= number_format($row['price'],2) ?></div>
            <a href="login.php"><button class="book-btn">Book Now</button></a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="text-align:center;">No services available at the moment.</p>
    <?php endif; ?>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="testimonials">
  <h2>What Our Clients Say</h2>
  <div class="testimonial">
    <p>“QuickClean saved me so much time! Unlike other cleaners who rush the job, their team really pays attention to every detail.”</p>
    <span class="author">— Angela M.</span>
  </div>
  <div class="testimonial">
    <p>“I'm beyond satisfied with the outcome of QuickClean's service. The team was professional, thorough, and very focused on their work.”</p>
    <span class="author">— Robert D.</span>
  </div>
  <div class="testimonial">
    <p>“10/10 for dedication and consistency! The cleaners worked quietly but efficiently.”</p>
    <span class="author">— Stephanie L.</span>
  </div>
</section>

<!-- CONTACT SECTION -->
<section class="contact-section">
  <h2>Contact Us</h2>
  <div class="contact-details">
    <p>📞 +63 923456789</p>
    <p>📧 support@quickclean.com</p>
    <p>📍 123 Clean Street, Quezon City, Philippines</p>
  </div>
  <div class="contact-form">
    <form>
      <input type="text" placeholder="Full Name" required>
      <input type="email" placeholder="Email" required>
      <input type="text" placeholder="Phone Number" required>
      <select required>
        <option value="">Select Service</option>
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

<!-- ABOUT US -->
<section class="about-section">
  <h2>About Us</h2>
  <p>At <strong>QuickClean</strong>, we believe that a clean home is the foundation of comfort and well-being. Our mission is to make cleaning easy, reliable, and accessible for every household.</p>
  <p>We specialize in professional home and post-construction cleaning services designed to fit your needs.</p>
  <h2>History</h2>
  <p>QuickClean was founded with a simple goal: to make home cleaning faster, easier, and more reliable for busy households. What started as a small idea has grown into a trusted service platform.</p>
</section>

</body>
</html>
<?php $conn->close(); ?>
