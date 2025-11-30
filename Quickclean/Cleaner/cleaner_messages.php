<?php
include 'db.php';
require_login();

if ($_SESSION['role'] !== 'cleaner') {
    echo "Access denied.";
    exit();
}

$cleaner_id = $_SESSION['user_id'];

// send reply
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_reply'])) {
    $user_id = intval($_POST['user_id']);
    $message = trim($_POST['message']);
    if ($user_id && $message !== '') {
        $ins = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $ins->bind_param("iis", $cleaner_id, $user_id, $message);
        $ins->execute();
        $ins->close();
        header("Location: cleaner_messages.php?u=".$user_id);
        exit();
    }
}

// users who chatted with this cleaner
$users = $conn->query("SELECT DISTINCT u.user_id, u.name FROM messages m JOIN user u ON (u.user_id = m.sender_id OR u.user_id = m.receiver_id) WHERE (m.sender_id = $cleaner_id OR m.receiver_id = $cleaner_id) AND u.user_id != $cleaner_id");

$selected_user = isset($_GET['u']) ? intval($_GET['u']) : 0;
$conversation = [];
if ($selected_user) {
    $q = $conn->prepare("SELECT m.sender_id, m.receiver_id, m.message, m.created_at, u.name as sender_name
        FROM messages m
        LEFT JOIN user u ON u.user_id = m.sender_id
        WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?)
        ORDER BY m.created_at ASC");
    $q->bind_param("iiii", $cleaner_id, $selected_user, $selected_user, $cleaner_id);
    $q->execute();
    $conversation = $q->get_result();
    $q->close();
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Cleaner Messages</title>
<style>
body{font-family:Arial;background:#f4f6f8;margin:0}
.wrap{max-width:1000px;margin:24px auto;padding:20px;background:#fff;border-radius:8px;display:flex;gap:20px}
.left{width:280px;border-right:1px solid #eee;padding-right:12px}
.right{flex:1;padding-left:12px}
.cleaner-item{padding:10px;border-radius:6px;margin-bottom:8px;background:#fafafa}
.chat{height:420px;overflow:auto;border:1px solid #eee;padding:12px;border-radius:6px;background:#fff}
.msg{margin:8px 0;padding:10px;border-radius:8px;max-width:70%;}
.msg.you{background:#e9f2ff}
.msg.they{background:#f1f0f0}
textarea{width:100%;height:80px;padding:8px;border-radius:6px}
button{padding:8px 12px;background:#2E89F0;color:#fff;border:none;border-radius:6px}
</style>
</head>
<body>
<div class="wrap">
  <div class="left">
    <h3>Conversations</h3>
    <?php while($u = $users->fetch_assoc()): ?>
      <div class="cleaner-item"><a href="cleaner_messages.php?u=<?php echo $u['user_id']; ?>"><?php echo htmlspecialchars($u['name']); ?></a></div>
    <?php endwhile; ?>
  </div>

  <div class="right">
    <?php if (!$selected_user): ?>
      <p>Select a user to view conversation</p>
    <?php else: ?>
      <h3>Conversation with
        <?php
          $r = $conn->prepare("SELECT name FROM user WHERE user_id = ?");
          $r->bind_param("i",$selected_user);
          $r->execute();
          $ro = $r->get_result()->fetch_assoc();
          echo htmlspecialchars($ro['name']);
          $r->close();
        ?>
      </h3>

      <div class="chat">
        <?php if ($conversation && $conversation->num_rows > 0): ?>
          <?php while($m = $conversation->fetch_assoc()): ?>
            <?php $isCleaner = ($m['sender_id'] == $cleaner_id); ?>
            <div class="msg <?php echo $isCleaner ? 'you' : 'they'; ?>">
              <div style="font-size:12px;color:#666"><?php echo htmlspecialchars($m['sender_name']); ?></div>
              <div><?php echo nl2br(htmlspecialchars($m['message'])); ?></div>
              <div style="font-size:11px;color:#999;margin-top:6px"><?php echo $m['created_at']; ?></div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p>No messages yet.</p>
        <?php endif; ?>
      </div>

      <div style="margin-top:12px;">
        <form method="post" action="cleaner_messages.php?u=<?php echo $selected_user; ?>">
          <input type="hidden" name="user_id" value="<?php echo $selected_user; ?>">
          <textarea name="message" required></textarea>
          <button type="submit" name="send_reply">Reply</button>
        </form>
      </div>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
