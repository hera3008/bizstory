<?
/*
	생성 : 2012.09.11
	위치 : 접수댓글실행
*/
	require_once "../common/setting.php";
	require_once $local_path . "/bizstory/mobile/process/mobile_setting.php";
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
		$chk_param['require'][] = array("field"=>"remark", "msg"=>"댓글내용");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];
		$ri_idx   = $_POST['ri_idx'];

		$command    = "insert"; //명령어
		$table      = "receipt_comment"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $mem_idx;
		$param['reg_date'] = date('Y-m-d H:i:s');
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;
		$param['ri_idx']   = $ri_idx;
		$param['ip_addr']  = $ip_address;
		$param['mem_idx']  = $mem_idx;
		$param['remark']   = nl2br($param['remark']);

		$chk_data = query_view("select max(rc_idx) as rc_idx, max(order_idx) as order_idx from " . $table);
		$param['rc_idx'] = ($chk_data['rc_idx'] == '') ? '1' : $chk_data['rc_idx'] + 1;
		$param['order_idx'] = ($chk_data['order_idx'] == '') ? '1' : $chk_data['order_idx'] + 1;

	// 회원정보
		$sub_where = " and mem.mem_idx = '" . $mem_idx . "'";
		$sub_data = member_info_data('view', $sub_where);
		if ($param['writer'] == "") $param['writer'] = $sub_data['mem_name'];

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$receipt_where = " and ri.ri_idx = '" . $ri_idx . "'";
		$receipt_data = receipt_info_data('view', $receipt_where);

		$hi_command    = "insert"; //명령어
		$hi_table      = "receipt_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $mem_idx;
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $mem_idx;
		$hi_param['ri_idx']      = $ri_idx;
		$hi_param['status']      = '';
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '댓글이 등록되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

	// 총수구하기
		$comment_where = " and rc.ri_idx = '" . $ri_idx . "'";
		$comment_list = receipt_comment_data('list', $comment_where, '', '', '');

		$str = '{"success_chk" : "Y", "total_num":"[' . number_format($comment_list['total_num']) . ']"}';
		echo $str;
		exit;
	}
?>