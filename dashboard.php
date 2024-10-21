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

    <!-- Bootstrap and Font Awesome -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">

    <style>
        body { display: flex; height: 100vh; margin: 0; }
        .sidebar {
            background-color: lightblue;
            width: 250px;
            display: flex;
            flex-direction: column;
            padding: 15px;
            position: fixed;
            height: 100vh;
        }
        .sidebar a {
            color: black;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 15px;
            border-radius: 4px;
            transition: all 0.2s;
        }
        .sidebar a:hover, .sidebar a.active {
            color: black;
        }
        .sidebar .user-profile {
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar .user-profile img {
            border-radius: 50%;
            width: 80px;
            height: 80px;
        }
        .content {
            margin-left: 250px;
            flex: 1;
            padding: 20px;
            background-color: #f8f9fa;
            overflow-y: auto;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .content {
                margin-left: 0;
            }
        }
    </style>

<!-- Include Profile Modal -->
</head>
<body>

    <!-- Sidebar -->
    <nav class="sidebar">
    <button type="submit" class="user-profile" data-bs-toggle="modal" data-bs-target="profileModal">
            <img src="assets/profile.jpg" alt="Profile Image" class="rounded-circle">
            <h5 class="mt-2"><?php echo htmlspecialchars($username); ?></h5>
            <p class="text-muted"><?php echo htmlspecialchars($email); ?></p>
    </button>
        <?php
        include("profileManagement.php");
        ?>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link active"><i class="fas fa-home me-2"></i>Dashboard</a>
            </li>
            <li class="nav-item">
                <a href="mywork.php" class="nav-link"><i class="fas fa-clipboard me-2"></i>My To-Do Lists</a>
            </li>
            <li class="nav-item">
                <a href="create_todo.php" class="nav-link"><i class="fas fa-plus me-2"></i>Create New List</a>
            </li>
            <li class="nav-item">
                <a href="tasks.php" class="nav-link"><i class="fas fa-tasks me-2"></i>Tasks Assigned to Me</a>
            </li>
            <li class="nav-item">
                <a href="favorite.php" class="nav-link"><i class="fas fa-star me-2"></i>Favorites</a>
            </li>
            <li class="nav-item">
                <a href="completed.php" class="nav-link"><i class="fas fa-check-circle me-2"></i>Completed Tasks</a>
            </li>
            <li class="nav-item">
                <a href="calendar.php" class="nav-link"><i class="fas fa-calendar-alt me-2"></i>Calendar</a>
            </li>
            <li class="nav-item mt-auto">
                <a href="settings.php" class="nav-link"><i class="fas fa-cog me-2"></i>Settings</a>
            </li>
            <li class="nav-item">
                <a href="logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
            </li>
        </ul>
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
                Create A Task
            </button>
        </form>
        <!-- modal to create list -->
    <?php
        include("createTask.php");
    ?>
        <!-- Display To-Do Lists -->
        <?php include("viewToDoList.php"); ?>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
