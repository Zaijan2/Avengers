<?php 
// ------------------ DATABASE CONNECTION ------------------
$servername = "localhost"; 
$username = "root";        
$password = "";            
$dbname = "quickclean";    

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ------------------ FETCH BOOKINGS ------------------
$sql = "SELECT * FROM bookings ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bookings Management - QuickClean</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: "Poppins", sans-serif; display: flex; min-height: 100vh; background: #f5f6fa; }

    /* Sidebar */
    .sidebar {
      width: 240px; background: #5da0e4; color: #fff;
      display: flex; flex-direction: column; padding: 20px 0;
      position: fixed; height: 100%;
    }
    .sidebar h2 { text-align: center; margin-bottom: 30px; font-weight: 600; }
    .sidebar ul { list-style: none; }
    .sidebar ul li { padding: 15px 20px; }
    .sidebar ul li a {
      text-decoration: none; color: #fff; font-size: 16px;
      display: block; transition: 0.3s;
    }
    .sidebar ul li a:hover, .sidebar ul li a.active {
      background: #1c6ed6; border-radius: 6px;
    }

    /* Main Content */
    .main-content { margin-left: 240px; padding: 20px; width: 100%; }

    /* Topbar */
    .topbar {
      display: flex; justify-content: space-between; align-items: center;
      background: #fff; padding: 10px 20px; border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1); margin-bottom: 20px;
    }
    .topbar h1 { font-size: 20px; color: #123; }

    /* Bookings Section */
    .bookings-section {
      background: #fff; padding: 20px; border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .bookings-section h2 { margin-bottom: 15px; color: #2E89F0; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td {
      border: 1px solid #ddd; padding: 10px; text-align: center; font-size: 14px;
    }
    th { background: #2E89F0; color: #fff; }
    tr:nth-child(even) { background: #f9f9f9; }

    /* Status text */
    .status-pending { color: #f1c40f; font-weight: 600; }
    .status-approved { color: #27ae60; font-weight: 600; }
    .status-declined { color: #e74c3c; font-weight: 600; }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>QuickClean</h2>
    <ul>
      <li><a href="admindashboard.php">Dashboard</a></li>
      <li><a href="customers.php">User</a></li>
      <li><a href="services.php">Services</a></li>
      <li><a href="booking.php" class="active">Bookings</a></li>
      <li><a href="transactions.php">Transactions</a></li>
      <li><a href="history.php">History</a></li>
      <li><a href="messages.php">Messages</a></li>
      <li><a href="settings.php">Settings</a></li>
      <li><a href="login.php">Logout</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="topbar">
      <h1>Bookings Management</h1>
      <div class="admin-profile">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" width="40" />
        <span>Admin</span>
      </div>
    </div>

    <div class="bookings-section">
      <h2>All Bookings</h2>

      <table>
        <thead>
          <tr>
            <th>Customer Name</th>
            <th>Service</th>
            <th>Date</th>
            <th>Time</th>
            <th>Address</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Price</th>
            <th>Notes</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

              $statusClass = "status-" . strtolower($row['status']);

              echo "<tr>
                      <td>{$row['name']}</td>
                      <td>{$row['service_name']}</td>
                      <td>{$row['date']}</td>
                      <td>{$row['time']}</td>
                      <td>{$row['address']}</td>
                      <td>{$row['email']}</td>
                      <td>{$row['phone']}</td>
                      <td>{$row['price']}</td>
                      <td>{$row['notes']}</td>
                      <td class='$statusClass'>{$row['status']}</td>
                    </tr>";
            }
          } else {
            echo "<tr><td colspan='10'>No bookings found.</td></tr>";
          }
          $conn->close();
          ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
