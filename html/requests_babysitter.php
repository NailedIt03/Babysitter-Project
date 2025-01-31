<?php
session_start();
include "connection.php"; 

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: log_in_babysitter.php");
    exit();
}

$requests = [
    [
        "parent_name" => "John Doe",
        "message" => "Looking for a babysitter this weekend.",
        "email" => "johndoe@example.com",
        "phone" => "123-456-7890"
    ],
    [
        "parent_name" => "Jane Smith",
        "message" => "Need a nanny for my 2-year-old.",
        "email" => "janesmith@example.com",
        "phone" => "987-654-3210"
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests Page</title>
    <link rel="stylesheet" href="../css/requests_babysitter.css">
</head>
<body>

<div class="header">
    <div class="top-left-logo">
        <img src="../images/LOGO.png" alt="Nestle Logo">
    </div>
    <div class="nav">
        <a href="hp_babysitter.php">HOME</a>
        <a href="calendar_babysitter.php">CALENDAR</a>
        <a href="requests_babysitter.php" class="active">REQUESTS</a>
        <form action="logout.php" method="post">
            <button type="submit" class="logout-button">LOG OUT</button>
        </form>
    </div>
</div>

<div class="container">
    <?php
        if (!empty($requests)) {
            foreach ($requests as $request) {
                echo "<div class='request-card'>
                        <div class='profile-pic'></div>
                        <div class='request-content'>
                            <strong>{$request['parent_name']}</strong>
                            <p>{$request['message']}</p>
                            <small>Contact: <a href='mailto:{$request['email']}'>{$request['email']}</a>, {$request['phone']}</small>
                        </div>
                      </div>";
            }
        } else {
            echo "<p>No requests available.</p>";
        }
    ?>
</div>

</body>
</html>
