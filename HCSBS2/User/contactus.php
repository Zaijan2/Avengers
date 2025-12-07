<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QuickClean - Contact Us</title>
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

  /* Reset & base */
  * { box-sizing: border-box; }
  html,body { height:100%; }
  body{
    margin:0;
    font-family: "Poppins", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    color:#123;
    -webkit-font-smoothing:antialiased;
    -moz-osx-font-smoothing:grayscale;
    background: #ffffff;
    line-height:1.4;
  }

  /* Header */
  .site-header{
    background: var(--brand-blue);
    height: var(--header-height);
    display:flex;
    align-items:center;
    width:100%;
    box-shadow: 0 1px 0 rgba(0,0,0,0.04);
  }

  .header-inner{
    width:100%;
    max-width: var(--max-content-width);
    margin:0 auto;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding: 12px 24px;
  }

  .logo{
    height:75px;
    width:auto;
    display:block;
  }

  .header-center{
    flex:1;
    display:flex;
    align-items:center;
    justify-content:center;
  }

  .tagline{
    font-family: "Baloo 2", "Poppins", sans-serif;
    font-weight:600;
    font-size:20px;
    color: #fff;
    letter-spacing:0.3px;
  }
  .profile {
  width:50px;
  height:50px;
  border-radius:70%;
  object-fit:cover;
  box-shadow: 0 2px 6px rgba(0,0,0,0.15);
  background: #fff;
  display:block;
  border: 3px solid rgba(255,255,255,0.4);
}

 
  /* Navigation bar */
  .nav-bar{
    background: var(--nav-yellow);
    height: var(--nav-height);
    display:flex;
    align-items:center;
    width:100%;
    box-shadow: 0 1px 0 rgba(0,0,0,0.06);
  }

  /* Center nav and prevent scrolling. Allow wrap on small screens. */
  .nav-list{
    width:100%;
    max-width: var(--max-content-width);
    margin:0 auto;
    padding:0;
    display:flex;
    list-style:none;
    align-items:center;
    justify-content:center; /* centered under tagline */
    gap:30px;
    flex-wrap:wrap; /* wrap to additional lines when needed */
  }

  .nav-link{
    text-decoration:none;
    color: var(--nav-link-color);
    font-weight:600;
    font-size:18px;
    padding:8px 4px;
    white-space:nowrap;
  }

  .nav-link.active{
    text-decoration: underline;
    text-underline-offset:6px;
    font-weight:800;
  }

  a:focus, button:focus {
    outline:3px solid rgba(46,137,240,0.18);
    outline-offset:3px;
  }

    /* ===== CONTACT SECTION ===== */
    .contact-section {
      max-width: var(--max-width);
      margin: 50px auto;
      padding: 0 20px;
      display: flex;
      flex-wrap: wrap;
      gap: 40px;
      align-items: flex-start;
      justify-content: center;
    }

    .contact-info {
      flex: 1 1 300px;
    }

    .contact-info h2 {
      color: var(--text-blue);
      font-size: 1.8rem;
      text-align: center;
      margin-bottom: 20px;
    }

    .contact-info p {
      margin-bottom: 20px;
      color: var(--text-blue);
      text-align: center;
    }

    .contact-details {
      display: flex;
      flex-direction: column;
      gap: 12px;
      text-align: center;
      font-size: 0.95rem;
    }

    .contact-details i {
      margin-right: 6px;
      color: var(--text-blue);
    }

    /* Form container */
    .contact-form {
      flex: 1 1 350px;
      background: #4da3ff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .contact-form form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .contact-form input,
    .contact-form select {
      padding: 12px 15px;
      border-radius: 6px;
      border: none;
      background: #fff;
      font-size: 1rem;
      color: var(--text-blue);
    }

   .contact-form button {
  padding: 8px 20px;              /* smaller height & width */
  border: none;
  background: var(--nav-yellow);
  color: var(--text-blue);
  font-weight: 700;
  cursor: pointer;
  border-radius: 25px;             /* circular sides */
  transition: background 0.3s ease;
  align-self: center;              /* centers button in form */
  font-size: 0.9rem;               /* slightly smaller text */
  width: auto;                     /* shrink to content */
}

.contact-form button:hover {
  background: #ffe066;
}

   
    @media (max-width: 768px) {
      .tagline {
        font-size: 0.9rem;
      }
      .navbar ul {
        gap: 20px;
      }
      .contact-section {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>
<body>
  <!-- Top header -->
  <header class="site-header" role="banner">
    <div class="header-inner">
      <div class="header-left">
        <img src="logo.png" alt="Quickclean logo" class="logo">
      </div>

      <div class="header-center" aria-hidden="false">
        <span class="tagline">QuickClean: Clean Spaces, Happy Faces.</span>
      </div>

     <div class="header-right">
  <img src="https://cdn-icons-png.flaticon.com/512/1077/1077063.png" alt="User Icon" class="profile">
</div>

    </div>
  </header>

  <!-- NAV -->
<nav class="nav-bar">
  <ul class="nav-list">
    <li><a href="customer-home.php" class="nav-link active">Home</a></li>
    <li><a href="servicepage.php" class="nav-link">Services</a></li>
    <li><a href="testimonial.php" class="nav-link">Testimonies</a></li>
    <li><a href="aboutus.php" class="nav-link">About Us</a></li>
    <li><a href="contactus.php" class="nav-link">Contact Us</a></li>
    <li><a href="logout.php" class="nav-link">Logout</a></li>
  </ul>
</nav>

  <!-- CONTACT SECTION -->
  <section class="contact-section">
    <div class="contact-info">
      <h2>CONTACT US</h2>
      <p>
        Need a spotless home? Connect with QuickClean today.
        Cleanliness is our promise, and we’re always ready to serve you.
      </p>
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