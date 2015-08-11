<?php
//outputs a json array of song info from playlist ID
$pID=(int)$_GET["pID"];
$userID = 1;//test

require_once('db_login.php');
$db = new PDO("mysql:dbname=".$db_database.";host=".$db_host, $db_username, $db_password);

if($pID != 0){	
	$stmt = $db->prepare("SELECT songIDs FROM playlists WHERE pID = ?");	
	$stmt->execute(array($pID));										
	$songIDs = $stmt->fetch();
	$IDArray = explode(",", $songIDs[0]);
}else{
	$stmt = $db->prepare("SELECT songID FROM files WHERE user = ?");	
	$stmt->execute(array($userID));									
	$IDArray = $stmt->fetchAll(PDO::FETCH_NUM);
}

foreach($IDArray as $i => $v) {
	$stmt = $db->prepare("SELECT realname, playtime, artist, album, genre FROM music WHERE songID = ?");	
	$stmt->execute(array($v[0]));
	$tempsonginfo = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$songinfo[$i]['ID'] = $v[0];
	$songinfo[$i]['realname'] = $tempsonginfo[0]['realname'];
	$songinfo[$i]['playtime'] = $tempsonginfo[0]['playtime'];
	$songinfo[$i]['artist'] = $tempsonginfo[0]['artist'];
	$songinfo[$i]['album'] = $tempsonginfo[0]['album'];
	$songinfo[$i]['genre'] = $tempsonginfo[0]['genre'];
}

echo json_encode($songinfo);