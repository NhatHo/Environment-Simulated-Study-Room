<?php
require "config.php";
$name = $_POST['title'];
$desc = $_POST['desc'];
if (isset($_POST['pictureFile'])) {
	$image = $_POST['pictureFile'];
} else {
	$image = "";
}
if (isset($_POST['audioURL'])) {
	$audioURL = $_POST['audioURL'];
} else {
	$audioURL = "";
}
if (isset($_POST['videoUrl'])) {
	$video = $_POST['videoUrl'];
} else {
	$video = "";
}

$connection = mysqli_connect (DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
// Check connection
if (mysqli_connect_errno()) {
	printf ("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$sql = "UPDATE scenes SET name='$name', description='$desc', image='$image', audio='$audioURL', video='$video'";

if (mysqli_query($connection, $sql) !== true) {
	 die("Error: ".mysqli_error());
}
echo "SUCCESS";
mysqli_close($connection);
exit();

?>