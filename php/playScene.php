<?php
require_once "phpLib/config.php";
require_once "phpLib/lib.php";
require_once "../StreamConductor/SceneConductor.php";
require_once "../StreamConductor/RemoteControl.php";
require_once "../StreamConductor/SlideshowStream.php";
require_once "../StreamConductor/VideoTranscoder.php";
require_once "../StreamConductor/RTPStream.php";
require_once "../StreamConductor/AudioStream.php";

$title = $_REQUEST["title"];
error_log("The selected Scene is: ".$title);

if (($connection = getConnection ()) == false) {
	die ("Cannot retrieve connection to DB");
}

$sql = "SELECT * FROM scenes WHERE title='$title'";
$result = mysqli_query($connection, $sql, MYSQLI_USE_RESULT);
if ($result) {
	$row=$result->fetch_assoc();
	error_log ("Retrieve: ".$row["title"]);
	$audioURL = ABSOLUTEPATH.$row["title"]."/".$row['soundtrack'];
	error_log ("Audio is: $audioURL");
	$videoURL = ABSOLUTEPATH.$row["title"]."/2/".$row['proj2_files'];
	error_log ("Video is: $videoURL");
	error_log ("Projector 2 is: ".PROJECTOR2);
	error_log ("Port is: ".PORT);
	$sceneDesc = array(
		'audio' => array(AUDIOPI, PORT, trim($audioURL)),
		'video' => array(
		    array(PROJECTOR2, PORT, 'video', trim($videoURL))
		)
	);
	mysqli_free_result($result);
	$conductor = new SceneConductor($sceneDesc);
	sleep(1);
	$conductor->play();
	error_log ("Scene has been played");
	sleep(120);
	$conductor->stop();
} else {
	//error_log ("Cannot find any data with $title");
	die ("Cannot retrieve data with $title");
}
echo json_encode(array("success"=>"$title scene will be played shortly"));
$connection->close();
?>
