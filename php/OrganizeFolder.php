<?php
class OrganizeFolder {
	var $path;
	function OrganizeFolder ($path) {
		$this->path = "../".$path; // because this file is in PHPLIB folder, it need to escape 2 layers
	}
	function randomizeFiles () {
		if (is_dir($this->path)) {
			if ($handle = opendir($this->path)) {
				/*
				* To organize the data ... first off ... need to rename all files in folder to new names
				*/
				$differentNameGenerator = 1;
				while (false !== ($fileName = readdir($handle))) {
					$extractData = explode (".", $fileName);
					if (end($extractData) == "jpg") { // avoid audio or video files
						$newName = $differentNameGenerator.$fileName;
						rename("$this->path/$fileName", "$this->path/$newName");
						$differentNameGenerator ++;
					}
				}
				closedir($handle);
				return true;
			}
		}
		error_log("Can't find $this->path directory");
		return false;		
	}
	function reorganizeFiles () {
		if (is_dir($this->path)) {
			if ($handle = opendir($this->path)) {
				// Then rename them again in order of image1, image2, image3, etc
				$counter = 1;
				while (false !== ($randomizedFile = readdir($handle))) {
					//error_log ("Second round filename: $randomizedFile");
					$extractData = explode (".", $randomizedFile);
					if (end($extractData) == "jpg") {// avoid audio or video files
						$newName = "image{$counter}.jpg";
						rename("$this->path/$randomizedFile", "$this->path/$newName");
						$counter ++;
					}
				}
				closedir($handle);
				return true;
			}
		}
	}
}
?>