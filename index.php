<?php	require_once('lib/limonade.php');	function configure(){		option('env',ENV_DEVLOPMENT);	}
	dispatch('/', 'logged_out');	dispatch('/user/:username', 'logged_in');
	function logged_out(){		include('testcookies.php');		set('page_title', "musicloud");		return html('', 'logged_out.php');	}	function logged_in(){		$user = params('username');		include('testcookies.php');		set('page_title', $user."'s music cloud");		return html('', 'user.php');	}
	run();