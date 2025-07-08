<?php
include 'db.php';
include __DIR__ . '/session_check.php';

if ($_SESSION['role'] !== 'collector') {
    die("Unauthorized access.");
}

$collector_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Collector Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-success">
  <div class="container-fluid d-flex justify-content-between">
    <span class="navbar-brand">Collector Dashboard</span>
    <div>
      <a href="edit_user.php" class="btn btn-info mb-3">Edit Profile</a>
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

  <?php if (isset($_GET['success']) && $_GET['success'] === 'collection'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      ✅ Collection recorded successfully!
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
</div>

<div class="container mt-4">
  <a href="../html/record_collection.html" class="btn btn-info mb-3">Record Collection</a>

  <!-- BIN LIST -->
  <h4>🗑️ All Bins</h4>
  <table class="table table-bordered">
    <thead class="table-success">
      <tr><th>ID</th><th>Location</th><th>Level</th><th>Capacity</th><th>Fill %</th></tr>
    </thead>
    <tbody>
    <?php
    $res = $conn->query("SELECT * FROM bins");
    while ($row = $res->fetch_assoc()) {
        $fill_percent = round(($row['current_level'] / $row['capacity']) * 100);
        echo "<tr>
          <td>{$row['bin_id']}</td>
          <td>{$row['location']}</td>
          <td>{$row['current_level']}</td>
          <td>{$row['capacity']}</td>
          <td>{$fill_percent}%</td>
        </tr>";
    }
    ?>
    </tbody>
  </table>

  <!-- PENDING COLLECTIONS -->
  <h4 class="mt-5">🛠 Pending Assigned Collections</h4>
  <table class="table table-bordered">
    <thead class="table-warning">
      <tr><th>Collection ID</th><th>Bin</th><th>Time</th><th>Status</th></tr>
    </thead>
    <tbody>
    <?php
    $stmt = $conn->prepare("SELECT * FROM collections WHERE collector_id = ? AND status = 'pending' ORDER BY collection_time ASC");
    $stmt->bind_param("i", $collector_id);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
      echo "<tr>
        <td>{$row['collection_id']}</td>
        <td>{$row['bin_id']}</td>
        <td>{$row['collection_time']}</td>
        <td>{$row['status']}</td>
      </tr>";
    }
    ?>
    </tbody>
  </table>

  <!-- COMPLETED COLLECTION LOG -->
  <h4 class="mt-5">✅ Collection Log</h4>
  <table class="table table-bordered">
    <thead class="table-secondary">
      <tr><th>Collection ID</th><th>Bin</th><th>Time</th><th>Status</th></tr>
    </thead>
    <tbody>
    <?php
    $stmt = $conn->prepare("SELECT * FROM collections WHERE collector_id = ? AND status = 'completed' ORDER BY collection_time DESC");
    $stmt->bind_param("i", $collector_id);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
      echo "<tr>
        <td>{$row['collection_id']}</td>
        <td>{$row['bin_id']}</td>
        <td>{$row['collection_time']}</td>
        <td>{$row['status']}</td>
      </tr>";
    }
    ?>
    </tbody>
  </table>
</div>

<!-- Optional JS for Bootstrap Alert Dismiss -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
