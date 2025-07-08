<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $bin_id = $_POST['bin_id'] ?? null;
    $location = $_POST['location'] ?? '';
    $waste_type = $_POST['waste_type'] ?? '';
    $capacity = $_POST['capacity'] ?? 0;
    $threshold = $_POST['threshold'] ?? 80;
    $recyclability = $_POST['recyclability'] ?? '';
    $assigned_users = $_POST['assigned_users'] ?? []; // Now expecting an array

    if (!$bin_id || !$location || !$waste_type || !$recyclability || $capacity <= 0) {
        die("❌ Missing or invalid fields.");
    }

    // ✅ Update bin details
    $stmt = $conn->prepare("UPDATE bins SET location = ?, waste_type = ?, capacity = ?, threshold = ?, recyclability = ? WHERE bin_id = ?");
    $stmt->bind_param("sssddi", $location, $waste_type, $capacity, $threshold, $recyclability, $bin_id);
    $stmt->execute();

    // ✅ Remove all existing user assignments for this bin
    $delete = $conn->prepare("DELETE FROM assigned_bins WHERE bin_id = ?");
    $delete->bind_param("i", $bin_id);
    $delete->execute();

    // ✅ Insert all selected users
    if (!empty($assigned_users)) {
        $insert = $conn->prepare("INSERT INTO assigned_bins (user_id, bin_id) VALUES (?, ?)");
        foreach ($assigned_users as $user_id) {
            $insert->bind_param("ii", $user_id, $bin_id);
            $insert->execute();
        }
    }

    header("Location: admin_dashboard.php");
    exit;
}
?>
