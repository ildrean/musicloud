<?php
	require_once('db_login.php'); //File on the server containing login and database information

	$user = $_POST["user"];	
	$db = new PDO("mysql:dbname=".$db_database.";host=".$db_host, $db_username, $db_password);
	//check if email is already taken
	//check if username is already taken
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$hashpass = hash('whirlpool', $salt.$pass);
	$stmt = $db->prepare("INSERT INTO login (user, pass, salt, email) VALUES(?, ?, ?, ?)");
	echo json_encode(array("result" => "success", "success" => true));