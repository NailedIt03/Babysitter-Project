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
    
    $query = "select * from babysitter where user_name = '$user_name' limit 1";
    $result = mysqli_query($con, $query);

    mysqli_query($con, $query);

  
    if($result)
    {
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            if($user_data['password'] === $password)
            {
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user_data['user_id'];
                $_SESSION['username'] = $user_data['user_name'];
                header("Location: hp_babysitter.php");
                die;}
        }
    }
    echo "wrong username or password!";
}else
{
    echo "Please enter some valid infomation!";
} 
}


 ?>
 <!DOCTYPE html>
 <html lang="en">
    <head>
    <title>Nestle - log in (parent)</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nestle - log in (parent)</title>
    <link rel="stylesheet" href="../css/style_log_in.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&display=swap" 
    rel="stylesheet">
    </head>
    <body>
    <div class="teal-rectangle">
    <h1 class="login-heading">Log in</h1>
 <form method="post">
    <h2>Username</h2>
 <input id="text" type="text" name="user_name"><br><br>
 <h2>Password</h2>
<input id="text" type="password" name="password"><br><br>
<input class="button-login" type="submit" value="Login"><br><br>

<a href="sign_up_parent.php">Click to Sign Up</a><br><br>
 </form>
</div>
    </body>
 </html>
