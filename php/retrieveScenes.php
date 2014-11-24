<?php
/*
* retrieveScenes.php script
* The scenes will be retrieve directly from the MySQL database
* only name, description and image name will be retrieved
* The information will then be used in client view, admin, edit and delete pages
*/
require_once "config.php";
require_once 'phpLib/OrganizeFolder.php';
$connection = mysqli_connect (DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
// Check connection
if (mysqli_connect_errno()) {
	printf ("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$sql = "SELECT name,description,path FROM scenes";
$result = mysqli_query($connection, $sql, MYSQLI_USE_RESULT);
$scenes = array();
if ($result) {
	while ($row = $result->fetch_assoc()) {
		$scenes[] = array("name"=>$row["name"], "description"=>$row["description"], "path"=>$row["path"]);
		//error_log ("The path is: ".$row["path"]);
		$organizer = new OrganizeFolder ($row["path"]);
		if (!$organizer->randomizeFiles()) {
			die ("Failed to randomize the folder".$row["path"]);
		} else if (!$organizer->reorganizeFiles()){
			die ("Failed to organize the folder".$row["path"]);
		}
	}
	mysqli_free_result($result);
} else {
	echo "Cannot find any data";
}
echo json_encode($scenes);
$connection->close();
unset($scenes);
exit();
?>