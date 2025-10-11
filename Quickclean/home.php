<?php
// --- DATABASE CONNECTION ---
$host = "localhost";
$user = "root";
$pass = "";
$db   = "quickclean"; // change this to your database name

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


// Fetch active services
$services = $conn->query("SELECT * FROM services WHERE status='active'");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>QuickClean - Home</title>

  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;800&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

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

  * { box-sizing: border-box; }
  html,body { height:100%; margin:0; font-family:"Poppins",sans-serif; background:#fff; color:#123; }

  /* HEADER */
  .site-header{ background:var(--brand-blue); height:var(--header-height); display:flex; align-items:center; }
  .header-inner{ width:100%; max-width:var(--max-content-width); margin:0 auto; display:flex; align-items:center; justify-content:space-between; padding:12px 24px; }
  .logo{ height:75px; }
  .tagline{ font-family:"Baloo 2"; color:#fff; font-weight:600; font-size:20px; }
  .profile{ width:50px; height:50px; border-radius:50%; background:#fff; }

  /* NAV */
  .nav-bar{ background:var(--nav-yellow); height:var(--nav-height); display:flex; align-items:center; }
  .nav-list{ display:flex; justify-content:center; gap:30px; list-style:none; width:100%; margin:0; padding:0; }
  .nav-link{ color:var(--nav-link-color); text-decoration:none; font-weight:600; font-size:18px; }
  .nav-link.active{ text-decoration:underline; text-underline-offset:6px; font-weight:800; }

  /* HERO */
  .hero{ background:var(--muted-bg); padding:80px 24px; text-align:center; }
  .hero h1{ font-family:"Baloo 2"; color:var(--text-blue); font-size:72px; margin-bottom:20px; }
  .hero p{ font-size:18px; color:#1b4d7a; margin-bottom:30px; }
  .cta-btn{ background:var(--cta-yellow); border:none; padding:12px 24px; border-radius:25px; font-weight:700; cursor:pointer; }

  /* ADS SLIDER */
  .ads-slider {
    position: relative;
    width: 100%;
    max-width: var(--max-content-width);
    margin: 40px auto;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
  }
  .ads-slide { display: none; position: relative; }
  .ads-slide img {
    width: 100%;
    height: 450px;
    object-fit: cover;
  }
  .ads-caption {
    position: absolute;
    bottom: 20px;
    left: 30px;
    background: rgba(0,0,0,0.5);
    color: #fff;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 18px;
  }

  /* SERVICES */
  .services-section { padding: 60px 40px; background: #f9fafb; }
  .services-grid {
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap:30px;
    max-width:var(--max-content-width);
    margin:0 auto;
  }
  .service-card {
    background:#fff;
    border-radius:12px;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
    overflow:hidden;
    transition:transform 0.2s;
  }
  .service-card:hover { transform:translateY(-4px); }
  .service-card img{ width:100%; height:200px; object-fit:cover; }
  .service-card-content{ padding:20px; }
  .service-card-content h3{ color:var(--text-blue); margin-bottom:10px; }
  .service-card-content p{ font-size:15px; color:#333; }
  .price{ font-weight:600; color:#2E89F0; margin-top:10px; }
  </style>
</head>
<body>

  <!-- HEADER -->
  <header class="site-header">
    <div class="header-inner">
      <img src="logo.png" alt="QuickClean logo" class="logo">
      <div class="tagline">QuickClean: Clean Spaces, Happy Faces.</div>
      <img src="https://cdn-icons-png.flaticon.com/512/1077/1077063.png" class="profile">
    </div>
  </header>

  <!-- NAV -->
  <nav class="nav-bar">
    <ul class="nav-list">
      <li><a href="homepage.php" class="nav-link active">Home</a></li>
      <li><a href="servicepage.php" class="nav-link">Services</a></li>
      <li><a href="testimonial.php" class="nav-link">Testimonies</a></li>
      <li><a href="aboutus.php" class="nav-link">About Us</a></li>
      <li><a href="contactus.php" class="nav-link">Contact Us</a></li>
    </ul>
  </nav>

  <!-- HERO (No hardcoded images here) -->
  <section class="hero">
    <h1>Welcome to QuickClean</h1>
    <p>Turn your home or office into a spotless haven of comfort and cleanliness.</p>
    <button class="cta-btn">BOOK NOW</button>
  </section>


  <!-- SERVICES -->
  <section class="services-section">
    <h2 style="text-align:center;color:#2E89F0;font-family:'Baloo 2';margin-bottom:40px;">Our Services</h2>
    <div class="services-grid">
      <?php if ($services && $services->num_rows > 0): ?>
        <?php while($row = $services->fetch_assoc()): ?>
          <div class="service-card">
           <img src="Admin/uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['service_name']); ?>">

            <div class="service-card-content">
              <h3><?php echo htmlspecialchars($row['service_name']); ?></h3>
              <p><?php echo htmlspecialchars($row['description']); ?></p>
              <p class="price">₱<?php echo htmlspecialchars($row['price']); ?></p>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p style="text-align:center;">No services available at the moment.</p>
      <?php endif; ?>
    </div>
  </section>

  <script>
  // Automatic Ad Slider
  let slideIndex = 0;
  showSlides();

  function showSlides() {
    let slides = document.getElementsByClassName("ads-slide");
    for (let i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
    }
    slideIndex++;
    if (slideIndex > slides.length) { slideIndex = 1; }
    if (slides.length > 0) slides[slideIndex-1].style.display = "block";
    setTimeout(showSlides, 4000); // change every 4 seconds
  }
  </script>
</body>
</html>
<?php $conn->close(); ?>
