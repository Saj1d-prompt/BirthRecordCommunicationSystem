<?php
session_start();
include "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['userID'];
    $password = $_POST['password'];

    if ($password === "parent") {
        $_SESSION['userID'] = $userID;
        $_SESSION['role'] = "parent";
        header("Location: parent_dashboard.php");
        exit();
    } elseif ($password === "admin") {
        $_SESSION['userID'] = $userID;
        $_SESSION['role'] = "admin";
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid User ID or Password'); window.location.href='login.php';</script>";
    }
}
?>
