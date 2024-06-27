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

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];

		$command    = "insert"; //명령어
		$table      = "diligence_set"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");
		$param['comp_idx'] = $comp_idx;

		if (is_array($_POST["work_week"])) $param["work_week"] = implode(",", $_POST["work_week"]);

		if ($param['start_yn'] == '') $param['start_yn'] = 'Y';
		if ($param['start_hour'] == '') $param['start_hour'] = '00';
		if ($param['start_minute'] == '') $param['start_minute'] = '00';
		$param['start_time'] = $param['start_hour'] . ':' . $param['start_minute'];

		if ($param['end_yn'] == '') $param['end_yn'] = 'N';
		if ($param['end_hour'] == '') $param['end_hour'] = '00';
		if ($param['end_minute'] == '') $param['end_minute'] = '00';
		$param['end_time']   = $param['end_hour']   . ':' . $param['end_minute'];

		if ($param['night_yn'] == '') $param['night_yn'] = 'N';
		if ($param['night_hour'] == '') $param['night_hour'] = '00';
		if ($param['night_minute'] == '') $param['night_minute'] = '00';
		$param['night_time'] = $param['night_hour'] . ':' . $param['night_minute'];

		if ($param['open_yn'] == '') $param['open_yn'] = 'Y';

		unset($param['start_hour']);
		unset($param['start_minute']);
		unset($param['end_hour']);
		unset($param['end_minute']);
		unset($param['night_hour']);
		unset($param['night_minute']);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$ds_idx   = $_POST['ds_idx'];
		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];

		$command    = "update"; //명령어
		$table      = "diligence_set"; //테이블명
		$conditions = "ds_idx = '" . $ds_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if (is_array($_POST["work_week"])) $param["work_week"] = implode(",", $_POST["work_week"]);

		if ($param['start_yn'] == '') $param['start_yn'] = 'Y';
		if ($param['start_hour'] == '') $param['start_hour'] = '00';
		if ($param['start_minute'] == '') $param['start_minute'] = '00';
		$param['start_time'] = $param['start_hour'] . ':' . $param['start_minute'];

		if ($param['end_yn'] == '') $param['end_yn'] = 'N';
		if ($param['end_hour'] == '') $param['end_hour'] = '00';
		if ($param['end_minute'] == '') $param['end_minute'] = '00';
		$param['end_time']   = $param['end_hour']   . ':' . $param['end_minute'];

		if ($param['night_yn'] == '') $param['night_yn'] = 'N';
		if ($param['night_hour'] == '') $param['night_hour'] = '00';
		if ($param['night_minute'] == '') $param['night_minute'] = '00';
		$param['night_time'] = $param['night_hour'] . ':' . $param['night_minute'];

		if ($param['open_yn'] == '') $param['open_yn'] = 'Y';

		unset($param['start_hour']);
		unset($param['start_minute']);
		unset($param['end_hour']);
		unset($param['end_minute']);
		unset($param['night_hour']);
		unset($param['night_minute']);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}
?>