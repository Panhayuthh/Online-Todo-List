<?php
require 'config.php';
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$id = $_SESSION['id'];

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User profile not found!";
    exit();
}

// Update user profile on form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? ''; 

    // Start building the update query
    $updates = [];
    $params = [];

    // Update username and email if they're not empty
    if (!empty($username)) {
        $updates[] = "username = ?";
        $params[] = $username;
    }
    if (!empty($email)) {
        $updates[] = "email = ?";
        $params[] = $email;
    }

    // Handle password change
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $updates[] = "password = ?";
        $params[] = $hashedPassword;
    }

    // Execute the update query if there are updates
    if (!empty($updates)) {
        $updateQuery = "UPDATE user SET " . implode(", ", $updates) . " WHERE id = ?";
        $params[] = $id; 
        $stmt = $conn->prepare($updateQuery);
        $stmt->execute($params);
    }
    
    header("Location: profileManagement.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Info</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="style.css" rel="stylesheet">
    <style>
      
    </style>
</head>
<body>
    <!-- <button class="btn btn-primary p-2 mt-3 " style="margin-left:20px">
        <a href="index.php" class="text-white" style="text-decoration: none;">Home</a>
    </button> -->

    <div class="wrapper">
        <!-- Sidebar -->
        <?php require 'sidebar.php'; ?>

        <!-- Content -->
        <div class="container-fluid">
            <div class="container mt-5 col-4">
                <div class="card p-4 shadow-sm">
                    <div class="d-flex align-items-center">
                        <img id="profilePreview" 
                             src="<?= isset($user['profile_picture']) && !empty($user['profile_picture']) ? 'userprofile/' . htmlspecialchars($user['profile_picture']) : 'https://via.placeholder.com/100' ?>" 
                             alt="Profile Photo" 
                             class="rounded-circle me-4" 
                             style="width: 100px; height: 100px; object-fit: cover">
                        <div>
                            <h4 class="mb-0"><?= htmlspecialchars($user['username']) ?></h4>
                            <!-- <p class="text-muted mb-1">Personalize your account with a photo.<br>Your profile photo will appear on apps and devices that use your account.</p> -->
                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#changeProfile">
                                Change Photo
                            </button>
                        </div>
                    </div>
                </div>
        
                <!-- Change Profile Picture Modal -->
                <div class="modal fade" id="changeProfile" tabindex="-1" aria-labelledby="changeProfileLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Change Profile Picture</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="upload.php" method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <input type="file" name="userprofile" class="form-control" accept="image/*" required>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-primary">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        
                <?php
                // Display success or error messages
                if (isset($_GET['success'])) {
                    echo '<div class="alert alert-success">' . htmlspecialchars($_GET['success']) . '</div>';
                } elseif (isset($_GET['error'])) {
                    echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
                }
                ?>
        
                <!-- Profile Info Card -->
                <div class="card mt-4 p-4 shadow-sm">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-0">Profile info</h5>
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            Edit Info
                        </button>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Username</h6>
                            <p class="text-muted mb-0"><?= htmlspecialchars($user['username']) ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Email</h6>
                            <p class="text-muted mb-0"><?= htmlspecialchars($user['email']) ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Password</h6>
                            <p class="text-muted mb-0">*******</p> <!-- Hide the actual password -->
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- Edit Profile Modal -->
            <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProfileModalLabel">Edit Profile Info</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="profileManagement.php" method="post">
                                <div class="mb-3">
                                    <label for="usernameInput" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="usernameInput" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="emailInput" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="emailInput" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="passwordInput" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Enter new password">
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
