<?php
session_start();

// ✅ Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "quickclean";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// ✅ PDO configuration (for insert/query)
$dbHost = "localhost";
$dbPort = 3306;
$dbName = "quickclean";
$dbUser = "root";
$dbPass = "";

function getPdo() {
  global $dbHost, $dbPort, $dbName, $dbUser, $dbPass;
  $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $dbHost, $dbPort, $dbName);
  $opts = array(
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
  );
  return new PDO($dsn, $dbUser, $dbPass, $opts);
}

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// ---------- Handle POST ----------
$errors = array();
$values = array(
  'name' => '',
  'email' => '',
  'phone' => '',
  'address' => '',
  'password' => '',
  'confirmPassword' => '',
  'company' => '',
  'terms' => ''
);
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $values['name'] = trim($_POST['name'] ?? '');
  $values['email'] = trim($_POST['email'] ?? '');
  $values['phone'] = trim($_POST['phone'] ?? '');
  $values['address'] = trim($_POST['address'] ?? '');
  $values['password'] = $_POST['password'] ?? '';
  $values['confirmPassword'] = $_POST['confirmPassword'] ?? '';
  $values['company'] = trim($_POST['company'] ?? '');
  $values['terms'] = isset($_POST['terms']) ? '1' : '';

  // ✅ Validation
  if ($values['name'] === '') $errors['name'] = 'Enter your full name.';
  if ($values['email'] === '' || !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Enter a valid email.';
  if ($values['phone'] === '' || !preg_match('/^\+?\d[\d\s\-]{7,}$/', $values['phone'])) $errors['phone'] = 'Enter a valid phone number.';
  if ($values['password'] === '') $errors['password'] = 'Enter your password.';
  if ($values['password'] !== $values['confirmPassword']) $errors['confirmPassword'] = 'Passwords must match.';
  if (empty($values['terms'])) $errors['terms'] = 'You must agree to terms and policies.';

  // ✅ If no errors, insert into DB
  if (empty($errors)) {
    try {
      $pdo = getPdo();

      // Check for duplicate email
      $check = $pdo->prepare("SELECT user_id FROM user WHERE email = :email");
      $check->execute([':email' => $values['email']]);
      if ($check->fetch()) {
        $errors['email'] = 'Email already exists.';
      } else {
        $hash = password_hash($values['password'], PASSWORD_DEFAULT);

        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO user (name, email, password, address, contact_num, role) 
                               VALUES (:name, :email, :password, :address, :phone, :role)");
        $stmt->execute([
          ':name' => $values['name'],
          ':email' => $values['email'],
          ':password' => $hash,
          ':address' => $values['address'],
          ':phone' => $values['phone'],
          ':role' => 'customer'
        ]);
        $success = true;
      }
    } catch (Exception $e) {
      $errors['form'] = "Database error: " . $e->getMessage();
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>QuickClean — Register</title>
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;800&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
  :root{
    --brand-blue:#66B7F0;--nav-yellow:#FFDB58;--cta-yellow:#FFD54A;
    --text-blue:#2E89F0;--bg:#f6f8fa;--muted:#6b7b8c;
    --card:#fff;--radius:12px;--max-w:1100px;
  }
  *{box-sizing:border-box}
  html,body{height:100%}
  body{margin:0;font-family:"Poppins";background:var(--bg);color:#0f2a3a;}
  .qc-header{background:var(--brand-blue);color:#fff;}
  .qc-header-inner{max-width:var(--max-w);margin:0 auto;display:flex;align-items:center;gap:12px;padding:12px 20px}
  .qc-logo{width:56px;height:56px;border-radius:8px}
  .qc-tagline{font-family:"Baloo 2";font-size:18px;text-align:center;flex:1}
  .qc-nav{background:var(--nav-yellow);}
  .qc-nav ul{max-width:var(--max-w);margin:0 auto;padding:8px 20px;display:flex;gap:18px;list-style:none;justify-content:center}
  .qc-nav a{color:var(--text-blue);font-weight:700;text-decoration:none;}
  .container{max-width:var(--max-w);margin:28px auto;padding:0 20px}
  .card{background:var(--card);border-radius:var(--radius);padding:22px;box-shadow:0 0 8px rgba(0,0,0,0.1)}
  label{display:block;font-weight:700;margin-bottom:6px}
  .form-row{margin-bottom:14px}
  .input{width:100%;padding:10px 12px;border-radius:10px;border:1px solid #dfe6ee;}
  .btn{border:0;padding:10px 14px;border-radius:10px;font-weight:700;cursor:pointer}
  .btn-primary{background:var(--text-blue);color:#fff}
  .err{color:#b21b1b;font-size:14px;margin-top:4px}
  </style>
</head>
<body>
  <header class="qc-header">
    <div class="qc-header-inner">
      <img src="assets/logo.png" class="qc-logo">
      <div class="qc-tagline">QuickClean: Clean Spaces, Happy Faces.</div>
    </div>
    <nav class="qc-nav">
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#" class="active">Services</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </nav>
  </header>

  <main class="container">
    <div class="card">
      <h1>Create your QuickClean account</h1>

      <?php if (!empty($errors['form'])): ?>
        <div class="err"><?= h($errors['form']); ?></div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div style="background:#e9fbff;padding:15px;border-radius:10px;">
          <h3>Registration Successful!</h3>
          <p>Welcome, <?= h($values['name']); ?>. You can now <a href="login.php">sign in</a>.</p>
        </div>
      <?php else: ?>
      <form method="post">
        <div class="form-row">
          <label>Full Name</label>
          <input name="name" class="input" value="<?= h($values['name']); ?>">
          <div class="err"><?= $errors['name'] ?? ''; ?></div>
        </div>
        <div class="form-row">
          <label>Email</label>
          <input name="email" class="input" type="email" value="<?= h($values['email']); ?>">
          <div class="err"><?= $errors['email'] ?? ''; ?></div>
        </div>
        <div class="form-row">
          <label>Phone</label>
          <input name="phone" class="input" type="text" value="<?= h($values['phone']); ?>">
          <div class="err"><?= $errors['phone'] ?? ''; ?></div>
        </div>
        <div class="form-row">
          <label>Address</label>
          <input name="address" class="input" value="<?= h($values['address']); ?>">
        </div>
        <div class="form-row">
          <label>Password</label>
          <input name="password" class="input" type="password">
          <div class="err"><?= $errors['password'] ?? ''; ?></div>
        </div>
        <div class="form-row">
          <label>Confirm Password</label>
          <input name="confirmPassword" class="input" type="password">
          <div class="err"><?= $errors['confirmPassword'] ?? ''; ?></div>
        </div>
        <div class="form-row">
          <label><input type="checkbox" name="terms" <?= !empty($values['terms']) ? 'checked' : ''; ?>> I agree to terms</label>
          <div class="err"><?= $errors['terms'] ?? ''; ?></div>
        </div>
        <button class="btn btn-primary">Register</button>
      </form>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>
