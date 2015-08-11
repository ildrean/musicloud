<?php
function parseID3($filepath, $user){
	require_once('../getid3/getid3.php');
	
	//require_once('/home1/gsylvest/public_html/db_login.php'); //File containing login and database information
	require_once('db_login.php'); //File on the server containing login and database information 
	$connection = mysql_connect($db_host, $db_username, $db_password) or die(mysql_error());
	$db_select = mysql_select_db($db_database) or die(mysql_error()); 

	// Initialize getID3 engine
	$getID3 = new getID3;

	// Analyze file and store returned data in $ThisFileInfo
	$ThisFileInfo = $getID3->analyze($filepath);
	
	CopyTagsToComments($ThisFileInfo);
	
	//copy individual values to vars. see structure.txt for more values
	$tempArtist = implode(' & ', $ThisFileInfo['comments_html']['artist']); //more than one artist may be present, use implode
	$tempFileName = $ThisFileInfo['comments_html']['filename'][0];
	$tempPlayTime = $ThisFileInfo['comments_html']['playtime_string'][0];
	$tempAlbum = $ThisFileInfo['comments_html']['album'][0];
	$tempGenre = $ThisFileInfo['comments_html']['genre'][0];
	
	//add code for parsing realname, does filename have an extension?<-----------------------------------
	
	mysql_query("INSERT INTO music (user, filename, realname, playtime, artist, album, genre) VALUES('$user', '$tempFilename', '$tempFilename', '$tempPlaytime', '$tempArtist', '$tempAlbum', '$tempGenre') ") or die(mysql_error());
						
}
?>