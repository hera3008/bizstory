<?
/*
	생성 : 2012.08.06
	위치 : 고객관리 > 접수목록 - 보기 - 접수상태
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$receipt_info = new receipt_info();
	$receipt_info->ri_idx = $ri_idx;
	$receipt_info->data_path = $comp_receipt_path;
	$receipt_info->data_dir = $comp_receipt_dir;

	$status_list = $receipt_info->receipt_status_only();

	foreach ($status_list as $status_k => $status_v)
	{
		foreach ($status_v as $status_k1 => $status_data)
		{
			echo $status_data;
		}
	}
?>
