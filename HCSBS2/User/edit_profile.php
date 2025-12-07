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

// fetch current values
$stmt = $conn->prepare("SELECT name, email, address, contact_num, profile_pic FROM user WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Profile</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;800&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

  <style>
    /* --- DESIGN STYLES (NAV BAR & HEADER) --- */
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

    /* --- PAGE CONTENT STYLES (Modified for margin) --- */
    .wrap{
        max-width:700px;
        margin:40px auto; /* Increased top margin to move content below header/nav */
        padding:30px; 
        background:#fff; 
        border-radius:12px; /* Smoother border-radius */
        box-shadow: 0 3px 8px rgba(0,0,0,0.06);
    }
    /* Updated form styles to use theme colors */
    label{display:block;margin-top:10px;font-weight:600}
    input, textarea{width:100%;padding:10px;margin-top:6px;border:1px solid #ccc;border-radius:6px; font-size: 16px;}
    button{
        margin-top:20px;
        padding:12px 20px;
        background:var(--text-blue);
        color:#fff;
        border:none;
        border-radius:6px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    button:hover { background: #1a6cb3; }
    img.preview{width:100px;height:100px;border-radius:50%;object-fit:cover; margin-bottom: 10px;}
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

<div class="wrap">
  <h2>Edit Profile</h2>
  <form method="post" action="update_profile.php" enctype="multipart/form-data">
    <label>Name</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

    <label>Address</label>
    <textarea name="address"><?php echo htmlspecialchars($user['address']); ?></textarea>

    <label>Contact Number</label>
    <input type="text" name="contact_num" value="<?php echo htmlspecialchars($user['contact_num']); ?>">

    <label>Profile Picture (optional)</label>
    <?php if (!empty($user['profile_pic']) && file_exists("uploads/".$user['profile_pic'])): ?>
      <div><img src="uploads/<?php echo htmlspecialchars($user['profile_pic']); ?>" class="preview" alt="pic"></div>
    <?php endif; ?>
    <input type="file" name="profile_pic" accept="image/*">

    <button type="submit">Save Profile</button>
  </form>
</div>
</body>
</html>