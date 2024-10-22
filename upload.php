<?php
require('config.php');
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo'])) {
    $photo = $_FILES['photo']['name'];
    $tmp = $_FILES['photo']['tmp_name'];

    // Fetch current profile picture from the database
    $stmt = $conn->prepare("SELECT profile_picture FROM user WHERE id = ?");
    $stmt->execute([$id]);
    $currentPhoto = $stmt->fetchColumn();

    // Check for errors
    if ($_FILES['photo']['error'] == 0) {
        // Delete the old photo if it exists
        if ($currentPhoto && file_exists("userprofile/$currentPhoto")) {
            unlink("userprofile/$currentPhoto");
        }

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($tmp, 'userprofile/' . $photo)) {
            // Update the user's profile picture in the database
            $query = "UPDATE user SET profile_picture = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$photo, $id]);

            // Set success message in session
            $_SESSION['success'] = "Profile picture updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to upload photo.";
        }
    } else {
        $_SESSION['error'] = "Failed to upload photo.";
    }

    // Redirect back to profile management
    header('Location: profileManagement.php');
    exit();
}
?>
