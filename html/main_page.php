<?php
session_start();
include("connection.php");
include("functions.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nestle</title>
    <link rel="stylesheet" href="../css/main_page.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&display=swap" 
    rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <a href="#" class="nav-link">Sign up (parent)</a>
        <a href="#" class="nav-link">Sign up (babysitter)</a>
        <a href="#" class="nav-link">Log in</a>
    </nav>
    <p class="tagline">Find the best babysitter service using Nestle</p>
    <img src="../images/LOGO.png" alt="Site Logo" class="top-left-logo">
    <div class="circle"></div>
</body>
</html>
