<?php
session_start();  
include 'config.php';  
$userId = 1;  
$username = "John Doe"; 
$email = "user@gmail.com";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['listTitle'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("
        INSERT INTO to_do_list (user_id, title, description, due_date, status)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$userId, $title, $description, $due_date, $status]);

    header("Location: dashboard.php");
    exit;
}
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
                    $stmt = $conn->prepare("
                        SELECT COUNT(*) AS to_do_list_count
                        FROM to_do_list
                        WHERE user_id = ?
                    ");
                    $stmt->execute([$userId]);  

                    $result = $stmt->fetch(PDO::FETCH_ASSOC); 

                    if ($result) {
                        echo "<li>Total To-Do Lists: " . htmlspecialchars($result['to_do_list_count']) . "</li>";
                    } else {
                        echo "<li>No to-do lists found.</li>";
                    }
                    ?>

                </ul>
            </div>
        </div>
        <form id="todoForm" method="POST" action="create_todo.php" class="mb-4">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#descriptionModal">
                Create List
            </button>
        </form>
<div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="dashboard.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="descriptionModalLabel">Add To-Do List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="listTitle">Title</label>
                        <input type="text" class="form-control" id="listTitle" name="listTitle" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="4" placeholder="Add more details..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="due_date">Due Date:</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="not_started">Not Started</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" >Create List</button>
                </div>
            </form>
            
        </div>
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
