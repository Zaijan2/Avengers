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

// --- HANDLE FORM SUBMISSION ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST["name"]);
  $email = trim($_POST["email"]);
  $password = $_POST["password"];
  $confirm = $_POST["confirm"];
  $address = trim($_POST["address"]);
  $contact = trim($_POST["contact"]);

  // Validation
  if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
    $error = "Please fill in all required fields.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email address.";
  } elseif ($password !== $confirm) {
    $error = "Passwords do not match.";
  } else {
    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM user WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
      $error = "Email already registered.";
    } else {
      // Hash password
      $hashed = password_hash($password, PASSWORD_DEFAULT);
      $role = "customer";

      // Insert into table
      $sql = "INSERT INTO user (name, email, password, address, contact_num, role)
              VALUES ('$name', '$email', '$hashed', '$address', '$contact', '$role')";

      if (mysqli_query($conn, $sql)) {
        $success = "Account created successfully! You can now <a href='login.php'>login</a>.";
      } else {
        $error = "Error: " . mysqli_error($conn);
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - QuickClean</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      background: #f0f4f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .auth-container {
      width: 100%;
      max-width: 400px;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
      text-align: center;
    }
    .auth-container h2 {
      color: #2E89F0;
      margin-bottom: 20px;
    }
    .auth-container form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    .auth-container input {
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }
    .auth-container button {
      padding: 12px;
      border: none;
      background: #FFDB58;
      color: #123;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.3s;
    }
    .auth-container button:hover {
      background: #ffe066;
    }
    .auth-container p {
      margin-top: 15px;
      font-size: 0.9rem;
    }
    .auth-container a {
      color: #2E89F0;
      text-decoration: none;
      font-weight: 600;
    }
    .auth-container a:hover {
      text-decoration: underline;
    }
    .message {
      font-size: 0.9rem;
      margin-bottom: 10px;
    }
    .error {
      color: #d9534f;
    }
    .success {
      color: #5cb85c;
    }
  </style>
</head>
<body>
  <div class="auth-container">
    <h2>Create an Account</h2>

    <?php if (!empty($error)): ?>
      <div class="message error"><?php echo $error; ?></div>
    <?php elseif (!empty($success)): ?>
      <div class="message success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="address" placeholder="Address (optional)">
      <input type="text" name="contact" placeholder="Contact Number (optional)">
      <input type="password" name="password" placeholder="Password" minlength="6" required>
      <input type="password" name="confirm" placeholder="Confirm Password" required>
      <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
  </div>
</body>
</html>
