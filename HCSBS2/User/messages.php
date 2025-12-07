<?php
session_start();

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

require_login();
//database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "quickclean";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];

// If sending a new message from user to cleaner
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $cleaner_id = intval($_POST['cleaner_id']);
    $message = trim($_POST['message']);
    if ($cleaner_id && $message !== '') {
        $ins = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $ins->bind_param("iis", $user_id, $cleaner_id, $message);
        $ins->execute();
        $ins->close();
        header("Location: messages.php?c=".$cleaner_id);
        exit();
    }
}

// Get list of cleaners (to start conversation)
$cleaners = $conn->query("SELECT user_id, name FROM user WHERE role='cleaner'");

// If a cleaner is selected, show the conversation
$selected_cleaner = isset($_GET['c']) ? intval($_GET['c']) : 0;
$conversation = [];
if ($selected_cleaner) {
    $q = $conn->prepare("SELECT m.sender_id, m.receiver_id, m.message, m.created_at, u.name as sender_name
        FROM messages m
        LEFT JOIN user u ON u.user_id = m.sender_id
        WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?)
        ORDER BY m.created_at ASC");
    $q->bind_param("iiii", $user_id, $selected_cleaner, $selected_cleaner, $user_id);
    $q->execute();
    $conversation = $q->get_result();
    $q->close();
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Messages</title>

<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;800&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
/* --- DESIGN STYLES (NAV BAR & HEADER) --- */
:root{
  --brand-blue: #6DAFF2;
  --nav-yellow: #FFDB58;
  --text-blue: #2E89F0;
  --nav-link-color: #0b3b66;
  --header-height: 110px;
  --nav-height: 64px;
  --max-content-width: 1360px;
}

* { box-sizing: border-box; }
body{ font-family:"Poppins",sans-serif; background:#f4f6f8; margin:0; color:#123; }

/* HEADER DESIGN */
.site-header{ background:var(--brand-blue); height:var(--header-height); display:flex; align-items:center; }

/* Centering CSS for Tagline */
.header-inner{ 
    width:100%; 
    max-width:var(--max-content-width); 
    margin:0 auto; 
    display:flex; 
    align-items:center; 
    justify-content:space-between; 
    padding:12px 24px; 
}
.logo-text{ 
    font-family:'Baloo 2'; 
    font-size: 32px; 
    color:white; 
    font-weight:800; 
    min-width: 150px; 
}
.tagline{ 
    font-family:"Baloo 2"; 
    color:#fff; 
    font-weight:600; 
    font-size:20px; 
    flex-grow: 1; 
    text-align: center; 
    padding: 0 20px;
}
.header-placeholder {
    min-width: 150px; 
}

/* YELLOW NAV BAR DESIGN */
.nav-bar{ background:var(--nav-yellow); height:var(--nav-height); display:flex; align-items:center; }
.nav-list{ display:flex; justify-content:center; gap:30px; list-style:none; width:100%; margin:0; padding:0; }
.nav-link{ color:var(--nav-link-color); text-decoration:none; font-weight:600; font-size:18px; }
.nav-link.active{ text-decoration:underline; text-underline-offset:6px; font-weight:800; }
.nav-link:hover { color: var(--text-blue); }

/* --- MESSAGING CONTENT STYLES (Adjusted) --- */
.wrap{
    max-width:1000px;
    margin:40px auto; /* Adjusted top margin for spacing below nav */
    padding:20px;
    background:#fff;
    border-radius:12px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.06);
    display:flex;
    gap:20px;
}
.left{width:280px;border-right:1px solid #eee;padding-right:12px}
.right{flex:1;padding-left:12px}
.cleaner-item{padding:10px;border-radius:6px;margin-bottom:8px;background:#fafafa}
.chat{height:420px;overflow:auto;border:1px solid #eee;padding:12px;border-radius:6px;background:#fff}
.msg{margin:8px 0;padding:10px;border-radius:8px;max-width:70%;}
.msg.you{background:#dcf8c6;margin-left:auto}
.msg.they{background:#f1f0f0}
.form{margin-top:12px}
textarea{width:100%;height:80px;padding:8px;border-radius:6px}
button{
    padding:8px 12px;
    background:var(--text-blue);
    color:#fff;
    border:none;
    border-radius:6px;
    cursor: pointer;
}
h3 { font-family: "Baloo 2"; color: var(--text-blue); margin-top: 0; }
</style>
</head>
<body>

<header class="site-header">
  <div class="header-inner">
    <div class="logo-text">QuickClean</div>
    <div class="tagline">Clean Spaces, Happy Faces.</div>
    <span class="header-placeholder"></span> 
  </div>
</header>

<nav class="nav-bar">
  <ul class="nav-list">
    <li><a href="customer-home.php" class="nav-link">Home</a></li>
    <li><a href="user_page.php" class="nav-link">Dashboard</a></li>
    <li><a href="tracking.php" class="nav-link">Track Services</a></li>
    <li><a href="messages.php" class="nav-link active">Messages</a></li>
    <li><a href="logout.php" class="nav-link">Logout</a></li>
  </ul>
</nav>

<div class="wrap">
  <div class="left">
    <h3>Cleaners</h3>
    <?php while($c = $cleaners->fetch_assoc()): ?>
      <div class="cleaner-item">
        <a href="messages.php?c=<?php echo $c['user_id']; ?>"><?php echo htmlspecialchars($c['name']); ?></a>
      </div>
    <?php endwhile; ?>
  </div>

  <div class="right">
    <?php if (!$selected_cleaner): ?>
      <p>Select a cleaner at left to view conversation.</p>
    <?php else: ?>
      <h3>Conversation with <?php
          $r = $conn->prepare("SELECT name FROM user WHERE user_id = ?");
          $r->bind_param("i",$selected_cleaner);
          $r->execute();
          $row = $r->get_result()->fetch_assoc();
          echo htmlspecialchars($row['name']);
          $r->close();
      ?></h3>

      <div class="chat">
        <?php if ($conversation && $conversation->num_rows > 0): ?>
          <?php while($m = $conversation->fetch_assoc()): ?>
            <?php
              $isYou = ($m['sender_id'] == $user_id);
            ?>
            <div class="msg <?php echo $isYou ? 'you' : 'they'; ?>">
              <div style="font-size:12px;color:#666"><?php echo htmlspecialchars($m['sender_name']); ?></div>
              <div><?php echo nl2br(htmlspecialchars($m['message'])); ?></div>
              <div style="font-size:11px;color:#999;margin-top:6px"><?php echo $m['created_at']; ?></div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p>No messages yet. Send the first message below.</p>
        <?php endif; ?>
      </div>

      <div class="form">
        <form method="post" action="messages.php?c=<?php echo $selected_cleaner; ?>">
          <input type="hidden" name="cleaner_id" value="<?php echo $selected_cleaner; ?>">
          <textarea name="message" required placeholder="Type your message..."></textarea>
          <button type="submit" name="send_message">Send</button>
        </form>
      </div>
    <?php endif; ?>
  </div>
</div>

</body>
</html>