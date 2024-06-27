<?
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
?>