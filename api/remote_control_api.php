<?php
	require_once "../php/config.php";
	include_once "../StreamConductor/RemoteControl.php";

	if (isset($_POST["function"]))
	{
		if ($_POST["function"] == "1") //Power
		{
			//Power down projectors
		}
		else if ($_POST["function"] == "2") //Stop
		{
			$vlc_remote = new RemoteControl(AUDIOPI, 3010);
			$vlc_remote->stop();
		}
		else if ($_POST["function"] == "3") //Pause
		{
			$vlc_remote = new RemoteControl(AUDIOPI, 3010);
			$vlc_remote->pause();	
		}
		else if ($_POST["function"] == "4") //Play
		{
			$vlc_remote = new RemoteControl(AUDIOPI, 3010);
			$vlc_remote->play();
		}
		else if ($_POST["function"] == "5") //Rewind
		{
			$vlc_remote = new RemoteControl(AUDIOPI, 3010);
			$vlc_remote->decreaseVol();
		}
		else if ($_POST["function"] == "6") //Forward
		{
			$vlc_remote = new RemoteControl(AUDIOPI, 3010);
			$vlc_remote->increaseVol();
		}
		header("X-PHP-Response-Code: 200", true, 200);
	}
	else
	{
		header("X-PHP-Response-Code: 404", true, 404);
	}
?>