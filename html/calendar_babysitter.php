<?php
session_start();
include "connection.php";

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: log_in_babysitter.php");
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

$childrenList = explode(",", $children);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $childName = $_POST['child_name'];
    $eventDate = $_POST['event_date'];
    $eventDescription = $_POST['event_description'];
    $eventTime = $_POST['event_time'];
    if (!empty($childName) && !empty($eventDate) && !empty($eventDescription) && !empty($eventTime)) {
        $query = "INSERT INTO events (parent_id, child_name, event_date, event_description, event_time) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("issss", $parentId, $childName, $eventDate, $eventDescription, $eventTime); 

        if ($stmt->execute()) {
            $message = "Event added successfully!";
        } else {
            $message = "Failed to add event.";
        }

        $stmt->close();
    } else {
        $message = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar Babysitter</title>
    <link rel="stylesheet" href="../css/calendar_babysitter.css">
</head>
<body>
    <div class="header">
        <div class="top-left-logo"><img src="../images/LOGO.png" alt="Site Logo"></div>
        <div class="nav">
            <a href="hp_babysitter.php" class="nav-item">HOME</a>
            <a href="calendar_babysitter.php" class="nav-item">CALENDAR</a>
            <a href="#" class="nav-item">PARENTS</a>
            <form action="logout.php" method="post" class="logout-form">
                <button type="submit" class="logout-button">LOG OUT</button>
            </form>
        </div>
    </div>

    <div class="content">
        <h1>Assign Event to a Child</h1>
        <?php if (isset($message)) echo "<p>$message</p>"; ?>

        <form action="calendar_babysitter.php" method="POST">
            <label for="child_name">Select Child:</label>
            <select name="child_name" id="child_name" required>
                <option value="" disabled selected>Select a child</option>
                <?php foreach ($childrenList as $child): ?>
                    <option value="<?php echo htmlspecialchars(trim($child)); ?>">
                        <?php echo htmlspecialchars(trim($child)); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br><br>

            <label for="event_date">Event Date:</label>
            <input type="date" name="event_date" id="event_date" required>
            <br><br>

            <label for="event_time">Event Time:</label>
            <input type="time" name="event_time" id="event_time" required>
            <br><br>

            <label for="event_description">Event Description:</label>
            <textarea name="event_description" id="event_description" rows="4" cols="50" required></textarea>
            <br><br>

            <button type="submit">Add Event</button>
        </form>

        <h2>Your Scheduled Events</h2>
        <table border="1">
            <tr>
                <th>Child Name</th>
                <th>Event Date</th>
                <th>Event Time</th>
                <th>Event Description</th>
            </tr>
            <?php
            $query = "SELECT child_name, event_date, event_time, event_description FROM events WHERE parent_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("i", $parentId); 
            $stmt->execute();
            $stmt->bind_result($childName, $eventDate, $eventTime, $eventDescription);

            while ($stmt->fetch()):
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($childName); ?></td>
                    <td><?php echo htmlspecialchars($eventDate); ?></td>
                    <td><?php echo htmlspecialchars($eventTime); ?></td>
                    <td><?php echo htmlspecialchars($eventDescription); ?></td>
                </tr>
            <?php endwhile;
            $stmt->close();
            ?>
        </table>
    </div>
</body>
</html>
