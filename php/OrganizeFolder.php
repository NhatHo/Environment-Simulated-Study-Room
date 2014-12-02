<?php
require_once "phpLib/config.php";
require_once "phpLib/lib.php";
function organizeScene ($title, $proj1_files, $proj2_files, $proj3_files) {
	$path = "../".FILESYSTEM.$title;
	if (isPicture ($proj1_files)) {
		randomizeFiles ($path."/1");
	}
	if (isPicture ($proj2_files)) {
		randomizeFiles ($path."/2");
	}
	if (isPicture ($proj3_files)) {
		randomizeFiles ($path."/3");
	}
}
function isPicture ($fileName) {
	if (strpos(PICTURES, $fileName) !== false){
		return true;
	} else {
		return false;
	}
}
function randomizeFiles ($path) {
	if (is_dir($path)) {
		if ($handle = opendir($path)) {
			/*
			* To organize the data ... first off ... need to rename all files in folder to new names
			*/
			$differentNameGenerator = 1;
			while (false !== ($fileName = readdir($handle))) {
				if ($fileName != ".." && $fileName != ".") { // avoid top files in folders
					//error_log ("File is: ".$fileName);
					$newName = $differentNameGenerator.$fileName;
					rename("$path/$fileName", "$path/$newName");
					$differentNameGenerator ++;
				}
			}
			closedir($handle);
			if(reorganizeFiles ($path) == true)
				return true;
			else
				return false;
		}
	}
	error_log("Can't find $path directory in randomizeFiles");
	return false;		
}
function reorganizeFiles ($path) {
	if (is_dir($path)) {
		if ($handle = opendir($path)) {
			// Then rename them again in order of image1, image2, image3, etc
			$counter = 1;
			while (false !== ($fileName = readdir($handle))) {
				if ($fileName != ".." && $fileName != ".") {// avoid top files in folders
					$extension = pathinfo($fileName, PATHINFO_EXTENSION);
					$newName = "image{$counter}.{$extension}";
					rename("$path/$fileName", "$path/$newName");
					$counter ++;
				}
			}
			closedir($handle);
			return true;
		}
	}
	error_log("Can't find $tpath directory in reorganizeFiles");
	return false;	
}
?>