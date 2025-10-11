<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>QuickClean - Testimonials</title>

  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;800&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

  <style>
  :root {
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
  html, body { height:100%; margin:0; }
  body {
    font-family: "Poppins", sans-serif;
    background: #ffffff;
    color:#123;
    line-height:1.5;
  }

  /* Header */
  .site-header {
    background: var(--brand-blue);
    height: var(--header-height);
    display:flex;
    align-items:center;
    width:100%;
  }
  .header-inner {
    max-width: var(--max-content-width);
    margin:0 auto;
    width:100%;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding: 12px 24px;
  }
  .logo {
    height:75px;
    width:auto;
  }
  .tagline {
    font-family:"Baloo 2", cursive;
    color:white;
    font-size:20px;
    font-weight:600;
  }
  .profile {
    width:50px;
    height:50px;
    border-radius:50%;
    object-fit:cover;
    background:#fff;
    border:3px solid rgba(255,255,255,0.4);
    box-shadow:0 2px 6px rgba(0,0,0,0.15);
  }

  /* Navigation */
  .nav-bar {
    background: var(--nav-yellow);
    height: var(--nav-height);
    display:flex;
    align-items:center;
    justify-content:center;
  }
  .nav-list {
    list-style:none;
    display:flex;
    gap:30px;
    margin:0;
    padding:0;
    flex-wrap:wrap;
  }
  .nav-link {
    color: var(--nav-link-color);
    text-decoration:none;
    font-weight:600;
    font-size:18px;
  }
  .nav-link.active {
    text-decoration:underline;
    text-underline-offset:6px;
    font-weight:800;
  }

  /* Testimonials */
  .testimonials {
    max-width: 1100px;
    margin: 0 auto;
    padding: 60px 20px;
    text-align: center;
  }
  .testimonials h2 {
    font-family:"Baloo 2", cursive;
    font-size:32px;
    color: var(--text-blue);
    margin-bottom: 30px;
  }
  .testimonial {
    background:#E9F5FF;
    border-left:5px solid var(--text-blue);
    border-radius:8px;
    padding:25px 30px;
    margin:20px auto;
    max-width:900px;
    text-align:left;
    font-size:16px;
    color:#0b3b66;
  }
  .testimonial .author {
    font-weight:600;
    text-align:right;
    margin-top:12px;
    color:#0b3b66;
  }
  </style>
</head>

<body>
  <!-- HEADER -->
  <header class="site-header">
    <div class="header-inner">
      <img src="logo.png" alt="QuickClean logo" class="logo">
      <div class="tagline">QuickClean: Clean Spaces, Happy Faces.</div>
      <img src="https://cdn-icons-png.flaticon.com/512/1077/1077063.png" alt="User profile" class="profile">
    </div>
  </header>

  <!-- NAVIGATION -->
  <nav class="nav-bar">
    <ul class="nav-list">
      <li><a href="home.php" class="nav-link">Home</a></li>
      <li><a href="servicepage.php" class="nav-link">Services</a></li>
      <li><a href="testimonial.php" class="nav-link active">Testimonies</a></li>
      <li><a href="aboutus.php" class="nav-link">About Us</a></li>
      <li><a href="contactus.php" class="nav-link">Contact Us</a></li>
    </ul>
  </nav>

  <!-- TESTIMONIALS -->
  <section class="testimonials">
    <h2>What Our Clients Say</h2>

    <div class="testimonial">
      <p>“QuickClean saved me so much time! Unlike other cleaners who rush the job, their team really pays attention to every detail. From now on, QuickClean will be our go-to home cleaning service. Thank you so much!”</p>
      <span class="author">— Angela M.</span>
    </div>

    <div class="testimonial">
      <p>“I'm beyond satisfied with the outcome of QuickClean's service. The team was professional, thorough, and very focused on their work. Every corner of the house was spotless, and it truly exceeded my expectations. Definitely a 5-star experience—highly recommended!”</p>
      <span class="author">— Robert D.</span>
    </div>

    <div class="testimonial">
      <p>“10/10 for dedication and consistency! The cleaners worked quietly but efficiently, making sure our home looked brand new after a stressful renovation. We've tried other services before, but QuickClean went above and beyond what we expected.”</p>
      <span class="author">— Stephanie L.</span>
    </div>
  </section>
</body>
</html>
