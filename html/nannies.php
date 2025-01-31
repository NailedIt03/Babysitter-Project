<?php 
session_start();
include "connection.php";
include "functions.php";

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: log_in_parent.php");
    exit();
}
$username = $_SESSION['username'];
$parent_id = $_SESSION['parent_id'];

// Fetch parent's child
$child_query = $con->prepare("SELECT child FROM parents WHERE id = ?");
$child_query->bind_param("i", $parent_id);
$child_query->execute();
$child_result = $child_query->get_result();
$child_data = $child_result->fetch_assoc();
$child_name = $child_data['child'] ?? '';

// Fetch babysitters from the database
$sql = "SELECT id, user_name, profile_pic FROM babysitter";
$result = $con->query($sql);

// Handle babysitter request submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['babysitter_id'])) {
    $babysitter_id = $_POST['babysitter_id'];
    $message = send_babysitter_request($con, $parent_id, $babysitter_id, $child_name);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Babysitters</title>
    <link rel="stylesheet" href="../css/nannies.css">
</head>
<body>
    <div class="header">
        <div class="top-left-logo"><img src="../images/LOGO.png" alt="Site Logo"></div>
        <div class="nav">
            <a href="hp_parent.php" class="nav-item">HOME</a>
            <a href="calendar_parent.php" class="nav-item">CALENDAR</a>
            <a href="#" class="nav-item">NANNIES</a>
            <form action="logout.php" method="post" class="logout-form">
                <button type="submit" class="logout-button">LOG OUT</button>
            </form>
        </div>
    </div>
    
    <div class="container">
        <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>

        <?php while ($row = $result->fetch_assoc()) { 
            $profile_pic = !empty($row['profile_pic']) ? "../html/uploads_bby/" . htmlspecialchars($row['profile_pic']) : "../html/uploads_bby/default.png";
        ?>
            <div class="babysitter-box">
                <img src="<?php echo $profile_pic; ?>" alt="Profile Picture" width="150" height="150">
                <h3><?php echo htmlspecialchars($row['user_name']); ?></h3>
                
                <form method="POST">
                    <input type="hidden" name="babysitter_id" value="<?= $row['id'] ?>">
                    <button type="submit">Request Babysitter</button>
                </form>
            </div>
        <?php } ?>
    </div>
</body>
</html>
