<?php
$userId = $_SESSION['id'];

// Get the current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 8; // Number of tasks per page
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
$tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card mb-4">
    <div class="card-header">
        To-Do Lists Overview
    </div>
    <div class="card-body">
        <!-- Filter and Search Form -->
        <form method="GET" class="mb-3">
            <div class="row justify-content-md-center">
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#taskModal">
                        Create A Task
                    </button>
                </div>
                <div class="col-md-2">
                    <select name="status_filter" class="form-select">
                        <option value="">All Tasks</option>
                        <option value="completed" <?php echo isset($_GET['status_filter']) && $_GET['status_filter'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="incomplete" <?php echo isset($_GET['status_filter']) && $_GET['status_filter'] === 'incomplete' ? 'selected' : ''; ?>>Incomplete</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="search_query" class="form-control" placeholder="Search tasks..." value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>">
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary w-100">Apply</button>
                </div>
            </div>
        </form>

        <?php require 'taskModal.php'; ?>

        <table class="table table-bordered" id="taskTable">
            <thead>
                <tr>
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
                if (empty($tables)) {
                    echo "<tr><td colspan='7'>No to-do lists found.</td></tr>";
                } else {
                    foreach ($tables as $table) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($table['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($table['title']) . "</td>";
                        echo "<td>" . htmlspecialchars(date('d-m-Y', strtotime($table['due_date']))) . "</td>";
                        echo "<td>" . ucfirst(htmlspecialchars($table['status'])) . "</td>";
                        echo "<td>" . htmlspecialchars($table['priority']) . "</td>";
                        echo "<td>" . htmlspecialchars($table['description']) . "</td>";
                        echo "<td>
                            <div class='dropdown'>
                                <a class='link' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                    <i class='fas fa-ellipsis-v'></i>
                                </a>
                                <ul class='dropdown-menu'>
                                    <li><a class='dropdown-item' data-bs-toggle='modal' data-bs-target='#editTaskModal" . $table['id'] . "'>Edit</a></li>
                                    <li><a class='dropdown-item' href='delete_task.php?id=" . $table['id'] . "' onclick='return confirm(\"Are you sure?\");'>Delete</a></li>
                                </ul>
                            </div>
                        </td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>

        <?php require 'editTask.php'; ?>
        <?php require 'deleteTask.php'; ?>

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
