<?php
session_start();
include "connection.php";


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: log_in_parent.php");
    exit();
}

$username = $_SESSION['username'];


$query = "SELECT id, child FROM parents WHERE user_name = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($parentId, $children);
$stmt->fetch();
$stmt->close();

$childList = $children ? explode(',', $children) : [];


$updates_query = $con->prepare("
    SELECT bu.child_name, bu.update_text, bu.update_time, b.user_name AS babysitter_name
    FROM babysitter_updates bu
    JOIN babysitter b ON bu.babysitter_id = b.id
    WHERE bu.parent_id = ?
    ORDER BY bu.update_time DESC
");
$updates_query->bind_param("i", $parentId);
$updates_query->execute();
$updates_result = $updates_query->get_result();
$updates_query->close();

// Fetch parent profile picture
$query = "SELECT profile_pic FROM parents WHERE user_name = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($profilePic);
$stmt->fetch();
$stmt->close();
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
                <div class="profile-picture">
                    <img src="uploads/<?php echo htmlspecialchars($profilePic ?? 'default.png'); ?>" alt="Profile Picture">
                </div>
                <form action="upload_profile_pic.php" method="POST" enctype="multipart/form-data" class="upload-form">
    <label for="profile_pic" class="upload-label">Choose a File</label>
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
            <h2>Babysitter Updates</h2>
            <?php if ($updates_result->num_rows > 0): ?>
                <div class="nanny-updates">
                    <?php while ($row = $updates_result->fetch_assoc()) { ?>
                        <div class="update">
                            <p><strong>Nanny <?= htmlspecialchars($row['babysitter_name']) ?></strong> (for <?= htmlspecialchars($row['child_name']) ?>)</p>
                            <p><?= htmlspecialchars($row['update_text']) ?></p>
                            <span><?= $row['update_time'] ?></span>
                        </div>
                    <?php } ?>
                </div>
            <?php else: ?>
                <p>No updates from the babysitter yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
