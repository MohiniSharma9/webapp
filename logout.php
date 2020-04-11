<?php
    // initialize the session
    session_start();
    
    // unset all of the session variables
    $_SESSION = array();
    
    // destroy the session.
    session_destroy();
    
    // redirect to login page
    header("location: login.php");
    exit;
?>