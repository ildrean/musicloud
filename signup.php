<?php
	require_once('db_login.php'); //File on the server containing login and database information

	$user = $_POST["user"];		$pass = $_POST["pass"];	$email = $_POST["email"];
	$db = new PDO("mysql:dbname=".$db_database.";host=".$db_host, $db_username, $db_password);
	//check if email is already taken	$stmt = $db->prepare("SELECT COUNT(*) FROM login WHERE email=?");	$stmt->execute(array($email));	$emailarray = $stmt->fetchAll();	if($emailarray['COUNT(*)'] != 0){		//return error message, ask if they want to recover this account		echo json_encode(array("result" => "error", "error" => "Email address is already in use. Click forgot password to recover your account."));		exit();	}
	//check if username is already taken	$stmt = $db->prepare("SELECT COUNT(*) FROM login WHERE user=?");	$stmt->execute(array($user));	$userarray = $stmt->fetchAll();	if($userarray['COUNT(*)'] != 0){		//return error message, ask them to use a different username		echo json_encode(array("result" => "error", "error" => "Username is already in use."));		exit();	}
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";    $salt = "";        for ($i = 0; $i < 22; $i++) {        $salt .= $characters[mt_rand(0, strlen($characters))];    }
	$hashpass = hash('whirlpool', $salt.$pass);
	$stmt = $db->prepare("INSERT INTO login (user, pass, salt, email) VALUES(?, ?, ?, ?)");	$stmt->execute(array($user, $hashpass, $salt, $email));
	echo json_encode(array("result" => "success", "success" => true));