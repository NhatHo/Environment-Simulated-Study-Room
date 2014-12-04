<!DOCTYPE html>
<html>

<?php

/*
 * To test: Create a new table named "httptest" in "environment_room" database with row "requestNumber (int)"
 *			Fill out the form on the page and submit.
 *
 *
 *
 */

	require_once "../config.php";
	include_once "../StreamConductor/RemoteControl.php";

	$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

	if ($mysqli->connect_errno) {
	    printf ("Connect failed: %s\n", $mysqli->connect_error);
	    exit();
	}

	if (isset($_POST["function"]))
	{
		if ($_POST["function"] == "1") //Power
		{
			$query = $mysqli->query("	UPDATE httptest 
										SET requestNumber = 1");
			//Power down projectors
		}
		else if ($_POST["function"] == "2") //Stop
		{
			$query = $mysqli->query("	UPDATE httptest 
										SET requestNumber = 2");
			$vlc_remote = new RemoteControl(AUDIOPI, 3010);
			$vlc_remote->stop();
		}
		else if ($_POST["function"] == "3") //Pause
		{
			$query = $mysqli->query("	UPDATE httptest 
										SET requestNumber = 3");
			$vlc_remote = new RemoteControl(AUDIOPI, 3010);
			$vlc_remote->pause();	
		}
		else if ($_POST["function"] == "4") //Play
		{
			$query = $mysqli->query("	UPDATE httptest 
										SET requestNumber = 4");
			$vlc_remote = new RemoteControl(AUDIOPI, 3010);
			$vlc_remote->play();
		}
		else if ($_POST["function"] == "5") //Rewind
		{
			$query = $mysqli->query("	UPDATE httptest 
										SET requestNumber = 5");
			$vlc_remote = new RemoteControl(AUDIOPI, 3010);
			$vlc_remote->decreaseVol();
		}
		else if ($_POST["function"] == "6") //Forward
		{
			$query = $mysqli->query("	UPDATE httptest 
										SET requestNumber = 6");
			$vlc_remote = new RemoteControl(AUDIOPI, 3010);
			$vlc_remote->increaseVol();
		}
		header("X-PHP-Response-Code: 200", true, 200);
	}

	$query = $mysqli->query("	SELECT requestNumber
								FROM httptest");
?>

<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="HTTP Request Test">
    <meta name="author" content="">

	<title>HTTP Request Test</title>
</head>
<body>
	<h1>The latest HTTP request number is: <?php echo $query->fetch_object()->requestNumber; ?></h1>
	<form name="test" action="remote_control_api.php" method="POST">
		<select name="function">
			<option value="1">Power</option>
			<option value="2">Play</option>
			<option value="3">Pause</option>
			<option value="4">Stop</option>
			<option value="5">Rewind</option>
			<option value="6">Forward</option>
		</select>
		<button name="submit" value="submit" type="submit">Submit</button>
	</form>
</body>
</html>