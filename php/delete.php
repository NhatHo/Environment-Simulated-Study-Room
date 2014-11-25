<?php
require_once 'config.php';
$checkboxValue = $_REQUEST["scenes"];

if(!isset($checkboxValue)) {
	die ("No scene was SELECTED");
}
$connection = mysqli_connect (DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
// Check connection
if (mysqli_connect_errno()) {
	printf ("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
function removeFolder ($dir) {
	$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
	$files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
	foreach($files as $file) {
		if ($file->getFilename() === '.' || $file->getFilename() === '..') {
			continue;
		}
		if ($file->isDir()){
			rmdir($file->getRealPath());
		} else {
			unlink($file->getRealPath());
		}
	}
	rmdir($dir);
	return true;
}
foreach ($checkboxValue as $key=>$name) {
	if (strlen(trim($name)) > 0) {
		$sql = "SELECT * FROM scenes WHERE name='$name'";
		$result = mysqli_query ($connection, $sql);

		if ($result->num_rows == 1) {
			$row = $result->fetch_assoc();
			$dir = "../".$row["path"];
			if(!removeFolder ($dir)) {
				die("Cannot remove the directory and its files");
			}
			$removeSql = "DELETE FROM scenes WHERE name='$name'";
			if (mysqli_query ($connection, $removeSql) != true) {
				die ("Cannot remove the row from database");
			}
			mysqli_free_result($result);
		} else {
			die("Cannot find this scene in Database");
		}
	} else {
		die ("Scene name is empty");
	}
}
mysqli_close($connection);
exit();
?>