<?
/*
	생성 : 2012.06.08
	위치 : 설정폴더 > 컨텐츠관리 > 게시판관리 > 말머리 실행
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
		$chk_param['require'][] = array("field"=>"menu_name", "msg"=>"말머리명");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$comp_idx = $_POST['comp_idx'];
		$part_idx = $_POST['part_idx'];
		$bs_idx   = $_POST['bs_idx'];

		$param['menu_name'] = $_POST['post_menu_name'];
		$param['view_yn']   = $_POST['post_view_yn'];

	// 등록
		$command    = "insert"; //명령어
		$table      = "bbs_category"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;
		$param['bs_idx']   = $bs_idx;

		if ($param['view_yn'] == '') $param['view_yn'] = 'Y';

		$data = query_view("
			select max(sort) as max_sort
			from " . $table . "
			where del_yn = 'N' and comp_idx = '" . $comp_idx . "' and bs_idx = '" . $bs_idx . "'");
		$param["sort"] = ($data["max_sort"] == "") ? "1" : $data["max_sort"] + 1;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " and comp_idx = '" . $comp_idx . "' and bs_idx = '" . $bs_idx . "'";
		data_sort_action($table, 'bc_idx', $where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$comp_idx = $_POST['comp_idx'];
		$part_idx = $_POST['part_idx'];
		$bs_idx   = $_POST['bs_idx'];
		$bc_idx   = $_POST['idx'];

		$param['menu_name'] = $_POST['edit_menu_name'];
		$param['view_yn']   = $_POST['edit_view_yn'];

	// 수정
		$command    = "update"; //명령어
		$table      = "bbs_category"; //테이블명
		$conditions = "bc_idx = '" . $bc_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($param['view_yn'] == '') $param['view_yn'] = 'N';

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " and comp_idx = '" . $comp_idx . "' and bs_idx = '" . $bs_idx . "'";
		data_sort_action($table, 'bc_idx', $where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$comp_idx = $_POST['comp_idx'];
		$part_idx = $_POST['part_idx'];
		$bs_idx   = $_POST['bs_idx'];
		$bc_idx   = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "bbs_category"; //테이블명
		$conditions = "bc_idx = '" . $bc_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " and comp_idx = '" . $comp_idx . "' and bs_idx = '" . $bs_idx . "'";
		data_sort_action($table, 'bc_idx', $where);

		$str = '{"success_chk" : "Y", "error_string":"", "idx":"' . $bs_idx . '"}';
		echo $str;
		exit;
	}

// yn 함수
	function check_yn()
	{
		global $_POST, $_SESSION, $sess_str;

		$sub_action = $_POST['sub_action'];
		$post_value = $_POST['post_value'];

		$comp_idx = $_POST['comp_idx'];
		$part_idx = $_POST['part_idx'];
		$bs_idx   = $_POST['bs_idx'];
		$bc_idx   = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "bbs_category"; //테이블명
		$conditions = "bc_idx = '" . $bc_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($post_value == "Y") $param[$sub_action] = "N";
		else $param[$sub_action] = "Y";

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":"", "idx":"' . $bs_idx . '"}';
		echo $str;
		exit;
	}

//위 정렬 함수
	function sort_up()
	{
		global $_POST, $_SESSION, $sess_str;

		$table = "bbs_category"; //테이블명

		$comp_idx = $_POST['comp_idx'];
		$part_idx = $_POST['part_idx'];
		$bs_idx   = $_POST['bs_idx'];
		$bc_idx   = $_POST['idx'];

		$where = " and bc.bc_idx = '" . $bc_idx . "'";
		$data = bbs_category_data('view', $where);

		$sort_where = " and bc.comp_idx = '" . $comp_idx . "' and bc.bs_idx = '" . $bs_idx . "' and bc.sort < '" . $data["sort"] . "'";
		$sort_order = "bc.sort desc";
		$prev_data = bbs_category_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $prev_data["sort"] . "' where bc_idx = '" . $bc_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where bc_idx = '" . $prev_data["bc_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$where = " and comp_idx = '" . $comp_idx . "' and bs_idx = '" . $bs_idx . "'";
		data_sort_action($table, 'bc_idx', $where);

		$str = '{"success_chk" : "Y", "error_string":"", "idx":"' . $bs_idx . '"}';
		echo $str;
		exit;
	}

//아래 정렬 함수
	function sort_down()
	{
		global $_POST, $_SESSION, $sess_str;

		$table = "bbs_category"; //테이블명

		$comp_idx = $_POST['comp_idx'];
		$part_idx = $_POST['part_idx'];
		$bs_idx   = $_POST['bs_idx'];
		$bc_idx   = $_POST['idx'];

		$where = " and bc.bc_idx = '" . $bc_idx . "'";
		$data = bbs_category_data('view', $where);

		$sort_where = " and bc.comp_idx = '" . $comp_idx . "' and bc.bs_idx = '" . $bs_idx . "' and bc.sort > '" . $data["sort"] . "'";
		$sort_order = "bc.sort asc";
		$next_data = bbs_category_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $next_data["sort"] . "' where bc_idx = '" . $bc_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where bc_idx = '" . $next_data["bc_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$where = " and comp_idx = '" . $comp_idx . "' and bs_idx = '" . $bs_idx . "'";
		data_sort_action($table, 'bc_idx', $where);

		$str = '{"success_chk" : "Y", "error_string":"", "idx":"' . $bs_idx . '"}';
		echo $str;
		exit;
	}
?>