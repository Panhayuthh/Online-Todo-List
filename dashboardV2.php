<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TODO List Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container my-5">
        <div class="row mb-3">
            <h1>MY TODO LIST</h1>
        </div>

        <!-- Title -->
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header bg-white"></div>
                    <div class="card-body">
                        <h5 class="card-title">All Tasks</h5>
                        <p class="card-text">Total Tasks:</p>
                         <?php
                            require 'config.php';
                            session_start();
                            $userId = $_SESSION['id'];

                            $query = ("SELECT COUNT(*) AS task_count FROM task t JOIN to_do_list l ON t.todo_list_id = l.id WHERE l.user_id = ?");

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
                            $query = ("SELECT COUNT(*) AS task_count FROM task t JOIN to_do_list l ON t.todo_list_id = l.id WHERE l.user_id = ? AND t.status = 'Not Started'");

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
                            $query = ("SELECT COUNT(*) AS task_count FROM task t JOIN to_do_list l ON t.todo_list_id = l.id WHERE l.user_id = ? AND t.status = 'Completed'");

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

    <!-- Task view table -->
     <div class="container">
         <?php require 'viewToDoList.php'; ?>
     </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>