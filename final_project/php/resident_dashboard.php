<?php
include 'db.php';
include __DIR__ . '/session_check.php';

if ($_SESSION['role'] !== 'resident') {
    die("Unauthorized access.");
}
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Resident Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-primary">
  <div class="container-fluid d-flex justify-content-between">
    <span class="navbar-brand">Resident Dashboard</span>
    <div>
   <a href="edit_user.php" class="btn btn-outline-dark btn-sm ms-2">Edit Profile</a>
    <a href="logout.php" class="btn btn-outline-light">Logout</a>
  </div>
</div>
</nav>
<div class="container mt-3">
  <div class="alert alert-info">
    <strong>Welcome, <?= $_SESSION['name'] ?>!</strong><br>
    <strong>User ID:</strong> <?= $_SESSION['user_id'] ?> |
    <strong>Role:</strong> <?= ucfirst($_SESSION['role']) ?>
  </div>
</div>

<div class="container mt-4">
  <a href="../html/dispose_waste.php" class="btn btn-primary mb-3">Dispose Waste</a>

  <h4>My Waste Logs</h4>
  <table class="table table-bordered">
    <tr class="table-primary"><th>ID</th><th>Bin</th><th>Type</th><th>Qty</th><th>Time</th></tr>
    <?php
    $res = $conn->prepare("SELECT * FROM waste_disposal WHERE user_id = ?");
    $res->bind_param("i", $user_id);
    $res->execute();
    $result = $res->get_result();
    while ($row = $result->fetch_assoc()) {
      echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['bin_id']}</td>
        <td>{$row['waste_type']}</td>
        <td>{$row['quantity_kg']}kg</td>
        <td>{$row['disposal_time']}</td>
      </tr>";
    }
    ?>
  </table>

  <h4 class="mt-5">My Notifications</h4>
  <table class="table table-bordered">
    <tr class="table-primary"><th>Message</th><th>Time</th></tr>
    <?php
    $res = $conn->prepare("SELECT * FROM notifications WHERE user_id = ?");
    $res->bind_param("i", $user_id);
    $res->execute();
    $result = $res->get_result();
    while ($row = $result->fetch_assoc()) {
      echo "<tr>
        <td>{$row['message']}</td>
        <td>{$row['timestamp']}</td>
      </tr>";
    }
    ?>
  </table>
</div>
</body>
</html>
