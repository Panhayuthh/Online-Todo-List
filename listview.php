<?php
    require 'config.php';

    session_start();
    $userId = $_SESSION['id'];

    if(!isset($_SESSION['id'])){
        header("Location: login.php");
    }

    require_once 'createList.php';

    $listQuery = "SELECT * FROM to_do_list WHERE user_id = ?";
    $stmt = $conn->prepare($listQuery);
    $stmt->execute([$userId]);
    $toDoLists = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <div class="container my-5">
                <div class="row mb-3">
                    <h1>My To-Do Lists</h1>
                </div>
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
                        echo "<div class='card-header bg-danger-subtle d-flex justify-content-between align-items-center'>";
                        echo "<h5 class='m-0'>" . htmlspecialchars($list['title']) . "</h5>";
                        echo "<div class='dropdown'>";
                        echo "<a class='link' role='button' data-bs-toggle='dropdown' aria-expanded='false'>";
                        echo "<i class='fas fa-ellipsis-v'></i>";  // The three dots icon
                        echo "</a>";
                        echo "<ul class='dropdown-menu'>";
                        echo "<li><a class='dropdown-item' data-bs-toggle='modal' data-bs-target='#editListModal" . $list['id'] . "'>Edit List</a></li>";
                        echo "<li><a class='dropdown-item' href='deleteList.php?id=" . $list['id'] . "' onclick='return confirm(\"Are you sure you want to delete this list?\");'>Delete List</a></li>";
                        echo "</ul>";
                        echo "</div>";
                        echo "</div>";

                        echo "<div class='card-body'>";
                        

        
                        if ($tasks) {
                            foreach ($tasks as $task) {
                                echo "<div class='card mb-2'>";
                                echo "<div class='card-header d-flex justify-content-between align-items-center'>";
                                echo "<h6 class='card-title m-0'>" . htmlspecialchars($task['name']) . "</h6>";
                                echo "<div class='dropdown'>";
                                echo "<a class='link' role='button' data-bs-toggle='dropdown' aria-expanded='false'>";
                                echo "<i class='fas fa-ellipsis-v'></i>";
                                echo "</a>";
                                echo "<ul class='dropdown-menu'>";
                                echo "<li><a class='dropdown-item' data-bs-toggle='modal' data-bs-target='#editTaskModal" . $task['id'] . "'>Edit</a></li>";
                                echo "<li><a class='dropdown-item' href='deleteTask.php?id=" . $task['id'] . "' onclick='return confirm(\"Are you sure?\");'>Delete</a></li>";
                                echo "</ul>";
                                echo "</div>";
                                echo "</div>";
                                echo "<div class='card-body'>";
                                echo "<p class='card-text'>Status: " . htmlspecialchars($task['status']) . "</p>";
                                echo "<p class='card-text'>Due Date: " . htmlspecialchars(date('d-m-Y', strtotime($task['due_date']))) . "</p>";
                                echo $task['id'];
                                echo "</div>";
                                echo "</div>";
                                require 'editTask.php';
                            }
                        } else {
                            echo "<p>No tasks found for this list.</p>";
                        }
        
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }

                    require 'deleteTask.php';
                    ?>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>

</html>
