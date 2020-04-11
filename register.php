<?php
    // include config file
    require_once "assets/connection.php";
    
    // define variables and initialize with empty values
    $username = $password = $confirm_password = "";
    $username_err = $password_err = $confirm_password_err = "";
    
    // processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
    
        // Validate username
        if(empty(trim($_POST["username"])))
        {
            $username_err = '<p class="text-danger">Please enter a username.</p>';
        } 
        else
        {
            // prepare a select statement
            $sql = "SELECT id FROM users WHERE username = ?";
            
            if($stmt = mysqli_prepare($connection, $sql))
            {
                // bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                
                // set parameters
                $param_username = trim($_POST["username"]);
                
                // attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    /* store result */
                    mysqli_stmt_store_result($stmt);
                    
                    if(mysqli_stmt_num_rows($stmt) == 1)
                    {
                        $username_err = '<p class="text-danger">This username is already taken.</p>';
                    } 
                    else
                    {
                        $username = trim($_POST["username"]);
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
        
        // validate password
        if(empty(trim($_POST["password"])))
        {
            $password_err = '<p class="text-danger">Please enter a password.</p>';     
        } 
        elseif(strlen(trim($_POST["password"])) < 6)
        {
            $password_err = '<p class="text-danger">Password must have atleast 6 characters.</p>';
        } 
        else
        {
            $password = trim($_POST["password"]);
        }
        
        // validate confirm password
        if(empty(trim($_POST["confirm_password"])))
        {
            $confirm_password_err = '<p class="text-danger">Please confirm password.</p>';     
        } 
        else
        {
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password))
            {
                $confirm_password_err = '<p class="text-danger">Password did not match.</p>';
            }
        }
        
        // check input errors before inserting in database
        if(empty($username_err) && empty($password_err) && empty($confirm_password_err))
        {
            
            // prepare an insert statement
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            
            if($stmt = mysqli_prepare($connection, $sql))
            {
                // bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
                
                // Set parameters
                $param_username = $username;
                $param_password = password_hash($password, PASSWORD_DEFAULT); // creates a password hash
                
                // attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt))
                {
                    // redirect to login page
                    header("location: login.php");
                } else{
                    echo "Something went wrong. Please try again later.";
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
    <title>Sign Up</title>

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
                            <h2 class="text-center">Sign Up</h2>
                            <p class="text-center">PROVIDE THE DETAILS TO CREATE AN ACCOUNT.</p>
                            <hr>
                            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="form-group <?= (!empty($username_err)) ? 'has-error' : ''; ?>">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control"placeholder="Enter username" value="<?= $username; ?>">
                                    <span class="help-block"><?= $username_err; ?></span>
                                </div>    
                                <div class="form-group <?= (!empty($password_err)) ? 'has-error' : ''; ?>">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Enter password" value="<?= $password; ?>">
                                    <span class="help-block"><?= $password_err; ?></span>
                                </div>
                                <div class="form-group <?= (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                                    <label>Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Enter confirm password" value="<?php echo $confirm_password; ?>">
                                    <span class="help-block"><?= $confirm_password_err; ?></span>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-outline-success" value="Submit">
                                    <input type="reset" class="btn btn-outline-danger float-right" value="Reset">
                                </div>
                                <p>Already have an account? <a href="login.php">Login here</a>.</p>
                            </form>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>