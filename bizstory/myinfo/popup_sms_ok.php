<?
/*
	생성 : 2012.12.27
	수정 : 2013.02.25
	위치 : 직원정보 - SMS보내기 - 실행
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
		$chk_param['require'][] = array("field"=>"remark", "msg"=>"내용");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param       = $_POST['param'];
		$comp_idx    = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx    = $_SESSION[$sess_str . '_part_idx'];
		$mem_idx     = $_SESSION[$sess_str . '_mem_idx'];
		$receive_idx = $_POST['receive_idx'];

		$command    = "insert"; //명령어
		$table      = "message_sms"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']      = $mem_idx;
		$param['reg_date']    = date("Y-m-d H:i:s");
		$param['comp_idx']    = $comp_idx;
		$param['part_idx']    = $part_idx;
		$param['send_idx']    = $mem_idx;
		$param['receive_idx'] = $receive_idx;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 알림건
		charge_push_send($mem_idx, $receive_idx, '', 'sms', $param['contents'], '', '', '');

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}
?>