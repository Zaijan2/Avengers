<?php
// --- DATABASE CONNECTION ---
$host = "localhost";
$user = "root";
$pass = "";
$db   = "quickclean"; // change this to your database name

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
};
error_reporting(0);

// ADD SERVICE
if (isset($_POST['add_service'])) {
  $name = $_POST['service_name'];
  $desc = $_POST['description'];
  $price = $_POST['price'];
  
  // Handle image upload
  $target_dir = "uploads/";
  if (!is_dir($target_dir)) mkdir($target_dir);
  $image = $_FILES["image"]["name"];
  $target_file = $target_dir . basename($image);
  move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

  $sql = "INSERT INTO services (service_name, image, description, price) 
          VALUES ('$name', '$image', '$desc', '$price')";
  $conn->query($sql);
  header("Location: services.php");
  exit();
}

// DELETE SERVICE
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $result = $conn->query("SELECT image FROM services WHERE service_id=$id");
  $row = $result->fetch_assoc();
  if ($row && $row['image'] && file_exists("uploads/".$row['image'])) {
    unlink("uploads/".$row['image']);
  }
  $conn->query("DELETE FROM services WHERE service_id=$id");
  header("Location: services.php");
  exit();
}

// UPDATE SERVICE
if (isset($_POST['update_service'])) {
  $id = $_POST['service_id'];
  $name = $_POST['service_name'];
  $desc = $_POST['description'];
  $price = $_POST['price'];
  
  // Optional new image
  if (!empty($_FILES["image"]["name"])) {
    $target_dir = "uploads/";
    $image = $_FILES["image"]["name"];
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    $conn->query("UPDATE services SET service_name='$name', description='$desc', price='$price', image='$image' WHERE service_id=$id");
  } else {
    $conn->query("UPDATE services SET service_name='$name', description='$desc', price='$price' WHERE service_id=$id");
  }

  header("Location: services.php");
  exit();
}

// FETCH SERVICES
$result = $conn->query("SELECT * FROM services ORDER BY service_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Services - QuickClean</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: "Poppins", sans-serif; display: flex; min-height: 100vh; background: #f5f6fa; }
    .sidebar { width: 240px; background: #5da0e4ff; color: #fff; padding: 20px 0; position: fixed; height: 100%; }
    .sidebar h2 { text-align: center; margin-bottom: 30px; }
    .sidebar ul { list-style: none; }
    .sidebar ul li { padding: 15px 20px; }
    .sidebar ul li a { text-decoration: none; color: #fff; display: block; transition: 0.3s; }
    .sidebar ul li a:hover, .sidebar ul li a.active { background: #1c6ed6; border-radius: 6px; }
    .main-content { margin-left: 240px; padding: 20px; width: 100%; }
    .topbar { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 10px 20px; border-radius: 8px; margin-bottom: 20px; }
    .services-section { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; vertical-align: middle; }
    th { background: #f1f1f1; }
    td img { width: 80px; height: 80px; object-fit: cover; border-radius: 6px; }
    .add-btn { background: #2E89F0; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; margin-bottom: 15px; }
    .actions button, .actions a { margin-right: 6px; padding: 6px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; }
    .edit-btn { background: #ffdb58; }
    .delete-btn { background: #ff6b6b; color: #fff; text-decoration: none; }
    form input, form textarea { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 6px; }
  </style>
</head>
<body>
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

  <div class="main-content">
    <div class="topbar">
      <h1>Manage Services</h1>
    </div>

    <div class="services-section">
      <h2>Services List</h2>
      <button class="add-btn" onclick="document.getElementById('addForm').style.display='block'">+ Add New Service</button>

      <!-- Add Service Form -->
      <form id="addForm" method="POST" enctype="multipart/form-data" style="display:none;">
        <input type="text" name="service_name" placeholder="Service Name" required>
        <input type="file" name="image" accept="image/*" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="number" name="price" placeholder="Price (₱)" required>
        <button type="submit" name="add_service">Save</button>
      </form>

      <table>
        <thead>
          <tr>
            <th>Image</th>
            <th>Service Name</th>
            <th>Description</th>
            <th>Price (₱)</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><img src="uploads/<?= $row['image'] ?>" alt="Service"></td>
            <td><?= $row['service_name'] ?></td>
            <td><?= $row['description'] ?></td>
            <td><?= $row['price'] ?></td>
            <td class="actions">
              <form method="POST" enctype="multipart/form-data" style="display:inline;">
                <input type="hidden" name="service_id" value="<?= $row['service_id'] ?>">
                <input type="text" name="service_name" value="<?= $row['service_name'] ?>" required>
                <textarea name="description" required><?= $row['description'] ?></textarea>
                <input type="number" name="price" value="<?= $row['price'] ?>" required>
                <input type="file" name="image" accept="image/*">
                <button type="submit" name="update_service" class="edit-btn">Update</button>
              </form>
              <a href="?delete=<?= $row['service_id'] ?>" onclick="return confirm('Delete this service?')" class="delete-btn">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
