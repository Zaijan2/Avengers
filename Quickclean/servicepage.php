<?php

// Database connection
$host = "localhost";
$user = "root"; // default for XAMPP
$pass = "";     // leave blank if no password
$db   = "quickclean"; // your database name

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Fetch services from database
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
    html,body { height:100%; }
    body {
      margin:0;
      font-family: "Poppins", sans-serif;
      background:#ffffff;
      color:#123;
      line-height:1.4;
    }

    /* Header */
    .site-header {
      background: var(--brand-blue);
      height: var(--header-height);
      display:flex;
      align-items:center;
      justify-content:center;
      width:100%;
      box-shadow:0 1px 0 rgba(0,0,0,0.04);
    }
    .header-inner {
      width:100%;
      max-width: var(--max-content-width);
      margin:0 auto;
      display:flex;
      align-items:center;
      justify-content:space-between;
      padding:12px 24px;
    }
    .logo {
      height:75px;
      width:auto;
    }
    .tagline {
      font-weight:600;
      font-size:20px;
      color:#fff;
    }
    .profile {
      width:50px;
      height:50px;
      border-radius:50%;
      object-fit:cover;
      background:#fff;
      border:3px solid rgba(255,255,255,0.4);
    }

    /* Nav */
    .nav-bar {
      background: var(--nav-yellow);
      height: var(--nav-height);
      display:flex;
      align-items:center;
      width:100%;
      box-shadow: 0 1px 0 rgba(0,0,0,0.06);
    }
    .nav-list {
      width:100%;
      max-width: var(--max-content-width);
      margin:0 auto;
      display:flex;
      justify-content:center;
      list-style:none;
      gap:30px;
      flex-wrap:wrap;
      padding:0;
    }
    .nav-link {
      text-decoration:none;
      color:var(--nav-link-color);
      font-weight:600;
      font-size:18px;
    }
    .nav-link.active {
      text-decoration:underline;
      font-weight:700;
    }

    /* Services */
    .services-section {
      padding: 50px 20px;
    }
    .services-inner {
      max-width: var(--max-content-width);
      margin: 0 auto;
      text-align: center;
    }
    .services-section h2 {
      font-size: 2rem;
      color: var(--text-blue);
      margin-bottom: 10px;
      font-weight:700;
    }
    .services-subtext {
      max-width: 760px;
      margin: 0 auto 30px;
      color: #444;
    }
    .services-grid {
      display:grid;
      grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));
      gap:20px;
    }
    .service-card {
      background:#fff;
      border-radius:12px;
      padding:20px;
      box-shadow:0 6px 18px rgba(0,0,0,0.06);
      transition: transform 0.25s, box-shadow 0.25s;
      text-align:center;
    }
    .service-card:hover {
      transform:translateY(-6px);
      box-shadow:0 18px 40px rgba(0,0,0,0.12);
    }
    .service-card img {
      width:100%;
      height:180px;
      object-fit:cover;
      border-radius:8px;
      margin-bottom:10px;
    }
    .service-card h3 {
      color: var(--text-blue);
      margin-bottom: 6px;
    }
    .service-card p {
      color:#555;
      font-size:0.95rem;
      min-height:60px;
    }
    .service-price {
      color:#e6b93a;
      font-weight:700;
      margin-top:10px;
      font-size:1.1rem;
    }

    /* CTA */
    .services-cta {
      display:flex;
      justify-content:center;
      gap:18px;
      margin-top:30px;
      flex-wrap:wrap;
    }
    .btn-book {
      background: var(--nav-yellow);
      border:none;
      padding:12px 22px;
      border-radius:28px;
      font-weight:700;
      cursor:pointer;
      transition:background 0.3s ease, transform 0.3s ease;
    }
    .btn-book:hover {
      background:#e6c73b;
      transform:translateY(-2px);
    }
    .hotline {
      display:flex;
      align-items:center;
      gap:6px;
      font-size:0.95rem;
    }
    .hotline strong { color: var(--text-blue); }

    footer {
      text-align:center;
      padding:18px;
      background:#f6f7f8;
      color:#556;
      margin-top:40px;
    }
  </style>
</head>
<body>
  <header class="site-header">
    <div class="header-inner">
      <img src="logo.png" alt="QuickClean logo" class="logo">
      <span class="tagline">QuickClean: Clean Spaces, Happy Faces.</span>
      <img src="https://cdn-icons-png.flaticon.com/512/1077/1077063.png" class="profile" alt="User">
    </div>
  </header>

  <nav class="nav-bar">
    <ul class="nav-list">
      <li><a href="home.php" class="nav-link">Home</a></li>
      <li><a href="servicepage.php" class="nav-link active">Services</a></li>
      <li><a href="testimonial.php" class="nav-link">Testimonies</a></li>
      <li><a href="aboutus.php" class="nav-link">About Us</a></li>
      <li><a href="contactus.php" class="nav-link">Contact Us</a></li>
    </ul>
  </nav>

  <section class="services-section">
    <div class="services-inner">
      <h2>What We Offer</h2>
      <p class="services-subtext">Our expert cleaning team ensures every corner shines with care and precision.</p>

      <div class="services-grid">
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <div class="service-card">
              <img src="Admin/uploads/<?= htmlspecialchars($row['image'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($row['service_name']) ?>">
              <h3><?= htmlspecialchars($row['service_name']) ?></h3>
              <p><?= htmlspecialchars($row['description']) ?></p>
              <div class="service-price">₱<?= number_format($row['price'], 2) ?></div>
              
              <!-- ✅ CHANGED THIS PART ONLY -->
              <a href="customer-booking.php?service_id=<?= $row['service_id'] ?>">
                <button class="btn-book">BOOK NOW</button>
              </a>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p>No services available yet. Please check back later.</p>
        <?php endif; ?>
      </div>

      <div class="services-cta">
        <a href="customer-booking.php"><button class="btn-book">BOOK NOW</button></a>
        <div class="hotline">Call our hotline <strong>0923456789</strong></div>
      </div>
    </div>
  </section>

  <footer>© 2025 QuickClean. All Rights Reserved.</footer>
</body>
</html>
