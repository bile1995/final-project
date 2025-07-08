<!DOCTYPE html>
<html>
<head>
  <title>Add Bin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    #match-message {
      display: none;
    }
  </style>
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 700px;">
  <h2 class="mb-4">Add Bin</h2>

  <form action="../php/add_bin.php" method="POST">
    
    <!-- Location Type -->
    <div class="mb-3">
      <label for="location_type" class="form-label">Location Type</label>
      <select id="location_type" class="form-select" onchange="toggleLocationFields()" required>
        <option value="">-- Select --</option>
        <option value="apartment">Apartment</option>
        <option value="house">House</option>
      </select>
    </div>

    <!-- Apartment Fields -->
    <div id="apartment-fields" style="display: none;">
      <div class="mb-3">
        <label class="form-label">Apartment Number</label>
        <input type="text" id="apartment_number" class="form-control" placeholder="e.g., 21">
      </div>
      <div class="mb-3">
        <label class="form-label">Unit Number</label>
        <input type="text" id="unit_number" class="form-control" placeholder="e.g., 5B">
      </div>
    </div>

    <!-- House Fields -->
    <div id="house-fields" style="display: none;">
      <div class="mb-3">
        <label class="form-label">House Address</label>
        <input type="text" id="house_address" class="form-control" placeholder="e.g., House #10">
      </div>
    </div>

    <!-- Final location value -->
    <input type="hidden" name="location" id="location">

    <!-- Waste Type -->
    <div class="mb-3">
      <label for="waste_type" class="form-label">Waste Type</label>
      <select name="waste_type" class="form-select" required>
        <option value="organic">Organic</option>
        <option value="plastic">Plastic</option>
        <option value="metal">Metal</option>
        <option value="paper">Paper</option>
        <option value="mixed">Mixed</option>
        <option value="hazardous">Hazardous</option>
      </select>
    </div>

    <!-- Recyclability -->
    <div class="mb-3">
      <label for="recyclability" class="form-label">Recyclability</label>
      <select name="recyclability" class="form-select" required>
        <option value="recyclable">Recyclable</option>
        <option value="non-recyclable">Non-Recyclable</option>
      </select>
    </div>

    <!-- Capacity -->
    <div class="mb-3">
      <label for="capacity" class="form-label">Capacity (kg)</label>
      <input type="number" name="capacity" class="form-control" required>
    </div>

    <!-- Threshold -->
    <div class="mb-3">
      <label for="threshold" class="form-label">Fullness Threshold (%)</label>
      <input type="number" name="threshold" class="form-control" value="80" min="1" max="100" required>
    </div>

    <!-- Assigned Resident (Optional) -->
    <div class="mb-3">
      <label for="assigned_user_id" class="form-label">Assign to Resident (Optional)</label>
      <select name="assigned_user_id" id="assigned_user_id" class="form-select">
        <option value="">-- No Assignment --</option>
        <?php
        include '../php/db.php';
        $residents = $conn->query("SELECT user_id, name, building_number, unit_number FROM users WHERE role = 'resident'");
        while ($r = $residents->fetch_assoc()) {
            $label = "{$r['name']} - Apt {$r['building_number']} - Unit {$r['unit_number']} (ID: {$r['user_id']})";
            echo "<option value='{$r['user_id']}' data-building='{$r['building_number']}' data-unit='{$r['unit_number']}'>{$label}</option>";
        }
        ?>
      </select>
      <div id="match-message" class="text-success mt-2">✅ Resident auto-selected from location.</div>
    </div>

    <button type="submit" class="btn btn-success w-100">Add Bin</button>
  </form>
</div>

<script>
function toggleLocationFields() {
  const type = document.getElementById("location_type").value;
  document.getElementById("apartment-fields").style.display = type === "apartment" ? "block" : "none";
  document.getElementById("house-fields").style.display = type === "house" ? "block" : "none";
}

document.querySelector("form").addEventListener("submit", function(e) {
  const type = document.getElementById("location_type").value;
  let location = "";

  if (type === "apartment") {
    const apt = document.getElementById("apartment_number").value.trim();
    const unit = document.getElementById("unit_number").value.trim();
    location = `Apartment ${apt}` + (unit ? ` - Unit ${unit}` : '');

    const options = document.querySelectorAll("#assigned_user_id option");
    let matched = false;
    options.forEach(opt => {
      const b = opt.dataset.building;
      const u = opt.dataset.unit;
      if (b === apt && u === unit) {
        opt.selected = true;
        matched = true;
      }
    });

    if (matched) {
      const msg = document.getElementById("match-message");
      msg.style.display = "block";
      setTimeout(() => msg.style.display = "none", 5000);
    }

  } else if (type === "house") {
    location = document.getElementById("house_address").value.trim();
  }

  document.getElementById("location").value = location;
});
</script>
</body>
</html>
