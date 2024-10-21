
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-clipboard-list me-1"></i> To-Do Lists Overview
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

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Task</th>
                    <th>List</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            <?php
                require_once 'config.php';

                $statusFilter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';
                $searchQuery = isset($_GET['search_query']) ? $_GET['search_query'] : '';
                $params = [$userId]; // Start with the userId as the initial parameter
                
                $sql = "SELECT t.id, t.name, l.title, t.description, t.due_date, t.status 
                        FROM task t 
                        JOIN to_do_list l ON t.todo_list_id = l.id 
                        WHERE l.user_id = ?";
                
                if ($statusFilter) {
                    if ($statusFilter === 'completed') {
                        $sql .= " AND t.status = 'completed'";
                    } elseif ($statusFilter === 'incomplete') {
                        $sql .= " AND (t.status = 'Not Started' OR t.status = 'In Progress')";
                    }
                }
                
                if ($searchQuery) {
                    $sql .= " AND t.name LIKE ?";
                    $params[] = '%' . $searchQuery . '%';
                }
                
                $stmt = $conn->prepare($sql);
                $stmt->execute($params);
                $lists = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($lists) {
                    foreach ($lists as $list) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($list['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($list['title']) . "</td>";
                        echo "<td>" . htmlspecialchars($list['due_date']) . "</td>";
                        echo "<td>" . ucfirst(htmlspecialchars($list['status'])) . "</td>";
                        echo "<td>" . htmlspecialchars($list['description']) . "</td>";
                        echo "<td>
                            <a href='view_list.php?id=" . $list['id'] . "' class='btn btn-info btn-sm'>Edit</a>
                            <a href='delete_list.php?id=" . $list['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                          </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No to-do lists found.</td></tr>";
                }
            ?>
            
            </tbody>
        </table>
    </div>
</div>
