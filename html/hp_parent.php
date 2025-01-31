<?php
session_start();
include "connection.php";

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: log_in_parent.php");
    exit();
}

$username = $_SESSION['username'];

$query = "SELECT child FROM parents WHERE user_name = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($children);
$stmt->fetch();
$stmt->close();

$childList = $children ? explode(',', $children) : [];
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
    <div class="header">
        <div class="top-left-logo"><img src="../images/LOGO.png" alt="Site Logo"></div>
        <div class="nav">
            <a href="hp_parent.php" class="nav-item">HOME</a>
            <a href="calendar_parent.php" class="nav-item">CALENDAR</a>
            <a href="nannies.php" class="nav-item">NANNIES</a>
            <form action="logout.php" method="post" class="logout-form">
                <button type="submit" class="logout-button">LOG OUT</button>
            </form>
        </div>
    </div>
    
    <div class="content">
        <div class="left-panel">
            <div class="pf_box">
                <p><strong><?php echo htmlspecialchars($username); ?></strong></p>

                <?php
                $query = "SELECT profile_pic FROM parents WHERE user_name = ?";
                $stmt = $con->prepare($query);
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->bind_result($profilePic);
                $stmt->fetch();

                if ($profilePic) {
                    echo '<div class="profile-picture">
                            <img src="uploads/' . htmlspecialchars($profilePic) . '" alt="Profile Picture">
                          </div>';
                } else {
                    echo '<p>No profile picture uploaded yet.</p>';
                }
                $stmt->close();
                ?>

                <form action="upload_profile_pic.php" method="POST" enctype="multipart/form-data" class="upload-form">
                    <label for="profile_pic">Upload Profile Picture:</label>
                    <input type="file" name="profile_pic" id="profile_pic" accept="image/*" required>
                    <button type="submit">Upload</button>
                </form>
            </div>

            <div class="children-box">
                <h2>Children:</h2>
                <ul>
                    <?php
                    if ($childList) {
                        foreach ($childList as $child) {
                            echo "<li>" . htmlspecialchars(trim($child)) . "</li>";
                        }
                    } else {
                        echo "<li>No children added yet.</li>";
                    }
                    ?>
                </ul>

                <form action="add_child.php" method="POST" class="add-child-form">
                    <input type="text" name="child_name" placeholder="Add a child..." required>
                    <button type="submit">+ Add</button>
                </form>
            </div>
        </div>

        <div class="right-panel">
            <div class="nanny-updates">
                <div class="update">
                    <p><strong>Nanny Ema</strong></p>
                    <p>Chrisopher was put to sleep</p>
                    <span>15:00</span>
                    <a href="#">+reply</a>
                </div>
                <div class="update">
                    <p><strong>Nanny Ema</strong></p>
                    <p>Stephanie ate lunch</p>
                    <span>14:30</span>
                    <a href="#">+reply</a>
                </div>
                <div class="update">
                    <p><strong>Nanny Ema</strong></p>
                    <p>Both of them came back from school</p>
                    <span>13:55</span>
                    <a href="#">+reply</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
