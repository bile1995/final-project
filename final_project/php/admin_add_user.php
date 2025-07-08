<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];
$building = $_POST['building_number'] ?? null;
$unit = $_POST['unit_number'] ?? null;

// Check if email already exists
$check = $conn->prepare("SELECT * FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$res = $check->get_result();
if ($res->num_rows > 0) {
    die("❌ Email already in use.");
}

// Insert user
$stmt = $conn->prepare("INSERT INTO users (name, email, password, role, building_number, unit_number) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $name, $email, $password, $role, $building, $unit);
if ($stmt->execute()) {
    echo "✅ User added successfully! <a href='admin_dashboard.php'>Return to Dashboard</a>";
    header("Location: admin_dashboard.php");
exit();

} else {
    echo "❌ Failed to add user.";
}
?>
