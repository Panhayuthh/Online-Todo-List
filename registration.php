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
    <link rel="stylesheet" href="styles.css">
</head>
<body style="background-image: url('user/theme.png'); background-size: cover; background-repeat: no-repeat;" class="text-center bg-dark">

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card p-4" style="width: 400px; background-color: #1c1c1c;">
            <form action="register_process.php" class="box" method="post">
                <h1 class="text-white">SIGN IN</h1>
                <p class="text-muted">Please enter your information!</p>
                <input type="text" name="username" class="form-control mb-3 text-center" placeholder="Username" required>
                <input type="email" name="email" class="form-control mb-3 text-center" placeholder="Email" required>
                <input type="password" name="password" class="form-control mb-3 text-center" placeholder="Password" required>
                <input type="password" name="confirm_password" class="form-control mb-3 text-center" placeholder="Confirm Password" required>
                <button type="submit" class="btn btn-outline-success btn-block mt-3">Register</button>
                <a href="loginpage.php">Already have an account?</a>

                <div class="col-12 mt-4">
                    <ul class="social-network list-inline">
                        <li class="list-inline-item"><a href="#" class="icoFacebook" title="Facebook"><i class="fab fa-facebook-f fa-2x text-white"></i></a></li>
                        <li class="list-inline-item"><a href="#" class="icoTwitter" title="Twitter"><i class="fab fa-twitter fa-2x text-white"></i></a></li>
                        <li class="list-inline-item"><a href="#" class="icoGoogle" title="Google+"><i class="fab fa-google-plus fa-2x text-white"></i></a></li>
                    </ul>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
