<?php
require_once "phpLib/config.php";
require_once "phpLib/lib.php";

$title = $_REQUEST['title'];

if (!isset($title)) {
	die ("Scene name was not passed in");
}
$title = trim($title);

if (($connection = getConnection()) == false) {
	die ("Cannot retrieve connection to DB");
}

$sql = "SELECT * FROM scenes WHERE title='$title'";
$result = mysqli_query($connection, $sql, MYSQLI_USE_RESULT);
$info = array();
if($result) {
	$info = $result->fetch_assoc();
	mysqli_free_result($result);
} else {
	error_log ("Cannot find the information of this scene");
	die("Cannot find the information of this scene");
}
echo json_encode($info);
mysqli_close($connection);
exit();
?>