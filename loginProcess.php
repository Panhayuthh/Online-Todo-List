<?php
require_once('config.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? ''; // Use null coalescing to handle unset values
    $password = $_POST['password'] ?? '';

    // Validate email and password inputs (you can expand this as needed)
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and password are required!";
        header('Location: login.php');
        exit();
    }

    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // For debugging, you might want to remove this in production
        // echo "User found: " . print_r($user, true); 

        if (password_verify($password, $user['password'])) {
            // Store user information in session
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to the desired page after successful login
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password!";
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "No user found with that email.";
        header('Location: login.php');
        exit();
    }
}
?>
