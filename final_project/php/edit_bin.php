<?php
include 'db.php';
session_start();

$bin_id = $_GET['id'] ?? null;
if (!$bin_id) die("❌ Bin ID not provided.");

// Fetch bin
$stmt = $conn->prepare("SELECT * FROM bins WHERE bin_id = ?");
$stmt->bind_param("i", $bin_id);
$stmt->execute();
$result = $stmt->get_result();
$bin = $result->fetch_assoc();
if (!$bin) die("❌ Bin not found.");

// Fetch all residents
$residents = $conn->query("SELECT user_id, name FROM users WHERE role = 'resident'");

// Fetch assigned users for this bin
$assigned_users = [];
$assigned_stmt = $conn->prepare("SELECT user_id FROM assigned_bins WHERE bin_id = ?");
$assigned_stmt->bind_param("i", $bin_id);
$assigned_stmt->execute();
$assigned_result = $assigned_stmt->get_result();
while ($row = $assigned_result->fetch_assoc()) {
    $assigned_users[] = $row['user_id'];
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Bin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 700px;">
  <h2 class="mb-4">Edit Bin</h2>

  <form action="../php/update_bin.php" method="POST">
    <input type="hidden" name="bin_id" value="<?= $bin['bin_id'] ?>">

    <div class="mb-3">
      <label class="form-label">Location</label>
      <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($bin['location']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Waste Type</label>
      <select name="waste_type" class="form-select" required>
        <?php
        $types = ['organic', 'plastic', 'metal', 'paper', 'mixed', 'hazardous'];
        foreach ($types as $type) {
          $selected = $bin['waste_type'] === $type ? 'selected' : '';
          echo "<option value='$type' $selected>" . ucfirst($type) . "</option>";
        }
        ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Capacity (kg)</label>
      <input type="number" name="capacity" class="form-control" value="<?= $bin['capacity'] ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Threshold (%)</label>
      <input type="number" name="threshold" class="form-control" value="<?= $bin['threshold'] ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Recyclability</label>
      <select name="recyclability" class="form-select" required>
        <option value="recyclable" <?= $bin['recyclability'] === 'recyclable' ? 'selected' : '' ?>>Recyclable</option>
        <option value="non-recyclable" <?= $bin['recyclability'] === 'non-recyclable' ? 'selected' : '' ?>>Non-Recyclable</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Assign to Residents (Optional)</label>
      <select name="assigned_users[]" class="form-select" multiple>
        <?php while ($r = $residents->fetch_assoc()): ?>
  <?php
    $selected = in_array($r['user_id'], $assigned_users) ? 'selected' : '';
    $b = $r['building_number'];
    $u = $r['unit_number'];
  ?>
  <option 
    value="<?= $r['user_id'] ?>" 
    <?= $selected ?>
    data-building="<?= htmlspecialchars($b) ?>" 
    data-unit="<?= htmlspecialchars($u) ?>">
    <?= htmlspecialchars($r['name']) ?> (ID: <?= $r['user_id'] ?>)
  </option>
<?php endwhile; ?>

      </select>
      <small class="text-muted">Hold Ctrl (or ⌘ on Mac) to select multiple users.</small>
    </div>

    <button type="submit" class="btn btn-primary">Update Bin</button>
    <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
<script>
document.querySelector("form").addEventListener("submit", function(e) {
  const location = document.querySelector("input[name='location']").value.trim();

  const match = location.match(/Apartment\s+(\d+)\s*-\s*Unit\s*(\w+)/i);
  if (match) {
    const apt = match[1];
    const unit = match[2];
    const options = document.querySelectorAll("select[name='assigned_users[]'] option");
    let matched = false;

    options.forEach(opt => {
      const building = opt.getAttribute("data-building");
      const unitNo = opt.getAttribute("data-unit");
      if (building === apt && unitNo === unit) {
        opt.selected = true;
        matched = true;
      }
    });

    if (matched) {
      let msg = document.getElementById("match-message");
      if (!msg) {
        msg = document.createElement("div");
        msg.id = "match-message";
        msg.className = "text-success mb-3";
        msg.innerText = "✅ Resident auto-selected from location.";
        document.querySelector("form").insertBefore(msg, document.querySelector("form").firstChild);
      }
      msg.style.display = "block";
      setTimeout(() => msg.style.display = "none", 5000);
    }
  }
});
</script>

</body>
</html>
