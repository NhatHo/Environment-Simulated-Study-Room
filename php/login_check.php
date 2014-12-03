<?php
require_once "phpLib/config.php";
require_once "phpLib/lib.php";
$page = $_REQUEST["page"];
if (($connection = getConnection ()) == false) {
	die ("Cannot retrieve connection to DB");
}

$result = "false";
session_start();
if (isset($_SESSION['username'], $_SESSION['login_string'], $page)) {
	$login_string = $_SESSION['login_string'];
	$username = $_SESSION['username'];

	$user_browser = $_SERVER['HTTP_USER_AGENT'];

	if ($stmt = $connection->prepare("SELECT password FROM users WHERE username = ? LIMIT 1")) { 
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows == 1) {
			$stmt->bind_result($password);
			$stmt->fetch();
			$login_check = hash('sha512', $password . $user_browser);
			if ($login_check == $login_string) {
				if ($username == "student" && $page != "studentAllowed") {
					$result = "ignored";
				} else {
					$result = "true";
				}
			}
		}
	}
}
echo json_encode(array("result"=>$result));
?>