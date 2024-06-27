<?
	require_once "../common/setting.php";
	require_once $local_path . "/bizstory/m/process/mobile_setting.php";
	require_once $mobile_path . "/process/member_chk.php";
	require_once $mobile_path . "/process/no_direct.php";

	if($sub_type == "")
	{
		$str = '{"success_chk" : "N", "error_string" : "sub_type 명이 필요합니다."}';
		echo $str;
		exit;
	}

	if(!function_exists($sub_type))
	{
		$str = '{"success_chk" : "N", "error_string" : "sub_type method 가 없습니다."}';
		echo $str;
		exit;
	}
	call_user_func($sub_type);
	exit;

//기초값 검사
	function chk_before($param, $chk_type = 'json')
	{
	//필수검사
		$chk_param['require'][] = array("field"=>"remark", "msg"=>"업무보고내용");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$param     = $_POST['param'];
		$code_comp = $_SESSION[$sess_str . '_comp_idx'];
		$code_part = $_SESSION[$sess_str . '_part_idx'];
		$code_mem  = $_SESSION[$sess_str . '_mem_idx'];
		$wi_idx    = $_POST['wi_idx'];

		$command    = "insert"; //명령어
		$table      = "work_report"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $mem_idx;
		$param['reg_date'] = date('Y-m-d H:i:s');
		$param['comp_idx'] = $code_comp;
		$param['part_idx'] = $code_part;
		$param['wi_idx']   = $wi_idx;
		$param['ip_addr']  = $ip_address;
		$param['mem_idx']  = $code_mem;
		$param['remark']   = nl2br($param['remark']);

		$chk_data = query_view("select max(wr_idx) as wr_idx from " . $table);
		$param['wr_idx'] = ($chk_data['wr_idx'] == '') ? '1' : $chk_data['wr_idx'] + 1;

	// 회원정보
		$sub_where = " and mem.mem_idx = '" . $code_mem . "'";
		$sub_data = member_info_data('view', $sub_where);
		if ($param['writer'] == "") $param['writer'] = $sub_data['mem_name'];

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$work_where = " and wi.wi_idx = '" . $wi_idx . "'";
		$work_data = work_info_data('view', $work_where);

		$hi_command    = "insert"; //명령어
		$hi_table      = "work_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $mem_idx;
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $code_comp;
		$hi_param['part_idx']    = $code_part;
		$hi_param['mem_idx']     = $code_mem;
		$hi_param['wi_idx']      = $wi_idx;
		$hi_param['status']      = '';
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '업무보고가 등록되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 푸시건 - 요청, 승인, 알림업무일 경우만, 업무등록자에게
		//$push = new PUSH("bizstory_push");

		$msg_type = 'work';
		$message  = strip_tags($work_data['subject']);
		$message  = '[업무보고] ' . string_cut($message, 20);

		$writer_mem = $work_data['mem_idx'];
		if ($writer_mem != $code_mem && $writer_mem > 0)
		{
			$mem_where = " and mem.mem_idx = '" . $writer_mem . "'";
			$mem_data = member_info_data('view', $mem_where);

			$receiver = $mem_data['mem_id'];
			//$result = @$push->push_send($sender, $code_comp, $code_part, $writer_mem, $receiver, $msg_type, $message);
			push_send($sender, $comp_idx, $part_idx, $mem_idx, $receiver, $msg_type, $message);
			unset($mem_data);
		}

	// 담당자에게 - 업무보고작성자, 업무등록자제외
		$charge_idx = ',' . $work_data['charge_idx'] . ','; // 업무등록자
		$charge_idx = str_replace(',' . $code_mem . ',', '', $charge_idx);
		$charge_idx = str_replace(',' . $writer_mem . ',', '', $charge_idx);
		$charge_arr = explode(',', $charge_idx);
		foreach ($charge_arr as $charge_k => $charge_data)
		{
			$mem_where = " and mem.mem_idx = '" . $charge_data . "'";
			$mem_data = member_info_data('view', $mem_where);

			$receiver = $mem_data['mem_id'];
			//$result = @$push->push_send($sender, $code_comp, $code_part, $writer_mem, $receiver, $msg_type, $message);
			push_send($sender, $comp_idx, $part_idx, $mem_idx, $receiver, $msg_type, $message);
			unset($mem_data);
		}

	// 총수구하기
		$report_where = " and wr.wi_idx = '" . $wi_idx . "'";
		$report_page = work_report_data('page', $report_where);

		$str = '{"success_chk" : "Y", "total_num":"[' . number_format($report_page['total_num']) . ']"}';
		echo $str;
		exit;
	}
?>