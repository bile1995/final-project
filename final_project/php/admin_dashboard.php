<?php
include 'db.php';
include __DIR__ . '/session_check.php';

if ($_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid d-flex justify-content-between">
    <span class="navbar-brand">Admin Dashboard</span>
    <div>
      <a href="edit_user.php" class="btn btn-outline-primary btn-sm ms-2">Edit Profile</a>
      <a href="logout.php" class="btn btn-outline-light btn-sm ms-2">Logout</a>
    </div>
  </div>
</nav>

<!-- USER INFO BANNER -->
<div class="container mt-3">
  <div class="alert alert-info">
    <strong>Welcome, <?= $_SESSION['name'] ?>!</strong><br>
    <strong>User ID:</strong> <?= $_SESSION['user_id'] ?> |
    <strong>Role:</strong> <?= ucfirst($_SESSION['role']) ?>
  </div>
</div>

<div class="container mt-4">

  <!-- Add Bin & User Buttons -->
  <div class="mb-3 d-flex gap-2">
    <a href="../html/add_bin.php" class="btn btn-primary">➕ Add Bin</a>
    <a href="../html/add_user.html" class="btn btn-warning">➕ Add User</a>
  </div>

  <!-- Manage Users -->
  <h4>Users</h4>
  <table class="table table-bordered">
    <tr class="table-dark">
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Role</th>
      <th>Location</th>
      <th>Action</th>
    </tr>
    <?php
    $res = $conn->query("SELECT * FROM users");
    while ($row = $res->fetch_assoc()) {
      echo "<tr>
        <td>{$row['user_id']}</td>
        <td>{$row['name']}</td>
        <td>{$row['email']}</td>
        <td>{$row['role']}</td>
        <td>";
          if (!empty($row['building_number']) && !empty($row['unit_number'])) {
            echo "Building {$row['building_number']}, Unit {$row['unit_number']}";
          } elseif (!empty($row['house_address'])) {
            echo "House: {$row['house_address']}";
          } else {
            echo "Not Provided";
          }
        echo "</td>
        <td>
          <a href='edit_user_admin.php?id={$row['user_id']}' class='btn btn-sm btn-warning'>Edit</a>
          <a href='delete.php?type=user&id={$row['user_id']}' class='btn btn-sm btn-danger'>Delete</a>
        </td>
      </tr>";
    }
    ?>
  </table>

  <!-- Manage Bins -->
<h4 class="mt-5">Bins</h4>
<table class="table table-bordered">
  <tr class="table-dark">
    <th>ID</th><th>Location</th><th>Type</th><th>Capacity</th><th>Current</th><th>Assigned To</th><th>Action</th>
  </tr>
  <?php
  // Get all bins
  $bin_query = $conn->query("SELECT * FROM bins ORDER BY bin_id ASC");
  while ($bin = $bin_query->fetch_assoc()) {
    $bin_id = $bin['bin_id'];

    // Fetch all users assigned to this bin
    $assigned = '';
    $users_query = $conn->prepare("
      SELECT u.name, u.user_id, u.building_number, u.unit_number, u.house_address 
      FROM assigned_bins ab 
      JOIN users u ON ab.user_id = u.user_id 
      WHERE ab.bin_id = ?
    ");
    $users_query->bind_param("i", $bin_id);
    $users_query->execute();
    $assigned_result = $users_query->get_result();

    if ($assigned_result->num_rows > 0) {
      $assigned_array = [];
      while ($u = $assigned_result->fetch_assoc()) {
        $location_info = '';
        if (!empty($u['building_number']) && !empty($u['unit_number'])) {
          $location_info = " (Apt {$u['building_number']}-{$u['unit_number']})";
        } elseif (!empty($u['house_address'])) {
          $location_info = " (House: {$u['house_address']})";
        }
        $assigned_array[] = "{$u['name']} (ID: {$u['user_id']}){$location_info}";
      }
      $assigned = implode('<br>', $assigned_array); // Newline-separated list
    } else {
      $assigned = "Unassigned";
    }

    echo "<tr>
      <td>{$bin['bin_id']}</td>
      <td>{$bin['location']}</td>
      <td>{$bin['waste_type']}</td>
      <td>{$bin['capacity']}</td>
      <td>{$bin['current_level']}</td>
      <td>$assigned</td>
      <td>
        <a href='edit_bin.php?id={$bin['bin_id']}' class='btn btn-sm btn-warning me-1'>Edit</a>
        <a href='delete.php?type=bin&id={$bin['bin_id']}' class='btn btn-sm btn-danger'>Delete</a>
      </td>
    </tr>";
  }
  ?>
</table>


  <!-- Assign Collections -->
  <h4 class="mt-5">🛠 Assign Collections</h4>
  <form action="assign_collection.php" method="POST" class="row g-3 mb-4">
    <div class="col-md-4">
      <select name="collector_id" class="form-select" required>
        <option value="">Select Collector</option>
        <?php
        $collectors = $conn->query("SELECT * FROM users WHERE role='collector'");
        while ($c = $collectors->fetch_assoc()) {
          echo "<option value='{$c['user_id']}'>{$c['name']} ({$c['email']})</option>";
        }
        ?>
      </select>
    </div>
    <div class="col-md-4">
      <select name="bin_id" class="form-select" required>
        <option value="">Select Bin</option>
        <?php
        $bins = $conn->query("SELECT * FROM bins");
        while ($b = $bins->fetch_assoc()) {
          echo "<option value='{$b['bin_id']}'>{$b['bin_id']} - {$b['location']}</option>";
        }
        ?>
      </select>
    </div>
    <div class="col-md-3">
      <input type="datetime-local" name="collection_time" class="form-control" required>
    </div>
    <div class="col-md-1">
      <button class="btn btn-success w-100">Assign</button>
    </div>
  </form>

  <!-- Collections (Pending & Completed) -->
  <h4>📦 Due Collections</h4>
  <table class="table table-bordered">
    <tr class="table-dark">
      <th>ID</th><th>Collector</th><th>Bin</th><th>Time</th><th>Status</th><th>Action</th>
    </tr>
    <?php
    $res = $conn->query("SELECT c.*, u.name FROM collections c JOIN users u ON c.collector_id = u.user_id WHERE c.status = 'pending' ORDER BY c.collection_time ASC");
    while ($row = $res->fetch_assoc()) {
      echo "<tr>
        <td>{$row['collection_id']}</td>
        <td>{$row['name']}</td>
        <td>{$row['bin_id']}</td>
        <td>{$row['collection_time']}</td>
        <td>{$row['status']}</td>
        <td><a href='delete.php?type=collection&id={$row['collection_id']}' class='btn btn-sm btn-danger'>Delete</a></td>
      </tr>";
    }
    ?>
  </table>

  <h4 class="mt-5">✅ Completed Collections</h4>
  <table class="table table-bordered">
    <tr class="table-dark">
      <th>ID</th><th>Collector</th><th>Bin</th><th>Time</th><th>Status</th>
    </tr>
    <?php
    $res = $conn->query("SELECT c.*, u.name FROM collections c JOIN users u ON c.collector_id = u.user_id WHERE c.status = 'completed' ORDER BY c.collection_time DESC");
    while ($row = $res->fetch_assoc()) {
      echo "<tr>
        <td>{$row['collection_id']}</td>
        <td>{$row['name']}</td>
        <td>{$row['bin_id']}</td>
        <td>{$row['collection_time']}</td>
        <td>{$row['status']}</td>
      </tr>";
    }
    ?>
  </table>

  <!-- Waste Logs -->
  <h4 class="mt-5">♻️ Waste Logs</h4>
  <table class="table table-bordered">
    <tr class="table-dark">
      <th>ID</th><th>User</th><th>Bin</th><th>Type</th><th>Quantity (kg)</th><th>Time</th>
    </tr>
    <?php
    $res = $conn->query("SELECT w.*, u.name FROM waste_disposal w JOIN users u ON w.user_id = u.user_id ORDER BY w.disposal_time DESC");
    while ($row = $res->fetch_assoc()) {
      echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['name']}</td>
        <td>{$row['bin_id']}</td>
        <td>{$row['waste_type']}</td>
        <td>{$row['quantity_kg']}</td>
        <td>{$row['disposal_time']}</td>
      </tr>";
    }
    ?>
  </table>

</div>
</body>
</html>
