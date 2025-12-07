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

// Get current month and year, or from URL parameters
$current_month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$current_year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Calculate previous and next month
$prev_month = $current_month - 1;
$prev_year = $current_year;
if ($prev_month < 1) {
    $prev_month = 12;
    $prev_year--;
}

$next_month = $current_month + 1;
$next_year = $current_year;
if ($next_month > 12) {
    $next_month = 1;
    $next_year++;
}

// Get all bookings for the current month that are accepted or have transactions
$sql = "SELECT b.*, t.status AS transaction_status
        FROM bookings b
        LEFT JOIN transactions t ON b.booking_id = t.booking_id
        WHERE MONTH(b.date) = $current_month 
        AND YEAR(b.date) = $current_year
        AND b.status IN ('pending', 'accepted')
        ORDER BY b.date, b.time";

$result = mysqli_query($conn, $sql);

// Organize bookings by date
$bookings_by_date = [];
while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['date'];
    if (!isset($bookings_by_date[$date])) {
        $bookings_by_date[$date] = [];
    }
    $bookings_by_date[$date][] = $row;
}

// Get month name
$month_name = date('F', mktime(0, 0, 0, $current_month, 1, $current_year));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Schedule - <?php echo $month_name . ' ' . $current_year; ?></title>
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

    /* --- Calendar Header --- */
    .calendar-header {
      background: white;
      border-radius: 16px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
      padding: 24px 28px;
      margin-bottom: 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
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

    .calendar-title {
      font-size: 24px;
      font-weight: 700;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .calendar-nav {
      display: flex;
      gap: 8px;
    }

    .calendar-nav a, .calendar-nav button {
      width: 40px;
      height: 40px;
      border: none;
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
      border-radius: 10px;
      cursor: pointer;
      font-size: 18px;
      transition: all 0.2s ease;
      color: #667eea;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
    }

    .calendar-nav a:hover, .calendar-nav button:hover {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      transform: scale(1.05);
    }

    .calendar-nav .today-btn {
      width: auto;
      padding: 0 16px;
      font-size: 14px;
    }

    /* --- Schedule Grid --- */
    .schedule-grid {
      display: grid;
      gap: 16px;
      margin-bottom: 24px;
    }

    .schedule-day {
      background: white;
      border-radius: 16px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
      overflow: hidden;
      animation: fadeIn 0.5s ease;
      transition: all 0.3s ease;
    }

    .schedule-day:hover {
      transform: translateY(-4px);
      box-shadow: 0 24px 70px rgba(0, 0, 0, 0.2);
    }

    .schedule-day-header {
      padding: 20px 24px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .schedule-date {
      font-size: 18px;
      font-weight: 700;
    }

    .schedule-count {
      background: rgba(255, 255, 255, 0.2);
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 600;
    }

    .schedule-bookings {
      padding: 20px 24px;
    }

    .schedule-booking {
      display: flex;
      gap: 16px;
      padding: 16px;
      margin-bottom: 12px;
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
      border-radius: 12px;
      border-left: 4px solid #667eea;
      transition: all 0.2s ease;
      cursor: pointer;
      text-decoration: none;
      color: inherit;
    }

    .schedule-booking:last-child {
      margin-bottom: 0;
    }

    .schedule-booking:hover {
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
      transform: translateX(4px);
    }

    .booking-time {
      flex-shrink: 0;
      width: 80px;
      text-align: center;
      padding: 12px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .booking-time-value {
      font-size: 18px;
      font-weight: 700;
      color: #667eea;
      line-height: 1;
      margin-bottom: 4px;
    }

    .booking-time-period {
      font-size: 11px;
      color: #718096;
      font-weight: 600;
      text-transform: uppercase;
    }

    .booking-details {
      flex: 1;
    }

    .booking-title {
      font-size: 16px;
      font-weight: 600;
      color: #2d3748;
      margin-bottom: 6px;
    }

    .booking-info {
      font-size: 14px;
      color: #718096;
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
    }

    .booking-info-item {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .booking-status {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 600;
      text-transform: capitalize;
    }

    .booking-status.pending {
      background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
      color: white;
    }

    .booking-status.accepted {
      background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
      color: white;
    }

    .booking-status.ontheway {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      color: white;
    }

    .booking-status.completed {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
    }

    /* --- Empty State --- */
    .empty-state {
      background: white;
      border-radius: 16px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
      padding: 80px 40px;
      text-align: center;
      animation: fadeIn 0.5s ease;
    }

    .empty-state-icon {
      font-size: 80px;
      margin-bottom: 24px;
      opacity: 0.3;
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-10px);
      }
    }

    .empty-state-title {
      font-size: 24px;
      font-weight: 700;
      color: #2d3748;
      margin-bottom: 12px;
    }

    .empty-state-text {
      font-size: 16px;
      color: #718096;
      margin-bottom: 24px;
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

      .header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
      }

      .header h1 {
        font-size: 24px;
      }

      .calendar-header {
        flex-direction: column;
        gap: 16px;
        text-align: center;
      }

      .schedule-booking {
        flex-direction: column;
      }

      .booking-time {
        width: 100%;
      }

      .booking-info {
        flex-direction: column;
        gap: 8px;
      }
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="sidebar">
      <div class="sidebar-logo">QuickClean</div>
      <div class="nav-item"><a href="dashboard.html">📊 Dashboard</a></div>
      <div class="nav-item"><a href="assigned.php">📋 My Bookings</a></div>
      <div class="nav-item"><a href="schedule.php" class="active">📅 Schedule</a></div>
      <div class="nav-item"><a href="earnings.html">💰 Earnings</a></div>
      <div class="nav-item"><a href="notification.html">🔔 Notifications</a></div>
      <div class="nav-item"><a href="profile.html">👤 Profile</a></div>
    </div>

    <div class="content">
      <div class="header">
        <h1>Schedule</h1>
      </div>

      <!-- Calendar Navigation -->
      <div class="calendar-header">
        <div class="calendar-title"><?php echo $month_name . ' ' . $current_year; ?></div>
        <div class="calendar-nav">
          <a href="?month=<?php echo $prev_month; ?>&year=<?php echo $prev_year; ?>">←</a>
          <a href="schedule.php" class="today-btn">Today</a>
          <a href="?month=<?php echo $next_month; ?>&year=<?php echo $next_year; ?>">→</a>
        </div>
      </div>

      <?php if (count($bookings_by_date) > 0): ?>
      <!-- Schedule with Bookings -->
      <div class="schedule-grid">
        <?php foreach ($bookings_by_date as $date => $bookings): ?>
        <div class="schedule-day">
          <div class="schedule-day-header">
            <div class="schedule-date"><?php echo date('l, F j', strtotime($date)); ?></div>
            <div class="schedule-count"><?php echo count($bookings); ?> booking<?php echo count($bookings) > 1 ? 's' : ''; ?></div>
          </div>
          <div class="schedule-bookings">
            <?php foreach ($bookings as $booking): ?>
            <a href="bookingdetails.php?booking=<?php echo $booking['booking_id']; ?>" class="schedule-booking">
              <div class="booking-time">
                <?php
                // Format time
                $time_parts = explode(':', $booking['time']);
                $hour = (int)$time_parts[0];
                $minute = $time_parts[1];
                $period = $hour >= 12 ? 'PM' : 'AM';
                $display_hour = $hour > 12 ? $hour - 12 : ($hour == 0 ? 12 : $hour);
                ?>
                <div class="booking-time-value"><?php echo sprintf('%02d:%s', $display_hour, $minute); ?></div>
                <div class="booking-time-period"><?php echo $period; ?></div>
              </div>
              <div class="booking-details">
                <div class="booking-title"><?php echo htmlspecialchars($booking['service_name'] . ' - ' . $booking['name']); ?></div>
                <div class="booking-info">
                  <div class="booking-info-item">
                    <span>📍</span>
                    <span><?php echo htmlspecialchars($booking['address']); ?></span>
                  </div>
                  <div class="booking-info-item">
                    <span>💰</span>
                    <span>₱<?php echo number_format($booking['price'], 2); ?></span>
                  </div>
                  <div class="booking-info-item">
                    <?php
                    // Determine status
                    if ($booking['transaction_status'] == 'completed') {
                        echo '<span class="booking-status completed">✓ Completed</span>';
                    } elseif ($booking['transaction_status'] == 'on the way') {
                        echo '<span class="booking-status ontheway">🚗 On the way</span>';
                    } elseif ($booking['status'] == 'accepted') {
                        echo '<span class="booking-status accepted">Accepted</span>';
                    } elseif ($booking['status'] == 'pending') {
                        echo '<span class="booking-status pending">Pending</span>';
                    }
                    ?>
                  </div>
                </div>
              </div>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <!-- Empty State -->
      <div class="empty-state">
        <div class="empty-state-icon">📅</div>
        <div class="empty-state-title">No bookings scheduled for <?php echo $month_name . ' ' . $current_year; ?></div>
        <div class="empty-state-text">Your schedule will appear here once bookings are assigned to you.</div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
<?php mysqli_close($conn); ?>