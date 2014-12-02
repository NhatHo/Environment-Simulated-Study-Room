<?php
require_once 'phpLib/config.php';
require_once 'phpLib/lib.php';
include_once 'login.php';
if (($connection = getConnection ()) == false) {
	die ("Cannot retrieve connection to DB");
}


if (isset($_POST['username'], $_POST['hashedPassword'])) {
    $username = $_POST['username'];
    $password = $_POST['hashedPassword'];
 
    if (login($username, $password, $connection) == true) {
		if ($username == "student") {
			header('Location: ../client.html');
		} else if ($username == "admin") {
			header('Location: ../admin.html');
		}
    } else {
        header('Location: ../index.html?error=1');
    }
} else {
    echo 'Invalid Request';
}

?>