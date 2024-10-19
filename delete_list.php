<?php
include 'config.php';

if (isset($_GET['id'])) {
    $listId = $_GET['id'];

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("DELETE FROM tasks WHERE list_id = ?");
        $stmt->bind_param("i", $listId);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM to_do_list WHERE id = ?");
        $stmt->bind_param("i", $listId);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        header("Location: dashboard.php");
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
