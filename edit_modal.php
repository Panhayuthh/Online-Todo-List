<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $taskId = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM task WHERE id = ?");
    $stmt->execute([$taskId]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taskId = $_POST['taskId'];
    $taskName = trim($_POST['taskName']);
    $description = trim($_POST['description']);
    $dueDate = $_POST['dueDate'];
    $status = $_POST['status'];
    $priority = $_POST['priority'];

    $query = "UPDATE task SET name = ?, description = ?, due_date = ?, status = ?, priority = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$taskName, $description, $dueDate, $status, $priority, $taskId]);

    header("Location: dashboardV2.php");
    exit;

}

foreach ($tables as $table) {
    ?>
    <div class="modal fade" id="editTaskModal<?= $table['id'] ?>" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="edit_modal.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="taskName" class="form-label">Task Name</label>
                            <input type="text" class="form-control" id="taskName" name="taskName" value="<?= htmlspecialchars($table['name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description"><?= htmlspecialchars($table['description']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="dueDate" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="dueDate" name="dueDate" value="<?= htmlspecialchars(date('Y-m-d', strtotime($table['due_date']))); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Not Started" <?= $table['status'] == 'Not Started' ? 'selected' : '' ?>>Not Started</option>
                                <option value="In Progress" <?= $table['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                <option value="Completed" <?= $table['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select" id="priority" name="priority" required>
                                <option value="Low" <?= $table['priority'] == 'Low' ? 'selected' : '' ?>>Low</option>
                                <option value="Medium" <?= $table['priority'] == 'Medium' ? 'selected' : '' ?>>Medium</option>
                                <option value="High" <?= $table['priority'] == 'High' ? 'selected' : '' ?>>High</option>
                            </select>
                        </div>
                        <input type="hidden" name="taskId" value="<?= $table['id'] ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
}
?>