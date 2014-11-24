<?php
require_once 'config.php';
$sceneName = $_POST['name'];

if (!isset($sceneName)) {
	die ("Scene name was not passed in");
}
$connection = mysqli_connect (DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
// Check connection
if (mysqli_connect_errno()) {
	printf ("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$sql = "SELECT * FROM scenes WHERE name='$sceneName'";
$result = mysqli_query($connection, $sql);
$info = array();
if($result->num_rows == 1) {
	$row = $result->fetch_assoc();
	$info[] = $row;
	mysqli_free_result($result);
} else {
	die("Cannot find the information of this scene");
}
mysqli_close($connection);
echo json_encode($info);
unset($info);
exit();
?>