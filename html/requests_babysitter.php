<?php
session_start();
include "connection.php";
include "functions.php";


if (!isset($_SESSION['babysitter_id'])) {
    die("Unauthorized access.");
}

$babysitter_id = $_SESSION['babysitter_id'];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_id'], $_POST['action'])) {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];
    $message = handle_babysitter_request($con, $request_id, $action, $babysitter_id);
}


$requests = $con->prepare("
    SELECT br.id, p.user_name AS parent_name, p.profile_pic, br.child_name 
    FROM babysitter_requests br
    JOIN parents p ON br.parent_id = p.id
    WHERE br.babysitter_id = ? AND br.status = 'pending'
");
$requests->bind_param("i", $babysitter_id);
$requests->execute();
$requests_result = $requests->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/requests_babysitter.css">
    <title>Babysitter Requests</title>
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

    <h2>Pending Babysitting Requests</h2>
    <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>

    <div class="requests-list">
        <?php while ($row = $requests_result->fetch_assoc()) { 
            
            $profile_pic = !empty($row['profile_pic']) ? "../html/uploads/" . htmlspecialchars($row['profile_pic']) : "../html/uploads/default.png";
        ?>
            <div class="request-container">
                <img src="<?= $profile_pic ?>" alt="Parent Profile" class="profile-pic">
                <div class="request-details">
                    <p><strong><?= htmlspecialchars($row['parent_name']) ?></strong></p>
                    <p>Requested for: <strong><?= htmlspecialchars($row['child_name']) ?></strong></p>
                </div>
                <form method="POST" class="request-actions">
                    <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                    <button type="submit" name="action" value="accept" class="accept-btn">Accept</button>
                    <button type="submit" name="action" value="reject" class="reject-btn">Reject</button>
                </form>
            </div>
        <?php } ?>
    </div>

</body>
</html>

