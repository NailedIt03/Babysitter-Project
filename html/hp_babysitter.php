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

    
    
        <div class="header">
        <div class="top-left-logo"><img src="../images/LOGO.png" alt="Site Logo"></div>
        <div class="nav">
            <a href="hp_babysitter.php" class="nav-item">HOME</a>
            <a href="calendar_babysitter.php" class="nav-item">CALENDAR</a>
            <a href="requests_babysitter.php" class="nav-item">REQUESTS</a>
            <form action="logout.php" method="post" class="logout-form">
                <button type="submit" class="logout-button">LOG OUT</button>
            </form>
        </div>
    </div>

    <div class="dashboard">
        <div class="profile-section">
        <div class="pf_box">
            <p><strong><?php echo "Nanny ";echo htmlspecialchars(string: $username); ?></strong></p>

            <?php
                $query = "SELECT profile_pic FROM babysitter WHERE user_name = ?";
                $stmt = $con->prepare($query);
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->bind_result($profilePic);
                $stmt->fetch();

                if ($profilePic) {
                    echo '<div class="profile-picture">
                            <img src="uploads_bby/' . htmlspecialchars($profilePic) . '" alt="Profile Picture">
                          </div>';
                } else {
                    echo '<p>No profile picture uploaded yet.</p>';
                }
                $stmt->close();
                
                ?>

                <form action="upload_profile_pic_bby.php" method="POST" enctype="multipart/form-data" class="upload-form">
                    <label for="profile_pic">Upload Profile Picture:</label>
                    <input type="file" name="profile_pic" id="profile_pic" accept="image/*" required>
                    <button type="submit">Upload</button>
                </form>
            </div>

            <p class="caretaker-info">• currently taking care of KID1NAME and KID2NAME for PARENT NAME</p>
            <button class="customer-list-btn">Pick a customer list</button>
        </div>

        <!-- Activity Section -->
        <div class="activity-section">
            <div class="activity-update">
                <textarea placeholder="post and update..."></textarea>
                <button>done</button>
            </div>
            <div class="activity-log">
                <div class="log-item">
                    <p><strong>NANNY NAME</strong><br>KID1NAME was put to sleep</p>
                    <span>15:00</span>
                </div>
                <div class="log-item">
                    <p><strong>NANNY NAME</strong><br>KID2NAME just ate his vegetables</p>
                    <span>14:30</span>
                </div>
                <div class="log-item">
                    <p><strong>NANNY NAME</strong><br>KID2NAME and KID1NAME came back from school</p>
                    <span>13:55</span>
                </div>
            </div>
        </div>

    </div>

</body>
</html>