<?php
include '../php/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'resident') {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];

// Fetch bins assigned to this user using assigned_bins table
$query = $conn->prepare("
    SELECT b.* 
    FROM bins b 
    INNER JOIN assigned_bins ab ON b.bin_id = ab.bin_id 
    WHERE ab.user_id = ?
");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$bins = [];
while ($row = $result->fetch_assoc()) {
    $bins[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dispose Waste</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
    function updateWasteTypes() {
      const category = document.getElementById("category").value;
      const wasteTypeSelect = document.getElementById("waste_type");
      let options = "";

      if (category === "recyclable") {
        options = `
          <option value="plastic">Plastic</option>
          <option value="metal">Metal</option>
          <option value="paper">Paper</option>
        `;
      } else if (category === "non-recyclable") {
        options = `
          <option value="organic">Organic</option>
          <option value="mixed">Mixed Waste</option>
          <option value="hazardous">Hazardous</option>
        `;
      }
      wasteTypeSelect.innerHTML = options;
    }
  </script>
</head>
<body class="bg-light">
  <div class="container mt-5" style="max-width: 600px;">
    <h2 class="mb-4">Dispose Waste</h2>

    <?php if (empty($bins)): ?>
      <div class="alert alert-warning">❌ No bins assigned to your account.</div>
    <?php else: ?>
    <form action="../php/dispose_waste.php" method="POST">
      <!-- Bin Selection -->
      <div class="mb-3">
        <label class="form-label">Select Bin</label>
        <select name="bin_id" class="form-select" required>
          <option value="">-- Select Bin --</option>
          <?php foreach ($bins as $bin): ?>
            <option value="<?= $bin['bin_id'] ?>">
              <?= htmlspecialchars($bin['location']) ?> (<?= $bin['waste_type'] ?>, <?= $bin['recyclability'] ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Category -->
      <div class="mb-3">
        <label class="form-label">Waste Category</label>
        <select name="category" id="category" class="form-select" onchange="updateWasteTypes()" required>
          <option value="">-- Select Category --</option>
          <option value="recyclable">Recyclable</option>
          <option value="non-recyclable">Non-Recyclable</option>
        </select>
      </div>

      <!-- Waste Type -->
      <div class="mb-3">
        <label class="form-label">Waste Type</label>
        <select name="waste_type" id="waste_type" class="form-select" required>
          <option value="">-- Select Waste Type --</option>
        </select>
      </div>

      <!-- Quantity -->
      <div class="mb-3">
        <label class="form-label">Quantity (kg)</label>
        <input type="number" name="quantity_kg" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-success w-100">Submit</button>
    </form>
    <?php endif; ?>
  </div>
</body>
</html>
