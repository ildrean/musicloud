<?php
//returns a json encoded array of all songinfo for a given user
//$user = $_COOKIE['user'];
$user = "tester";

require_once('db_login.php'); //File on the server containing login and database information
$db = new PDO("mysql:dbname=".$db_database.";host=".$db_host, $db_username, $db_password);

$stmt = $db->prepare("SELECT userID FROM login WHERE user = ?");	//possibly change incoming data (cookie, session, etc) so it is userID instead of user
$stmt->execute(array($user));										//so this block would be unused
$userID = $stmt->fetch();

$stmt = $db->prepare("SELECT songID FROM files WHERE user = ?");	
$stmt->execute(array($userID[0]));									
$IDArray = $stmt->fetchAll(PDO::FETCH_NUM);

$j=0;
for($i=0;$i<count($IDArray);$i++){
	
	$stmt = $db->prepare("SELECT realname, playtime, artist, album, genre FROM music WHERE songID = ?");	
	$stmt->execute(array($IDArray[$i][0]));																	
	$songDataArray = $stmt->fetchAll(PDO::FETCH_ASSOC);																	

	$songinfo[$i]['ID'] = $IDArray[$i][0];
	$songinfo[$i]['realname'] = $songDataArray[0]['realname'];
	$songinfo[$i]['playtime'] = $songDataArray[0]['playtime'];
	$songinfo[$i]['artist'] = $songDataArray[0]['artist'];
	$songinfo[$i]['album'] = $songDataArray[0]['album'];
	$songinfo[$i]['genre'] = $songDataArray[0]['genre'];

}	

echo json_encode($songinfo);