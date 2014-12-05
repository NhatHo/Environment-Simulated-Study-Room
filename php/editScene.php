<?php
require_once "phpLib/config.php";
require_once "phpLib/lib.php";
require_once 'organizeFolder.php';

if (!isset($_REQUEST['title'])) {
	die ("Cannot find the data of this scene on file system");
}
$title = $_REQUEST['title'];
$desc = mysql_real_escape_string($_REQUEST['desc']);
$cover = mysql_real_escape_string($_REQUEST['cover']);
$proj1Num = $_REQUEST['proj1Num'];
$proj1Files = mysql_real_escape_string($_REQUEST['proj1Files']);
$proj2Num = $_REQUEST['proj2Num'];
$proj2Files = mysql_real_escape_string($_REQUEST['proj2Files']);
$proj3Num = $_REQUEST['proj3Num'];
$proj3Files = mysql_real_escape_string($_REQUEST['proj3Files']);
$soundtrack = mysql_real_escape_string($_REQUEST['soundtrack']);

$path = "../".FILESYSTEM.$title;
$mainFolder = $cover." ".$soundtrack." indicator.txt"; // indicator is needed to make sure that folder won't be organized multiple times

if (($connection = getConnection ()) == false) {
	die ("Cannot retrieve connection to DB");
}

function cleanupFolder ($path, $selectedFiles) {
	$selectedFiles = trim($selectedFiles);
	//error_log ("The path is: $path");
	//error_log ("The selectedFiles is: $selectedFiles");
	$unselectedItems = array();
	if (is_dir($path)) {
		if ($handle = opendir($path)) {
			while (false !== ($fileName = readdir($handle))) {
				if ($fileName != "." && $fileName != ".." && is_dir($path.$fileName) === false) {
					//error_log("Checking file: $fileName");
					if (strpos($selectedFiles, $fileName) === false) {
						//error_log("File is not in the list: $fileName");
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
			//error_log ("Cannot open the directory: $path");
			return false;
		}
	} else {
		//error_log("cannot find this Directory: $path");
		return false;
	}
	return true;
}
cleanupFolder ("$path/", $mainFolder);	
cleanupFolder ("$path/1/", $proj1Files);	
cleanupFolder ("$path/2/", $proj2Files);	
cleanupFolder ("$path/3/", $proj3Files);
if (($proj1 = retrieveExtension ($proj1Files)) != false) {
	$proj1Files = $proj1;
}
if (($proj2 = retrieveExtension ($proj2Files)) != false) {
	$proj2Files = $proj2;
}
if (($proj3 = retrieveExtension ($proj3Files)) != false) {
	$proj3Files = $proj3;
}
$sql = "UPDATE scenes SET description='$desc', cover='$cover', proj1_num_imgs=$proj1Num, proj1_files='$proj1Files', proj2_num_imgs=$proj2Num, proj2_files='$proj2Files', proj3_num_imgs=$proj3Num, proj3_files='$proj3Files', soundtrack='$soundtrack' WHERE title='$title'";
if (mysqli_query($connection, $sql) != true) {
	error_log ("Failed to update database");
	die ("Failed to update database with new information");
}
mysqli_close($connection);
$indicator = "$path/indicator.txt";
$myfile = fopen($indicator, "w") or die("Unable to open file!");
$txt = "Edited";
fwrite($myfile, $txt);
fclose($myfile);
echo json_encode(array("jsonrpc"=>"2.0", "result"=>null, "error"=>'undefined'));
exit();

?>
