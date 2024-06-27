<?
/*
	생성 : 2013.01.15
	수정 : 2013.01.16
	위치 : 전문가코너 > 코드설정 > 거래처검색분류 - 실행
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
		$chk_param['require'][] = array("field"=>"field_subject", "msg"=>"검색조건명");
		$chk_param['require'][] = array("field"=>"field_name", "msg"=>"필드명");
		$chk_param['require'][] = array("field"=>"field_type", "msg"=>"필드형식");
		$chk_param['require'][] = array("field"=>"field_length", "msg"=>"필드길이");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param = $_POST['param'];

		$command    = "insert"; //명령어
		$table      = "expert_client_search_field"; //테이블명
		$conditions = ""; //조건

		$chk_table  = "expert_client_search";

	// 필드있는지 체크
		$chk_field["query_string"] = "show full columns from " . $chk_table;
		$chk_field["page_size"] = "";
		$chk_field["page_num"]  = "";
		$field_list = query_list($chk_field);

		$field_chk = "N";
		foreach ($field_list as $k => $field_data)
		{
			if (is_array($field_data))
			{
				if ($field_data["Field"] == $param["field_name"])
				{
					$field_chk = "Y";
					break;
				}
			}
		}

		if ($field_chk == "N")
		{
			$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
			$param['reg_date'] = date("Y-m-d H:i:s");

			if ($param['view_yn'] == '') $param['view_yn'] = 'Y';
			if ($param['essential_yn'] == '') $param['essential_yn'] = 'N';

			$data = query_view("select max(sort) as max_sort from " . $table . " where del_yn = 'N'");
			$param["sort"] = ($data["max_sort"] == "") ? "1" : $data["max_sort"] + 1;

			$data = query_view("select max(ecsf_idx) as ecsf_idx from " . $table);
			$param["ecsf_idx"] = $data["ecsf_idx"] + 1;

			chk_before($param);

			$query_str = make_sql($param, $command, $table, $conditions);
			db_query($query_str);
			query_history($query_str, $table, $command);

			$where = "";
			data_sort_action($table, 'ecsf_idx', $where);

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 데이타타입
			$column_type = "varchar( " . $param["field_length"] . " )";

		// 기본값
			if ($param["essential_yn"] == "Y") $default_type = "NOT NULL DEFAULT ''";
			else $default_type = "NULL DEFAULT NULL";

		// 설명
			if($param["field_subject"] != "") $cont_type = "COMMENT '" . $param["field_subject"] . "'";
			else $cont_type = "";

		// 필드추가
			$sql = "ALTER TABLE `" . $chk_table . "` ADD `" . $param["field_name"] . "` " . $column_type . " " . $default_type . " " . $cont_type;
			db_query($sql);
			query_history($sql, $chk_table, 'ALTER TABLE ADD');

			$str = '{"success_chk" : "Y", "error_string":""}';
			echo $str;
			exit;
		}
		else
		{
			$str = '{"success_chk" : "N", "error_string" : "등록된 필드명입니다. 다른 필드명으로 변경하세요."}';
			echo $str;
			exit;
		}
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$ecsf_idx   = $_POST['ecsf_idx'];
		$param      = $_POST['param'];
		$field_name = $_POST['field_name'];

		$command    = "update"; //명령어
		$table      = "expert_client_search_field"; //테이블명
		$conditions = "ecsf_idx = '" . $ecsf_idx . "'"; //조건

		$chk_table  = "expert_client_search";

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($param['view_yn'] == '') $param['view_yn'] = 'Y';
		if ($param['essential_yn'] == '') $param['essential_yn'] = 'N';

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 데이타타입
		$column_type = "varchar( " . $param["field_length"] . " )";

	// 기본값
		if ($param["essential_yn"] == "Y") $default_type = "NOT NULL DEFAULT ''";
		else $default_type = "NULL DEFAULT NULL";

	// 설명
		if($param["field_subject"] != "") $cont_type = "COMMENT '" . $param["field_subject"] . "'";
		else $cont_type = "";

	// 필드수정
		$sql = "ALTER TABLE `" . $chk_table . "` CHANGE `" . $field_name . "` `" . $field_name . "` " . $column_type . " " . $default_type . " " . $cont_type;
		db_query($sql);
		query_history($sql, $chk_table, 'ALTER TABLE CHANGE');

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$ecsf_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "expert_client_search_field"; //테이블명
		$conditions = "ecsf_idx = '" . $ecsf_idx . "'"; //조건

		$chk_table  = "expert_client_search";

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 필드삭제
		$where = " and ecsf.ecsf_idx = '" . $ecsf_idx . "'";
		$data = expert_client_search_field_data("view", $where);

		$sql = "ALTER TABLE `" . $chk_table . "` DROP column `" . $data["field_name"] . ";";
		db_query($sql);
		query_history($sql, $chk_table, 'ALTER TABLE DROP');

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = "";
		data_sort_action($table, 'ecsf_idx', $where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// yn 함수
	function check_yn()
	{
		global $_POST, $_SESSION, $sess_str;

		$sub_action = $_POST['sub_action'];
		$ecsf_idx    = $_POST['idx'];
		$post_value = $_POST['post_value'];

		$command    = "update"; //명령어
		$table      = "expert_client_search_field"; //테이블명
		$conditions = "ecsf_idx = '" . $ecsf_idx . "'"; //조건

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

//위 정렬 함수
	function sort_up()
	{
		global $_POST, $_SESSION, $sess_str;

		$table = "expert_client_search_field"; //테이블명

		$ecsf_idx = $_POST["idx"];

		$where = " and ecsf.ecsf_idx = '" . $ecsf_idx . "'";
		$data = expert_client_search_field_data('view', $where);

		$sort_where = " and ecsf.sort < '" . $data["sort"] . "'";
		$sort_order = "ecsf.sort desc";
		$prev_data = expert_client_search_field_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $prev_data["sort"] . "' where ecsf_idx = '" . $ecsf_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where ecsf_idx = '" . $prev_data["ecsf_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$where = "";
		data_sort_action($table, 'ecsf_idx', $where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

//아래 정렬 함수
	function sort_down()
	{
		global $_POST, $_SESSION, $sess_str;

		$table = "expert_client_search_field"; //테이블명

		$ecsf_idx = $_POST["idx"];

		$where = " and ecsf.ecsf_idx = '" . $ecsf_idx . "'";
		$data = expert_client_search_field_data('view', $where);

		$sort_where = " and ecsf.sort > '" . $data["sort"] . "'";
		$sort_order = "ecsf.sort asc";
		$next_data = expert_client_search_field_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $next_data["sort"] . "' where ecsf_idx = '" . $ecsf_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where ecsf_idx = '" . $next_data["ecsf_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$where = "";
		data_sort_action($table, 'ecsf_idx', $where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}
?>