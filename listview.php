<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>To-Do List Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
    <div class="card-body">
        <div class="row">
            <div class="col mb-3">
                <h1 class="card-title">My To Do Lists</h1>
                <div class="container my-5">
                        <?php
                        require 'config.php'; 
                        session_start();
                        $userId = $_SESSION['id'];

                        $listQuery = "SELECT * FROM to_do_list WHERE user_id = ?";
                        $stmt = $conn->prepare($listQuery);
                        $stmt->execute([$userId]);
                        $toDoLists = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach ($toDoLists as $list) {
                            echo "<li class='list-group-item'>";
                            echo "</li>";
                        }
                        ?>    
                        </ul>
                        </div>

                        <div class="col mb-1"></div>
                        <a href="createList.php" class="btn btn-primary">+Create new list</a>
                    </li>
                </ul>
            </div>
</div>
            
            <div class="container my-1">
                <div class="row mb-3">
                    <h2>Tasks</h2>
                </div>
                
                <div class="row">
                    <?php
                    foreach ($toDoLists as $list) {
                        $taskQuery = "SELECT * FROM task WHERE todo_list_id = ?";
                        $stmt = $conn->prepare($taskQuery);
                        $stmt->execute([$list['id']]);
                        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        echo "<div class='col mb-3'>"; 
                        echo "<div class='card'>";
                        echo "<h5 class='card-header bg-danger-subtle'>" . htmlspecialchars($list['name']) . "</h5>";
                        echo "<div class='card-body'>";
                        echo "<h4 class='card-title'>$list[title]</h4>";

                        if ($tasks) {
                            foreach ($tasks as $task) {
                                echo "<div class='card mb-2'>";
                                echo "<div class='card-body'>";
                                echo "<h6 class='card-title'>" . htmlspecialchars($task['name']) . "</h6>";
                                echo "<p class='card-text'>Status: " . htmlspecialchars($task['status']) . "</p>";
                                echo "<p class='card-text'>Due Date: " . htmlspecialchars($task['due_date']) . "</p>";
                                echo "</div>"; 
                                echo "</div>"; 
                            }
                        } else {
                            echo "<p>No tasks found for this list.</p>";
                        }
            
                        echo "</div>"; 
                        echo "</div>"; 
                        echo "</div>"; 
                    }
                    ?>
                </div>
            </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
