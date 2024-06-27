<?
/*
	생성 : 2012.12.14
	수정 : 2013.05.20
	위치 : 총설정폴더 > 컨텐츠관리 > 게시판관리 - 실행
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
		$chk_param['require'][] = array("field"=>"subject", "msg"=>"게시판제목");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

//입력처리 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param = $_POST['param'];

		$command    = "insert"; //명령어
		$table      = "comp_bbs_setting"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");

		if ($param['skin_name']   == '') $param['skin_name']   = 'basic';
		if ($param["list_row"]    == '') $param["list_row"]    = "15";
		if ($param['view_yn']     == '') $param['view_yn']     = 'Y';
		if ($param["category_yn"] == '') $param["category_yn"] = "N";
		if ($param["reply_yn"]    == '') $param["reply_yn"]    = "N";
		if ($param["comment_yn"]  == '') $param["comment_yn"]  = "N";
		if ($param["link_yn"]     == '') $param["link_yn"]     = "N";
		if ($param["file_yn"]     == '') $param["file_yn"]     = "N";

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

//수정처리 함수
	function modify()
	{
		global $_POST, $_SESSION, $field_str, $this_page, $site_info, $sess_str;

		$param  = $_POST['param'];
		$bs_idx = $_POST['bs_idx'];

		chk_before($param);

		$command    = "update"; //명령어
		$table      = "comp_bbs_setting"; //테이블명
		$conditions = "bs_idx = '" . $bs_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($param['skin_name']   == '') $param['skin_name']   = 'basic';
		if ($param["list_row"]    == '') $param["list_row"]    = "15";
		if ($param['view_yn']     == '') $param['view_yn']     = 'Y';
		if ($param["category_yn"] == '') $param["category_yn"] = "N";
		if ($param["reply_yn"]    == '') $param["reply_yn"]    = "N";
		if ($param["comment_yn"]  == '') $param["comment_yn"]  = "N";
		if ($param["link_yn"]     == '') $param["link_yn"]     = "N";
		if ($param["file_yn"]     == '') $param["file_yn"]     = "N";

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

//삭제처리 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$bs_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "comp_bbs_setting"; //테이블명
		$conditions = "bs_idx='" . $bs_idx . "'"; //조건

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

// yn 함수
	function check_yn()
	{
		global $_POST, $_SESSION, $sess_str;

		$sub_action = $_POST['sub_action'];
		$bs_idx     = $_POST['idx'];
		$post_value = $_POST['post_value'];

		$command    = "update"; //명령어
		$table      = "comp_bbs_setting"; //테이블명
		$conditions = "bs_idx = '" . $bs_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($post_value == "Y") $param[$sub_action] = "N";
		else $param[$sub_action] = "Y";

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}
?>