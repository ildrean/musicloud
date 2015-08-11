<?php
//outputs a json array of song info from song ID input
$ID=$_GET["ID"];

require_once('db_login.php');
$db = new PDO("mysql:dbname=".$db_database.";host=".$db_host, $db_username, $db_password);
	
$stmt = $db->prepare("SELECT realname, playtime, artist, album, genre, filepath FROM music WHERE songID = ?");	
$stmt->execute(array($ID));										
$fileinfo = $stmt->fetchAll(PDO::FETCH_ASSOC);

$filetype = end(explode('.', $fileinfo[0]['filepath']));

echo json_encode(array("ID" => $ID, "filepath" => $fileinfo[0]['filepath'], "filetype" => $filetype, "realname" => $fileinfo[0]['realname'], "playtime" => $fileinfo[0]['playtime'], "artist" => $fileinfo[0]['artist'], "album" => $fileinfo[0]['album'], "genre" => $fileinfo[0]['genre']));