<?php

    require 'config.php';

    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
        $listId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($listId === false) {
            echo "Invalid ID!";
            exit;
        }

        $stmt = $conn->prepare("SELECT title FROM to_do_list WHERE id = ?");
        $stmt->execute([$listId]);
        $list = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $listId = $_POST['listId'];
        $listTitle = $_POST['listTitle'];

        $query = "UPDATE to_do_list SET title = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$listTitle, $listId]);

        header("Location: index.php");
        exit;
    }
?>

<div class="modal fade" id="editListModal<?= $list['id'] ?>" tabindex="-1" aria-labelledby="editListModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="editList.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="editListModalLabel">Edit List</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="listTitle" class="form-label">List Title</label>
                        <input type="text" class="form-control" id="listTitle" name="listTitle" value="<?= htmlspecialchars($list['title']) ?>" required>
                    </div>
                    <input type="hidden" name="listId" value="<?= $list['id'] ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>