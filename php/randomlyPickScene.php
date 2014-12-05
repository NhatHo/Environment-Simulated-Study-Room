<?php
require_once "phpLib/config.php";
require_once "phpLib/lib.php";
require_once "../StreamConductor/SceneConductor.php";
require_once "../StreamConductor/RemoteControl.php";
require_once "../StreamConductor/SlideshowStream.php";
require_once "../StreamConductor/VideoTranscoder.php";
require_once "../StreamConductor/RTPStream.php";
require_once "../StreamConductor/AudioStream.php";
function playRandom () {
if (($connection = getConnection ()) == false) {
	die ("Cannot retrieve connection to DB");
}

$sql = "SELECT * FROM scenes WHERE 1";
$result = mysqli_query($connection, $sql, MYSQLI_USE_RESULT);
$availableScenes = array();
if ($result) {
	while (($row = $result->fetch_assoc()) !== null) {
		$scenes[] = $row;
	}
	mysqli_free_result($result);
	error_log ("Number of scene is: ".count($scenes));
	$random = rand(0, count($scenes)-1);
	error_log ("Random number is: ".$random);
	error_log ("Retrieve: ".$scenes[$random]["title"]);
        $audioURL = ABSOLUTEPATH.$scenes[$random]["title"]."/".$scenes[$random]['soundtrack'];
        error_log ("Audio is: $audioURL");
        $videoURL = ABSOLUTEPATH.$scenes[$random]["title"]."/2/".$scenes[$random]['proj1_files'];
        //error_log ("Video is: $videoURL");
        error_log ("Projector 2 is: ".PROJECTOR2);
        error_log ("Port is: ".PORT);
        $sceneDesc = array(
 	            'audio' => array(AUDIOPI, PORT, trim($audioURL)),
                    'video' => array(
                         array(PROJECTOR2, PORT, 'video', trim($videoURL))
                 )
        );
        $conductor = new SceneConductor($sceneDesc);
        sleep(1);
        $conductor->play();
        error_log ("Scene has been played");
        sleep(120);
        $conductor->stop();
        error_log ("Scene has been stopped");
} else {
         //error_log ("Cannot find any data with $title");
         die ("Cannot retrieve data with $title");
}
$connection->close();
}
?>
