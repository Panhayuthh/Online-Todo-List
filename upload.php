<?php
require 'config.php'; // Include your database connection

session_start();

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted and the file is set
if (isset($_POST['submit']) && isset($_FILES['userprofile'])) {
    $profile = $_FILES['userprofile']['name'];
    $img_size = $_FILES['userprofile']['size'];
    $tmp_name = $_FILES['userprofile']['tmp_name'];
    $error = $_FILES['userprofile']['error'];

    // Check for upload errors
    if ($error === 0) {
        // Check file size (max 125KB)
        if ($img_size > 1250000) {
            $em = "File is too large! Maximum file size is 125KB.";
            header("Location: profileManagement.php?error=$em");
            exit();
        } else {
            // Get the file extension
            $img_ex = pathinfo($profile, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex); // Convert to lowercase

            // Allowed file types
            $allowed_exs = ['jpg', 'jpeg', 'png', 'gif'];

            // Check if the uploaded file is an allowed type
            if (in_array($img_ex_lc, $allowed_exs)) {
                // Create a unique filename to prevent overwriting
                $new_image_name = uniqid("IMG-", true) . '.' . $img_ex_lc;

                // Specify the upload directory
                $upload_path = 'userprofile/' . $new_image_name;

                // Move the uploaded file to the designated directory
                if (move_uploaded_file($tmp_name, $upload_path)) {
                    // Update the user's profile picture in the database
                    $user_id = $_SESSION['id']; // Get the logged-in user's ID
                    $stmt = $conn->prepare("UPDATE user SET profile_picture = ? WHERE id = ?");
                    $stmt->execute([$new_image_name, $user_id]);

                    // Redirect with a success message
                    header("Location: profileManagement.php?success=File uploaded successfully!");
                    exit();
                } else {
                    $em = "Failed to move the uploaded file.";
                    header("Location: profileManagement.php?error=$em");
                    exit();
                }
            } else {
                $em = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
                header("Location: profileManagement.php?error=$em");
                exit();
            }
        }
    } else {
        $em = "An error occurred during file upload.";
        header("Location: profileManagement.php?error=$em");
        exit();
    }
} else {
    header("Location: profileManagement.php");
    exit();
}
