<?
/*
	수정 : 2012.11.22
	위치 : 회계업무 > 운영비관리 - 실행
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
		$chk_param['require'][] = array("field"=>"account_type", "msg"=>"종류");
		$chk_param['require'][] = array("field"=>"gubun_code", "msg"=>"구분");
		$chk_param['require'][] = array("field"=>"account_date", "msg"=>"날짜");
		$chk_param['require'][] = array("field"=>"account_price", "msg"=>"금액");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

//입력처리 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $param['part_idx'];
		$ai_code  = $_POST['ai_code'];

		$command    = "insert"; //명령어
		$table      = "account_info"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");
		$param['comp_idx'] = $comp_idx;

		$param['account_price'] = str_replace(',', '', $param['account_price']);
		if ($param['charge_yn'] == '') $param['charge_yn'] = 'N';

		if ($ai_code == '')
		{
			$data = query_view("select max(ai_code) as ai_code from " . $table);
			$param["ai_code"] = ($data["ai_code"] == "") ? "1" : $data["ai_code"] + 1;
		}
		else
		{
			$param["ai_code"] = $ai_code;
		}

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":"", "ai_code":"' . $param["ai_code"] . '"}';
		echo $str;
		exit;
	}

//수정처리 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param   = $_POST['modify_param'];
		$ai_idx  = $_POST['ai_idx'];
		$ai_code = $_POST['ai_code'];

		chk_before($param);

		$command    = "update"; //명령어
		$table      = "account_info"; //테이블명
		$conditions = "ai_idx = '" . $ai_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		$param['account_price'] = str_replace(',', '', $param['account_price']);
		if ($param['charge_yn'] == '') $param['charge_yn'] = 'N';

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":"", "ai_code":"' . $ai_code . '"}';
		echo $str;
		exit;
	}

//삭제처리 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$ai_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "account_info"; //테이블명
		$conditions = "ai_idx='" . $ai_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}
?>