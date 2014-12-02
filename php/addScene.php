<?php
/*
* Collect data from AJAX call from add.js file
* The data then will be inserted into the MySQL database
* After that, the script checks if the insert command was successfully executed and return "SUCCESS"
* Else it will kill the process and return error to Add.js
*/
require_once "phpLib/config.php";
require_once "phpLib/lib.php";

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

if (($connection = getConnection ()) === false) {
	die ("Cannot retrieve connection to DB");
}

if (($proj1 = retrieveExtension ($proj1Files)) != false) {
	$proj1Files = $proj1;
}
if (($proj2 = retrieveExtension ($proj2Files)) != false) {
	$proj2Files = $proj2;
}
if (($proj3 = retrieveExtension ($proj3Files)) != false) {
	$proj3Files = $proj3;
}
//error_log ("What's the matter the tile is: $title $desc $cover $proj1Num $proj1Files $proj2Num $proj2Files $proj3Num $proj3Files");
$sql = "INSERT INTO scenes (title, description, cover, proj1_num_imgs, proj1_files, proj2_num_imgs, proj2_files, proj3_num_imgs, proj3_files ,soundtrack) VALUES ('{$title}', '{$desc}', '{$cover}', {$proj1Num}, '{$proj1Files}', {$proj2Num}, '{$proj2Files}', {$proj3Num}, '{$proj3Files}' , '{$soundtrack}');";
if (mysqli_query($connection, $sql) !== true) {
	error_log ("Error: ".mysqli_connect_error());
	die("Error: ".mysqli_connect_error());
}
$path = "../".FILESYSTEM.$title;
//error_log ("Path is: $path");
if (!file_exists($path)) {
	@mkdir($path, 0700);
}
$myfile = fopen($path."/indicator.txt", "w") or die("Unable to open file!");
$txt = "Edited";
fwrite($myfile, $txt);
fclose($myfile);
mysqli_close($connection);
echo json_encode(array("jsonrpc"=>"2.0", "result"=>null, "error"=>'undefined'));
exit();

?>