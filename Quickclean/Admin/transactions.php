<?php
// ------------------ DATABASE CONNECTION ------------------
$conn = new mysqli("localhost", "root", "", "quickclean");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ------------------ UPDATE TRANSACTION STATUS ------------------
if (isset($_GET['complete'])) {
  $id = intval($_GET['complete']);
  $conn->query("UPDATE bookings SET status='completed' WHERE booking_id=$id");
  header("Location: transactions.php");
  exit;
}

// ------------------ FETCH ACCEPTED & COMPLETED BOOKINGS ------------------
$result = $conn->query("SELECT * FROM bookings WHERE status IN ('accepted', 'completed') ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Transactions - QuickClean</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: "Poppins", sans-serif; background: #f5f6fa; display: flex; }

    /* Sidebar */
    .sidebar {
      width: 240px; background: #5da0e4; color: #fff;
      height: 100vh; position: fixed; top: 0; left: 0; padding-top: 20px;
    }
    .sidebar h2 { text-align: center; margin-bottom: 30px; font-weight: 600; }
    .sidebar ul { list-style: none; padding: 0; }
    .sidebar ul li { padding: 15px 20px; }
    .sidebar ul li a {
      color: #fff; text-decoration: none; font-size: 16px;
      display: block; transition: background 0.3s;
    }
    .sidebar ul li a:hover, .sidebar ul li a.active {
      background: #1c6ed6; border-radius: 6px;
    }

    /* Main Content */
    .main-content {
      margin-left: 240px; padding: 20px;
      width: calc(100% - 240px);
    }

    .topbar {
      display: flex; justify-content: space-between; align-items: center;
      background: #fff; padding: 10px 20px; border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1); margin-bottom: 20px;
    }

    h1 { color: #2E89F0; }

    /* Table */
    .transactions-section {
      background: #fff; padding: 20px; border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    table {
      width: 100%; border-collapse: collapse; margin-top: 15px;
    }
    th, td {
      border: 1px solid #ddd; padding: 10px; text-align: center; font-size: 14px;
    }
    th { background: #2E89F0; color: #fff; }
    tr:nth-child(even) { background: #f9f9f9; }

    .status-onway { color: orange; font-weight: 600; }
    .status-completed { color: green; font-weight: 600; }

    .btn {
      padding: 6px 12px; border: none; border-radius: 6px;
      cursor: pointer; font-size: 13px; font-weight: 600;
      text-decoration: none; display: inline-block;
    }
    .btn-complete { background: #27ae60; color: #fff; }
    .btn-complete:hover { background: #1f8a4d; }
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
      <li><a href="messages.php">Messages</a></li>
      <li><a href="settings.php">Settings</a></li>
      <li><a href="login.php">Logout</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="topbar">
      <h1>Transactions</h1>
    </div>

    <div class="transactions-section">
      <h2>Accepted Bookings</h2>
      <table>
        <thead>
          <tr>
            <th>Customer</th>
            <th>Service</th>
            <th>Date</th>
            <th>Time</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Price</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $statusLabel = $row['status'] == 'accepted' ? 'On the Way' : 'Completed';
              $statusClass = $row['status'] == 'accepted' ? 'status-onway' : 'status-completed';
              echo "<tr>
                      <td>{$row['name']}</td>
                      <td>{$row['service_name']}</td>
                      <td>{$row['date']}</td>
                      <td>{$row['time']}</td>
                      <td>{$row['address']}</td>
                      <td>{$row['phone']}</td>
                       <td>{$row['price']}</td>
                      <td class='$statusClass'>$statusLabel</td>
                      <td>";
              if ($row['status'] == 'accepted') {
                echo "<a href='transactions.php?complete={$row['booking_id']}' class='btn btn-complete'>Mark Completed</a>";
              } else {
                echo "✔ Done";
              }
              echo "</td></tr>";
            }
          } else {
            echo "<tr><td colspan='8'>No accepted bookings yet.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
<?php $conn->close(); ?>
