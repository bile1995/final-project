<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("❌ Unauthorized access.");
}

// Get values from form
$user_id = $_POST['user_id'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$role = $_POST['role'] ?? '';
$residence_type = $_POST['residence_type'] ?? '';
$building_number = $_POST['building_number'] ?? null;
$unit_number = $_POST['unit_number'] ?? null;
$house_address = $_POST['house_address'] ?? null;
$new_password = $_POST['new_password'] ?? '';

if (!$user_id || !$name || !$email || !$role) {
    die("❌ Missing required fields.");
}

// Check if email is already used by another user
$check_email = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
$check_email->bind_param("si", $email, $user_id);
$check_email->execute();
$check_email->store_result();
if ($check_email->num_rows > 0) {
    die("❌ Email already in use.");
}
$check_email->close();

// Build dynamic update query
$fields = "name = ?, email = ?, role = ?, residence_type = ?";
$params = [$name, $email, $role, $residence_type];
$types = "ssss";

// Handle residence fields
if ($residence_type === 'apartment') {
    $fields .= ", building_number = ?, unit_number = ?, house_address = NULL";
    $params[] = $building_number;
    $params[] = $unit_number;
    $types .= "ss";
} elseif ($residence_type === 'house') {
    $fields .= ", house_address = ?, building_number = NULL, unit_number = NULL";
    $params[] = $house_address;
    $types .= "s";
}

// Handle password update (optional)
if (!empty($new_password)) {
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $fields .= ", password = ?";
    $params[] = $hashed;
    $types .= "s";
}

// Add user ID to the end
$params[] = $user_id;
$types .= "i";

// Execute update
$sql = "UPDATE users SET $fields WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    header("Location: admin_dashboard.php");
    exit;
} else {
    die("❌ Failed to update user: " . $stmt->error);
}
?>
