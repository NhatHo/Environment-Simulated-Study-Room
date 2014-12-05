<?php
	require_once "phpLib/config.php";
  	require_once "phpLib/lib.php";
  	require_once "../StreamConductor/SceneConductor.php";
  	require_once "../StreamConductor/RemoteControl.php";
  	require_once "../StreamConductor/SlideshowStream.php";
  	require_once "../StreamConductor/VideoTranscoder.php";
  	require_once "../StreamConductor/RTPStream.php";
	require_once "../StreamConductor/AudioStream.php";
	require_once "randomlyPickScene.php";

	if (isset($_POST["function"]))
	{
		header("X-PHP-Response-Code: 200", true, 200);
		if ($_POST["function"] == "1") //Power
		{
			//Power down projectors
		}
		else if ($_POST["function"] == "2") //Stop
		{
			error_log("STOPPPPP");
			$vlc_remote = new RemoteControl(AUDIOPI, PORT);
			$vlc_remote->stop();
			$vlc_remote = new RemoteControl(PROJECTOR2, PORT);
			$vlc_remote->stop();

		}
		else if ($_POST["function"] == "3") //Pause
		{
			$vlc_remote = new RemoteControl(AUDIOPI, PORT);
			$vlc_remote->pause();	
		}
		else if ($_POST["function"] == "4") //Play
		{
			error_log ("Inside PLAY FUNCTION");
			playRandom();
		}
		else if ($_POST["function"] == "5") //Rewind
		{
			error_log("REWINDDD");
			$vlc_remote = new RemoteControl(AUDIOPI, PORT);
			$vlc_remote->decreaseVol();
		}
		else if ($_POST["function"] == "6") //Forward
		{
			error_log("FORWARD");
			$vlc_remote = new RemoteControl(AUDIOPI, PORT);
			$vlc_remote->increaseVol();
		}
	}
	else
	{
		header("X-PHP-Response-Code: 404", true, 404);
	}
?>
