

<?php
    // initialize the session
    session_start();
    
    // check if the user is already logged in, if yes then redirect him to welcome page
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
    {
        header("location: welcome.php");
        exit;
    }
    
    // include config file
    require_once "assets/connection.php";
    
    // define variables and initialize with empty values
    $username = $password = "";
    $username_err = $password_err = "";
    
    // processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // check if username is empty
        if(empty(trim($_POST["username"])))
        {
            $username_err = '<p class="text-danger">Please enter username.</p>';
        } else{
            $username = trim($_POST["username"]);
        }
        
        // check if password is empty
        if(empty(trim($_POST["password"])))
        {
            $password_err = '<p class="text-danger">Please enter your password.</p>';
        } 
        else
        {
            $password = trim($_POST["password"]);
        }
        
        // validate credentials
        if(empty($username_err) && empty($password_err))
        {
            // prepare a select statement
            $sql = "SELECT id, username, password FROM users WHERE username = ?";
            
            if($stmt = mysqli_prepare($connection, $sql))
            {
                // bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                
                // set parameters
                $param_username = $username;
                
                // attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt))
                {
                    // store result
                    mysqli_stmt_store_result($stmt);
                    
                    // check if username exists, if yes then verify password
                    if(mysqli_stmt_num_rows($stmt) == 1)
                    {                    
                        // bind result variables
                        mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                        if(mysqli_stmt_fetch($stmt))
                        {
                            if(password_verify($password, $hashed_password))
                            {
                                // password is correct, so start a new session
                                session_start();
                                
                                // store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;                            
                                
                                // redirect user to welcome page
                                header("location: welcome.php");
                            } 
                            else
                            {
                                // display an error message if password is not valid
                                $password_err = '<p class="text-danger">The password you entered was not valid.</p>';
                            }
                        }
                    } 
                    else
                    {
                        // display an error message if username doesn't exist
                        $username_err = '<p class="text-danger">No account found with that username.</p>';
                    }
                }
                else
                {
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
            
            // close statement
            mysqli_stmt_close($stmt);
        }
        
        // close connection
        mysqli_close($connection);
    }
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

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
    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card card-signin my-5">
                    <div class="card-body">
                        <div class="wrapper">
                            <h2 class="text-center">Login</h2>
                            <p class="text-center">Details required to login.</p>
                            <hr>
                            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="form-group <?= (!empty($username_err)) ? 'has-error' : ''; ?>">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control" placeholder="Enter username" value="<?= $username; ?>">
                                    <span class="help-block"><?= $username_err; ?></span>
                                </div>    
                                <div class="form-group <?= (!empty($password_err)) ? 'has-error' : ''; ?>">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Enter password">
                                    <span class="help-block"><?= $password_err; ?></span>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-outline-success" value="Login">
                                </div>
                                    <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
                            </form>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

    