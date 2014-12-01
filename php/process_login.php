<?php
require_once "config.php";
include_once 'login.php';
$connection = mysqli_connect (DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if (mysqli_connect_errno()) {
    printf ("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

if (isset($_POST['username'], $_POST['hashedPassword'])) {
    $username = $_POST['username'];
    $password = $_POST['hashedPassword'];
 
    if (login($username, $password, $connection) == true) {
        header('Location: ../admin.html');
    } else {
        header('Location: ../index.html?error=1');
    }
} else {
    echo 'Invalid Request';
}

?>