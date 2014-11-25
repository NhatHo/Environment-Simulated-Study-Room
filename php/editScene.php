<?php
require_once "config.php";
require_once 'OrganizeFolder.php';

if (!isset($_REQUEST['title'])) {
	die ("Cannot find the data of this scene on file system");
}
$name = $_REQUEST['title'];
$desc = $_REQUEST['desc'];
$numImg = $_REQUEST['numImg'];
$images = $_REQUEST['images'];
$audio = $_REQUEST['audio'];
//error_log ("Name is: {$name} Description: {$desc} Num of Images: {$numImg} Images are: {$images} Audio is: {$audio}", 0);

$selectedItems = array();
$selectedItems[] = trim($audio);
$unselectedItems = array();
$images = trim($images);
$images = explode (".jpg", $images);
foreach ($images as $key=>$image) {
	$image = trim($image);
	if (strlen($image) > 0) {
		$selectedItems[] = $image.".jpg";
	}
}
$connection = mysqli_connect (DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
// Check connection
if (mysqli_connect_errno()) {
	printf ("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
$sql = "UPDATE scenes SET description='$desc', numImages=$numImg, soundtrack='$audio' WHERE name='$name'";
if (mysqli_query($connection, $sql)) {
	$path = "../".FILESYSTEM.$name."/";
	//error_log ("The path is: $path");
	if (is_dir($path)) {
		if ($handle = opendir($path)) {
			while (false !== ($fileName = readdir($handle))) {
				if ($fileName != "." && $fileName != "..") {
					//error_log("Checking file: $fileName");
					$foundFlag = false;
					foreach ($selectedItems as $key=>$item) {
						if ($fileName == $item) {
							$foundFlag = true;
							break;
						}
					}
					if ($foundFlag == false) {
						//error_log("Unfounded One was: $fileName");
						$unselectedItems[] = $fileName;
					}
				}
			}
			closedir($handle);
			foreach ($unselectedItems as $key=>$file) {
				if (file_exists($path.$file)) {
					//error_log("Deleting file: ".$path.$file);
					unlink($path.$file);
				}
			}
		} else {
			error_log ("Cannot open the directory: $path");
		}
	} else {
		error_log("cannot find this Directory: $path");
	}

} else {
	die("Cannot update the data of scene on database");
}
$organizer = new OrganizeFolder (FILESYSTEM.$name);
if (!$organizer->randomizeFiles()) {
	die ("Failed to randomize the folder".$row["path"]);
} else if (!$organizer->reorganizeFiles()){
	die ("Failed to organize the folder".$row["path"]);
}
mysqli_close($connection);

exit();

?>