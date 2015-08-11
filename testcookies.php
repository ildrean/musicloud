<?php
	if(isset($_COOKIE['user']) && isset($_COOKIE['pass'])){		$userCookie = $_COOKIE['user'];		$passCookie = $_COOKIE['pass'];
		$cookieVerify = mysql_query("SELECT user,pass FROM login WHERE user='$userCookie'") or die(mysql_error());		$cookieArray = mysql_fetch_array($cookieVerify);
		if($cookieArray['pass'] == $passCookie){			session_start();			$_SESSION['user'] = $userCookie;			$_SESSION['pass'] = $passCookie;			header("location: user.php");		}	}