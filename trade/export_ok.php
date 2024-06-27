<?
/*
	생성 : 2012.11.21
	위치 : 무역업무 > 수출신고 - 실행
*/
	include "../common/setting.php";
	include "../common/no_direct.php";
	include "../common/member_chk.php";

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
		$chk_param['require'][] = array("field"=>"declare_company", "msg"=>"신고자 상호");
		$chk_param['require'][] = array("field"=>"declare_name", "msg"=>"신고자 성명");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];

		$command    = "insert"; //명령어
		$table      = "export_info"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date('Y-m-d H:i:s');
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;
		$param['mem_idx']  = $_SESSION[$sess_str . '_mem_idx'];

		if ($param['export_section'] == '') $param['export_section'] = 'C';
		if ($param['buyer_mark']     == '') $param['buyer_mark']     = 'ZZZZZZZZ9999A';
		if ($param['report_section'] == '') $param['report_section'] = 'H';
		if ($param['deal_section']   == '') $param['deal_section']   = '15';
		if ($param['export_kind']    == '') $param['export_kind']    = 'A';
		if ($param['payment_how']    == '') $param['payment_how']    = 'TT';
		if ($param['trasfer_terms']  == '') $param['trasfer_terms']  = 'CFR';
		if ($param['load_harbor']    == '') $param['load_harbor']    = 'ICN';
		if ($param['carry_means']    == '') $param['carry_means']    = '40';
		if ($param['test_how']       == '') $param['test_how']       = 'B';

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param  = $_POST['param'];
		$ei_idx = $_POST['ei_idx'];

		$command    = "update"; //명령어
		$table      = "export_info"; //테이블명
		$conditions = "ei_idx = '" . $ei_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($param['export_section'] == '') $param['export_section'] = 'C';
		if ($param['buyer_mark']     == '') $param['buyer_mark']     = 'ZZZZZZZZ9999A';
		if ($param['report_section'] == '') $param['report_section'] = 'H';
		if ($param['deal_section']   == '') $param['deal_section']   = '15';
		if ($param['export_kind']    == '') $param['export_kind']    = 'A';
		if ($param['payment_how']    == '') $param['payment_how']    = 'TT';
		if ($param['trasfer_terms']  == '') $param['trasfer_terms']  = 'CFR';
		if ($param['load_harbor']    == '') $param['load_harbor']    = 'ICN';
		if ($param['carry_means']    == '') $param['carry_means']    = '40';
		if ($param['test_how']       == '') $param['test_how']       = 'B';

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$ei_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "export_info"; //테이블명
		$conditions = "ei_idx = '" . $ei_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}
?>