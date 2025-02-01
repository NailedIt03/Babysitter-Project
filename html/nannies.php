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

// Fetch babysitter request statuses
$requests_query = $con->prepare("SELECT babysitter_id, status FROM babysitter_requests WHERE parent_id = ?");
$requests_query->bind_param("i", $parent_id);
$requests_query->execute();
$requests_result = $requests_query->get_result();

$requests_status = [];
while ($row = $requests_result->fetch_assoc()) {
    $requests_status[$row['babysitter_id']] = $row['status'];
}
$requests_query->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Babysitters</title>
    <link rel="stylesheet" href="../css/nannies.css">
    <script>
        function sendRequest(babysitterId, button) {
            button.disabled = true;
            button.textContent = "Pending...";

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "send_babysitter_request.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    button.textContent = "Pending";
                    button.classList.add("pending");
                }
            };
            xhr.send("babysitter_id=" + babysitterId);
        }
    </script>
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
        <?php while ($row = $result->fetch_assoc()) { 
            $profile_pic = !empty($row['profile_pic']) ? "../html/uploads_bby/" . htmlspecialchars($row['profile_pic']) : "../html/uploads_bby/default.png";
            $status = isset($requests_status[$row['id']]) ? $requests_status[$row['id']] : "none";

            // Button styling based on request status
            if ($status === "accepted") {
                $button_text = "Accepted";
                $button_class = "accepted";
                $button_disabled = "disabled";
            } elseif ($status === "rejected") {
                $button_text = "Rejected";
                $button_class = "rejected";
                $button_disabled = "disabled";
            } elseif ($status === "pending") {
                $button_text = "Pending";
                $button_class = "pending";
                $button_disabled = "disabled";
            } else {
                $button_text = "Request Babysitter";
                $button_class = "";
                $button_disabled = "";
            }
        ?>
            <div class="babysitter-box">
                <img src="<?php echo $profile_pic; ?>" alt="Profile Picture" width="150" height="150">
                <h3><?php echo htmlspecialchars($row['user_name']); ?></h3>
                
                <button class="<?= $button_class ?>" <?= $button_disabled ?> onclick="sendRequest(<?= $row['id'] ?>, this)">
                    <?= $button_text ?>
                </button>
            </div>
        <?php } ?>
    </div>
</body>
</html>

