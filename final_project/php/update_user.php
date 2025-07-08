<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$residence_type = $_POST['residence_type'] ?? '';
$building_number = $_POST['building_number'] ?? null;
$unit_number = $_POST['unit_number'] ?? null;
$house_address = $_POST['house_address'] ?? null;
$current_password = $_POST['current_password'] ?? null;
$new_password = $_POST['new_password'] ?? null;

if (!$name || !$email) {
    die("❌ Name and email are required.");
}

// Check for duplicate email
$check_email = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
$check_email->bind_param("si", $email, $user_id);
$check_email->execute();
$check_email->store_result();
if ($check_email->num_rows > 0) {
    die("❌ Email already in use.");
}
$check_email->close();

// Build query
$fields = "name = ?, email = ?";
$params = [$name, $email];
$types = "ss";

if (isset($_SESSION['role']) && $_SESSION['role'] === 'resident') {
    $fields .= ", residence_type = ?";
    $params[] = $residence_type;
    $types .= "s";

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
}

// Handle password change
if (!empty($new_password)) {
    if (empty($current_password)) {
        die("❌ Current password is required to change password.");
    }
    $get_pass = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $get_pass->bind_param("i", $user_id);
    $get_pass->execute();
    $res = $get_pass->get_result();
    $data = $res->fetch_assoc();
    if (!password_verify($current_password, $data['password'])) {
        die("❌ Current password is incorrect.");
    }
    $hashed_new = password_hash($new_password, PASSWORD_DEFAULT);
    $fields .= ", password = ?";
    $params[] = $hashed_new;
    $types .= "s";
}

$params[] = $user_id;
$types .= "i";

$sql = "UPDATE users SET $fields WHERE user_id = ?";
$update = $conn->prepare($sql);
$update->bind_param($types, ...$params);

if ($update->execute()) {
    echo "✅ Profile updated successfully!";
    $role = $_SESSION['role'];
    header("Location: {$role}_dashboard.php");
    exit;
} else {
    echo "❌ Failed to update profile.";
}
?>
