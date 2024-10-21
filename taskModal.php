<?php
    require_once 'config.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $todoListId = $_POST['todoListId'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $due_date = $_POST['due_date'];
        $status = $_POST['status'];
        $priority = $_POST['priority'];
        $created_at = date('Y-m-d H:i:s');

        $query = "INSERT INTO task (todo_list_id, name, description, due_date, status, priority, createAt) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$todoListId, $name, $description, $due_date, $status, $priority, $created_at]);

        header("Location: dashboardV2.php");
        exit;
    }
?>

<html>
    <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="taskModal.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="descriptionModalLabel">Add task to your list!!</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="todoListId" class="form-label">Select List</label>
                            <select class="form-select" id="todoListId" name="todoListId" required>
                                <option value="">Select List</option>
                                <?php
                                    $stmt = $conn->prepare("SELECT * FROM to_do_list WHERE user_id = ?");
                                    $stmt->execute([$userId]);
                                    $lists = $stmt->fetchAll();
                                    foreach ($lists as $list) {
                                        echo "<option value='".$list['id']."'>".$list['title']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Task Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Not Started">Not Started</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select" id="priority" name="priority" required>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</html>
