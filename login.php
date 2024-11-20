<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php
        if (isset($_GET['error'])) {
            echo '<div class="alert error">' . htmlspecialchars($_GET['error']) . '</div>';
        }
        ?>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <a href="#">Forgot password?</a>
        </form>
    </div>
</body>
</html>

<?php
session_start();
include 'db.php'; // Include the database connection file

$login = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if the username exists in the database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verify the entered password with the hashed password in the database
        if (password_verify($password, $row['password'])) {
            $login = true;
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            header("location: new/index.html"); // Redirect to the desired page
            exit;
        } else {
            $showError = "Invalid password";
            header("Location: login.php?error=" . urlencode($showError));
            exit();
        }
    } else {
        $showError = "Invalid username";
        header("Location: login.php?error=" . urlencode($showError));
        exit();
    }
}
?>