<?php
//creates new playlist from POSTed JSON
//$user = $_COOKIE['user'];
$user = "1";
$playlist = implode(',', $_POST["newplaylist"]);

$name = $_POST["name"];

require_once('db_login.php'); //File on the server containing login and database information
$db = new PDO("mysql:dbname=".$db_database.";host=".$db_host, $db_username, $db_password);

$stmt = $db->prepare("INSERT INTO playlists (userID, name, songIDs) VALUES(?,?,?)");	
$stmt->execute(array($user, $name, $playlist));

echo "Playlist ".$name." created.";