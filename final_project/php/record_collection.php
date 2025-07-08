<?php
include 'db.php';
session_start();

// Ensure the user is logged in and is a collector
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'collector') {
    die("❌ Unauthorized access.");
}

$collector_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bin_id = $_POST['bin_id'];

    // ✅ Validate collector
    $check = $conn->prepare("SELECT * FROM users WHERE user_id = ? AND role = 'collector'");
    $check->bind_param("i", $collector_id);
    $check->execute();
    $res = $check->get_result();
    if ($res->num_rows === 0) {
        die("❌ Invalid collector ID.");
    }

    // ✅ Check if bin exists and is NOT empty
    $bin_check = $conn->prepare("SELECT current_level FROM bins WHERE bin_id = ?");
    $bin_check->bind_param("i", $bin_id);
    $bin_check->execute();
    $bin_result = $bin_check->get_result();

    if ($bin_result->num_rows === 0) {
        die("❌ Bin ID not found.");
    }

    $bin = $bin_result->fetch_assoc();
    if ($bin['current_level'] == 0) {
        die("❌ Bin is already empty. No collection needed.");
    }

    // ✅ Check if there's a pending collection task
    $task_check = $conn->prepare("SELECT * FROM collections WHERE collector_id = ? AND bin_id = ? AND status = 'pending' LIMIT 1");
    $task_check->bind_param("ii", $collector_id, $bin_id);
    $task_check->execute();
    $task_result = $task_check->get_result();

    if ($task_result->num_rows === 0) {
        die("❌ No pending collection task found for this bin and collector.");
    }

    // ✅ Reset bin
    $reset = $conn->prepare("UPDATE bins SET current_level = 0 WHERE bin_id = ?");
    $reset->bind_param("i", $bin_id);
    $reset->execute();

    // ✅ Mark task as completed
    $mark_done = $conn->prepare("UPDATE collections SET status = 'completed' WHERE collector_id = ? AND bin_id = ? AND status = 'pending'");
    $mark_done->bind_param("ii", $collector_id, $bin_id);
    $mark_done->execute();

    // ✅ Redirect to collector dashboard
    header("Location: collector_dashboard.php?success=1");
    exit;
}
?>
