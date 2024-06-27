<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = $_SESSION[$sess_str . '_part_idx'];
	$ci_idx    = $idx;

	$where = " and ci.ci_idx = '" . $ci_idx . "'";
	$client_data = client_info_data('view', $where);

	if ($client_data['total_num'] > 0)
	{
		$charge_info = $client_data['charge_info'];
		$charge_info_arr = explode('||', $charge_info);
		$info_str = explode('/', $charge_info_arr[0]);

		$json_str = '{
	"success_chk":"Y",
	"client_code":"' . $client_data['client_code'] . '"
}';
	}
	else
	{
		$json_str = '{"success_chk":"N"}';
	}

	echo $json_str;
?>