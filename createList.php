<?php
    require_once 'config.php';
    session_start();
    $userId = $_SESSION['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $listTitle = trim($_POST['listTitle']);
        $userId = $_SESSION['id'];

        $query = "INSERT INTO to_do_list (user_id, title) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$userId, $listTitle]);

        header("Location: index.php");
        exit;
    }

?>
<html>
    <div class="container">
        <form action="createList.php" method="post">
            <div class="mb-3">
                <label for="listTitle" class="form-label">List Title</label>
                <input type="text" class="form-control" id="listTitle" name="listTitle" required>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Create List</button>
            </div>
        </form>
    </div>
</html>