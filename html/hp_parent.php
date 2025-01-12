<?php
// Start the session
session_start();
include "connection.php";
include "functions.php";

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect to login page
    header("Location: log_in_parent.php");
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page (Parent)</title>
    <link rel="stylesheet" href="../css/hp_parent.css">
</head>
<body>
    <!-- Horizontal Line -->
    <div class="line"></div>
    
    <!-- Profile Box -->
    <div class="pf_box">
        <p>Welcome, <strong><?php echo htmlspecialchars($username); ?></strong>!</p>
    </div>
    
    <!-- Comment Box -->
    <div class="cm_box">
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>

