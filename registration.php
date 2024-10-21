<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <style>
    body{
        background-color: #3b7ddd;
    }
</style>
</head>
<div class="container d-flex justify-content-center align-items-center text-center" style="min-height: 100vh ">
    <div class="card" style="height:500px; background-color: #FFFFFF">
        <form action="registerationProcess.php" class="box mt-5" method="post">
            <h1 class="text-black">Create an account</h1>
            <p class="text-muted">Please enter your information!</p>
            <!-- Adjusting the width of input boxes -->
            <input type="text" name="username" class="form-control text-center mb-3" placeholder="Username" style="width: 70%; margin: 0 auto;" required>
            <input type="email" name="email" class="form-control mb-3 text-center" placeholder="Email" style="width: 70%; margin: 0 auto" required>
            <input type="password" name="password" class="form-control mb-3 text-center" placeholder="Password" style="width: 70%; margin: 0 auto;" required>
            <input type="password" name="confirm_password" class="form-control mb-3 text-center" placeholder="Confirm Password" style="width: 70%; margin: 0 auto;" required>
            <button type="submit" class="btn btn-primary btn-block mt-3 text-white mb-2" style="width: 70%; margin: 0 auto;">Register</button>
            <a href="login.php">Already have an account?</a>
        </form>
    </div>
</div>

    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
