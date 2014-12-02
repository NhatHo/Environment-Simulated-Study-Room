<?php
/*
* retrieveScenes.php script
* The scenes will be retrieve directly from the MySQL database
* only name, description and image name will be retrieved
* The information will then be used in client view, admin, edit and delete pages
*/
require_once 'phpLib/config.php';
require_once 'phpLib/lib.php';
require_once 'organizeFolder.php';
if (($connection = getConnection ()) == false) {
	die ("Cannot retrieve connection to DB");
}
$sql = "SELECT * FROM scenes";
$result = mysqli_query($connection, $sql, MYSQLI_USE_RESULT);
$scenes = array();
if ($result) {
	while ($row = $result->fetch_assoc()) {
		$scenes[] = array("title"=>$row["title"], "description"=>$row["description"], "coverImage"=>"data/".$row["title"]."/".$row["cover"]);
		$indicator = "../".FILESYSTEM.$row["title"]."/indicator.txt";
		$file = file_get_contents($indicator, FILE_USE_INCLUDE_PATH);
		if ($file == "Edited") {
			organizeScene ($row["title"], $row["proj1_files"], $row["proj2_files"], $row["proj3_files"]);
			$myfile = fopen($indicator, "w") or die("Unable to open file!");
			$txt = "Organized";
			fwrite($myfile, $txt);
			fclose($myfile);
		}
		//error_log ("The path is: ".$row["path"]);
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