<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "quickclean"; // change if your DB name is different

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST['email']);
  $pass = trim($_POST['password']);

  $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // compare plain passwords (for demo only — hash later)
    if ($pass === $user['password']) {
      $_SESSION['user_id'] = $user['user_id'];
      $_SESSION['name'] = $user['name'];
      $_SESSION['role'] = $user['role'];

      // redirect based on role
      if ($user['role'] == 'admin') {
        header("Location: admin-dashboard.html");
      } else {
        header("Location: customer-dashboard.html");
      }
      exit();
    } else {
      $message = "❌ Incorrect password!";
    }
  } else {
    $message = "❌ No account found with that email!";
  }

  $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - QuickClean</title>
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
      color: red;
      margin-bottom: 10px;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <div class="auth-container">
    <h2>Login</h2>

    <?php if ($message) echo "<div class='message'>$message</div>"; ?>

    <form method="POST" action="home.php">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <p>Don’t have an account? <a href="register.php">Register here</a></p>
  </div>
</body>
</html>
