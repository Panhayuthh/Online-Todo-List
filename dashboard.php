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
        <!-- modal to create list -->
    <?php
        include("modal_createList.php");
    ?>
        <!-- Display To-Do Lists -->
    <?php 
        include("viewToDoList.php");
    ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
