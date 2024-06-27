<?
/*
	수정 : 2013.05.22
	위치 : 설정폴더(총관리자용) > 설정관리 > 메뉴관리 - 실행
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
	function chk_before($param)
	{
	//필수검사
		$chk_param['require'][] = array("field"=>"menu_depth", "msg"=>"메뉴단계");
		$chk_param['require'][] = array("field"=>"menu_name", "msg"=>"메뉴명");

	//체크합니다.
		//param_check($param, $chk_param, 'json');
	}

//등록 함수
	function post()
	{
		global $_POST, $field_str, $this_page, $sess_str, $_SESSION;
		global $auth_menu;

		$_POST["param"] = string_output($_POST["param"]);

		$param = $_POST["param"];

		$command    = "insert"; //명령어
		$table      = "menu_info"; //테이블명
		$conditions = ""; //조건

		$param["reg_id"]   = $_SESSION[$sess_str . "_mem_idx"];
		$param["reg_date"] = date("Y-m-d H:i:s");

		if ($param["tab_yn"]  == "") $param["tab_yn"]  = "N";
		if ($param["view_yn"] == "") $param["view_yn"] = "Y";

		$data = query_view("select max(mi_idx) as mi_idx from " . $table);
		$param["mi_idx"] = ($data["mi_idx"] == "") ? "1" : $data["mi_idx"] + 1;

		$param["menu_code"] = time() . $param["mi_idx"];

		$data = query_view("select max(sort) as max_sort from " . $table . " where del_yn = 'N'");
		$param["sort"] = ($data["max_sort"] == "") ? "1" : $data["max_sort"] + 1;

		$up_mi_idxArr = "";
		for ($i = 1; $i < $param["menu_depth"]; $i++)
		{
			$up_mi_idxArr .= "," . $param["menu" . $i];
			unset($param["menu" . $i]);
		}
		$param["up_mi_idx"] = $up_mi_idxArr;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " ";
		data_level_depth_action($table, "mi_idx", "up_mi_idx", $where);
		data_level_sort_action($table, "mi_idx", "up_mi_idx", $where);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

//수정 함수
	function modify()
	{
		global $_POST, $field_str, $this_page, $sess_str, $_SESSION;
		global $auth_menu;

		$_POST["param"] = string_output($_POST["param"]);

		$param  = $_POST["param"];
		$param1 = $_POST["param"];
		$mi_idx = $_POST["mi_idx"];

		$command    = "update"; //명령어
		$table      = "menu_info"; //테이블명
		$conditions = "mi_idx = '" . $mi_idx . "'"; //조건

		$param["mod_id"]   = $_SESSION[$sess_str . "_mem_idx"];
		$param["mod_date"] = date("Y-m-d H:i:s");

		if ($param["tab_yn"]      == "") $param["tab_yn"]      = "N";
		if ($param["view_yn"]     == "") $param["view_yn"]     = "Y";

		$up_mi_idxArr = "";
		for ($i = 1; $i < $param["menu_depth"]; $i++)
		{
			$up_mi_idxArr .= "," . $param["menu" . $i];
			unset($param["menu" . $i]);
		}
		$param["up_mi_idx"] = $up_mi_idxArr;

	// 변경전의 데이타를 가지고 온다.
		$where = " and mi.mi_idx = '" . $mi_idx . "'";
		$old_data = menu_info_data("view", $where);

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 상위가 다를경우
		if ($old_data["up_mi_idx"] != $param["up_mi_idx"])
		{
			$dif_depth = $old_data["menu_depth"] - $param["menu_depth"];

		// 자기 하위것들 같이 움직이기
			$old_up_mi_idx = $old_data["up_mi_idx"] . "," . $old_data["mi_idx"];
			$new_up_mi_idx = $param["up_mi_idx"] . "," . $mi_idx;

			$where = " and concat(mi.up_mi_idx, ',') like '%" . $old_up_mi_idx . ",%'";
			$down_list = menu_info_data("list", $where, "", "", "");
			foreach ($down_list as $k => $down_data)
			{
				if (is_array($down_data))
				{
					$down_conditions = "mi_idx = '" . $down_data["mi_idx"] . "'";

					$down_param["menu_depth"] = $down_data["menu_depth"] - $dif_depth;
					$down_param["up_mi_idx"] = $new_up_mi_idx;
					$down_param["up_mi_idx"] = str_replace($old_up_mi_idx, $new_up_mi_idx, $down_data["up_mi_idx"]);

					$query_str = make_sql($down_param, $command, $table, $down_conditions);
					db_query($query_str);
					query_history($query_str, $table, $command);
				}
			}
		}

		$where = " ";
		data_level_depth_action($table, "mi_idx", "up_mi_idx", $where);
		data_level_sort_action($table, "mi_idx", "up_mi_idx", $where);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$mi_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "menu_info"; //테이블명
		$conditions = "mi_idx = '" . $mi_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " ";
		data_level_depth_action($table, "mi_idx", "up_mi_idx", $where);
		data_level_sort_action($table, "mi_idx", "up_mi_idx", $where);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

//위 정렬 함수
	function sort_up()
	{
		global $_POST, $_SESSION, $sess_str;

		$table = "menu_info"; //테이블명

		$mi_idx = $_POST["idx"];

		$where = " and mi.mi_idx = '" . $mi_idx . "'";
		$data = menu_info_data('view', $where);

		$sort_where = " and mi.menu_depth = '" . $data["menu_depth"] . "' and mi.sort < '" . $data["sort"] . "'";
		$sort_order = "mi.sort desc";
		$prev_data = menu_info_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $prev_data["sort"] . "' where mi_idx = '" . $mi_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where mi_idx = '" . $prev_data["mi_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$where = "";
		data_level_depth_action($table, "mi_idx", "up_mi_idx", $where);
		data_level_sort_action($table, "mi_idx", "up_mi_idx", $where);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

//아래 정렬 함수
	function sort_down()
	{
		global $_POST, $_SESSION, $sess_str;

		$table = "menu_info"; //테이블명

		$mi_idx = $_POST["idx"];

		$where = " and mi.mi_idx = '" . $mi_idx . "'";
		$data = menu_info_data('view', $where);

		$sort_where = " and mi.menu_depth = '" . $data["menu_depth"] . "' and mi.sort > '" . $data["sort"] . "'";
		$sort_order = "mi.sort asc";
		$next_data = menu_info_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $next_data["sort"] . "' where mi_idx = '" . $mi_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where mi_idx = '" . $next_data["mi_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$where = "";
		data_level_depth_action($table, "mi_idx", "up_mi_idx", $where);
		data_level_sort_action($table, "mi_idx", "up_mi_idx", $where);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// yn 함수
	function check_yn()
	{
		global $_POST, $_SESSION, $sess_str;

		$sub_action = $_POST['sub_action'];
		$post_value = $_POST['post_value'];
		$mi_idx    = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "menu_info"; //테이블명
		$conditions = "mi_idx = '" . $mi_idx . "'"; //조건

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
?>