<?php

require 'deleteTask.php';
$userId = $_SESSION['id'];

// Get the current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5; // Number of tasks per page
$offset = ($page - 1) * $limit;

// Get filters
$statusFilter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';
$searchQuery = isset($_GET['search_query']) ? $_GET['search_query'] : '';
$params = [$userId];

// Count total tasks based on filters
$countSql = "SELECT COUNT(*) as total 
              FROM task t 
              JOIN to_do_list l ON t.todo_list_id = l.id 
              WHERE l.user_id = ?";

if ($statusFilter) {
    if ($statusFilter === 'completed') {
        $countSql .= " AND t.status = 'Completed'";
    } elseif ($statusFilter === 'incomplete') {
        $countSql .= " AND (t.status = 'Not Started' OR t.status = 'In Progress')";
    }
}

if ($searchQuery) {
    $countSql .= " AND t.name LIKE ?";
    $params[] = '%' . $searchQuery . '%';
}

$countStmt = $conn->prepare($countSql);
$countStmt->execute($params);
$totalTasks = $countStmt->fetchColumn();
$totalPages = ceil($totalTasks / $limit);

// Fetch tasks based on filters and pagination
$sql = "SELECT t.id, t.name, l.title, t.description, t.due_date, t.status, t.priority 
        FROM task t 
        JOIN to_do_list l ON t.todo_list_id = l.id 
        WHERE l.user_id = ?";

$params = [$userId];

if ($statusFilter) {
    if ($statusFilter === 'completed') {
        $sql .= " AND t.status = 'Completed'";
    } elseif ($statusFilter === 'incomplete') {
        $sql .= " AND (t.status = 'Not Started' OR t.status = 'In Progress')";
    }
}

if ($searchQuery) {
    $sql .= " AND t.name LIKE ?";
    $params[] = '%' . $searchQuery . '%';
}

$sql .= " ORDER BY t.status DESC
          LIMIT $limit OFFSET $offset";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card mb-4 shadow-sm" id="tableCard">
    <div class="card-header">
    <h4 class="my-0 font-weight-normal">Tasks Overview</h4>
    </div>
    <div class="card-body">
        <!-- Filter and Search Form -->
        <form method="GET">
            <div class="row justify-content-md-center row-cols-1 row-cols-sm-2">
                <div class="mb-3 col-xl-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#taskModal">
                        Create A Task
                    </button>
                </div>
                <div class="mb-3 col-xl-3">
                    <select name="status_filter" class="form-select">
                        <option value="">All Tasks</option>
                        <option value="completed" <?php echo isset($_GET['status_filter']) && $_GET['status_filter'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="incomplete" <?php echo isset($_GET['status_filter']) && $_GET['status_filter'] === 'incomplete' ? 'selected' : ''; ?>>Incomplete</option>
                    </select>
                </div>
                <div class="mb-3 col-sm-8 col-xl-4">
                    <input type="text" name="search_query" class="form-control w-100" placeholder="Search tasks..." value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>">
                </div>
                <div class="mb-3 col-sm-4 col-xl-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Apply</button>
                </div>
            </div>
        </form>

        <?php require 'taskModal.php'; ?>

        <div class="table-responsive">
            <table class="table table-bordered" id="taskTable">
                <thead>
                    <tr>
                        <th></th>
                        <th>Task</th>
                        <th>List</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (empty($tasks)) {
                        echo "<tr><td colspan='7'>No to-do lists found.</td></tr>";
                    } else {
                        foreach ($tasks as $task) {
                            $isCompleted = $task['status'] === 'Completed';
                            echo "<tr class='" . ($isCompleted ? 'table-success' : '') . "'>";
                            echo "<td><input type='checkbox' class='complete-task-checkbox' data-task-id='" . $task['id'] . "' " . ($isCompleted ? 'checked disabled' : '') . "></td>";
                            echo "<td>" . htmlspecialchars($task['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($task['title']) . "</td>";
                            echo "<td>" . htmlspecialchars(date('d-m-Y', strtotime($task['due_date']))) . "</td>";
                            echo "<td>" . ucfirst(htmlspecialchars($task['status'])) . "</td>";
                            echo "<td>" . htmlspecialchars($task['priority']) . "</td>";
                            echo "<td>" . htmlspecialchars($task['description']) . "</td>";
                            echo "<td>
                                <div class='dropdown'>
                                    <a class='link' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                        <i class='fas fa-ellipsis-v'></i>
                                    </a>
                                    <ul class='dropdown-menu'>
                                        <li><a class='dropdown-item' data-bs-toggle='modal' data-bs-target='#editTaskModal" . $task['id'] . "'>Edit</a></li>
                                        <li><a class='dropdown-item' href='deleteTask.php?id=" . $task['id'] . "' onclick='return confirm(\"Are you sure?\");'>Delete</a></li>
                                    </ul>
                                </div>
                            </td>";
                            echo "</tr>";
                            require 'editTask.php';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center" id="pagination">
                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&status_filter=<?php echo urlencode($statusFilter); ?>&search_query=<?php echo urlencode($searchQuery); ?>" tabindex="-1" aria-disabled="true">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&status_filter=<?php echo urlencode($statusFilter); ?>&search_query=<?php echo urlencode($searchQuery); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&status_filter=<?php echo urlencode($statusFilter); ?>&search_query=<?php echo urlencode($searchQuery); ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>