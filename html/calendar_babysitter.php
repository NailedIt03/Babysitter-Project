<?php
session_start();
include "connection.php";
include "functions.php";

// Ensure the babysitter is logged in
if (!isset($_SESSION['babysitter_id'])) {
    die("Unauthorized access.");
}

$babysitter_id = $_SESSION['babysitter_id'];

// Fetch parent IDs where request is accepted
$parent_query = $con->prepare("
    SELECT DISTINCT parent_id 
    FROM babysitter_requests 
    WHERE babysitter_id = ? AND status = 'accepted'
");
$parent_query->bind_param("i", $babysitter_id);
$parent_query->execute();
$parent_result = $parent_query->get_result();

$parent_ids = [];
while ($row = $parent_result->fetch_assoc()) {
    $parent_ids[] = $row['parent_id'];
}
$parent_query->close();

// If no accepted parents, show a message
if (empty($parent_ids)) {
    $events_result = false;
} else {
    // Fetch events for accepted parents only
    $parent_ids_placeholder = implode(",", array_fill(0, count($parent_ids), "?"));
    $types = str_repeat("i", count($parent_ids));

    $query = "
        SELECT e.id, e.child_name, e.event_date, e.event_time, e.event_description, e.status, p.user_name AS parent_name
        FROM events e
        JOIN parents p ON e.parent_id = p.id
        WHERE e.parent_id IN ($parent_ids_placeholder)
    ";

    $stmt = $con->prepare($query);
    $stmt->bind_param($types, ...$parent_ids);
    $stmt->execute();
    $events_result = $stmt->get_result();
}

// Handle task updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['event_id'], $_POST['status'])) {
    $event_id = $_POST['event_id'];
    $status = $_POST['status'];

    $update_query = $con->prepare("UPDATE events SET status = ? WHERE id = ?");
    $update_query->bind_param("si", $status, $event_id);
    if ($update_query->execute()) {
        $message = "Task updated successfully!";
    } else {
        $message = "Failed to update task.";
    }
    $update_query->close();
    header("Refresh:0"); // Refresh page to show updates
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Babysitter Calendar</title>
    <link rel="stylesheet" href="../css/calendar_babysitter.css">
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

    <div class="content">
        <h1>Assigned Tasks</h1>
        <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>

        <?php if ($events_result && $events_result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Child Name</th>
                <th>Event Date</th>
                <th>Event Time</th>
                <th>Event Description</th>
                <th>Parent</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $events_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['child_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['event_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['event_time']); ?></td>
                    <td><?php echo htmlspecialchars($row['event_description']); ?></td>
                    <td><?php echo htmlspecialchars($row['parent_name']); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($row['status'])); ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="event_id" value="<?= $row['id'] ?>">
                            <select name="status">
                                <option value="pending" <?= ($row['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="completed" <?= ($row['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p>No events assigned from accepted parents.</p>
        <?php endif; ?>
    </div>
</body>
</html>
