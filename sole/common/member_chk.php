<?
	If ($_SESSION[$sess_str . "_sole_idx"] == "")
	{
		$login_url = $local_dir . '/sole/login.php?move_url=' . $move_url;
		header("Location: " . $login_url);
		exit;
	}
	else
	{
		$code_sole = $_SESSION[$sess_str . "_sole_idx"];
	}
?>