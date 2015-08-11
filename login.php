<?php	require_once('db_login.php'); //File containing login and database information
	$user = $_POST["user"];	$pass = $_POST["pass"];
	$db = new PDO("mysql:dbname=".$db_database.";host=".$db_host, $db_username, $db_password);	$stmt = $db->prepare("SELECT user,pass,salt FROM login WHERE user=?");		$stmt->execute(array($user));
	$loginarray = $stmt->fetch();
	$hashpass = hash('whirlpool', $loginarray["salt"].$pass);	if($loginarray["pass"] == $hashpass){		session_start();		$_SESSION['user'] = $user;		$_SESSION['pass'] = $hashpass;		setcookie("user", $user, time()+2592000000, '/', 'localhost', false, false);		setcookie("pass", $hashpass, time()+2592000000, '/', 'localhost', false, false);
		echo json_encode(array("result" => "success", "success" => true));	}else{		echo json_encode(array("result" => "error", "error" => "Invalid username or password."));	}