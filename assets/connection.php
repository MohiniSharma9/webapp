
<?php
// included config
include 'config.php';
/* attempt to connect to MySQL database */
$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// check connection
if($connection === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}