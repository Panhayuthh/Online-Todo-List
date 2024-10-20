<div class="card mb-4">
        <div class="card-header bg-success text-white">Your To-Do Lists</div>
        <div class="card-body">
            <!-- Filter and Search Form -->
            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <select name="status_filter" class="form-select">
                            <option value="">All Tasks</option>
                            <option value="completed" <?php echo isset($_GET['status_filter']) && $_GET['status_filter'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="incomplete" <?php echo isset($_GET['status_filter']) && $_GET['status_filter'] === 'incomplete' ? 'selected' : ''; ?>>Incomplete</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search_query" class="form-control" placeholder="Search tasks..." value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Apply</button>
                    </div>
                </div>
            </form>
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $statusFilter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';
                    $searchQuery = isset($_GET['search_query']) ? $_GET['search_query'] : '';
                    $stmt = $conn->prepare("
                        SELECT id, title, description, due_date, status
                        FROM to_do_list
                        WHERE user_id = ?
                    ");
                    $stmt->execute([$userId]);
                    $lists = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($statusFilter) {
                        if ($statusFilter === 'completed') {
                            $sql .= " AND status = 'completed'";
                        } elseif ($statusFilter === 'incomplete') {
                            $sql .= " AND status = 'incomplete'";
                        }
                    }

                    if ($searchQuery) {
                        $sql .= " AND title LIKE ?";
                        $params[] = '%' . $searchQuery . '%';
                    }
    
                    $stmt = $conn->prepare($sql);
                    $stmt->execute($params);
                    $lists = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($lists) {
                        foreach ($lists as $list) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($list['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($list['description']) . "</td>";
                            echo "<td>" . htmlspecialchars($list['due_date']) . "</td>";
                            echo "<td>" . ucfirst(htmlspecialchars($list['status'])) . "</td>";
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
    </div>