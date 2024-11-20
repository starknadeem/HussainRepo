<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Signup</h2>
        <?php
        if (isset($_GET['error'])) {
            echo '<div class="alert error">' . htmlspecialchars($_GET['error']) . '</div>';
        }
        ?>
        <form action="signup.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <label for="cpassword">Confirm Password:</label>
            <input type="password" name="cpassword" required>

            <button type="submit">Signup</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
<?php
include 'db.php'; // Include the database connection file

$showAlert = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];

    // Check if the username exists
    $existSql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $existSql);
    $numExistRows = mysqli_num_rows($result);

    if ($numExistRows > 0) {
        $showError = "Username Already Exists";
        header("Location: signup.php?error=" . urlencode($showError));
        exit();
    } else {
        if ($password == $cpassword) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hash')";
            $res = mysqli_query($conn, $sql);

            if ($res) {
                $showAlert = true;
                header("Location: login.php"); // Redirect to login page after successful registration
                exit();
            }
        } else {
            $showError = "Passwords do not match";
            header("Location: signup.php?error=" . urlencode($showError));
            exit();
        }
    }
}
?>