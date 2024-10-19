<?php
require_once 'config.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    try {
        $sql = "INSERT INTO user (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $kunci->prepare($sql);

        if ($stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashed_password
        ])) {
            echo "Registration successful!";
            header('Location: loginpage.php'); 
            exit();
        } else {
            echo "Registration failed. Please try again.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
