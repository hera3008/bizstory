<?
/*
	수정 : 2013.03.25
	위치 : 설정폴더 > 직원관리 > 부서관리 - 실행
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
		$chk_param['require'][] = array("field"=>"group_name", "msg"=>"부서명");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $param['part_idx'];

	// 기본값설정
		if ($param['default_yn'] == 'Y')
		{
			$where = " and csg.comp_idx = '" . $comp_idx . "' and csg.part_idx = '" . $part_idx . "' and csg.default_yn = 'Y'";
			$page_data = company_staff_group_data("page", $where);
			if ($page_data['total_num'] > 0)
			{
				$str = '{"success_chk" : "N", "error_string" : "설정된 기본값이 있습니다."}';
				echo $str;
				exit;
			}
		}

		$command    = "insert"; //명령어
		$table      = "company_staff_group"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");
		$param['comp_idx'] = $comp_idx;

		if ($param['view_yn'] == '') $param['view_yn'] = 'Y';
		if ($param['default_yn'] == '') $param['default_yn'] = 'N';

		$data = query_view("select max(sort) as max_sort from " . $table . " where del_yn = 'N' and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'");
		$param["sort"] = ($data["max_sort"] == "") ? "1" : $data["max_sort"] + 1;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$sort_where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_sort_action($table, 'csg_idx', $sort_where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$csg_idx  = $_POST['csg_idx'];
		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $param['part_idx'];

	// 기본값설정
		if ($param['default_yn'] == 'Y')
		{
			$where = " and csg.comp_idx = '" . $comp_idx . "' and csg.part_idx = '" . $part_idx . "' and csg.default_yn = 'Y' and csg.csg_idx != '" . $csg_idx . "'";
			$page_data = company_staff_group_data("page", $where);
			if ($page_data['total_num'] > 0)
			{
				$str = '{"success_chk" : "N", "error_string" : "설정된 기본값이 있습니다."}';
				echo $str;
				exit;
			}
		}

	// 수정
		$command    = "update"; //명령어
		$table      = "company_staff_group"; //테이블명
		$conditions = "csg_idx = '" . $csg_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($param['view_yn'] == '') $param['view_yn'] = 'N';
		if ($param['default_yn'] == '') $param['default_yn'] = 'N';

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$sort_where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_sort_action($table, 'csg_idx', $sort_where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$csg_idx  = $_POST['idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_POST['code_part'];

		$command    = "update"; //명령어
		$table      = "company_staff_group"; //테이블명
		$conditions = "csg_idx = '" . $csg_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$sort_where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_sort_action($table, 'csg_idx', $sort_where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// yn 함수
	function check_yn()
	{
		global $_POST, $_SESSION, $sess_str;

		$sub_action = $_POST['sub_action'];
		$csg_idx    = $_POST['idx'];
		$post_value = $_POST['post_value'];
		$comp_idx   = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx   = $_POST['code_part'];

		$command    = "update"; //명령어
		$table      = "company_staff_group"; //테이블명
		$conditions = "csg_idx = '" . $csg_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

	// 기본값설정
		if ($sub_action == "default_yn" && $post_value == "N")
		{
			$where = " and csg.comp_idx = '" . $comp_idx . "' and csg.part_idx = '" . $part_idx . "' and csg.default_yn = 'Y'";
			$page_data = company_staff_group_data("page", $where);
			if ($page_data['total_num'] > 0)
			{
				$str = '{"success_chk" : "N", "error_string" : "설정된 기본값이 있습니다."}';
				echo $str;
				exit;
			}
		}

		if ($post_value == "Y") $param[$sub_action] = "N";
		else $param[$sub_action] = "Y";

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

//위 정렬 함수
	function sort_up()
	{
		global $_POST, $_SESSION, $sess_str;

		$table = "company_staff_group"; //테이블명

		$csg_idx  = $_POST["idx"];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_POST['code_part'];

		$where = " and csg.csg_idx = '" . $csg_idx . "'";
		$data = company_staff_group_data('view', $where);

		$sort_where = " and csg.comp_idx = '" . $comp_idx . "' and csg.part_idx = '" . $part_idx . "' and csg.sort < '" . $data["sort"] . "'";
		$sort_order = "csg.sort desc";
		$prev_data = company_staff_group_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $prev_data["sort"] . "' where csg_idx = '" . $csg_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where csg_idx = '" . $prev_data["csg_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sort_where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_sort_action($table, 'csg_idx', $sort_where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

//아래 정렬 함수
	function sort_down()
	{
		global $_POST, $_SESSION, $sess_str;

		$table = "company_staff_group"; //테이블명

		$csg_idx  = $_POST["idx"];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_POST['code_part'];

		$where = " and csg.csg_idx = '" . $csg_idx . "'";
		$data = company_staff_group_data('view', $where);

		$sort_where = " and csg.comp_idx = '" . $comp_idx . "' and csg.part_idx = '" . $part_idx . "' and csg.sort > '" . $data["sort"] . "'";
		$sort_order = "csg.sort asc";
		$next_data = company_staff_group_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $next_data["sort"] . "' where csg_idx = '" . $csg_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where csg_idx = '" . $next_data["csg_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sort_where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_sort_action($table, 'csg_idx', $sort_where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}
?>