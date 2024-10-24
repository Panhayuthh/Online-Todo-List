<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['taskId'], $input['status'])) {
        $taskId = $input['taskId'];
        $status = $input['status'];

        $query = "UPDATE task SET status = :status WHERE id = :taskId";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':taskId', $taskId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    } else {
        // If data is missing
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
    }
}
?>
