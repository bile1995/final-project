<!DOCTYPE html>
<html>
<head>
    <title>Password Hasher</title>
   
</head>
<body>

<form method="POST">
    <h3>Password Hasher</h3>
    <input type="password" name="password" placeholder="Enter password" required>
    <input type="submit" value="Hash Password">
    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $password = $_POST['password'];
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        echo "<div class='result'><strong>Hashed Password:</strong><br>$hashed</div>";
    }
    ?>
</form>

</body>
</html>
