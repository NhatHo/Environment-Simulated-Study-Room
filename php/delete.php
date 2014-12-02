<?php
require_once "phpLib/config.php";
require_once "phpLib/lib.php";
$checkboxValue = $_REQUEST["scenes"];

if(!isset($checkboxValue)) {
	die ("No scene was SELECTED");
}
if (($connection = getConnection ()) == false) {
	die ("Cannot retrieve connection to DB");
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
foreach ($checkboxValue as $key=>$title) {
	if (strlen(trim($title)) > 0) {
		$removeSql = "DELETE FROM scenes WHERE title='$title'";
		if (mysqli_query ($connection, $removeSql) != true) {
			die ("Cannot remove the row from database");
		}
		$dir = "../".FILESYSTEM.$title;
		if(!removeFolder ($dir)) {
			die("Cannot remove the directory and its files");
		}
	} else {
		die ("Scene title is empty");
	}
}
mysqli_close($connection);
exit();
?>