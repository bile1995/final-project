<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $location = $_POST['location'] ?? '';
    $waste_type = $_POST['waste_type'] ?? '';
    $capacity = $_POST['capacity'] ?? 0;
    $threshold = $_POST['threshold'] ?? 80;
    $recyclability = $_POST['recyclability'] ?? '';
    $assigned_user_id = $_POST['assigned_user_id'] ?? null;

    if (!$location || !$waste_type || !$recyclability || $capacity <= 0) {
        die("❌ Please fill all required fields.");
    }

    // Insert bin (without assigned_user_id)
    $stmt = $conn->prepare("INSERT INTO bins (location, waste_type, capacity, threshold, recyclability) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdds", $location, $waste_type, $capacity, $threshold, $recyclability);

    if (!$stmt->execute()) {
        die("❌ Failed to add bin: " . $stmt->error);
    }

    // Get the last inserted bin ID
    $bin_id = $conn->insert_id;

    // Assign bin to user (if selected)
    if (!empty($assigned_user_id)) {
        $assign = $conn->prepare("INSERT INTO assigned_bins (bin_id, user_id) VALUES (?, ?)");
        $assign->bind_param("ii", $bin_id, $assigned_user_id);
        $assign->execute();
    }

    // Confirmation & redirect
    echo "
    <!DOCTYPE html>
    <html>
    <head>
      <title>Bin Added</title>
      <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
      <meta http-equiv='refresh' content='5;url=admin_dashboard.php'>
    </head>
    <body class='bg-light'>
      <div class='container mt-5'>
        <div class='alert alert-success'>
          ✅ Bin added successfully! Redirecting to dashboard in 5 seconds...
        </div>
      </div>
    </body>
    </html>";
    exit;
}
?>
