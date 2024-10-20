<div class="card mb-4">
        <div class="card-header bg-success text-white">Your To-Do Lists</div>
        <div class="card-body">
    
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
                    $stmt = $conn->prepare("
                        SELECT id, title, description, due_date, status
                        FROM to_do_list
                        WHERE user_id = ?
                    ");
                    $stmt->execute([$userId]);
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