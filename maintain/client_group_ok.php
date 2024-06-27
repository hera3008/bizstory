<?
/*
	수정 : 2013.03.25
	위치 : 설정폴더 > 거래처관리 > 거래처분류 - 실행
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
		$chk_param['require'][] = array("field"=>"group_name", "msg"=>"거래처분류명");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		//$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$comp_idx = $param['comp_idx'];
		$part_idx = $param['part_idx'];

	// 기본값설정
		if ($param['default_yn'] == 'Y')
		{
			$where = " and ccg.default_yn='Y'" . (!$comp_idx && !$part_idx ? " and ccg.import_yn = 'Y'":"");
			$where = $where .($comp_idx ? " and ccg.comp_idx = '" . $comp_idx . "'" : "");
			$where = $where .($part_idx ? " and ccg.part_idx = '" . $part_idx . "'" : "");
			//$where = " and ccg.comp_idx = '" . $comp_idx . "' and ccg.part_idx = '" . $part_idx . "' and ccg.default_yn = 'Y'";
			$page_data = company_client_group_data("page", $where);
			if ($page_data['total_num'] > 0)
			{
				$str = '{"success_chk" : "N", "error_string" : "설정된 기본값이 있습니다."}';
				echo $str;
				exit;
			}
		}

		$command    = "insert"; //명령어
		$table      = "company_client_group"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");
		$param['comp_idx'] = $comp_idx;

		if ($param['code_bold'] == '') $param['code_bold'] = 'N';
		if ($param['view_yn'] == '') $param['view_yn'] = 'Y';
		if ($param['default_yn'] == '') $param['default_yn'] = 'N';
		if ($param['import_yn'] == '') $param['import_yn'] = 'N';

		// group_code 값 설정
		// 코드구성는 1뎁스 : 01, 2뎁스 : 0101, 3뎁스 : 010101 두자리씩 증가
		// max 값을 구해 1 증가.
		$where = !$comp_idx && !$part_idx ? " import_yn = 'Y'":"";		
		$where = $where . ($param['menu_depth'] > 1 ? " and left(group_code, ". strlen($param['group_code']) . ") = '" . $param['group_code'] ."' " : "");  //2뎁스 부터 윈쪽부터 4자리(3뎁스는 6자리) 하여 group_code 추출
		$where = $where .($comp_idx ? " and comp_idx = '" . $comp_idx . "'" : "");																//업체코드
		$where = $where .($part_idx ? " and part_idx = '" . $part_idx . "'" : "");																//업체 파트너 코드
		$last_depth = ($param['menu_depth'] > 1 ? (int)$param['menu_depth'] * 2 : 2);																//등록 뎁스에서 max 값
		$data = query_view("select MAX(left(group_code, " . $last_depth .")) as max_code from " . $table . " where " . $where);		
		if(!$data["max_code"])
		{
			//없을때
			$param["group_code"] = "01";
		}
		else if(strlen($data["max_code"]) < $last_depth)
		{
			//현 뎁스에서 하위 뎁스가 없을때 첫 하위 뎁스 입력 코드 생성
			$param["group_code"] = $param['group_code']."01";
		}
		else
		{
			//현 데스에서 +1 하여 코드 생성
			$param["group_code"] = sprintf("%0{$last_depth}d", (int)$data["max_code"] + 1);
		}

		$where = "del_yn = 'N'".(!$comp_idx && !$part_idx ? " and import_yn = 'Y'":"");
		$where = $where .($comp_idx ? "and comp_idx = '" . $comp_idx . "'" : "");
		$where = $where .($part_idx ? "and part_idx = '" . $part_idx . "'" : "");
		$data = query_view("select max(sort) as max_sort from " . $table . " where ".$where);
		$param["sort"] = ($data["max_sort"] == "") ? "1" : $data["max_sort"] + 1;

		$up_ccg_idxArr = "";
		for ($i = 1; $i < $param["menu_depth"]; $i++)
		{
			$up_ccg_idxArr .= "," . $param["menu" . $i];
			unset($param["menu" . $i]);
		}
		$param["up_ccg_idx"] = $up_ccg_idxArr;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_level_depth_action($table, "ccg_idx", "up_ccg_idx", $where);
		data_level_sort_action($table, "ccg_idx", "up_ccg_idx", $where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$ccg_idx  = $_POST['ccg_idx'];
		$param    = $_POST['param'];
		//$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$comp_idx = $param['comp_idx'];
		$part_idx = $param['part_idx'];

	// 기본값설정
		if ($param['default_yn'] == 'Y')
		{
			$where = " and ccg.default_yn = 'Y' and ccg.code_idx != '" . $ccg_idx . "'" . (!$comp_idx && !$part_idx ? " and ccg.import_yn = 'Y'":"");
			$where = $where .($comp_idx ? "and ccg.comp_idx = '" . $comp_idx . "'" : "");
			$where = $where .($part_idx ? "and ccg.part_idx = '" . $part_idx . "'" : "");
			//$where = " and ccg.comp_idx = '" . $comp_idx . "' and ccg.part_idx = '" . $part_idx . "' and ccg.default_yn = 'Y' and ccg.ccg_idx != '" . $ccg_idx . "'";
			$page_data = company_client_group_data("page", $where);
			if ($page_data['total_num'] > 0)
			{
				$str = '{"success_chk" : "N", "error_string" : "설정된 기본값이 있습니다."}';
				echo $str;
				exit;
			}
		}

	// 수정
		$command    = "update"; //명령어
		$table      = "company_client_group"; //테이블명
		$conditions = "ccg_idx = '" . $ccg_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($param['code_bold'] == '') $param['code_bold'] = 'N';
		if ($param['view_yn'] == '') $param['view_yn'] = 'N';
		if ($param['default_yn'] == '') $param['default_yn'] = 'N';
		if ($param['import_yn'] == '') $param['import_yn'] = 'N';

		$up_ccg_idxArr = "";
		for ($i = 1; $i < $param["menu_depth"]; $i++)
		{
			$up_ccg_idxArr .= "," . $param["menu" . $i];
			unset($param["menu" . $i]);
		}
		$param["up_ccg_idx"] = $up_ccg_idxArr;

	// 변경전의 데이타를 가지고 온다.
		$where = " and ccg.ccg_idx = '" . $ccg_idx . "'";
		$old_data = company_client_group_data("view", $where);

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 상위가 다를경우
		if ($old_data["up_ccg_idx"] != $param["up_ccg_idx"])
		{
			$dif_depth = $old_data["menu_depth"] - $param["menu_depth"];

		// 자기 하위것들 같이 움직이기
			$old_up_ccg_idx = $old_data["up_ccg_idx"] . "," . $old_data["ccg_idx"];
			$new_up_ccg_idx = $param["up_ccg_idx"] . "," . $ccg_idx;

			$where = " and concat(ccg.up_ccg_idx, ',') like '%" . $old_up_ccg_idx . ",%'";
			$down_list = company_client_group_data("list", $where, "", "", "");
			foreach ($down_list as $k => $down_data)
			{
				if (is_array($down_data))
				{
					$down_conditions = "ccg_idx = '" . $down_data["ccg_idx"] . "'";

					$down_param["menu_depth"] = $down_data["menu_depth"] - $dif_depth;
					$down_param["up_ccg_idx"] = $new_up_ccg_idx;
					$down_param["up_ccg_idx"] = str_replace($old_up_ccg_idx, $new_up_ccg_idx, $down_data["up_ccg_idx"]);

					$query_str = make_sql($down_param, $command, $table, $down_conditions);
					db_query($query_str);
					query_history($query_str, $table, $command);
				}
			}
		}

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		
		$where = !$comp_idx && !$part_idx ? " and import_yn = 'Y'":"";
		$where = $where .($comp_idx ? "and comp_idx = '" . $comp_idx . "'" : "");
		$where = $where .($part_idx ? "and part_idx = '" . $part_idx . "'" : "");
		//$where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";		
		data_level_depth_action($table, "ccg_idx", "up_ccg_idx", $where);
		data_level_sort_action($table, "ccg_idx", "up_ccg_idx", $where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$ccg_idx  = $_POST['idx'];
		//$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$comp_idx = $_POST['comp_idx'];
		$part_idx = $_POST['code_part'];

		$command    = "update"; //명령어
		$table      = "company_client_group"; //테이블명
		$conditions = "ccg_idx = '" . $ccg_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$where = $comp_idx && !$part_idx ? " and code.import_yn = 'Y'":"";
		$where = $where .($comp_idx ? "and comp_idx = '" . $comp_idx . "'" : "");
		$where = $where .($part_idx ? "and part_idx = '" . $part_idx . "'" : "");
		//$where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_level_depth_action($table, "ccg_idx", "up_ccg_idx", $where);
		data_level_sort_action($table, "ccg_idx", "up_ccg_idx", $where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// yn 함수
	function check_yn()
	{
		global $_POST, $_SESSION, $sess_str;

		$sub_action = $_POST['sub_action'];
		$ccg_idx    = $_POST['idx'];
		$post_value = $_POST['post_value'];
		//$comp_idx   = $_SESSION[$sess_str . '_comp_idx'];
		$comp_idx 	= $_POST['comp_idx'];
		$part_idx   = $_POST['code_part'];

		$command    = "update"; //명령어
		$table      = "company_client_group"; //테이블명
		$conditions = "ccg_idx = '" . $ccg_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

	// 기본값설정
		if ($sub_action == "default_yn" && $post_value == "N")
		{
			$where = " and ccg.default_yn = 'Y'".($comp_idx && !$part_idx ? " and ccg.import_yn = 'Y'":"");
			$where = $where .($comp_idx ? "and ccg.comp_idx = '" . $comp_idx . "'" : "");
			$where = $where .($part_idx ? "and ccg.part_idx = '" . $part_idx . "'" : "");
			//$where = " and ccg.comp_idx = '" . $comp_idx . "' and ccg.part_idx = '" . $part_idx . "' and ccg.default_yn = 'Y'";
			$page_data = company_client_group_data("page", $where);
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

		$table = "company_client_group"; //테이블명

		$ccg_idx = $_POST["idx"];
		//$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$comp_idx = $_POST['comp_idx'];
		$part_idx = $_POST['code_part'];

		$where = " and ccg.ccg_idx = '" . $ccg_idx . "'";
		$data = company_client_group_data('view', $where);

		$sort_where = " and ccg.menu_depth = '" . $data["menu_depth"] . "' and ccg.sort < '" . $data["sort"] . "'" . ($comp_idx && !$part_idx ? " and ccg.import_yn = 'Y'":"");
		$sort_where = $where .($comp_idx ? "and ccg.comp_idx = '" . $comp_idx . "'" : "");
		$sort_where = $where .($part_idx ? "and ccg.part_idx = '" . $part_idx . "'" : "");
		//$sort_where = " and ccg.comp_idx = '" . $comp_idx . "' and ccg.part_idx = '" . $part_idx . "' and ccg.menu_depth = '" . $data["menu_depth"] . "' and ccg.sort < '" . $data["sort"] . "'";
		$sort_order = "ccg.sort desc";
		$prev_data = company_client_group_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $prev_data["sort"] . "' where ccg_idx = '" . $ccg_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where ccg_idx = '" . $prev_data["ccg_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$where = $comp_idx && !$part_idx ? " and import_yn = 'Y'":"";
		$where = $where .($comp_idx ? "and comp_idx = '" . $comp_idx . "'" : "");
		$where = $where .($part_idx ? "and part_idx = '" . $part_idx . "'" : "");
		//$where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_level_depth_action($table, "ccg_idx", "up_ccg_idx", $where);
		data_level_sort_action($table, "ccg_idx", "up_ccg_idx", $where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

//아래 정렬 함수
	function sort_down()
	{
		global $_POST, $_SESSION, $sess_str;

		$table = "company_client_group"; //테이블명

		$ccg_idx = $_POST["idx"];
		//$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$comp_idx = $_POST['comp_idx'];
		$part_idx = $_POST['code_part'];

		$where = " and ccg.ccg_idx = '" . $ccg_idx . "'";
		$data = company_client_group_data('view', $where);

		$sort_where = " and ccg.menu_depth = '" . $data["menu_depth"] . "' and ccg.sort > '" . $data["sort"] . "'". ($comp_idx && !$part_idx ? " and import_yn = 'Y'":"");
		$sort_where = $where .($comp_idx ? "and ccg.comp_idx = '" . $comp_idx . "'" : "");
		$sort_where = $where .($part_idx ? "and ccg.part_idx = '" . $part_idx . "'" : "");
		//$sort_where = " and ccg.comp_idx = '" . $comp_idx . "' and ccg.part_idx = '" . $part_idx . "' and ccg.menu_depth = '" . $data["menu_depth"] . "' and ccg.sort > '" . $data["sort"] . "'";
		$sort_order = "ccg.sort asc";
		$next_data = company_client_group_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $next_data["sort"] . "' where ccg_idx = '" . $ccg_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where ccg_idx = '" . $next_data["ccg_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$where = $comp_idx && !$part_idx ? " and import_yn = 'Y'":"";
		$where = $where .($comp_idx ? "and comp_idx = '" . $comp_idx . "'" : "");
		$where = $where .($part_idx ? "and part_idx = '" . $part_idx . "'" : "");
		//$where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_level_depth_action($table, "ccg_idx", "up_ccg_idx", $where);
		data_level_sort_action($table, "ccg_idx", "up_ccg_idx", $where);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}
?>