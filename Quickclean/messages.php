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
<html>
<head>
<meta charset="utf-8">
<title>Messages</title>
<style>
body{font-family:Arial;background:#f4f6f8;margin:0}
.wrap{max-width:1000px;margin:24px auto;padding:20px;background:#fff;border-radius:8px;display:flex;gap:20px}
.left{width:280px;border-right:1px solid #eee;padding-right:12px}
.right{flex:1;padding-left:12px}
.cleaner-item{padding:10px;border-radius:6px;margin-bottom:8px;background:#fafafa}
.chat{height:420px;overflow:auto;border:1px solid #eee;padding:12px;border-radius:6px;background:#fff}
.msg{margin:8px 0;padding:10px;border-radius:8px;max-width:70%;}
.msg.you{background:#dcf8c6;margin-left:auto}
.msg.they{background:#f1f0f0}
.form{margin-top:12px}
textarea{width:100%;height:80px;padding:8px;border-radius:6px}
button{padding:8px 12px;background:#2E89F0;color:#fff;border:none;border-radius:6px}
</style>
</head>
<body>

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
