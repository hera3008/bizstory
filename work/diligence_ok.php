<?
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
	function chk_before($param)
	{
	//필수검사
		$chk_param['require'][] = array("field"=>"subject", "msg"=>"프로젝트명");

	//체크합니다.
		//param_check($param, $chk_param, 'json');
	}

// 등록 함수
	function dili_post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];

		$command    = "insert"; //명령어
		$table      = "diligence_info"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");
		$param['comp_idx'] = $comp_idx;
		$param['mem_idx']  = $_SESSION[$sess_str . '_mem_idx'];

		if ($param['work_night_yn'] == '') $param['work_night_yn'] = 'N';
		if ($param['open_yn'] == '') $param['open_yn'] = 'Y';

		$chk_year  = substr($param['start_date'], 0, 4);
		$chk_month = substr($param['start_date'], 5, 2);
		$chk_day   = substr($param['start_date'], 8, 2);
		$maketime  = mktime(0,0,0, $chk_month, $chk_day, $chk_year);

		$param['start_week'] = date("w", $maketime);
		$param['end_date']   = $param['start_date'];
		$param['end_time']   = $param['start_time'];
		$param['end_week']   = $param['start_week'];

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y"}';
		echo $str;
		exit;
	}
?>