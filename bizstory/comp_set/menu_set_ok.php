<?
/*
	수정 : 2013.03.22
	위치 : 설정관리 > 코드관리 > 메뉴명관리 - 실행
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

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$menu_total = $_POST['menu_total'];

		for ($i = 1; $i < $menu_total; $i++)
		{
			$mi_idx    = $_POST['chk_mi_idx_' . $i];
			$mc_idx    = $_POST['chk_mc_idx_' . $i];
			$menu_name = $_POST['chk_menu_name_' . $i];

			$param['menu_name'] = $menu_name;

			if ($mc_idx == '')
			{
				$command    = "insert"; //명령어
				$table      = "menu_company"; //테이블명
				$conditions = ""; //조건

				$param['comp_idx'] = $_SESSION[$sess_str . '_comp_idx'];
				$param['part_idx'] = $_POST['code_part'];
				$param['mi_idx']   = $mi_idx;
				$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
				$param['reg_date'] = date("Y-m-d H:i:s");

			// 등록시 기본값 구하기
				$cmenu_where = " and mac.comp_idx = '" . $param['comp_idx'] . "' and mac.mi_idx = '" . $param['mi_idx'] . "' and mac.view_yn = 'Y'";
				$cmenu_data = menu_auth_company_data('view', $cmenu_where);
				$param['default_yn'] = $cmenu_data['default_yn'];
				$param['sort']       = $menu_data['sort']; // 정렬

				unset($cmenu_data);
			}
			else
			{
				$command    = "update"; //명령어
				$table      = "menu_company"; //테이블명
				$conditions = "mc_idx = '" . $mc_idx . "'"; //조건

				$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
				$param['mod_date'] = date("Y-m-d H:i:s");
			}

			$query_str = make_sql($param, $command, $table, $conditions);
			db_query($query_str);
			query_history($query_str, $table, $command);

			unset($param);
		}

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
		$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
		$code_part  = $_POST['code_part'];
		$mi_idx     = $_POST['idx'];

	// 값여부 확인
		$chk_where = " and mc.comp_idx = '" . $code_comp . "' and mc.part_idx = '" . $code_part . "' and mc.mi_idx = '" . $mi_idx . "'";
		$chk_page = menu_company_data('page', $chk_where);

		if ($chk_page['total_num'] == 0)
		{
			$command    = "insert"; //명령어
			$table      = "menu_company"; //테이블명
			$conditions = ""; //조건

			$param['comp_idx'] = $code_comp;
			$param['part_idx'] = $code_part;
			$param['mi_idx']   = $mi_idx;
			$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
			$param['reg_date'] = date("Y-m-d H:i:s");
		}
		else
		{
			$command    = "update"; //명령어
			$table      = "menu_company"; //테이블명
			$conditions = "comp_idx = '" . $code_comp . "' and part_idx = '" . $code_part . "' and mi_idx = '" . $mi_idx . "'"; //조건

			$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
			$param['mod_date'] = date("Y-m-d H:i:s");
		}

		if ($post_value == "Y") $param[$sub_action] = "N";
		else $param[$sub_action] = "Y";

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 위 정렬 함수
	function sort_up()
	{
		global $_POST, $_SESSION, $sess_str;

		$table = "menu_company"; //테이블명

		$mc_idx   = $_POST["idx"];
		$comp_idx = $_POST["chk_comp_idx"];
		$part_idx = $_POST["chk_part_idx"];
        
        $code_comp  = $_SESSION[$sess_str . '_comp_idx'];
        $code_part  = $_POST['code_part'];
        $mi_idx   = $_POST["mi_idx"];
        
    // 값여부 확인
        if ($code_comp != '' && $code_part != '') {
            $chk_where = " and mc.comp_idx = '" . $code_comp . "' and mc.part_idx = '" . $code_part . "' and mc.mi_idx = '" . $mi_idx . "'";
            $chk_page = menu_company_data('page', $chk_where);
    
            if ($chk_page['total_num'] == 0) {
                $command    = "insert"; //명령어
                $table      = "menu_company"; //테이블명
                $conditions = ""; //조건
    
                $param['comp_idx'] = $code_comp;
                $param['part_idx'] = $code_part;
                $param['mi_idx']   = $mi_idx;
                $param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
                $param['reg_date'] = date("Y-m-d H:i:s");
                
                $query_str = make_sql($param, $command, $table, $conditions);
                db_query($query_str);
                query_history($query_str, $table, $command);
                
                $mc_idx = mysql_insert_id();
            }
        }
        
		$where = " and mc.mc_idx = '" . $mc_idx . "'";
		$data = menu_company_data('view', $where);

		$sort_where = " and mc.comp_idx = '" . $comp_idx . "' and mc.part_idx = '" . $part_idx . "' and mi.menu_depth = '" . $data["menu_depth"] . "' and mc.sort < '" . $data["sort"] . "'";
		$sort_order = "mc.sort desc";
		$prev_data = menu_company_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $prev_data["sort"] . "' where mc_idx = '" . $mc_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where mc_idx = '" . $prev_data["mc_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		menu_level_sort($comp_idx, $part_idx);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 아래 정렬 함수
	function sort_down()
	{
		global $_POST, $_SESSION, $sess_str;

		$table = "menu_company"; //테이블명

		$mc_idx   = $_POST["idx"];
		$comp_idx = $_POST["chk_comp_idx"];
		$part_idx = $_POST["chk_part_idx"];

      
        $code_comp  = $_SESSION[$sess_str . '_comp_idx'];
        $code_part  = $_POST['code_part'];
        $mi_idx   = $_POST["mi_idx"];
        
    // 값여부 확인
        if ($code_comp != '' && $code_part != '') {
            $chk_where = " and mc.comp_idx = '" . $code_comp . "' and mc.part_idx = '" . $code_part . "' and mc.mi_idx = '" . $mi_idx . "'";
            $chk_page = menu_company_data('page', $chk_where);
    
            if ($chk_page['total_num'] == 0) {
                $command    = "insert"; //명령어
                $table      = "menu_company"; //테이블명
                $conditions = ""; //조건
    
                $param['comp_idx'] = $code_comp;
                $param['part_idx'] = $code_part;
                $param['mi_idx']   = $mi_idx;
                $param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
                $param['reg_date'] = date("Y-m-d H:i:s");
                
                $query_str = make_sql($param, $command, $table, $conditions);
                db_query($query_str);
                query_history($query_str, $table, $command);
                
                $mc_idx = mysql_insert_id();
            }
        }

		$where = " and mc.mc_idx = '" . $mc_idx . "'";
		$data = menu_company_data('view', $where);

		$sort_where = " and mc.comp_idx = '" . $comp_idx . "' and mc.part_idx = '" . $part_idx . "' and mi.menu_depth = '" . $data["menu_depth"] . "' and mc.sort > '" . $data["sort"] . "'";
		$sort_order = "mc.sort asc";
		$next_data = menu_company_data('view', $sort_where, $sort_order);

		$sql = "update " . $table . " set sort = '" . $next_data["sort"] . "' where mc_idx = '" . $mc_idx . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		$sql = "update " . $table . " set sort = '" . $data["sort"] . "' where mc_idx = '" . $next_data["mc_idx"] . "'";
		db_query($sql);
		query_history($sql, $table, "update");

		menu_level_sort($comp_idx, $part_idx);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}
?>