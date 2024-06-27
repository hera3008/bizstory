<?
/*
	생성 : 2012.10.12
	위치 : 접수실행
*/
	require_once "../common/setting.php";
	require_once $local_path . "/bizstory/mobile/process/mobile_setting.php";
	require_once $mobile_path . "/process/member_chk.php";
	require_once $mobile_path . "/process/no_direct.php";

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

		$comp_idx   = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx   = $_SESSION[$sess_str . '_part_idx'];
		$mem_idx    = $_SESSION[$sess_str . '_mem_idx'];

		$command    = "update"; //명령어
		$table      = "push_member"; //테이블명
		$conditions = "comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "' and mem_idx = '" . $mem_idx . "'"; //조건

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
?>