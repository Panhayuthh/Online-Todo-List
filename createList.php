<?php
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
    <div class="modal fade" id="listCreateModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="createList.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="descriptionModalLabel">Create a new list</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="listTitle" class="form-label">List Title</label>
                            <input type="text" class="form-control" id="listTitle" name="listTitle" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create List</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</html>