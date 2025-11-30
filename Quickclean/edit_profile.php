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
<html>
<head>
  <meta charset="utf-8">
  <title>Edit Profile</title>
  <style>
    body{font-family:Arial;background:#f4f6f8;margin:0}
    .wrap{max-width:700px;margin:24px auto;padding:20px;background:#fff;border-radius:8px}
    label{display:block;margin-top:10px;font-weight:600}
    input, textarea{width:100%;padding:8px;margin-top:6px;border:1px solid #ccc;border-radius:6px}
    button{margin-top:12px;padding:10px 14px;background:#2E89F0;color:#fff;border:none;border-radius:6px}
    img.preview{width:100px;height:100px;border-radius:50%;object-fit:cover}
  </style>
</head>
<body>
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
