<?php
session_start();
include "connection.php";

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: log_in_parent.php");
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $file = $_FILES['profile_pic'];
    $uploadDir = __DIR__ . '/uploads/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!in_array($file['type'], $allowedTypes)) {
        die("Only JPG, PNG, and GIF files are allowed.");
    }

    $fileName = uniqid() . '-' . preg_replace('/[^a-zA-Z0-9.\-_]/', '', basename($file['name']));
    $uploadPath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        
        $query = "SELECT profile_pic FROM parents WHERE user_name = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($existingProfilePic);
        $stmt->fetch();
        $stmt->close();

        if ($existingProfilePic && file_exists($uploadDir . $existingProfilePic)) {
            unlink($uploadDir . $existingProfilePic);
        }

        $query = "UPDATE parents SET profile_pic = ? WHERE user_name = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ss", $fileName, $username);
        if ($stmt->execute()) {
            header("Location: hp_parent.php"); 
            exit();
        } else {
            echo "Database update failed.";
        }
        $stmt->close();
    } else {
        echo "File upload failed.";
    }
}
?>
