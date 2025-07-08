<?php
session_start(); // ✅ Start the session

include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        // ✅ Store login session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        // ✅ Redirect to the correct dashboard
        switch ($user['role']) {
            case 'admin':
                header("Location: admin_dashboard.php");
                break;
            case 'collector':
                header("Location: collector_dashboard.php");
                break;
            case 'resident':
                header("Location: resident_dashboard.php");
                break;
            default:
                echo "❌ Unknown user role.";
        }
    } else {
        echo "❌ Incorrect password.";
    }
} else {
    echo "❌ User not found.";
}
?>
