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

// Get booking ID from URL
$booking_id = isset($_GET['booking']) ? (int)$_GET['booking'] : 0;

// Fetch booking details
$sql = "SELECT b.*, t.status AS transaction_status, t.proof_image, t.completed_at
        FROM bookings b
        LEFT JOIN transactions t ON b.booking_id = t.booking_id
        WHERE b.booking_id = $booking_id";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Booking not found.");
}

$booking = mysqli_fetch_assoc($result);

// Parse extras if it's JSON
$extras = [];
if (!empty($booking['extras'])) {
    $decoded = json_decode($booking['extras'], true);
    if (is_array($decoded)) {
        $extras = $decoded;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking #<?php echo $booking['booking_id']; ?> Details</title>
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
      flex-wrap: wrap;
      gap: 16px;
    }

    .header h1 {
      color: white;
      font-size: 32px;
      font-weight: 700;
      letter-spacing: -0.5px;
    }

    .status-badge {
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 14px;
      font-weight: 600;
      color: white;
      display: inline-block;
    }

    .status-badge.pending {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .status-badge.accepted {
      background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }

    .status-badge.ontheway {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    .status-badge.completed {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    /* --- Modern Card --- */
    .card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
      padding: 28px;
      margin-bottom: 24px;
      animation: fadeIn 0.5s ease;
      transition: all 0.3s ease;
    }

    .card:hover {
      transform: translateY(-4px);
      box-shadow: 0 24px 70px rgba(0, 0, 0, 0.2);
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
      color: #667eea;
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      padding-bottom: 12px;
      border-bottom: 2px solid #e2e8f0;
    }

    /* --- Checklist --- */
    .card ul {
      list-style: none;
      padding: 0;
    }

    .card ul li {
      padding: 12px 16px;
      margin-bottom: 10px;
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
      border-radius: 10px;
      font-size: 15px;
      color: #2d3748;
      display: flex;
      align-items: center;
      gap: 12px;
      transition: all 0.2s ease;
      border-left: 3px solid #667eea;
    }

    .card ul li:hover {
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
      transform: translateX(4px);
    }

    .card ul li::before {
      content: '✓';
      display: flex;
      align-items: center;
      justify-content: center;
      width: 24px;
      height: 24px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 50%;
      font-size: 14px;
      font-weight: bold;
      flex-shrink: 0;
    }

    /* --- Button Container --- */
    .button-group {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
    }

    /* --- Modern Buttons --- */
    .button {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 14px 28px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      text-decoration: none;
      font-size: 15px;
      font-weight: 600;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .button:active {
      transform: translateY(0);
    }

    .button.btn-back {
      background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
      box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
    }

    .button.btn-back:hover {
      box-shadow: 0 6px 20px rgba(107, 114, 128, 0.4);
    }

    .button.btn-accept {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .button.btn-accept:hover {
      box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }

    .button.btn-decline {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .button.btn-decline:hover {
      box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    }

    .button.btn-update {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .button.btn-update:hover {
      box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
    }

    /* --- Info Grid --- */
    .info-grid {
      display: grid;
      gap: 16px;
    }

    .info-item {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 16px;
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.03) 0%, rgba(118, 75, 162, 0.03) 100%);
      border-radius: 10px;
      transition: all 0.2s ease;
    }

    .info-item:hover {
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.08) 0%, rgba(118, 75, 162, 0.08) 100%);
    }

    .info-icon {
      font-size: 20px;
      flex-shrink: 0;
    }

    .info-content {
      flex: 1;
    }

    .info-label {
      color: #718096;
      font-size: 13px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 4px;
    }

    .info-value {
      color: #2d3748;
      font-size: 16px;
      font-weight: 500;
    }

    .proof-image {
      max-width: 100%;
      border-radius: 12px;
      margin-top: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .notes-box {
      background: #fef3c7;
      border-left: 4px solid #f59e0b;
      padding: 16px;
      border-radius: 8px;
      margin-top: 12px;
    }

    .notes-box p {
      color: #78350f;
      margin: 0;
      font-size: 15px;
      line-height: 1.6;
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

      .button-group {
        flex-direction: column;
      }

      .button {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="sidebar">
      <div class="sidebar-logo">QuickClean</div>
      <div class="nav-item"><a href="dashboard.html">📊 Dashboard</a></div>
      <div class="nav-item"><a href="assigned.php" class="active">📋 My Bookings</a></div>
      <div class="nav-item"><a href="schedule.html">📅 Schedule</a></div>
      <div class="nav-item"><a href="earnings.html">💰 Earnings</a></div>
      <div class="nav-item"><a href="notification.html">🔔 Notifications</a></div>
      <div class="nav-item"><a href="profile.html">👤 Profile</a></div>
    </div>

    <div class="content">
      <div class="header">
        <?php
        // Display status badge
        if ($booking['transaction_status'] == 'completed') {
            echo '<span class="status-badge completed">✓ Completed</span>';
        } elseif ($booking['transaction_status'] == 'on the way') {
            echo '<span class="status-badge ontheway">🚗 On the Way</span>';
        } elseif ($booking['status'] == 'accepted') {
            echo '<span class="status-badge accepted">✓ Accepted</span>';
        } elseif ($booking['status'] == 'pending') {
            echo '<span class="status-badge pending">⏳ Pending</span>';
        }
        ?>
      </div>

      <div class="card">
        <h2>👤 Customer Information</h2>
        <div class="info-grid">
          <div class="info-item">
            <span class="info-icon">👨</span>
            <div class="info-content">
              <div class="info-label">Name</div>
              <div class="info-value"><?php echo htmlspecialchars($booking['name']); ?></div>
            </div>
          </div>
          <div class="info-item">
            <span class="info-icon">📍</span>
            <div class="info-content">
              <div class="info-label">Address</div>
              <div class="info-value"><?php echo htmlspecialchars($booking['address']); ?></div>
            </div>
          </div>
          <div class="info-item">
            <span class="info-icon">📱</span>
            <div class="info-content">
              <div class="info-label">Phone</div>
              <div class="info-value"><?php echo htmlspecialchars($booking['phone']); ?></div>
            </div>
          </div>
          <?php if (!empty($booking['email'])): ?>
          <div class="info-item">
            <span class="info-icon">📧</span>
            <div class="info-content">
              <div class="info-label">Email</div>
              <div class="info-value"><?php echo htmlspecialchars($booking['email']); ?></div>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="card">
        <h2>🧹 Service Details</h2>
        <div class="info-grid">
          <div class="info-item">
            <span class="info-icon">🏠</span>
            <div class="info-content">
              <div class="info-label">Service</div>
              <div class="info-value"><?php echo htmlspecialchars($booking['service_name']); ?></div>
            </div>
          </div>
          <div class="info-item">
            <span class="info-icon">📅</span>
            <div class="info-content">
              <div class="info-label">Scheduled For</div>
              <div class="info-value"><?php echo date('F j, Y', strtotime($booking['date'])); ?> at <?php echo $booking['time']; ?></div>
            </div>
          </div>
          <div class="info-item">
            <span class="info-icon">💵</span>
            <div class="info-content">
              <div class="info-label">Price</div>
              <div class="info-value">₱<?php echo number_format($booking['price'], 2); ?></div>
            </div>
          </div>
          <div class="info-item">
            <span class="info-icon">🆔</span>
            <div class="info-content">
              <div class="info-label">Service ID</div>
              <div class="info-value">#<?php echo $booking['service_id']; ?></div>
            </div>
          </div>
        </div>
      </div>

      <?php if (!empty($extras)): ?>
      <div class="card">
        <h2>➕ Additional Services</h2>
        <ul>
          <?php foreach ($extras as $extra): ?>
            <li><?php echo htmlspecialchars($extra); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <?php if (!empty($booking['notes'])): ?>
      <div class="card">
        <h2>📝 Customer Notes</h2>
        <div class="notes-box">
          <p><?php echo nl2br(htmlspecialchars($booking['notes'])); ?></p>
        </div>
      </div>
      <?php endif; ?>

      <?php if ($booking['transaction_status'] == 'completed' && !empty($booking['proof_image'])): ?>
      <div class="card">
        <h2>📸 Proof of Completion</h2>
        <div class="info-item">
          <span class="info-icon">✅</span>
          <div class="info-content">
            <div class="info-label">Completed At</div>
            <div class="info-value"><?php echo date('F j, Y g:i A', strtotime($booking['completed_at'])); ?></div>
          </div>
        </div>
        <img src="<?php echo htmlspecialchars($booking['proof_image']); ?>" alt="Proof of Completion" class="proof-image">
      </div>
      <?php endif; ?>

      <div class="card">
        <h2>🔄 Actions</h2>
        <div class="button-group">
          <a class="button btn-back" href="assigned.php">← Back to Bookings</a>
          
          <?php
          // Show appropriate action buttons based on status
          if ($booking['status'] == 'pending' || ($booking['status'] == 'accepted' && $booking['transaction_status'] == NULL)) {
              echo '<a class="button btn-accept" href="accept_booking.php?id='.$booking['booking_id'].'">✓ Accept Booking</a>';
              echo '<a class="button btn-decline" href="decline_booking.php?id='.$booking['booking_id'].'">✗ Decline Booking</a>';
          } elseif ($booking['transaction_status'] == 'on the way') {
              echo '<button class="button btn-update" onclick="window.location.href=\'assigned.php\'">📋 Update to Complete</button>';
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</body>
</html>