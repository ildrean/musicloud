<?php
	if(isset($_COOKIE['user']) && isset($_COOKIE['pass'])){
		$cookieVerify = mysql_query("SELECT user,pass FROM login WHERE user='$userCookie'") or die(mysql_error());
		if($cookieArray['pass'] == $passCookie){