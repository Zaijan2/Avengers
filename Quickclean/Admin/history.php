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

// ------------------ FETCH COMPLETED TRANSACTIONS ------------------
$sql = "
  SELECT transaction_id, booking_id, customer_name, service_name, date, time, address, phone, email, status, action_date
  FROM transactions
  WHERE status = 'completed'
  ORDER BY action_date DESC
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Completed History - QuickClean</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: "Poppins", sans-serif; display: flex; min-height: 100vh; background: #f5f6fa; }

    /* Sidebar */
    .sidebar {
      width: 240px; background: #5da0e4ff; color: #fff;
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
    .admin-profile { display: flex; align-items: center; gap: 10px; }
    .admin-profile img { width: 40px; height: 40px; border-radius: 50%; }

    /* History Section */
    .history-section {
      background: #fff; padding: 20px; border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .history-section h2 { margin-bottom: 15px; color: #2E89F0; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td {
      border: 1px solid #ddd; padding: 10px; text-align: center; font-size: 14px;
    }
    th { background: #2E89F0; color: #fff; }
    tr:nth-child(even) { background: #f9f9f9; }
    .status-completed { color: #27ae60; font-weight: 600; }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>QuickClean</h2>
    <ul>
      <li><a href="admindashboard.php">Dashboard</a></li>
      <li><a href="customers.php">Users</a></li>
      <li><a href="services.php">Services</a></li>
      <li><a href="booking.php">Bookings</a></li>
      <li><a href="transactions.php">Transactions</a></li>
      <li><a href="history.php" class="active">History</a></li>
      <li><a href="messages.php">Messages</a></li>
      <li><a href="settings.php">Settings</a></li>
      <li><a href="login.php">Logout</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="topbar">
      <h1>Completed Transactions</h1>
      <div class="admin-profile">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Admin" />
        <span>Admin</span>
      </div>
    </div>

    <div class="history-section">
      <h2>Completed Services</h2>
      <table>
        <thead>
          <tr>
            <th>Transaction ID</th>
            <th>Customer</th>
            <th>Service</th>
            <th>Date</th>
            <th>Time</th>
            <th>Address</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Action Date</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>{$row['transaction_id']}</td>
                      <td>{$row['customer_name']}</td>
                      <td>{$row['service_name']}</td>
                      <td>{$row['date']}</td>
                      <td>{$row['time']}</td>
                      <td>{$row['address']}</td>
                      <td>{$row['email']}</td>
                      <td>{$row['phone']}</td>
                      <td class='status-completed'>Completed</td>
                      <td>{$row['action_date']}</td>
                    </tr>";
            }
          } else {
            echo "<tr><td colspan='10'>No completed transactions found.</td></tr>";
          }
          $conn->close();
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
