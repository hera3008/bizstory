<?
	$session_time = 60 * 60 * 6; // 6hours
	ini_set("session.cache_expire", $session_time);
	ini_set("session.gc_maxlifetime", $session_time);

	session_start();

	header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
	header("Content-type: text/html; charset=UTF-8");
	header("Pragma: no-cache");
	header("Cache-Control: no-cache, must-revalidate");

	foreach ($_COOKIE as $key => $value)
	{
		unset($_COOKIE[$key]);
	}

	foreach ($_SESSION as $key => $value)
	{
		unset($_SESSION[$key]);
	}

	$str = '{"success_chk" : "Y", "auto_value" : ""}';
	echo $str;
	exit;
?>