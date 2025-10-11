<?php 
// ------------------ DATABASE CONNECTION ------------------
$host = "localhost";
$user = "root"; // default for XAMPP
$pass = "";     // leave blank if no password
$db   = "quickclean"; // your database name

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// SHOW ALL ERRORS (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ------------------ FETCH DATA FOR DASHBOARD ------------------

// Count total users
$total_users = 0;
$result_users = $conn->query("SELECT COUNT(*) AS total FROM user");
if ($result_users && $row = $result_users->fetch_assoc()) {
  $total_users = $row['total'];
}

// Count total services
$total_services = 0;
$result_services = $conn->query("SELECT COUNT(*) AS total FROM services");
if ($result_services && $row = $result_services->fetch_assoc()) {
  $total_services = $row['total'];
}

// Count total messages
$total_messages = 0;
$result_messages = $conn->query("SELECT COUNT(*) AS total FROM contact_messages");
if ($result_messages && $row = $result_messages->fetch_assoc()) {
  $total_messages = $row['total'];
}

// Sum total revenue
$total_revenue = 0;
$result_revenue = $conn->query("SELECT SUM(amount) AS total FROM payments");
if ($result_revenue && $row = $result_revenue->fetch_assoc()) {
  $total_revenue = $row['total'] ?? 0;
}

// Fetch recent users
$recent_users = $conn->query("SELECT name, email, date_created FROM user ORDER BY date_created DESC LIMIT 5");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - QuickClean</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: "Poppins", sans-serif;
      display: flex;
      min-height: 100vh;
      background: #f5f6fa;
    }

    /* Sidebar */
    .sidebar {
      width: 240px;
      background: #5da0e4ff;
      color: #fff;
      display: flex;
      flex-direction: column;
      padding: 20px 0;
      position: fixed;
      height: 100%;
    }
    .sidebar h2 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: 600;
    }
    .sidebar ul { list-style: none; }
    .sidebar ul li { padding: 15px 20px; }
    .sidebar ul li a {
      text-decoration: none;
      color: #fff;
      font-size: 16px;
      display: block;
      transition: 0.3s;
    }
    .sidebar ul li a:hover,
    .sidebar ul li a.active {
      background: #1c6ed6;
      border-radius: 6px;
    }

    /* Main Content */
    .main-content {
      margin-left: 240px;
      padding: 20px;
      width: 100%;
    }

    /* Topbar */
    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #fff;
      padding: 10px 20px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }
    .topbar h1 { font-size: 20px; color: #123; }
    .admin-profile { display: flex; align-items: center; gap: 10px; }
    .admin-profile img {
      width: 40px; height: 40px; border-radius: 50%;
    }

    /* Dashboard Cards */
    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
    }
    .card {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      text-align: center;
    }
    .card h3 { font-size: 18px; margin-bottom: 10px; }
    .card p {
      font-size: 24px;
      font-weight: bold;
      color: #2E89F0;
    }

    /* Table */
    .table-section { margin-top: 30px; }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px 15px;
      border-bottom: 1px solid #eee;
      text-align: left;
    }
    th { background: #2E89F0; color: #fff; }
    tr:hover { background: #f9f9f9; }

    /* Responsive */
    @media (max-width: 768px) {
      .sidebar { width: 200px; }
      .main-content { margin-left: 200px; }
    }
    @media (max-width: 576px) {
      .sidebar { position: relative; width: 100%; height: auto; }
      .main-content { margin-left: 0; }
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>QuickClean</h2>
    <ul>
      <li><a href="admindashboard.php" class="active">Dashboard</a></li>
      <li><a href="customers.php">Users</a></li>
      <li><a href="services.php">Services</a></li>
      <li><a href="booking.php">Bookings</a></li>
       <li><a href="transactions.php">Transactions</a></li>
      <li><a href="messages.php">Messages</a></li>
      <li><a href="settings.php">Settings</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Topbar -->
    <div class="topbar">
      <h1>Admin Dashboard</h1>
      <div class="admin-profile">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Admin">
        <span>Admin</span>
      </div>
    </div>

    <!-- Cards -->
    <div class="cards">
      <div class="card">
        <h3>Total Users</h3>
        <p><?php echo $total_users; ?></p>
      </div>
      <div class="card">
        <h3>Services</h3>
        <p><?php echo $total_services; ?></p>
      </div>
      <div class="card">
        <h3>Messages</h3>
        <p><?php echo $total_messages; ?></p>
      </div>
      <div class="card">
        <h3>Revenue</h3>
        <p>₱<?php echo number_format($total_revenue ?? 0, 2); ?></p>
      </div>
    </div>

    <!-- Table -->
    <div class="table-section">
      <h2>Recent Users</h2>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Joined</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($recent_users && $recent_users->num_rows > 0): ?>
            <?php while ($user = $recent_users->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo date("M d, Y", strtotime($user['date_created'])); ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="3">No recent users found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
