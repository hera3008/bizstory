<?
/*
	생성 : 2012.07.26
	위치 : 접수, 업무관련 notify
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	if ($chk_date == '') $chk_date = date('Y-m-d H:i:s');

// 접수관련
	$receipt_where = " and ri.comp_idx = '" . $code_comp . "'";
	if ($set_part_yn == 'N') $receipt_where .= " and ri.part_idx = '" . $code_part . "'";
	$receipt_where .= " and code2.code_value <> '99' and code2.code_value <> '90' and code2.code_value <> '80'";
	$receipt_list = receipt_info_data('list', $receipt_where, '', '', '');

// 접수이력관련
	$receipth_where = " and rsh.comp_idx = '" . $code_comp . "'";
	if ($set_part_yn == 'N') $receipth_where .= " and rsh.part_idx = '" . $code_part . "'";
	$receipth_where .= " and ri.del_yn = 'N' and rsh.mem_idx > 0";
	$receipth_order = "rsh.reg_date desc";
	$receipth_list = receipt_status_history_data('list', $receipth_where, $receipth_order, '', '');

// 업무관련
	$work_where = " and wi.comp_idx = '" . $code_comp . "'";
	if ($set_part_yn == 'N') $work_where .= " and wi.part_idx = '" . $code_part . "'";
	$work_where .= "
		and (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "')
		and wi.work_status <> 'WS80' and wi.work_status <> 'WS90' and wi.work_status <> 'WS99' and wi.work_status <> 'WS50'
	";
	$work_list = work_info_data('list', $work_where, '', '', '');

// 업무이력관련
	$workh_where = " and wsh.comp_idx = '" . $code_comp . "'";
	if ($set_part_yn == 'N') $workh_where .= " and wsh.part_idx = '" . $code_part . "'";
	$workh_where .= " and wi.del_yn = 'N' and wsh.mem_idx > 0";
	$workh_order = "wsh.reg_date desc";
	$workh_list = work_status_history_data('list', $workh_where, $workh_order, '', '');

	echo 'receipt_total -> ', $receipt_list['total_num'], '<br />';
	echo 'receipth_total -> ', $receipth_list['total_num'], '<br />';
	echo 'work_total -> ', $work_list['total_num'], '<br />';
	echo 'workh_total -> ', $workh_list['total_num'], '<br />';
?>