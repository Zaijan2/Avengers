<?php
// --- DATABASE CONNECTION ---
$host = "localhost";
$user = "root";
$pass = "";
$db   = "quickclean";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get today's date
$today = date('Y-m-d');

// Get today's bookings count (all statuses except declined)
$today_bookings_sql = "SELECT COUNT(*) as count FROM bookings 
                       WHERE date = '$today' 
                       AND status != 'declined'";
$today_bookings_result = mysqli_query($conn, $today_bookings_sql);
$today_bookings_count = mysqli_fetch_assoc($today_bookings_result)['count'];

// Get completed bookings today
$completed_today_sql = "SELECT COUNT(*) as count FROM transactions t
                        JOIN bookings b ON t.booking_id = b.booking_id
                        WHERE b.date = '$today' 
                        AND t.status = 'completed'";
$completed_today_result = mysqli_query($conn, $completed_today_sql);
$completed_today_count = mysqli_fetch_assoc($completed_today_result)['count'];

// Get monthly earnings (sum of paid payments for completed bookings this month)
$current_month = date('m');
$current_year = date('Y');
$earnings_monthly_sql = "SELECT SUM(p.amount) as total FROM payments p
                         JOIN bookings b ON p.booking_id = b.booking_id
                         JOIN transactions t ON b.booking_id = t.booking_id
                         WHERE MONTH(b.date) = '$current_month'
                         AND YEAR(b.date) = '$current_year'
                         AND t.status = 'completed'
                         AND p.payment_status = 'paid'";
$earnings_monthly_result = mysqli_query($conn, $earnings_monthly_sql);
$earnings_monthly = mysqli_fetch_assoc($earnings_monthly_result)['total'] ?? 0;

// Get overall stats for rating calculation (example: based on completed jobs)
$total_completed_sql = "SELECT COUNT(*) as count FROM transactions WHERE status = 'completed'";
$total_completed_result = mysqli_query($conn, $total_completed_sql);
$total_completed = mysqli_fetch_assoc($total_completed_result)['count'];

// Get today's bookings details
$today_details_sql = "SELECT b.booking_id, b.name, b.service_name, b.time, b.status as booking_status,
                      t.status as transaction_status, 
                      p.payment_status
                      FROM bookings b
                      LEFT JOIN transactions t ON b.booking_id = t.booking_id
                      LEFT JOIN payments p ON b.booking_id = p.booking_id
                      WHERE b.date = '$today' 
                      AND b.status != 'declined'
                      GROUP BY b.booking_id
                      ORDER BY b.time";
$today_details_result = mysqli_query($conn, $today_details_sql);

if (!$today_details_result) {
    die("Query failed: " . mysqli_error($conn));
}

// Get upcoming bookings (next 7 days excluding today)
$next_week = date('Y-m-d', strtotime('+7 days'));
$upcoming_sql = "SELECT b.booking_id, b.name, b.service_name, b.date, b.time, 
                 b.status as booking_status, 
                 t.status as transaction_status, 
                 p.payment_status
                 FROM bookings b
                 LEFT JOIN transactions t ON b.booking_id = t.booking_id
                 LEFT JOIN payments p ON b.booking_id = p.booking_id
                 WHERE b.date > '$today' 
                 AND b.date <= '$next_week'
                 AND b.status != 'declined'
                 GROUP BY b.booking_id
                 ORDER BY b.date, b.time
                 LIMIT 5";
$upcoming_result = mysqli_query($conn, $upcoming_sql);

if (!$upcoming_result) {
    die("Query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cleaner Dashboard</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: #1a202c;
      min-height: 100vh;
    }

    .wrapper {
      display: flex;
      min-height: 100vh;
    }

    /* --- Sidebar with Glassmorphism --- */
    .sidebar {
      width: 260px;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border-right: 1px solid rgba(255, 255, 255, 0.2);
      padding: 30px 20px;
      color: white;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .sidebar-logo {
      font-size: 24px;
      font-weight: 800;
      margin-bottom: 40px;
      color: white;
      letter-spacing: -0.5px;
    }

    .nav-item {
      margin: 8px 0;
    }

    .nav-item a {
      display: flex;
      align-items: center;
      gap: 12px;
      background: rgba(255, 255, 255, 0.05);
      padding: 14px 16px;
      border-radius: 12px;
      text-decoration: none;
      color: rgba(255, 255, 255, 0.9);
      font-size: 15px;
      font-weight: 500;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border: 1px solid transparent;
    }

    .nav-item a:hover {
      background: rgba(255, 255, 255, 0.15);
      border-color: rgba(255, 255, 255, 0.3);
      transform: translateX(4px);
    }

    .nav-item a.active {
      background: white;
      color: #667eea;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* --- Content Section --- */
    .content {
      flex: 1;
      padding: 30px 40px;
      overflow-y: auto;
    }

    .header {
      margin-bottom: 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .header h1 {
      color: white;
      font-size: 32px;
      font-weight: 700;
      letter-spacing: -0.5px;
    }

    /* --- Stats Grid --- */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: white;
      border-radius: 16px;
      padding: 24px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
      animation: fadeIn 0.5s ease;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }

    .stat-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 24px 70px rgba(0, 0, 0, 0.2);
    }

    .stat-icon {
      font-size: 32px;
      margin-bottom: 12px;
    }

    .stat-label {
      font-size: 13px;
      color: #718096;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 8px;
    }

    .stat-value {
      font-size: 28px;
      font-weight: 700;
      color: #2d3748;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    /* --- Modern Card --- */
    .card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
      overflow: hidden;
      margin-bottom: 24px;
      animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .card h2 {
      padding: 24px 28px;
      color: #667eea;
      font-size: 20px;
      font-weight: 700;
      border-bottom: 2px solid #e2e8f0;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    /* --- Modern Table --- */
    .table-container {
      overflow-x: auto;
    }

    .table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
    }

    .table thead {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .table th {
      padding: 18px 20px;
      text-align: left;
      font-weight: 600;
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: white;
    }

    .table tbody tr {
      transition: all 0.2s ease;
      border-bottom: 1px solid #e2e8f0;
      cursor: pointer;
    }

    .table tbody tr:hover {
      background: linear-gradient(90deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
      transform: scale(1.01);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .table tbody tr:last-child {
      border-bottom: none;
    }

    .table td {
      padding: 20px;
      font-size: 15px;
      color: #2d3748;
    }

    /* --- Modern Status Badges --- */
    .status {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 600;
      text-transform: capitalize;
      transition: all 0.2s ease;
    }

    .status::before {
      content: '';
      width: 6px;
      height: 6px;
      border-radius: 50%;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% {
        opacity: 1;
      }
      50% {
        opacity: 0.5;
      }
    }

    .status.pending {
      background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
      color: white;
    }

    .status.pending::before {
      background: white;
    }

    .status.accepted {
      background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
      color: white;
    }

    .status.accepted::before {
      background: white;
    }

    .status.ontheway {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      color: white;
    }

    .status.ontheway::before {
      background: white;
    }

    .status.completed {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
    }

    .status.completed::before {
      background: white;
    }

    /* --- Empty State --- */
    .empty-state {
      padding: 40px;
      text-align: center;
      color: #718096;
    }

    .empty-state-icon {
      font-size: 48px;
      margin-bottom: 16px;
      opacity: 0.5;
    }

    .empty-state-text {
      font-size: 16px;
      font-weight: 500;
    }

    /* --- Responsive --- */
    @media (max-width: 768px) {
      .sidebar {
        position: fixed;
        left: -260px;
        height: 100vh;
        z-index: 1000;
        transition: left 0.3s ease;
      }

      .content {
        padding: 20px;
      }

      .header h1 {
        font-size: 24px;
      }

      .stats-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="sidebar">
      <div class="sidebar-logo">QuickClean</div>
      <div class="nav-item"><a href="dashboard.php" class="active">📊 Dashboard</a></div>
      <div class="nav-item"><a href="assigned.php">📋 My Bookings</a></div>
      <div class="nav-item"><a href="schedule.php">📅 Schedule</a></div>
      <div class="nav-item"><a href="earnings.html">💰 Earnings</a></div>
      <div class="nav-item"><a href="notification.html">🔔 Notifications</a></div>
      <div class="nav-item"><a href="profile.html">👤 Profile</a></div>
    </div>

    <div class="content">
      <div class="header">
        <h1>Cleaner Dashboard</h1>
      </div>

      <!-- Stats Overview -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon">📋</div>
          <div class="stat-label">Today's Bookings</div>
          <div class="stat-value"><?php echo $today_bookings_count; ?></div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">✅</div>
          <div class="stat-label">Completed Today</div>
          <div class="stat-value"><?php echo $completed_today_count; ?></div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">💵</div>
          <div class="stat-label">Monthly Earnings</div>
          <div class="stat-value">₱<?php echo number_format($earnings_monthly, 2); ?></div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">⭐</div>
          <div class="stat-label">Total Completed</div>
          <div class="stat-value"><?php echo $total_completed; ?></div>
        </div>
      </div>

      <!-- Today's Bookings Table -->
      <div class="card">
        <h2>📅 Today's Bookings</h2>
        <div class="table-container">
          <table class="table">
            <thead>
              <tr>
                <th>Booking ID</th>
                <th>Customer</th>
                <th>Service</th>
                <th>Time</th>
                <th>Payment</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (mysqli_num_rows($today_details_result) > 0) {
                  while ($booking = mysqli_fetch_assoc($today_details_result)) {
                      // Determine status display
                      if ($booking['transaction_status'] == 'completed') {
                          $status_class = 'completed';
                          $status_text = '✓ Completed';
                      } elseif ($booking['transaction_status'] == 'on the way') {
                          $status_class = 'ontheway';
                          $status_text = '🚗 On the way';
                      } elseif ($booking['booking_status'] == 'accepted') {
                          $status_class = 'accepted';
                          $status_text = 'Accepted';
                      } else {
                          $status_class = 'pending';
                          $status_text = 'Pending';
                      }

                      // Determine payment status display
                      $payment_class = '';
                      $payment_text = 'N/A';
                      if (!empty($booking['payment_status'])) {
                          if ($booking['payment_status'] == 'paid') {
                              $payment_class = 'completed';
                              $payment_text = '✓ Paid';
                          } elseif ($booking['payment_status'] == 'pending') {
                              $payment_class = 'pending';
                              $payment_text = 'Pending';
                          } elseif ($booking['payment_status'] == 'failed') {
                              $payment_class = 'status';
                              $payment_text = '✗ Failed';
                          } elseif ($booking['payment_status'] == 'refunded') {
                              $payment_class = 'status';
                              $payment_text = '↩ Refunded';
                          }
                      }

                      echo "<tr onclick=\"window.location.href='bookingdetails.php?booking=".$booking['booking_id']."'\">";
                      echo "<td><strong>#".$booking['booking_id']."</strong></td>";
                      echo "<td>".htmlspecialchars($booking['name'])."</td>";
                      echo "<td>".htmlspecialchars($booking['service_name'])."</td>";
                      echo "<td>".$booking['time']."</td>";
                      echo "<td><span class='status ".$payment_class."'>".$payment_text."</span></td>";
                      echo "<td><span class='status ".$status_class."'>".$status_text."</span></td>";
                      echo "</tr>";
                  }
              } else {
                  echo "<tr><td colspan='6' class='empty-state'>";
                  echo "<div class='empty-state-icon'>📭</div>";
                  echo "<div class='empty-state-text'>No bookings scheduled for today.</div>";
                  echo "</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Upcoming Section -->
      <div class="card">
        <h2>🔜 Upcoming (Next 7 Days)</h2>
        <?php if (mysqli_num_rows($upcoming_result) > 0): ?>
        <div class="table-container">
          <table class="table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Customer</th>
                <th>Service</th>
                <th>Payment</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              while ($booking = mysqli_fetch_assoc($upcoming_result)) {
                  // Determine status display
                  if ($booking['transaction_status'] == 'completed') {
                      $status_class = 'completed';
                      $status_text = '✓ Completed';
                  } elseif ($booking['transaction_status'] == 'on the way') {
                      $status_class = 'ontheway';
                      $status_text = '🚗 On the way';
                  } elseif ($booking['booking_status'] == 'accepted') {
                      $status_class = 'accepted';
                      $status_text = 'Accepted';
                  } else {
                      $status_class = 'pending';
                      $status_text = 'Pending';
                  }

                  // Determine payment status display
                  $payment_class = '';
                  $payment_text = 'N/A';
                  if (!empty($booking['payment_status'])) {
                      if ($booking['payment_status'] == 'paid') {
                          $payment_class = 'completed';
                          $payment_text = '✓ Paid';
                      } elseif ($booking['payment_status'] == 'pending') {
                          $payment_class = 'pending';
                          $payment_text = 'Pending';
                      } elseif ($booking['payment_status'] == 'failed') {
                          $payment_class = 'status';
                          $payment_text = '✗ Failed';
                      } elseif ($booking['payment_status'] == 'refunded') {
                          $payment_class = 'status';
                          $payment_text = '↩ Refunded';
                      }
                  }

                  echo "<tr onclick=\"window.location.href='bookingdetails.php?booking=".$booking['booking_id']."'\">";
                  echo "<td>".date('M j, Y', strtotime($booking['date']))."</td>";
                  echo "<td>".$booking['time']."</td>";
                  echo "<td>".htmlspecialchars($booking['name'])."</td>";
                  echo "<td>".htmlspecialchars($booking['service_name'])."</td>";
                  echo "<td><span class='status ".$payment_class."'>".$payment_text."</span></td>";
                  echo "<td><span class='status ".$status_class."'>".$status_text."</span></td>";
                  echo "</tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
          <div class="empty-state-icon">📭</div>
          <div class="empty-state-text">No upcoming bookings in the next 7 days.</div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
<?php mysqli_close($conn); ?>