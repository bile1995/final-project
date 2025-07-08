<?php
include 'db.php';

$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : (isset($_GET['user_id']) ? $_GET['user_id'] : null);

if (!$user_id) {
    die("❌ User ID not provided.");
}

$stmt = $conn->prepare("SELECT message, timestamp FROM notifications WHERE user_id = ? ORDER BY timestamp DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>Your Notifications</h2>";
while ($row = $result->fetch_assoc()) {
    echo "<p><strong>{$row['timestamp']}:</strong> {$row['message']}</p>";
}
?>
