<?php
    require 'config.php';
    session_start();

    if(!isset($_SESSION['id'])){
        header("Location: login.php");
    }

    $userId = $_SESSION['id'];

    require_once 'createList.php';
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>To-Do List Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="styles.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php require 'sidebar.php'; ?>

        <!-- Content -->
        <div class="main">
            <!-- <div class="container my-5">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title">My To-Do Lists</h1>
                        <div class="container my-5">
                            <ul class="list-group">
                                <?php
        
                                $listQuery = "SELECT * FROM to_do_list WHERE user_id = ?";
                                $stmt = $conn->prepare($listQuery);
                                $stmt->execute([$userId]);
                                $toDoLists = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
                                foreach ($toDoLists as $list) {
                                    echo "<li class='list-group-item'>";
                                    echo htmlspecialchars($list['title']);
                                    echo "</li>";
                                }
                                ?>
                            </ul>
                            <a href="createList.php" class="btn btn-primary mt-3">+ Create new list</a>
                        </div>
                    </div>
                </div>
            </div> -->
        
            <div class="container my-5">
                <h1 class="card-title">My To-Do Lists</h1>
                <div class="row my-3">
                    <div class="col">
                        <h2 class="m-0">Tasks</h2>
                    </div>
                    <div class="col d-flex justify-content-end">
                        <a href="createList.php" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#listCreateModal">
                            + Create new list
                        </a>
                    </div>
                </div>
                <div class="row" id="list">
                    <?php
                    foreach ($toDoLists as $list) {
                        $taskQuery = "  SELECT * FROM task 
                                        WHERE todo_list_id = ?
                                        ORDER BY status DESC, due_date ASC";
                        $stmt = $conn->prepare($taskQuery);
                        $stmt->execute([$list['id']]);
                        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
                        echo "<div class='col-3 mb-3 p-0'>";
                        echo "<div class='card'>";
                        echo "<h5 class='card-header bg-danger-subtle'>" . htmlspecialchars($list['title']) . "</h5>";
                        echo "<div class='card-body'>";
        
                        if ($tasks) {
                            foreach ($tasks as $task) {
                                echo "<div class='card mb-2'>";
                                echo "<div class='card-body'>";
                                echo "<h6 class='card-title'>" . htmlspecialchars($task['name']) . "</h6>";
                                echo "<p class='card-text'>Status: " . htmlspecialchars($task['status']) . "</p>";
                                echo "<p class='card-text'>Due Date: " . htmlspecialchars(date('d-m-Y', strtotime($task['due_date']))) . "</p>";
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>

</html>
