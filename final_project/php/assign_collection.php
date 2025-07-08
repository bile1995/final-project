<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $collector_id = $_POST['collector_id'];
    $bin_id = $_POST['bin_id'];

    // ✅ Validate collector
    $check = $conn->prepare("SELECT * FROM users WHERE user_id = ? AND role = 'collector'");
    $check->bind_param("i", $collector_id);
    $check->execute();
    $res = $check->get_result();
    if ($res->num_rows === 0) {
        die("❌ Invalid collector ID.");
    }

    // ✅ Check if bin exists and has garbage
    $bin_check = $conn->prepare("SELECT current_level FROM bins WHERE bin_id = ?");
    $bin_check->bind_param("i", $bin_id);
    $bin_check->execute();
    $bin_result = $bin_check->get_result();

    if ($bin_result->num_rows === 0) {
        die("❌ Bin ID not found.");
    }

    $bin = $bin_result->fetch_assoc();
    if ($bin['current_level'] == 0) {
        die("❌ Bin is empty. No collection needed.");
    }

    // ✅ Record collection assignment
    $stmt = $conn->prepare("INSERT INTO collections (collector_id, bin_id, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("ii", $collector_id, $bin_id);
    $stmt->execute();

    echo "✅ Collection assigned successfully!";
}
?>
