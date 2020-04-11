<?php
    // initialize the session
    session_start();
    
    // check if the user is logged in, if not then redirect to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
    {
        header("location: login.php");
        exit;
    }
    
    // include config file
    require_once "assets/connection.php";
    
    // define variables and initialize with empty values
    $new_password = $confirm_password = "";
    $new_password_err = $confirm_password_err = "";
    
    // processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
    
        // validate new password
        if(empty(trim($_POST["new_password"])))
        {
            $new_password_err = '<p class="text-danger">Please enter the new password.</p>';     
        } 
        elseif(strlen(trim($_POST["new_password"])) < 6)
        {
            $new_password_err = '<p class="text-danger">Password must have atleast 6 characters.</p>';
        } 
        else
        {
            $new_password = trim($_POST["new_password"]);
        }
        
        // validate confirm password
        if(empty(trim($_POST["confirm_password"])))
        {
            $confirm_password_err = '<p class="text-danger">Please confirm the password.</p>';
        } 
        else
        {
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($new_password_err) && ($new_password != $confirm_password))
            {
                $confirm_password_err = '<p class="text-danger">Password did not match.</p>';
            }
        }
            
        // check input errors before updating the database
        if(empty($new_password_err) && empty($confirm_password_err))
        {
            // prepare an update statement
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            
            if($stmt = mysqli_prepare($link, $sql))
            {
                // bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
                
                // set parameters
                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                $param_id = $_SESSION["id"];
                
                // attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt))
                {
                    // password updated successfully. Destroy the session, and redirect to login page
                    session_destroy();
                    header("location: login.php");
                    exit();
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
    <title>Reset Password</title>

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
                            <h2 class="text-center">Reset Password</h2>
                            <p class="text-center">Details to reset password required:</p>
                            <hr>
                            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                                <div class="form-group <?= (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                                    <label>New Password</label>
                                    <input type="password" name="new_password" class="form-control" placeholder="Enter new password" value="<?= $new_password; ?>">
                                    <span class="help-block"><?= $new_password_err; ?></span>
                                </div>
                                <div class="form-group <?= (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                                    <label>Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Enter confirm password">
                                    <span class="help-block"><?= $confirm_password_err; ?></span>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-outline-success" value="Submit">
                                    <a class="btn btn-link" href="welcome.php">Cancel</a>
                                </div>
                            </form>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
