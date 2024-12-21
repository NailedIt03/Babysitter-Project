<?php
session_start();
include "connection.php";
include "functions.php";

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if(!empty($user_name) && !empty($password) && !is_numeric($user_name))
{
    $user_id = random_num(20);
    $query = "insert into parents (user_id,user_name,password) values('$user_id','$user_name','$password')";

    mysqli_query($con,$query);

    header("Location: log_in_parent.php");
    die;
}else
{
    echo "Please enter some valid infomation!";
}
}
?>


<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nestle - sign up (parent)</title>
    <link rel="stylesheet" href="../css/style_sign_up_parent.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&display=swap" 
    rel="stylesheet">
</head>
<body>
<div class="teal-rectangle">
    <h1 class="sign-up-heading">SIGN UP</h1>
 <form method="post">
    <h2>Username</h2>
 <input id="text" type="text" name="user_name"><br><br>
 <h2>Password</h2>
<input id="text" type="password" name="password"><br><br>

<input class="button-sign-up" type="submit" value="Sign Up"><br><br>

<a href="log_in_parent.php">Click to Log In</a><br><br>
 </form>
</div>
    </body>
 </html>