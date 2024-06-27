<?
/*
	생성 : 2013.01.17
	수정 : 2013.01.17
	위치 : 전문가코너 > 코드설정 > 거래처검색분류 - 구성항목 실행
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
		$chk_param['require'][] = array("field"=>"code_name", "msg"=>"구성항목");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$ecsf_idx = $_POST['ecsf_idx'];

		$param['code_name']   = $_POST['post_code_name'];
		$param['view_yn']     = $_POST['post_view_yn'];
		$param['default_yn'] = $_POST['post_default_yn'];

	// 등록
		$command    = "insert"; //명령어
		$table      = "expert_client_search_field_data"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");
		$param['ecsf_idx'] = $ecsf_idx;

		if ($param['view_yn'] == '') $param['view_yn'] = 'Y';
		if ($param['default_yn'] == '') $param['default_yn'] = 'N';

	// 기본값설정
		if ($param['default_yn'] == 'Y')
		{
			$where = " and ecsfd.ecsf_idx = '" . $ecsf_idx . "' and ecsfd.default_yn = 'Y'";
			$page_data = expert_client_search_field_data_data("page", $where);
			if ($page_data['total_num'] > 0)
			{
				$str = '{"success_chk" : "N", "error_string" : "설정된 기본값이 있습니다."}';
				echo $str;
				exit;
			}
		}

		$data = query_view("select max(sort) as max_sort from " . $table . " where del_yn = 'N' and ecsf_idx = '" . $ecsf_idx . "'");
		$param["sort"] = ($data["max_sort"] == "") ? "1" : $data["max_sort"] + 1;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " and ecsf_idx = '" . $ecsf_idx . "'";
		data_sort_action($table, 'ecsfd_idx', $where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$ecsf_idx  = $_POST['ecsf_idx'];
		$ecsfd_idx = $_POST['idx'];

		$param['code_name']   = $_POST['edit_code_name'];
		$param['view_yn']     = $_POST['edit_view_yn'];
		$param['default_yn'] = $_POST['edit_default_yn'];

	// 수정
		$command    = "update"; //명령어
		$table      = "expert_client_search_field_data"; //테이블명
		$conditions = "ecsfd_idx = '" . $ecsfd_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($param['view_yn'] == '') $param['view_yn'] = 'N';
		if ($param['default_yn'] == '') $param['default_yn'] = 'N';

	// 기본값설정
		if ($param['default_yn'] == 'Y')
		{
			$where = " and ecsfd.ecsf_idx = '" . $ecsf_idx . "' and ecsfd.default_yn = 'Y' and ecsfd.ecsfd_idx = '" . $ecsfd_idx . "'";
			$page_data = expert_client_search_field_data_data("page", $where);
			if ($page_data['total_num'] > 0)
			{
				$str = '{"success_chk" : "N", "error_string" : "설정된 기본값이 있습니다."}';
				echo $str;
				exit;
			}
		}

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " and ecsf_idx = '" . $ecsf_idx . "'";
		data_sort_action($table, 'ecsfd_idx', $where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$ecsf_idx  = $_POST['ecsf_idx'];
		$ecsfd_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "expert_client_search_field_data"; //테이블명
		$conditions = "ecsfd_idx = '" . $ecsfd_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " and ecsf_idx = '" . $ecsf_idx . "'";
		data_sort_action($table, 'ecsfd_idx', $where);

		$str = '{"success_chk" : "Y", "error_string":"", "idx":"' . $ecsf_idx . '"}';
		echo $str;
		exit;
	}

// yn 함수
	function check_yn()
	{
		global $_POST, $_SESSION, $sess_str;

		$sub_action = $_POST['sub_action'];
		$post_value = $_POST['post_value'];
		$ecsf_idx   = $_POST['ecsf_idx'];
		$ecsfd_idx  = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "expert_client_search_field_data"; //테이블명
		$conditions = "ecsfd_idx = '" . $ecsfd_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

	// 기본값설정
		if ($sub_action == "default_yn" && $post_value == "N")
		{
			$where = " and ecsfd.ecsf_idx = '" . $ecsf_idx . "' and ecsfd.default_yn = 'Y'";
			$page_data = expert_client_search_field_data_data("page", $where);
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

		$str = '{"success_chk" : "Y", "error_string":"", "idx":"' . $ecsf_idx . '"}';
		echo $str;
		exit;
	}

//위 정렬 함수
	function sort_up()
	{
		global $_POST, $_SESSION, $sess_str;

		$table = "expert_client_search_field_data"; //테이블명

		$ecsf_idx  = $_POST['ecsf_idx'];
		$ecsfd_idx = $_POST['idx'];

		$where = " and ecsfd.ecsfd_idx = '" . $ecsfd_idx . "'";
		$data = expert_client_search_field_data_data('view', $where);

		$sort_where = " and ecsfd.ecsf_idx = '" . $ecsf_idx . "' and ecsfd.sort < '" . $data["sort"] . "'";
		$sort_order = "ecsfd.sort desc";
		$prev_data = expert_client_search_field_data_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $prev_data["sort"] . "' where ecsfd_idx = '" . $ecsfd_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where ecsfd_idx = '" . $prev_data["ecsfd_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$where = " and ecsf_idx = '" . $ecsf_idx . "'";
		data_sort_action($table, 'ecsfd_idx', $where);

		$str = '{"success_chk" : "Y", "error_string":"", "idx":"' . $ecsf_idx . '"}';
		echo $str;
		exit;
	}

//아래 정렬 함수
	function sort_down()
	{
		global $_POST, $_SESSION, $sess_str;

		$table = "expert_client_search_field_data"; //테이블명

		$ecsf_idx  = $_POST['ecsf_idx'];
		$ecsfd_idx = $_POST['idx'];

		$where = " and ecsfd.ecsfd_idx = '" . $ecsfd_idx . "'";
		$data = expert_client_search_field_data_data('view', $where);

		$sort_where = " and ecsfd.ecsf_idx = '" . $ecsf_idx . "' and ecsfd.sort > '" . $data["sort"] . "'";
		$sort_order = "ecsfd.sort asc";
		$next_data = expert_client_search_field_data_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $next_data["sort"] . "' where ecsfd_idx = '" . $ecsfd_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where ecsfd_idx = '" . $next_data["ecsfd_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$where = " and ecsf_idx = '" . $ecsf_idx . "'";
		data_sort_action($table, 'ecsfd_idx', $where);

		$str = '{"success_chk" : "Y", "error_string":"", "idx":"' . $ecsf_idx . '"}';
		echo $str;
		exit;
	}
?>