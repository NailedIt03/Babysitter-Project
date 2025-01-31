<?php
session_start();
include "connection.php";

// Ensure the babysitter is logged in
if (!isset($_SESSION['babysitter_id'])) {
    die("Unauthorized access.");
}

$babysitter_id = $_SESSION['babysitter_id'];
$username = $_SESSION['username'];

// Fetch babysitter profile picture
$query = "SELECT profile_pic FROM babysitter WHERE user_name = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($profilePic);
$stmt->fetch();
$stmt->close();

// Fetch accepted parents and their children
$parent_query = $con->prepare("
    SELECT DISTINCT p.id, p.user_name, p.child 
    FROM babysitter_requests br
    JOIN parents p ON br.parent_id = p.id
    WHERE br.babysitter_id = ? AND br.status = 'accepted'
");
$parent_query->bind_param("i", $babysitter_id);
$parent_query->execute();
$parents_result = $parent_query->get_result();
$parent_query->close();

// Handle new update submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_text'], $_POST['parent_id'], $_POST['child_name'])) {
    $update_text = $_POST['update_text'];
    $parent_id = $_POST['parent_id'];
    $child_name = $_POST['child_name'];

    $insert_update = $con->prepare("
        INSERT INTO babysitter_updates (babysitter_id, parent_id, child_name, update_text) 
        VALUES (?, ?, ?, ?)
    ");
    $insert_update->bind_param("iiss", $babysitter_id, $parent_id, $child_name, $update_text);
    $insert_update->execute();
    $insert_update->close();
}

// Fetch updates posted by the babysitter
$updates_query = $con->prepare("
    SELECT bu.child_name, bu.update_text, bu.update_time, p.user_name AS parent_name
    FROM babysitter_updates bu
    JOIN parents p ON bu.parent_id = p.id
    WHERE bu.babysitter_id = ?
    ORDER BY bu.update_time DESC
");
$updates_query->bind_param("i", $babysitter_id);
$updates_query->execute();
$updates_result = $updates_query->get_result();
$updates_query->close();
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
                <p><strong><?php echo "Nanny " . htmlspecialchars($username); ?></strong></p>
                <div class="profile-picture">
                    <img src="uploads_bby/<?php echo htmlspecialchars($profilePic ?? 'default.png'); ?>" alt="Profile Picture">
                </div>
                <form action="upload_profile_pic_bby.php" method="POST" enctype="multipart/form-data" class="upload-form">
                    <label for="profile_pic">Upload Profile Picture:</label>
                    <input type="file" name="profile_pic" id="profile_pic" accept="image/*" required>
                    <button type="submit">Upload</button>
                </form>
            </div>

            <h3>Select Parent to Send an Update</h3>
            <form method="POST">
                <label for="parent_id">Choose Parent:</label>
                <select name="parent_id" required id="parent_select">
                    <option value="" disabled selected>Select a Parent</option>
                    <?php while ($row = $parents_result->fetch_assoc()) { ?>
                        <option value="<?= $row['id'] ?>" data-children="<?= htmlspecialchars($row['child']) ?>">
                            <?= htmlspecialchars($row['user_name']) ?>
                        </option>
                    <?php } ?>
                </select>

                <label for="child_name">Child Name:</label>
                <select name="child_name" id="child_select" required>
                    <option value="" disabled selected>Select a Child</option>
                </select>

                <label for="update_text">Update:</label>
                <textarea name="update_text" placeholder="Write an update..." required></textarea>

                <button type="submit">Post Update</button>
            </form>
        </div>

        <!-- Activity Section -->
        <div class="activity-section">
            <h3>Your Updates</h3>
            <?php while ($row = $updates_result->fetch_assoc()) { ?>
                <div class="log-item">
                    <p><strong><?= htmlspecialchars($row['parent_name']) ?>'s child, <?= htmlspecialchars($row['child_name']) ?>:</strong></p>
                    <p><?= htmlspecialchars($row['update_text']) ?></p>
                    <span><?= $row['update_time'] ?></span>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        document.getElementById('parent_select').addEventListener('change', function() {
            let selectedParent = this.options[this.selectedIndex];
            let children = selectedParent.getAttribute('data-children');
            let childSelect = document.getElementById('child_select');

            childSelect.innerHTML = '<option value="" disabled selected>Select a Child</option>';

            if (children) {
                let childArray = children.split(',');

                let allChildrenOption = document.createElement('option');
                allChildrenOption.value = 'All Children';
                allChildrenOption.textContent = 'All Children';
                childSelect.appendChild(allChildrenOption);

                childArray.forEach(child => {
                    let option = document.createElement('option');
                    option.value = child.trim();
                    option.textContent = child.trim();
                    childSelect.appendChild(option);
                });
            }
        });
    </script>

</body>
</html>
