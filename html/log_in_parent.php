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
    //read from database
    $query = "select * from parents where user_name = '$user_name' limit 1";
    $result = mysqli_query($con, $query);

    mysqli_query(mysql: $con,query: $query);

    if($result)
    {
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            if($user_data['password'] === $password)
            {
                $_SESSION['user_id'] = $user_data['user_id'];
                header("Location: main_page.php");
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
<div id="box">
 <form method="post">
 <input id="text" type="text" name="user_name"><br><br>
<input id="text" type="password" name="password"><br><br>

<input id="button" type="submit" value="Login"><br><br>

<a href="sign_up_parent.php">Click to Sign Up</a><br><br>
 </form>
</div>
    </body>
 </html>


