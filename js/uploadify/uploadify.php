<?php
require_once('../../db_login.php'); //File containing login and database information
require_once('../../getid3/getid3/getid3.php');
//$user_cookie = $_POST['user_cookie'];
$user_cookie = "tester";
$db = new PDO("mysql:dbname=".$db_database.";host=".$db_host, $db_username, $db_password);
	
if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	
	//$targetPath = realpath($_SERVER['DOCUMENT_ROOT'].'/../uploads/'); 				//gsylvester.com root uploads
	//$targetFile = str_replace('//','/',$targetPath).'/'.$_FILES['Filedata']['name'];
	//$targetPath = realpath($_SERVER['DOCUMENT_ROOT'].'/musicloud/'.'/uploads/'); 		//localhost reg uploads
	//$targetFile = $targetPath."\\".$_FILES['Filedata']['name'];
	
	$targetPath = realpath($_SERVER['DOCUMENT_ROOT'].'/uploads/');					//gsylvester.com reg uploads
	$targetFile = str_replace('//','/',$targetPath).'/'.$_FILES['Filedata']['name'];
	
	$filePath = '/uploads/'.$_FILES['Filedata']['name'];	//used for jPlayer
	
	
	if(!is_dir($targetPath)){
		mkdir(str_replace('//','/',$targetPath), 0777, true);
	}

	rename($tempFile, $targetFile);
	chmod($targetFile, 0777);
		
	// Initialize getID3 engine
	$getID3 = new getID3;
	
	// Analyze file and store returned data in $ThisFileInfo
	$ThisFileInfo = $getID3->analyze($targetFile);
	getid3_lib::CopyTagsToComments($ThisFileInfo);
	
	//copy individual values to vars. see structure.txt for more values
	$tempArtist = implode(' & ', $ThisFileInfo['comments_html']['artist']); //more than one artist may be present, use implode
	$tempFileName = $ThisFileInfo['comments_html']['title'][0];
	$tempPlayTime = $ThisFileInfo['playtime_string'];
	$tempAlbum = $ThisFileInfo['comments_html']['album'][0];
	$tempGenre = $ThisFileInfo['comments_html']['genre'][0];
	
	$md5hash = md5_file($targetFile);
	
	$stmt = $db->prepare("INSERT INTO music (md5, realname, playtime, artist, album, genre, filepath) VALUES(?, ?, ?, ?, ?, ?, ?) ");
	$stmt->execute(array($md5hash, $tempFileName, $tempPlayTime, $tempArtist, $tempAlbum, $tempGenre, $filePath));
	
	$stmt = $db->prepare("INSERT INTO files (user, md5) VALUES(?, ?)");
	$stmt->execute(array($user_cookie, $md5hash));
	
		
        echo "1";
}