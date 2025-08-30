<?php
session_start();

if (isset($_SESSION['userID'])) {
    if ($_SESSION['password'] === 'parent') {
        header("Location: parentDashboard.php");
        exit();
    } elseif ($_SESSION['password'] === 'admin') {
        header("Location: adminDashboard.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login | Birth Record Communication System</title>
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <header class="header">
        <h1>Birth Record Communication System</h1>
    </header>
    <section class = "loginSection">
        <div class = "loginContainer">
        <h2>Login to Your Account</h2>
        <form action="loginProcess.php" method="post">
            <label for="username">User ID:</label>
            <input type="number" id="userID" name="userID" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" id="loginButton">Login</button>
        </form>
    </div>
    </section>
    
    <footer class="footer">
        <p>&copy; 2025 Birth Record Monitoring System. All rights reserved.</p>
    </footer>
    <!-- <script src="login.js"></script> -->
</body>
</html>