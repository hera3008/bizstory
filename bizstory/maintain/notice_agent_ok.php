<?
/*
	생성 : 2012.07.04
	수정 : 2013.05.22
	위치 : 설정폴더(총관리자용) > 컨텐츠관리 > 공지관리 > 에이전트공지 - 실행
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
		$chk_param['require'][] = array("field"=>"content", "msg"=>"내용");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

//등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param = $_POST["param"];

		$command    = "insert"; //명령어
		$table      = "notice_info"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date('Y-m-d H:i:s');

		if ($param["comp_all"] == "") $param["comp_all"] = "N";
		if ($param["view_yn"] == "") $param["view_yn"] = "Y";

		$data = query_view("select max(ni_idx) as ni_idx from " . $table);
		$param["ni_idx"] = ($data["ni_idx"] == "") ? "1" : $data["ni_idx"] + 1;

		$data = query_view("select max(sort) as max_sort from " . $table . " where del_yn = 'N'");
		$param["sort"] = ($data["max_sort"] == "") ? "1" : $data["max_sort"] + 1;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " and notice_type = '" . $param['notice_type'] . "'";
		data_sort_action($table, 'ni_idx', $where);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

//수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param  = $_POST["param"];
		$ni_idx = $_POST["ni_idx"];

		$command    = "update"; //명령어
		$table      = "notice_info"; //테이블명
		$conditions = "ni_idx = '" . $ni_idx . "'"; //조건

		$param["mod_id"]   = $_SESSION[$sess_str . "_mem_idx"];
		$param["mod_date"] = date("Y-m-d H:i:s");

		if ($param["comp_all"] == "") $param["comp_all"] = "N";
		if ($param["view_yn"] == "") $param["view_yn"] = "Y";

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " and notice_type = '" . $param['notice_type'] . "'";
		data_sort_action($table, 'ni_idx', $where);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$ni_idx = $_POST['idx'];
		$notice_type = $_POST['notice_type'];

		$command    = "update"; //명령어
		$table      = "notice_info"; //테이블명
		$conditions = "ni_idx = '" . $ni_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " and notice_type = '" . $notice_type . "'";
		data_sort_action($table, 'ni_idx', $where);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// yn 함수
	function check_yn()
	{
		global $_POST, $_SESSION, $sess_str;

		$sub_action = $_POST['sub_action'];
		$ni_idx     = $_POST['idx'];
		$post_value = $_POST['post_value'];

		$command    = "update"; //명령어
		$table      = "notice_info"; //테이블명
		$conditions = "ni_idx = '" . $ni_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($post_value == "Y") $param[$sub_action] = "N";
		else $param[$sub_action] = "Y";

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

//위 정렬 함수
	function sort_up()
	{
		global $_POST, $_SESSION, $sess_str;

		$table = "notice_info"; //테이블명

		$ni_idx  = $_POST["idx"];
		$notice_type = $_POST['notice_type'];

		$where = " and ni.ni_idx = '" . $ni_idx . "'";
		$data = notice_info_data('view', $where);

		$sort_where = " and ni.notice_type = " . $notice_type . "' and ni.sort < '" . $data["sort"] . "'";
		$sort_order = "ni.sort desc";
		$prev_data = notice_info_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $prev_data["sort"] . "' where ni_idx = '" . $ni_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where ni_idx = '" . $prev_data["ni_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$where = " and notice_type = '" . $notice_type . "'";
		data_sort_action($table, 'ni_idx', $where);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

//아래 정렬 함수
	function sort_down()
	{
		global $_POST, $_SESSION, $sess_str;

		$table = "notice_info"; //테이블명

		$ni_idx = $_POST["idx"];
		$notice_type = $_POST['notice_type'];

		$where = " and ni.ni_idx = '" . $ni_idx . "'";
		$data = notice_info_data('view', $where);

		$sort_where = " and ni.notice_type = " . $notice_type . "' and ni.sort > '" . $data["sort"] . "'";
		$sort_order = "ni.sort asc";
		$next_data = notice_info_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $next_data["sort"] . "' where ni_idx = '" . $ni_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where ni_idx = '" . $next_data["ni_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$where = " and notice_type = '" . $notice_type . "'";
		data_sort_action($table, 'ni_idx', $where);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}
?>