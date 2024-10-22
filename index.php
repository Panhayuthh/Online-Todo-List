<?php
    require 'config.php';
    session_start();

    if(!isset($_SESSION['id'])){
        header("Location: login.php");
    }

    $userId = $_SESSION['id'];
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TODO List Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="styles.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <!-- sidebar -->
        <?php require 'sidebar.php'; ?>
        
        <!-- Contain Body -->
        <div class="main">
            <div class="container mt-5">
                <div class="row mb-3">
                    <h1>My To-Do Lists</h1>
                </div>

                <!-- Title Card-->
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header bg-white"></div>
                            <div class="card-body">
                                <h5 class="card-title">All Tasks</h5>
                                <p class="card-text">Total Tasks:</p>
                                <?php
                                    $query = (" SELECT COUNT(*) AS task_count FROM task t 
                                                JOIN to_do_list l ON t.todo_list_id = l.id 
                                                WHERE l.user_id = ?");

                                    $stmt = $conn->prepare($query);
                                    $stmt->execute([$userId]);

                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                    echo "<h2 class='card-text'>".$result['task_count']."</h2>";
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                        <div class="card-header bg-danger-subtle"></div>
                            <div class="card-body">
                                <h5 class="card-title">Incomplete Tasks</h5>
                                <p class="card-text">Total Tasks:</p>
                                <?php
                                    $query = (" SELECT COUNT(*) AS task_count FROM task t 
                                                JOIN to_do_list l ON t.todo_list_id = l.id 
                                                WHERE l.user_id = ? 
                                                AND t.status = 'Not Started' OR t.status = 'In Progress'");

                                    $stmt = $conn->prepare($query);
                                    $stmt->execute([$userId]);

                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                    echo "<h2 class='card-text'>".$result['task_count']."</h2>";
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header bg-success-subtle"></div>    
                            <div class="card-body">
                                <h5 class="card-title">Completed Tasks</h5>
                                <p class="card-text">Total Tasks:</p>
                                <?php
                                    $query = (" SELECT COUNT(*) AS task_count FROM task t 
                                                JOIN to_do_list l ON t.todo_list_id = l.id 
                                                WHERE l.user_id = ? 
                                                AND t.status = 'Completed'");

                                    $stmt = $conn->prepare($query);
                                    $stmt->execute([$userId]);

                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                    echo "<h2 class='card-text'>".$result['task_count']."</h2>";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table View -->
            <div class="container mt-5">
                <?php require 'viewToDoList.php'; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>
</html>