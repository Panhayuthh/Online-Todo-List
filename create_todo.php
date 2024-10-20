<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Error: User is not logged in.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $listTitle = trim($_POST['listTitle']);
    $userId = $_SESSION['user_id']; 

    $stmt = $conn->prepare("INSERT INTO to_do_list (user_id, title) VALUES (?, ?)");

    if ($stmt->execute([$userId, $listTitle])) {
        header("Location: dashboard.php");
        exit(); 
    } else {
        echo "Error: " . $stmt->errorInfo()[2]; 
    }
}
?>
