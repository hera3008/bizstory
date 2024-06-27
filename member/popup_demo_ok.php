<?
	include "../common/setting.php";
	include "../common/no_direct.php";

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
		$chk_param["require"][] = array("field"=>"comp_name", "msg"=>"상호명");
		$chk_param["require"][] = array("field"=>"charge_name", "msg"=>"담당자");
		$chk_param["require"][] = array("field"=>"tel_num1", "msg"=>"연락처");
		$chk_param["require"][] = array("field"=>"mem_email1", "msg"=>"이메일");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

//등록 함수
	function reg_post()
	{
		global $_POST, $sess_str;

		$param = $_POST["param"];

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 업체정보
		$comp_command    = "insert"; //명령어
		$comp_table      = "demo_info"; //테이블명
		$comp_conditions = ""; //조건

		$param["reg_id"]   = '';
		$param["reg_date"] = date("Y-m-d H:i:s");

		$param["tel_num"]   = $param["tel_num1"] . '-' . $param["tel_num2"] . '-' . $param["tel_num3"];
		$param["mem_email"] = $param["mem_email1"] . '@' . $param["mem_email2"];

		unset($param['mem_email1']);
		unset($param['mem_email2']);
		unset($param['tel_num1']);
		unset($param['tel_num2']);
		unset($param['tel_num3']);

		$param["tel_num1"]   = str_replace('-', '', $param["tel_num"]);
		$param["mem_email1"] = str_replace('@', '', $param["mem_email"]);

		chk_before($param);

		unset($param['tel_num1']);
		unset($param['mem_email1']);

		$query_str = make_sql($param, $comp_command, $comp_table, $comp_conditions);
		db_query($query_str);
		query_history($query_str, $comp_table, $comp_command);

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 대표님께 알려주기
		//$push = new PUSH("bizstory_push");

		$msg_type = 'reg_ok';
		$message = strip_tags($param['comp_name']);
		$message = '[데모신청] ' . string_cut($message, 20);

		$mem_idx = 2;
		$mem_where = " and mem.mem_idx = '" . $mem_idx . "'";
		$mem_data = member_info_data('view', $mem_where);

		$comp_idx = $mem_data['comp_idx'];
		$part_idx = $mem_data['part_idx'];
		$receiver = $mem_data['mem_id'];

		//$result = @$push->push_send($sender, $comp_idx, $part_idx, $mem_idx, $receiver, $msg_type, $message);
		push_send($sender, $comp_idx, $part_idx, $mem_idx, $receiver, $msg_type, $message);
		unset($mem_data);

		$str = '{"success_chk" : "Y"}';
		echo $str;
		exit;
	}
?>