<?php
/*
* Collect data from AJAX call from add.js file
* The data then will be inserted into the MySQL database
* After that, the script checks if the insert command was successfully executed and return "SUCCESS"
* Else it will kill the process and return error to Add.js
*/
require_once "config.php";

$name = $_REQUEST['title'];
$desc = $_REQUEST['desc'];
$numImg = $_REQUEST['numImg'];
$audio = $_REQUEST['audio'];
//error_log("Audio is: ".$audio);
$name = rtrim($name, " ");
$path = "data/{$name}/";
$connection = mysqli_connect (DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
// Check connection
if (mysqli_connect_errno()) {
	printf ("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
//error_log ("What happen: $name, desc: $desc, Num: $numImg, path: $path");
$sql = "INSERT INTO scenes (name, description, numImages, path, soundtrack) VALUES ('$name', '$desc', $numImg, '$path', '$audio');";
if (mysqli_query($connection, $sql) != true) {
	 die("Error: ".mysqli_connect_error());
}
mysqli_close($connection);

exit();

?>