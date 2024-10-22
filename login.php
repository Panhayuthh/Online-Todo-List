<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<style>
    body{
        background-color: #3b7ddd;
    }

    .card {
        border-radius: 30px;
        padding: 30px;
        
    }
</style>
<body>
    
<div class="container-fluid d-flex justify-content-center align-items-center text-center" style="min-height: 100vh ;width: 700px ; ">
        <div class="card p-5" sstyle="width: 800px; height:900px ">
            <?php
            session_start(); 

            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']); 
            }
            ?>
           <form action="loginProcess.php" method="post" class="text-left">
            <h1 class="text-black text-center">Welcome Back!</h1>
            <p class="text-muted text-center">Please enter your email and password!</p>
            
            <!-- Email Label and Input -->
            <div class="form-group">
                <label for="email" class="text-left">Email:</label>
                <input type="email" name="email" class="form-control mb-3" placeholder="Enter your email" style="width: 100%; margin: 0 auto;" required>
            </div>
            
            <div class="form-group">
                <label for="password" class="text-left">Password:</label>
                <input type="password" name="password" class="form-control mb-3" placeholder="Enter your password" style="width: 100%; margin: 0 auto;" required>
            </div>
            
            <a class="forgot text-muted text-center" href="#">Forgot password?</a>
            <button type="submit" class="btn btn-primary btn-block mt-3 text-white mb-2" style="width: 70%; margin: 0 auto;">LogIn</button>
            
            <div class="text-center mt-2">
                <a href="registration.php">Don't have an account?</a>
            </div>
        </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
