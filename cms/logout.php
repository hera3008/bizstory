<?
	include "../bizstory/common/setting.php";

	foreach ($_COOKIE as $key => $value)
	{
		unset($_COOKIE[$key]);
	}

	foreach ($_SESSION as $key => $value)
	{
		unset($_SESSION[$key]);
	}

	echo '<meta http-equiv="refresh" content="0; url=', $local_dir, '/cms/login.php">';
?>