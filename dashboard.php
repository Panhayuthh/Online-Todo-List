<?php
include 'config.php';  // Include the database connection

$userId = 1;  // Example user ID
$username = "User";
$email = "User@gmail.com";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <style>
        body { display: flex; height: 100vh; margin: 0; }
        .sidebar { background-color: lightblue; width: 250px; padding: 15px; display: flex; flex-direction: column; }
        .sidebar a { color: black; text-decoration: none; font-size: 18px; padding: 10px 15px; border-radius: 4px; transition: all 0.2s; }
        .sidebar a:hover, .sidebar a.active { background-color: #ff6600; color: black; }
        .sidebar .user-profile { text-align: center; margin-bottom: 15px; }
        .sidebar .user-profile img { border-radius: 50%; width: 80px; height: 80px; }
        .content { flex: 1; padding: 20px; background-color: #f8f9fa; overflow-y: auto; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="user-profile">
            <img src="assets/path_to_profile_image.jpg" alt="Profile Image">
            <h5 class="mt-2"><?php echo htmlspecialchars($username); ?></h5>
            <p class="text-muted"><?php echo htmlspecialchars($email); ?></p>
        </div>
        <a href="dashboard.php" class="active"><i class="fas fa-home"></i> Home</a>
        <a href="mywork.php"><i class="fas fa-clipboard"></i> My Work</a>
        <a href="favorite.php"><i class="fas fa-star"></i> Favorites</a>
    </nav>

    <!-- Main Content -->
    <div class="content">
        <h1>Project Management Dashboard</h1>
        <h3>To-Do Lists Overview</h3>

        <!-- Overview of To-Do Lists -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Overview of To-Do Lists</div>
            <div class="card-body">
                <p>Welcome, <strong><?php echo htmlspecialchars($username); ?></strong>! Here is an overview of your existing to-do lists:</p>
                <ul>
                    <?php
                    $stmt = $kunci->prepare("
                        SELECT title, COUNT(tasks.id) AS task_count
                        FROM to_do_list
                        LEFT JOIN tasks ON to_do_list.id = tasks.list_id
                        WHERE to_do_list.user_id = ?
                        GROUP BY to_do_list.id
                    ");
                    $stmt->execute([$userId]);  // Correct PDO execution

                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Fetch all results

                    if (count($result) > 0) {
                        foreach ($result as $row) {
                            echo "<li>" . htmlspecialchars($row['title']) . ": " . $row['task_count'] . " tasks</li>";
                        }
                    } else {
                        echo "<li>No to-do lists found.</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Create New To-Do List -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">Create New To-Do List</div>
            <div class="card-body">
                <form method="POST" action="create_todo.php">
                    <div class="form-group">
                        <label for="listTitle">To-Do List Title</label>
                        <input type="text" name="listTitle" id="listTitle" class="form-control" placeholder="e.g., Work, Personal" required>
                        
                    </div>
                    <button type="submit" class="btn btn-primary">Create List</button>
                </form>
            </div>
        </div>

        <!-- Display To-Do Lists -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">Your To-Do Lists</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Number of Tasks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $kunci->prepare("
                            SELECT to_do_list.id, to_do_list.title, COUNT(tasks.id) AS task_count
                            FROM to_do_list
                            LEFT JOIN tasks ON to_do_list.id = tasks.list_id
                            WHERE to_do_list.user_id = ?
                            GROUP BY to_do_list.id
                        ");
                        $stmt->execute([$userId]);

                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (count($result) > 0) {
                            foreach ($result as $row) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                echo "<td>" . $row['task_count'] . "</td>";
                                echo "<td>";
                                echo "<a href='view_list.php?id=" . $row['id'] . "' class='btn btn-info btn-sm'>View</a> ";
                                echo "<a href='delete_list.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this list?\");'>Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No lists found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
