<?
	include "../common/setting.php";
	include "../common/no_direct.php";
	include "../common/member_chk.php";

	if($sub_type == "")
	{
		$str = '
		{
			  "success_chk"   : "N"
			, "error_string" : "sub_type 명이 필요합니다."
		}';
		echo $str;
		exit;
	}

	if(!function_exists($sub_type))
	{
		$str = '
		{
			  "success_chk"   : "N"
			, "error_string" : "sub_type method 가 없습니다."
		}';
		echo $str;
		exit;
	}
	call_user_func($sub_type);
	exit;

//기초값 검사
	function chk_before($param)
	{
	//필수검사
		$chk_param['require'][] = array("field"=>"subject", "msg"=>"서비스명");

	//체크합니다.
		//param_check($param, $chk_param, 'json');
	}

	$field_str = str_replace("|", "&", $field_str);

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $field_str, $this_page, $site_info, $sess_str;

		$param = $_POST['param'];

	// 기본값설정
		if ($param['default_yn'] == 'Y')
		{
			$where = " and si.default_yn = 'Y'";
			$data = service_info_data("page", $where);
			if ($data['total_num'] > 0)
			{
				$str = '
				{
					"success_chk" : "N"
					, "error_string" : "설정된 기본값이 있습니다."
				}';
				echo $str;
				exit;
			}
		}

		$command    = "insert"; //명령어
		$table      = "service_info"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");

		$data = query_view("select max(sort) as max_sort from " . $table . " where del_yn = 'N'");
		$param["sort"] = ($data["max_sort"] == "") ? "1" : $data["max_sort"] + 1;

		if ($param["use_price"] == '') $param["use_price"] = 0;
		else $param["use_price"] = str_replace(',', '', $param["use_price"]);

		if ($param["part_cnt"] == '') $param["part_cnt"] = 0;
		else $param["part_cnt"] = str_replace(',', '', $param["part_cnt"]);

		if ($param["client_cnt"] == '') $param["client_cnt"] = 0;
		else $param["client_cnt"] = str_replace(',', '', $param["client_cnt"]);

		if ($param["banner_cnt"] == '') $param["banner_cnt"] = 0;
		else $param["banner_cnt"] = str_replace(',', '', $param["banner_cnt"]);

		if ($param["sms_cnt"] == '') $param["sms_cnt"] = 0;
		else $param["sms_cnt"] = str_replace(',', '', $param["sms_cnt"]);

		if ($param["group_cnt"] == '') $param["group_cnt"] = 0;
		else $param["group_cnt"] = str_replace(',', '', $param["group_cnt"]);

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		data_sort_action($table, 'si_idx');

		$str = '{"success_chk" : "Y"}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $field_str, $this_page, $site_info, $sess_str;

		$si_idx = $_POST['si_idx'];
		$param  = $_POST['param'];

	// 기본값설정
		if ($param['default_yn'] == 'Y')
		{
			$where = " and si.default_yn = 'Y' and si.si_idx != '" . $si_idx . "'";
			$data = service_info_data("page", $where);
			if ($data['total_num'] > 0)
			{
				$str = '
				{
					"success_chk" : "N"
					, "error_string" : "설정된 기본값이 있습니다."
				}';
				echo $str;
				exit;
			}
		}

		$command    = "update"; //명령어
		$table      = "service_info"; //테이블명
		$conditions = "si_idx = '" . $si_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($param["use_price"] == '') $param["use_price"] = 0;
		else $param["use_price"] = str_replace(',', '', $param["use_price"]);

		if ($param["part_cnt"] == '') $param["part_cnt"] = 0;
		else $param["part_cnt"] = str_replace(',', '', $param["part_cnt"]);

		if ($param["client_cnt"] == '') $param["client_cnt"] = 0;
		else $param["client_cnt"] = str_replace(',', '', $param["client_cnt"]);

		if ($param["banner_cnt"] == '') $param["banner_cnt"] = 0;
		else $param["banner_cnt"] = str_replace(',', '', $param["banner_cnt"]);

		if ($param["sms_cnt"] == '') $param["sms_cnt"] = 0;
		else $param["sms_cnt"] = str_replace(',', '', $param["sms_cnt"]);

		if ($param["group_cnt"] == '') $param["group_cnt"] = 0;
		else $param["group_cnt"] = str_replace(',', '', $param["group_cnt"]);

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		data_sort_action($table, 'si_idx');

		$str = '{"success_chk" : "Y"}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $field_str, $this_page, $site_info, $sess_str;
		global $ip_address;

		$si_idx = $_POST['si_idx'];

		$command    = "update"; //명령어
		$table      = "service_info"; //테이블명
		$conditions = "si_idx = '" . $si_idx . "'"; //조건
		
		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		data_sort_action($table, 'si_idx');

		$str = '{"success_chk" : "Y"}';
		echo $str;
		exit;
	}

// yn 함수
	function check_yn()
	{
		global $_POST, $_SESSION, $field_str, $this_page, $site_info, $sess_str;

		$sub_action = $_POST['sub_action'];
		$si_idx     = $_POST['si_idx'];
		$post_value = $_POST['post_value'];

		$command    = "update"; //명령어
		$table      = "service_info"; //테이블명
		$conditions = "si_idx = '" . $si_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

	// 기본값설정
		if ($sub_action == "default_yn" && $post_value == "N")
		{
			$where = " and si.default_yn = 'Y'";
			$data = service_info_data("page", $where);
			if ($data['total_num'] > 0)
			{
				$str = '
				{
					"success_chk" : "N"
					, "error_string" : "설정된 기본값이 있습니다."
				}';
				echo $str;
				exit;
			}
		}

		if ($post_value == "Y") $param[$sub_action] = "N";
		else $param[$sub_action] = "Y";

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y"}';
		echo $str;
		exit;
	}

//위 정렬 함수
	function sort_up()
	{
		global $_POST, $_SESSION, $field_str, $this_page, $site_info, $sess_str;

		$table = "service_info"; //테이블명

		$si_idx = $_POST["si_idx"];

		$where = " and si.si_idx = '" . $si_idx . "'";
		$data = service_info_data("view", $where);

		$sort_where = " and si.sort < '" . $data["sort"] . "'";
		$sort_order = "si.sort desc";
		$prev_data = service_info_data("view", $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $prev_data["sort"] . "' where si_idx = '" . $si_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where si_idx = '" . $prev_data["si_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		data_sort_action($table, 'si_idx');

		$str = '{"success_chk" : "Y"}';
		echo $str;
		exit;
	}

//아래 정렬 함수
	function sort_down()
	{
		global $_POST, $_SESSION, $field_str, $this_page, $site_info, $sess_str;

		$table = "service_info"; //테이블명

		$si_idx = $_POST["si_idx"];

		$where = " and si.si_idx = '" . $si_idx . "'";
		$data = service_info_data("view", $where);

		$sort_where = " and si.sort > '" . $data["sort"] . "'";
		$sort_order = "si.sort asc";
		$next_data = service_info_data("view", $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $next_data["sort"] . "' where si_idx = '" . $si_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where si_idx = '" . $next_data["si_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		data_sort_action($table, 'si_idx');

		$str = '{"success_chk" : "Y"}';
		echo $str;
		exit;
	}
?>