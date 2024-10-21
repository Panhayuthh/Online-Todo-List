<?php

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $taskId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($taskId === false) {
        echo "Invalid ID!";
        exit;
    }

    $stmt = $conn->prepare("SELECT name FROM task WHERE id = ?");
    $stmt->execute([$taskId]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($task) {
        $delete_stmt = $conn->prepare("DELETE FROM task WHERE id = ?");
        if ($delete_stmt->execute([$taskId])) {
            // Optionally, set a session message for feedback
            header("Location: dashboardV2.php?message=Task deleted successfully");
        } else {
            echo "Failed to delete task!";
        }
    } else {
        echo "Task not found!";
    }
}

?>