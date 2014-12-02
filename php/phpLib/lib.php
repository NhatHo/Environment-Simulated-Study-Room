<?php
require_once 'config.php';

function retrieveExtension ($fileString) {
	$extension = explode(".", $fileString);
	$validExtension = trim(end($extension));
	if (strpos(getPicExtensions(), $validExtension) !== false){
		return $validExtension;
	} else {
		return false;
	}
}
function getConnection () {
	$connection = mysqli_connect (DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	// Check connection
	if (mysqli_connect_errno()) {
		printf ("Connect failed: %s\n", mysqli_connect_error());
		return false;
	}
	return $connection;
}
?>