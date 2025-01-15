<?php
session_start();
include "connection.php";

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: log_in_parent.php");
    exit();
}

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
    <div class="line"></div>
    

    <div class="pf_box">
        <p><strong>Welcome, <?php echo htmlspecialchars($username); ?>!</strong></p>

        <?php
        $query = "SELECT profile_pic FROM parents WHERE user_name = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($profilePic);
        $stmt->fetch();

        if ($profilePic) {
            echo '<img src="uploads/' . htmlspecialchars($profilePic) . '" alt="Profile Picture" style="width:100px;height:100px;border-radius:50%;">';
        } else {
            echo '<p>No profile picture uploaded yet.</p>';
        }
        $stmt->close();
        ?>


        <form action="upload_profile_pic.php" method="POST" enctype="multipart/form-data">
            <label for="profile_pic">Upload Profile Picture:</label>
            <input type="file" name="profile_pic" id="profile_pic" accept="image/*" required>
            <button type="submit">Upload</button>
        </form>
        </div>


<div class="cm_box"></div>

<form action="logout.php" method="post" style="text-align: center; margin-top: 20px;">
    <button type="submit" class="logout-button">Logout</button>
</form>

</body>
</html>
