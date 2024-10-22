<?php
include 'config.php';  

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $listId = $_GET['id'];  

    $stmt = $conn->prepare("SELECT title FROM to_do_list WHERE id = ?");
    $stmt->execute([$listId]);
    $ToDo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($ToDo) {
        $delete_stmt = $conn->prepare("DELETE FROM to_do_list WHERE id = ?");
        $delete_stmt->execute([$listId]);

        header("Location: listView.php");
        exit;
    } else {
        echo "To-do list not found!";
    }
} else {
    echo "Invalid ID!";
}
?>
