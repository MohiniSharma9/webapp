<?php
    // initialize the session
    session_start();
    
    // check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
    {
        header("location: login.php");
        exit;
    }
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>

    <!-- add library -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    <!-- add styles css -->
    <link rel="stylesheet" type="text/css" href="assets/css/styles.css">

</head>
<body>
    <br>
    <div class="text-center">
        <div class="page-header">
            <h1>Hi, <b class="text-danger"><?= htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
        </div>
        <hr>
        <p>
            <a href="reset-password.php" class="btn btn-outline-warning">Reset Your Password</a>
            <a href="logout.php" class="btn btn-outline-danger">Sign Out of Your Account</a>
        </p>
        
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-7 col-lg-6 mx-auto">
                    <div class="card card-signin my-5">
                        <div class="card-body">
                            <div class="embed-responsive embed-responsive-16by9">
                                <h1><i>Developed by "Mohini"</i></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

