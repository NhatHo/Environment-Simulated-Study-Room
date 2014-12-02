<?php
require_once "phpLib/config.php";
require_once "phpLib/lib.php";
$title = $_REQUEST["title"];
//error_log("The selected Scene is: ".$title);

if (($connection = getConnection ()) == false) {
	die ("Cannot retrieve connection to DB");
}

$sql = "SELECT * FROM scenes WHERE title='$title'";
$result = mysqli_query($connection, $sql, MYSQLI_USE_RESULT);
if ($result) {
	$row=$result->fetch_assoc();
	//error_log ("Retrieve: ".$row["title"]);
	mysqli_free_result($result);
} else {
	//error_log ("Cannot find any data with $title");
	die ("Cannot retrieve data with $title");
}
echo json_encode(array("success"=>"$title scene will be played shortly"));
$connection->close();
unset($scenes);
exit();
?>