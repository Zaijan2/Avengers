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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Profiles - QuickClean</title>
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
    .admin-profile {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .admin-profile img {
      width: 40px; height: 40px; border-radius: 50%;
    }

    /* Customers Section */
    .customers-section {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .customers-section h2 {
      margin-bottom: 15px;
      color: #2E89F0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
      font-size: 14px;
    }
    th {
      background: #2E89F0;
      color: #fff;
    }
    tr:nth-child(even) { background: #f9f9f9; }
    .btn {
      padding: 6px 12px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 13px;
    }
    .btn-primary { background: #2E89F0; color: #fff; }
    .btn-danger { background: #ff6b6b; color: #fff; }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>QuickClean</h2>
    <ul>
      <li><a href="admindashboard.php">Dashboard</a></li>
      <li><a href="customers.php" class="active">Users</a></li>
      <li><a href="services.php">Services</a></li>
      <li><a href="booking.php">Bookings</a></li>
      <li><a href="transactions.php">Transactions</a></li>
      <li><a href="messages.php">Messages</a></li>
      <li><a href="settings.php">Settings</a></li>
      <li><a href="login.php">Logout</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Topbar -->
    <div class="topbar">
      <h1>Customer Profiles & History</h1>
      <div class="admin-profile">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Admin">
        <span>Admin</span>
      </div>
    </div>

    <!-- Customers Section -->
    <div class="customers-section">
      <h2>Customers</h2>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Contact</th>
            <th>Address</th>
            <th>Email</th>
            <th>Role</th>
            <th>Date Created</th>
          </tr>
        </thead>
        <tbody id="customerTableBody">
          <?php
          $query = "SELECT * FROM user WHERE role = 'customer'";
          $result = mysqli_query($conn, $query);

          if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  echo "<tr>";
                  echo "<td>{$row['name']}</td>";
                  echo "<td>{$row['contact_num']}</td>";
                  echo "<td>{$row['address']}</td>";
                  echo "<td>{$row['email']}</td>";
                  echo "<td>{$row['role']}</td>";
                  echo "<td>{$row['date_created']}</td>";
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='6'>No customers found.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
