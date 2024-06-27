<?
/*
	생성 : 2012.04.23
	위치 : 업무폴더 > 나의업무 > 업무 - 분류선택 - 실행
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
		$chk_param['require'][] = array("field"=>"work_class_str", "msg"=>"업무분류");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$work_class_str = $_POST['work_class_str'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];

	// 등록
		$command    = "insert"; //명령어
		$table      = "code_work_class"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;
		$param['code_name'] = $work_class_str;

		if ($param['code_bold'] == '') $param['code_bold'] = 'N';
		if ($param['view_yn'] == '') $param['view_yn'] = 'Y';
		if ($param['default_yn'] == '') $param['default_yn'] = 'N';

		$data = query_view("
			select max(sort) as max_sort
			from " . $table . "
			where del_yn = 'N' and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'");
		$param["sort"] = ($data["max_sort"] == "") ? "1" : $data["max_sort"] + 1;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_sort_action($table, 'code_idx', $where);

		$str = '{"success_chk" : "Y"}';
		echo $str;
		exit;
	}
?>