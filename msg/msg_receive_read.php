<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	$where = " and mr.mr_idx = '" . $mr_idx . "'";
	$data = message_receive_data('view', $where);

	// 읽기체크
	if ($data['read_date'] == "" || $data['read_date'] == "0000-00-00 00:00:00")
	{
		$query_str = "
			update message_receive set
				read_date = now()
			where del_yn = 'N' and mr_idx = '" . $mr_idx . "'
		";
		db_query($query_str);
		query_history($query_str, 'message_receive', 'update');
	}

// 미확인쪽지
	$message_where = "and mr.comp_idx = '" . $code_comp . "' and mr.mem_idx = '" . $code_mem . "' and mr.recv_keep = 'N' and date_format(mr.read_date, '%Y-%m-%d') = '0000-00-00'";
	$message_list = message_receive_data('page', $message_where);

// 선택한 직원의 미확인쪽지
	$msg_ing_query = "
		select
			count(mr_idx)
		from
			message_receive
		where
			del_yn = 'N'
			and comp_idx = '" . $code_comp . "'
			and mem_idx = '" . $code_mem . "'
			and reg_id = '" . $mem_idx . "'
			and recv_keep = 'N'
			and date_format(read_date, '%Y-%m-%d') = '0000-00-00'
	";
	$msg_ing = query_page($msg_ing_query);

	$str = '{"success_chk" : "Y", "total_note" : "' . $message_list['total_num'] . '", "mem_note" : "' . $msg_ing['total_num'] . '"}';
	echo $str;
	exit;
?>
