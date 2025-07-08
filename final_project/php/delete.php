<?php
include 'db.php';
include 'session_check.php';

if ($_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

$type = $_GET['type'];
$id = $_GET['id'];

switch ($type) {
    case 'user':
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        break;
    case 'bin':
        $stmt = $conn->prepare("DELETE FROM bins WHERE bin_id = ?");
        break;
    case 'collection':
        $stmt = $conn->prepare("DELETE FROM collections WHERE collection_id = ?");
        break;
    default:
        die("Invalid delete type.");
}

$stmt->bind_param("i", $id);
$stmt->execute();
header("Location: admin_dashboard.php");
exit;
?>
