<?php
//returns all playlists for a user 
$uID=(int)$_GET["uID"];

require_once('db_login.php');
$db = new PDO("mysql:dbname=".$db_database.";host=".$db_host, $db_username, $db_password);

$stmt = $db->prepare("SELECT pID, name FROM playlists WHERE userID = ?");	
$stmt->execute(array($uID));										
$playlistInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($playlistInfo);