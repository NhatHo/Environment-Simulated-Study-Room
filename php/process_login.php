<?php
require_once "config.php";
include_once 'login.php';
$connection = mysqli_connect (DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
// Check connection
if (mysqli_connect_errno()) {
    printf ("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

//error_log($_POST["username"]." ".$_POST["hashedPassword"]);
//echo $_POST["username"];
//echo $_POST["hashedPassword"];
if (isset($_POST['username'], $_POST['hashedPassword'])) {
    $username = $_POST['username'];
    $password = $_POST['hashedPassword']; // The hashed password.
 
    if (login($username, $password, $connection) == true) {
        // Login success 
        header('Location: ../admin.html');
    } else {
        // Login failed 
        header('Location: ../index.html?error=1');
    }
} else {
    // The correct POST variables were not sent to this page. 
    echo 'Invalid Request';
}

?>