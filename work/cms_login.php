<?
	include "../common/setting.php";
	include "../common/no_direct.php";
	include "../common/member_chk.php";

	$client_idx = $_POST['client_idx'];

	unset($_SESSION[$sess_str . '_client_idx']);
	unset($_SESSION[$sess_str . '_client_name']);
	unset($_SESSION[$sess_str . '_cu_idx']);

	$client_where = " and ci.ci_idx = '" . $client_idx . "'";
	$client_data = client_info_data("view", $client_where);

	$_SESSION[$sess_str . '_client_idx']  = $client_data['ci_idx'];
	$_SESSION[$sess_str . '_client_name'] = $client_data['client_name'];
	$_SESSION[$sess_str . '_cu_idx']      = $_SESSION[$sess_str . '_mem_idx'];

	$str = '{"success_chk" : "Y"}';
	echo $str;
	exit;
?>