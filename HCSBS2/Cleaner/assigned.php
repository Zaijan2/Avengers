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

// GET ALL PENDING AND ACCEPTED BOOKINGS
$sql = "SELECT b.booking_id, b.name, b.date, b.time, b.status as booking_status,
        t.status AS transaction_status
        FROM bookings b
        LEFT JOIN transactions t ON b.booking_id = t.booking_id
        WHERE b.status IN ('pending', 'accepted')
        ORDER BY 
            CASE WHEN b.status = 'pending' THEN 1 ELSE 2 END,
            b.date, b.time";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Bookings</title>

<style>
body {font-family: Arial, sans-serif;background-color:#f4f6fa;margin:0;}
.wrapper {display:flex;min-height:100vh;}
.sidebar {width:230px;background:#2d6cdf;padding:20px;color:white;}
.sidebar .nav-item {margin:18px 0;}
.sidebar .nav-item a {display:block;background:rgba(255,255,255,0.15);padding:12px;border-radius:6px;color:white;text-decoration:none;}
.content {flex:1;padding:25px;}

.table {width:100%;border-collapse:collapse;margin-top:10px;}
.table th,.table td {padding:10px;border:1px solid #d6d8df;text-align:center;}
.table th {background:#e8ecf7;font-weight:bold;}

.status {padding:5px 10px;border-radius:4px;color:white;font-size:12px;display:inline-block;}
.status.new-booking {background:#8b5cf6;}
.status.awaiting {background:#f59e0b;}
.status.ontheway {background:#2d6cdf;}
.status.completed {background:#6b7280;}

.button {padding:8px 12px;background:#2d6cdf;color:white;border-radius:4px;text-decoration:none;font-size:13px;display:inline-block;}
.button:hover {background:#1e3a5f;}

.action-buttons {display:flex;gap:8px;justify-content:center;align-items:center;}

.button-accept {
    padding:8px 16px;
    background:#10b981;
    color:white;
    border-radius:4px;
    text-decoration:none;
    font-size:13px;
    font-weight:500;
    transition:all 0.2s;
}
.button-accept:hover {
    background:#0a865c;
    transform:translateY(-1px);
}

.button-decline {
    padding:8px 16px;
    background:#e11d48;
    color:white;
    border-radius:4px;
    text-decoration:none;
    font-size:13px;
    font-weight:500;
    transition:all 0.2s;
}
.button-decline:hover {
    background:#9f1239;
    transform:translateY(-1px);
}

.button-update {
    padding:8px 16px;
    background:#3b82f6;
    color:white;
    border-radius:4px;
    text-decoration:none;
    font-size:13px;
    font-weight:500;
    transition:all 0.2s;
    cursor:pointer;
}
.button-update:hover {
    background:#1e40af;
    transform:translateY(-1px);
}

.card {background:white;padding:16px;border-radius:6px;margin-top:20px;box-shadow:0 1px 3px rgba(0,0,0,0.1);}
.no-action {color:#6b7280;font-style:italic;}

.highlight-new {background-color:#f3f0ff;}

/* Modal Styles */
.modal {
    display:none;
    position:fixed;
    z-index:1000;
    left:0;
    top:0;
    width:100%;
    height:100%;
    background-color:rgba(0,0,0,0.5);
}

.modal-content {
    background-color:white;
    margin:5% auto;
    padding:30px;
    border-radius:8px;
    width:90%;
    max-width:500px;
    box-shadow:0 4px 6px rgba(0,0,0,0.1);
}

.modal-header {
    font-size:20px;
    font-weight:bold;
    margin-bottom:20px;
    color:#1f2937;
}

.modal-close {
    color:#9ca3af;
    float:right;
    font-size:28px;
    font-weight:bold;
    cursor:pointer;
    line-height:20px;
}

.modal-close:hover {
    color:#374151;
}

.form-group {
    margin-bottom:20px;
}

.form-group label {
    display:block;
    margin-bottom:8px;
    font-weight:500;
    color:#374151;
}

.form-group input[type="file"] {
    width:100%;
    padding:10px;
    border:2px dashed #d1d5db;
    border-radius:6px;
    background:#f9fafb;
    cursor:pointer;
}

.form-group input[type="file"]:hover {
    border-color:#2d6cdf;
}

.image-preview {
    margin-top:10px;
    max-width:100%;
    max-height:200px;
    display:none;
    border-radius:6px;
}

.button-complete {
    padding:12px 24px;
    background:#10b981;
    color:white;
    border:none;
    border-radius:6px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    width:100%;
    transition:all 0.2s;
}

.button-complete:hover {
    background:#059669;
}

.button-complete:disabled {
    background:#9ca3af;
    cursor:not-allowed;
}
</style>

</head>
<body>

<div class="wrapper">

<!-- SIDEBAR -->
<div class="sidebar">
  <div class="nav-item"><a href="dashboard.html">Dashboard</a></div>
  <div class="nav-item"><a href="assigned.php">My Bookings</a></div>
  <div class="nav-item"><a href="schedule.html">Schedule</a></div>
  <div class="nav-item"><a href="earnings.html">Earnings</a></div>
  <div class="nav-item"><a href="notification.html">Notifications</a></div>
  <div class="nav-item"><a href="profile.html">Profile</a></div>
</div>

<!-- CONTENT -->
<div class="content">

  <h1>My Bookings</h1>

  <div class="card">
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Customer</th>
          <th>Info</th>
          <th>Date / Time</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {

                // Highlight new pending bookings
                $rowClass = ($row['booking_status'] == 'pending') ? "class='highlight-new'" : "";

                echo "<tr $rowClass>";
                echo "<td>#".$row['booking_id']."</td>";
                echo "<td>".$row['name']."</td>";

                // INFO button
                echo "<td><a class='button' href='bookingdetails.php?booking=".$row['booking_id']."'>Details</a></td>";

                echo "<td>".$row['date']." ".$row['time']."</td>";

                // STATUS - Check booking_status first, then transaction_status
                if ($row['booking_status'] == 'pending') {
                    echo "<td><span class='status new-booking'>New Booking</span></td>";
                } elseif ($row['transaction_status'] == NULL) {
                    echo "<td><span class='status awaiting'>Awaiting Response</span></td>";
                } elseif ($row['transaction_status'] == "on the way") {
                    echo "<td><span class='status ontheway'>On the way</span></td>";
                } elseif ($row['transaction_status'] == "completed") {
                    echo "<td><span class='status completed'>Completed</span></td>";
                }

                // ACTION column
                echo "<td>";

                // Show action buttons for pending bookings OR accepted bookings without transaction status
                if ($row['booking_status'] == 'pending' || 
                    ($row['booking_status'] == 'accepted' && $row['transaction_status'] == NULL)) {
                    
                    echo "<div class='action-buttons'>";
                    echo "<a class='button-accept' href='accept_booking.php?id=".$row['booking_id']."'>Accept</a>";
                    echo "<a class='button-decline' href='decline_booking.php?id=".$row['booking_id']."'>Decline</a>";
                    echo "</div>";
                } 
                elseif ($row['transaction_status'] == "on the way") {
                    // Show UPDATE button for on the way bookings
                    echo "<button class='button-update' onclick='openCompleteModal(".$row['booking_id'].")'>Update Status</button>";
                }
                else {
                    echo "<span class='no-action'>No action needed</span>";
                }

                echo "</td>";

                echo "</tr>";
            }

        } else {
            echo "<tr><td colspan='6' style='text-align:center;padding:20px;color:#6b7280;'>No bookings available.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

</div>
</div>

<!-- Complete Modal -->
<div id="completeModal" class="modal">
  <div class="modal-content">
    <span class="modal-close" onclick="closeCompleteModal()">&times;</span>
    <div class="modal-header">Mark Booking as Complete</div>
    
    <form id="completeForm" action="complete_booking.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="booking_id" id="modal_booking_id">
      
      <div class="form-group">
        <label>Upload Proof of Completion (Required)</label>
        <input type="file" name="proof_image" id="proof_image" accept="image/*" required onchange="previewImage(event)">
        <img id="imagePreview" class="image-preview" alt="Image preview">
      </div>
      
      <button type="submit" class="button-complete" id="submitBtn">Complete Booking</button>
    </form>
  </div>
</div>

<script>
function openCompleteModal(bookingId) {
    document.getElementById('modal_booking_id').value = bookingId;
    document.getElementById('completeModal').style.display = 'block';
    document.getElementById('completeForm').reset();
    document.getElementById('imagePreview').style.display = 'none';
}

function closeCompleteModal() {
    document.getElementById('completeModal').style.display = 'none';
}

function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('completeModal');
    if (event.target == modal) {
        closeCompleteModal();
    }
}
</script>

</body>
</html>