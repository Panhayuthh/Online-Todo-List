<?php
include 'config.php';  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = 1;  
    $title = $_POST['listTitle'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("
        INSERT INTO to_do_list (user_id, title, description, due_date, status)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$userId, $title, $description, $due_date, $status]);

    header("Location: dashboard.php");
    exit;
}
?>
