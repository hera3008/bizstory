<?
/*
	수정 : 2013.03.22
	위치 : 설정관리 > 코드관리 > 지사관리 - 실행
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
		$chk_param['require'][] = array("field"=>"part_name", "msg"=>"지사명");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str, $comp_set_data;

		$param        = $_POST['param'];
		$comp_idx     = $_SESSION[$sess_str . '_comp_idx'];
		$set_part_num = $comp_set_data['part_cnt'];

	// 개수구하기
		$where = " and part.comp_idx = '" . $comp_idx . "'";
		$page_data = company_part_data('page', $where, '', '', '');
		if ($page_data['total_num'] >= $set_part_num)
		{
			$str = '{"success_chk" : "N", "error_string" : "지사는 ' . $set_part_num . '개까지 등록이 가능합니다.<br /> 더이상 지사를 등록할 수 없습니다."}';
			echo $str;
			exit;
		}

	// 기본값설정
		if ($param['default_yn'] == 'Y')
		{
			$where = " and part.comp_idx = '" . $comp_idx . "' and part.default_yn = 'Y'";
			$page_data = company_part_data("page", $where);
			if ($page_data['total_num'] > 0)
			{
				$str = '{"success_chk" : "N", "error_string" : "설정된 기본값이 있습니다."}';
				echo $str;
				exit;
			}
		}

		if ($param['view_yn'] == '') $param['view_yn'] = 'Y';
		if ($param['default_yn'] == '') $param['default_yn'] = 'N';

		$command    = "insert"; //명령어
		$table      = "company_part"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");
		$param['comp_idx'] = $comp_idx;
		$param['tel_num']  = $param['tel_num1'] . '-' . $param['tel_num2'] . '-' . $param['tel_num3'];
		unset($param['tel_num1']);
		unset($param['tel_num2']);
		unset($param['tel_num3']);

		$data = query_view("select max(part_idx) as part_idx from " . $table);
		$param["part_idx"] = ($data["part_idx"] == "") ? "1" : $data["part_idx"] + 1;

		$data = query_view("select max(sort) as max_sort from " . $table . " where del_yn = 'N' and comp_idx = '" . $comp_idx . "'");
		$param["sort"] = ($data["max_sort"] == "") ? "1" : $data["max_sort"] + 1;

		if (is_array($_POST["agent_type"])) $param["agent_type"] = implode(",", $_POST["agent_type"]);
		else $param["agent_type"] = "";

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$sort_where = " and comp_idx = '" . $comp_idx . "'";
		data_sort_action($table, 'part_idx', $sort_where);

	// 해당기본값 셋팅
		default_code_setting($comp_idx, $param["part_idx"]);

		$str = '{"success_chk" : "Y", "error_string":"", "part_idx":"' . $param["part_idx"] . '"}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$part_idx   = $_POST['part_idx'];
		$param      = $_POST['param'];
		$comp_idx   = $_SESSION[$sess_str . '_comp_idx'];

		$command    = "update"; //명령어
		$table      = "company_part"; //테이블명
		$conditions = "part_idx = '" . $part_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");
		$param['tel_num']  = $param['tel_num1'] . '-' . $param['tel_num2'] . '-' . $param['tel_num3'];
		unset($param['tel_num1']);
		unset($param['tel_num2']);
		unset($param['tel_num3']);

		if ($param['view_yn'] == '') $param['view_yn'] = 'N';
		if ($param['default_yn'] == '') $param['default_yn'] = 'N';

		if (is_array($_POST["agent_type"])) $param["agent_type"] = implode(",", $_POST["agent_type"]);
		else $param["agent_type"] = "";

	// 기본값설정
		if ($param['default_yn'] == 'Y')
		{
			$where = " and part.comp_idx = '" . $comp_idx . "' and part.default_yn = 'Y' and part.part_idx != '" . $part_idx . "'";
			$page_data = company_part_data("page", $where);
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

		$sort_where = "and comp_idx = '" . $comp_idx . "'";
		data_sort_action($table, 'part_idx', $sort_where);

		$str = '{"success_chk" : "Y", "error_string":"", "part_idx":"' . $part_idx . '"}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$part_idx = $_POST['idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];

		$command    = "update"; //명령어
		$table      = "company_part"; //테이블명
		$conditions = "part_idx = '" . $part_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$sort_where = "and comp_idx = '" . $comp_idx . "'";
		data_sort_action($table, 'part_idx', $sort_where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// yn 함수
	function check_yn()
	{
		global $_POST, $_SESSION, $sess_str;

		$sub_action = $_POST['sub_action'];
		$part_idx   = $_POST['idx'];
		$post_value = $_POST['post_value'];
		$comp_idx   = $_SESSION[$sess_str . '_comp_idx'];

		$command    = "update"; //명령어
		$table      = "company_part"; //테이블명
		$conditions = "part_idx = '" . $part_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

	// 기본값설정
		if ($sub_action == "default_yn" && $post_value == "N")
		{
			$where = " and part.comp_idx = '" . $comp_idx . "' and part.default_yn = 'Y'";
			$page_data = company_part_data("page", $where);
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

		$table = "company_part"; //테이블명

		$part_idx = $_POST["idx"];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];

		$where = " and part.part_idx = '" . $part_idx . "'";
		$data = company_part_data('view', $where);

		$sort_where = " and part.comp_idx = '" . $comp_idx . "' and part.sort < '" . $data["sort"] . "'";
		$sort_order = "part.sort desc";
		$prev_data = company_part_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $prev_data["sort"] . "' where part_idx = '" . $part_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where part_idx = '" . $prev_data["part_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sort_where = "and comp_idx = '" . $comp_idx . "'";
		data_sort_action($table, 'part_idx', $sort_where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

//아래 정렬 함수
	function sort_down()
	{
		global $_POST, $_SESSION, $sess_str;

		$table = "company_part"; //테이블명

		$part_idx = $_POST["idx"];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];

		$where = " and part.part_idx = '" . $part_idx . "'";
		$data = company_part_data('view', $where);

		$sort_where = " and part.comp_idx = '" . $comp_idx . "' and part.sort > '" . $data["sort"] . "'";
		$sort_order = "part.sort asc";
		$next_data = company_part_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $next_data["sort"] . "' where part_idx = '" . $part_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where part_idx = '" . $next_data["part_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sort_where = "and comp_idx = '" . $comp_idx . "'";
		data_sort_action($table, 'part_idx', $sort_where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}
?>