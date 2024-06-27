<?
/*
	생성 : 2012.11.21
	수정 : 2013.03.22
	위치 : 설정관리 > 코드관리 > 회계설정 > 통장관리 - 실행
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
		$chk_param['require'][] = array("field"=>"code_name", "msg"=>"계좌명");
		$chk_param['require'][] = array("field"=>"bank_num", "msg"=>"계좌번호");

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
			$where = " and code.comp_idx = '" . $comp_idx . "' and code.part_idx = '" . $part_idx . "' and code.default_yn = 'Y'";
			$page_data = code_account_bank_data("page", $where);
			if ($page_data['total_num'] > 0)
			{
				$str = '{"success_chk" : "N", "error_string" : "설정된 기본값이 있습니다."}';
				echo $str;
				exit;
			}
		}

		$command    = "insert"; //명령어
		$table      = "code_account_bank"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");
		$param['comp_idx'] = $comp_idx;

		if ($param['code_bold'] == '') $param['code_bold'] = 'N';
		if ($param['view_yn'] == '') $param['view_yn'] = 'Y';
		if ($param['default_yn'] == '') $param['default_yn'] = 'N';

		$data = query_view("select max(sort) as max_sort from " . $table . " where del_yn = 'N' and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'");
		$param["sort"] = ($data["max_sort"] == "") ? "1" : $data["max_sort"] + 1;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$sort_where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_sort_action($table, 'code_idx', $sort_where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$code_idx = $_POST['code_idx'];
		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $param['part_idx'];

	// 기본값설정
		if ($param['default_yn'] == 'Y')
		{
			$where = " and code.comp_idx = '" . $comp_idx . "' and code.part_idx = '" . $part_idx . "' and code.default_yn = 'Y' and code.code_idx != '" . $code_idx . "'";
			$page_data = code_account_bank_data("page", $where);
			if ($page_data['total_num'] > 0)
			{
				$str = '{"success_chk" : "N", "error_string" : "설정된 기본값이 있습니다."}';
				echo $str;
				exit;
			}
		}

		$command    = "update"; //명령어
		$table      = "code_account_bank"; //테이블명
		$conditions = "code_idx = '" . $code_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($param['code_bold'] == '') $param['code_bold'] = 'N';
		if ($param['view_yn'] == '') $param['view_yn'] = 'N';
		if ($param['default_yn'] == '') $param['default_yn'] = 'N';

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$sort_where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_sort_action($table, 'code_idx', $sort_where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$code_idx = $_POST['idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_POST['code_part'];

		$command    = "update"; //명령어
		$table      = "code_account_bank"; //테이블명
		$conditions = "code_idx = '" . $code_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$sort_where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_sort_action($table, 'code_idx', $sort_where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// yn 함수
	function check_yn()
	{
		global $_POST, $_SESSION, $sess_str;

		$sub_action = $_POST['sub_action'];
		$code_idx   = $_POST['idx'];
		$post_value = $_POST['post_value'];
		$comp_idx   = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx   = $_POST['code_part'];

		$command    = "update"; //명령어
		$table      = "code_account_bank"; //테이블명
		$conditions = "code_idx = '" . $code_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

	// 기본값설정
		if ($sub_action == "default_yn" && $post_value == "N")
		{
			$where = " and code.comp_idx = '" . $comp_idx . "' and code.part_idx = '" . $part_idx . "' and code.default_yn = 'Y'";
			$page_data = code_account_bank_data("page", $where);
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

		$table = "code_account_bank"; //테이블명

		$code_idx = $_POST["idx"];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_POST['code_part'];

		$where = " and code.code_idx = '" . $code_idx . "'";
		$data = code_account_bank_data('view', $where);

		$sort_where = " and code.comp_idx = '" . $comp_idx . "' and code.part_idx = '" . $part_idx . "' and code.sort < '" . $data["sort"] . "'";
		$sort_order = "code.sort desc";
		$prev_data = code_account_bank_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $prev_data["sort"] . "' where code_idx = '" . $code_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where code_idx = '" . $prev_data["code_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sort_where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_sort_action($table, 'code_idx', $sort_where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

//아래 정렬 함수
	function sort_down()
	{
		global $_POST, $_SESSION, $sess_str;

		$table = "code_account_bank"; //테이블명

		$code_idx = $_POST["idx"];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_POST['code_part'];

		$where = " and code.code_idx = '" . $code_idx . "'";
		$data = code_account_bank_data('view', $where);

		$sort_where = " and code.comp_idx = '" . $comp_idx . "' and code.part_idx = '" . $part_idx . "' and code.sort > '" . $data["sort"] . "'";
		$sort_order = "code.sort asc";
		$next_data = code_account_bank_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $next_data["sort"] . "' where code_idx = '" . $code_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where code_idx = '" . $next_data["code_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sort_where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_sort_action($table, 'code_idx', $sort_where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}
?>