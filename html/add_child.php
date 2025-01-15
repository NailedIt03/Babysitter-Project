<?php
session_start();
include "connection.php";

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: log_in_parent.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $newChild = trim($_POST['child_name']);

    if (!empty($newChild)) {
        
        $query = "SELECT child FROM parents WHERE user_name = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($existingChildren);
        $stmt->fetch();
        $stmt->close();

        $childList = $existingChildren ? explode(',', $existingChildren) : [];
        $childList[] = $newChild; 
        $updatedChildren = implode(',', $childList); 

        
        $query = "UPDATE parents SET child = ? WHERE user_name = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ss", $updatedChildren, $username);

        if ($stmt->execute()) {
            header("Location: hp_parent.php");
            exit();
        } else {
            echo "Error: Could not add child.";
        }

        $stmt->close();
    } else {
        echo "Child name cannot be empty.";
    }
}
?>
