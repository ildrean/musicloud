<?php
	$user = $_POST["user"];
	$db = new PDO("mysql:dbname=".$db_database.";host=".$db_host, $db_username, $db_password);
	$loginarray = $stmt->fetch();
	$hashpass = hash('whirlpool', $loginarray["salt"].$pass);
		echo json_encode(array("result" => "success", "success" => true));