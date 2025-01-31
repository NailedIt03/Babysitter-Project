<?php
session_start();
include "connection.php";

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: log_in_babysitter.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Babysitter Dashboard</title>
    <link rel="stylesheet" href="../css/hp_babysitter.css">
</head>
<body>

    <div class="navbar">
        <div class="logo">
            <img src="../images/LOGO.png" alt="Logo">
        </div>
        <div class="nav-links">
            <a href="hp_babysitter.php">HOME</a>
            <a href="calendar_babysitter.php">CALENDAR</a>
            <a href="requests_babysitter.php">REQUESTS</a>
            <a href="logout.php" class="logout">LOG OUT</a>
        </div>
    </div>

    <div class="dashboard">
        
        <div class="profile-section">
            <div class="profile-pic"></div>
            <h3><?php echo strtoupper($username); ?></h3>
            <p class="caretaker-info">• Currently taking care of KID1NAME and KID2NAME for PARENT NAME</p>
            <button class="customer-list-btn">Pick a customer list</button>
        </div>

        <div class="activity-section">
            <div class="activity-update">
                <textarea placeholder="Post an update..."></textarea>
                <button>Done</button>
            </div>
            <div class="activity-log">
                <div class="log-item">
                    <p><strong><?php echo strtoupper($username); ?></strong><br>KID1NAME was put to sleep</p>
                    <span>15:00</span>
                </div>
                <div class="log-item">
                    <p><strong><?php echo strtoupper($username); ?></strong><br>KID2NAME just ate their vegetables</p>
                    <span>14:30</span>
                </div>
                <div class="log-item">
                    <p><strong><?php echo strtoupper($username); ?></strong><br>KID2NAME and KID1NAME came back from school</p>
                    <span>13:55</span>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
