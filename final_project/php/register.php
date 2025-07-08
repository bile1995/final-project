<?php
include 'db.php';
session_start();

// Collect form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'resident';
$residence_type = $_POST['residence_type'] ?? null;
$building_number = $_POST['building_number'] ?? null;
$unit_number = $_POST['unit_number'] ?? null;
$house_address = $_POST['house_address'] ?? null;

// Validate required fields
if (!$name || !$email || !$password) {
    die("❌ All fields are required.");
}

// Check if email already exists
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    die("❌ User already exists with this email.");
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare SQL insert
$stmt = $conn->prepare("INSERT INTO users (name, email, password, role, residence_type, building_number, unit_number, house_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $name, $email, $hashed_password, $role, $residence_type, $building_number, $unit_number, $house_address);

if ($stmt->execute()) {
    // Store session
    $_SESSION['user_id'] = $stmt->insert_id;
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['role'] = $role;

    // Redirect to role-based dashboard
    header("Location: ../php/{$role}_dashboard.php");
    exit;
} else {
    die("❌ Failed to register.");
}
?>
