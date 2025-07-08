<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'resident') {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];
$bin_id = $_POST['bin_id'] ?? null;
$category = $_POST['category'] ?? '';
$waste_type = $_POST['waste_type'] ?? '';
$quantity = $_POST['quantity_kg'] ?? 0;

if (!$bin_id || !$waste_type || !$category || !$quantity) {
    die("❌ All fields are required.");
}

// ✅ Validate bin is assigned to this user using assigned_bins table
$check = $conn->prepare("SELECT * FROM assigned_bins WHERE bin_id = ? AND user_id = ?");
$check->bind_param("ii", $bin_id, $user_id);
$check->execute();
$res = $check->get_result();

if ($res->num_rows === 0) {
    die("❌ This bin is not assigned to your account.");
}

// ✅ Get bin info
$bin_stmt = $conn->prepare("SELECT * FROM bins WHERE bin_id = ?");
$bin_stmt->bind_param("i", $bin_id);
$bin_stmt->execute();
$bin_result = $bin_stmt->get_result();
$bin = $bin_result->fetch_assoc();

if (!$bin) {
    die("❌ Bin not found.");
}

$new_level = $bin['current_level'] + $quantity;

if ($new_level > $bin['capacity']) {
    die("❌ Bin is full. Remaining capacity: " . ($bin['capacity'] - $bin['current_level']) . "kg.");
}

// ✅ Insert waste record
$log = $conn->prepare("INSERT INTO waste_disposal (user_id, bin_id, waste_type, category, quantity_kg) VALUES (?, ?, ?, ?, ?)");
$log->bind_param("isssd", $user_id, $bin_id, $waste_type, $category, $quantity);
$log->execute();

// ✅ Update bin level
$update = $conn->prepare("UPDATE bins SET current_level = ? WHERE bin_id = ?");
$update->bind_param("di", $new_level, $bin_id);
$update->execute();

// ✅ Redirect back to dashboard
header("Location: ../php/resident_dashboard.php");
exit;
?>
