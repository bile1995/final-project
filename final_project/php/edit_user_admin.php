<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

if (!isset($_GET['id'])) {
    die("User ID not provided.");
}

$user_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 600px;">
  <h2 class="mb-4">Edit User (Admin)</h2>

  <form action="update_user_admin.php" method="POST">
    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">

    <div class="mb-3">
      <label class="form-label">Name:</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Email:</label>
      <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Role:</label>
      <select name="role" class="form-select" required>
        <option value="resident" <?= $user['role'] === 'resident' ? 'selected' : '' ?>>Resident</option>
        <option value="collector" <?= $user['role'] === 'collector' ? 'selected' : '' ?>>Collector</option>
        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
      </select>
    </div>

    <!-- Residence Type -->
    <div class="mb-3">
      <label class="form-label">Residence Type</label>
      <select name="residence_type" id="residence_type" class="form-select">
        <option value="">-- Select Type --</option>
        <option value="apartment" <?= $user['building_number'] ? 'selected' : '' ?>>Apartment</option>
        <option value="house" <?= $user['house_address'] ? 'selected' : '' ?>>House</option>
      </select>
    </div>

    <!-- Apartment Fields -->
    <div id="apartment-fields" style="display: <?= $user['building_number'] ? 'block' : 'none' ?>;">
      <div class="mb-3">
        <label class="form-label">Building Number</label>
        <input type="text" name="building_number" class="form-control" value="<?= htmlspecialchars($user['building_number']) ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Unit Number</label>
        <input type="text" name="unit_number" class="form-control" value="<?= htmlspecialchars($user['unit_number']) ?>">
      </div>
    </div>

    <!-- House Fields -->
    <div id="house-fields" style="display: <?= $user['house_address'] ? 'block' : 'none' ?>;">
      <div class="mb-3">
        <label class="form-label">House Address</label>
        <input type="text" name="house_address" class="form-control" value="<?= htmlspecialchars($user['house_address']) ?>">
      </div>
    </div>

    <!-- Password Reset -->
    <div class="mb-3">
      <label class="form-label">New Password (optional):</label>
      <input type="password" name="new_password" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Update User</button>
    <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<script>
  const typeSelect = document.getElementById("residence_type");
  const aptFields = document.getElementById("apartment-fields");
  const houseFields = document.getElementById("house-fields");

  if (typeSelect) {
    typeSelect.addEventListener("change", function () {
      if (this.value === "apartment") {
        aptFields.style.display = "block";
        houseFields.style.display = "none";
      } else if (this.value === "house") {
        aptFields.style.display = "none";
        houseFields.style.display = "block";
      } else {
        aptFields.style.display = "none";
        houseFields.style.display = "none";
      }
    });
  }
</script>
</body>
</html>
