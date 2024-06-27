<?
/*
	생성 : 2013.01.16
	수정 : 2013.01.17
	위치 : 전문가코너 > 거래처검색관리 - 실행
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

//입력처리 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param = $_POST['param'];
		$param['comp_idx'] = $_POST['comp_idx'];
		$param['ci_idx']   = $_POST['ci_idx'];

		$command    = "insert"; //명령어
		$table      = "expert_client_search"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");

		if ($param['view_yn'] == '') $param['view_yn'] = 'N';

	// 추가필드
		$field_where = " and ecsf.view_yn = 'Y'";
		$field_list = expert_client_search_field_data("list", $field_where, '', '', '');
		foreach ($field_list as $k => $field_data)
		{
			if (is_array($field_data))
			{
				$field_name = $field_data['field_name'];
				$field_type = $field_data['field_type'];
				if ($field_type == 'checkbox')
				{
					$value_data = $_POST[$field_name];
					if (is_array($value_data)) $param[$field_name] = implode(",", $value_data);
				}
			}
		}

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

//수정처리 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param   = $_POST['param'];
		$ecs_idx = $_POST['ecs_idx'];

		$command    = "update"; //명령어
		$table      = "expert_client_search"; //테이블명
		$conditions = "ecs_idx = '" . $ecs_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($param['view_yn'] == '') $param['view_yn'] = 'N';

	// 추가필드
		$field_where = " and ecsf.view_yn = 'Y'";
		$field_list = expert_client_search_field_data("list", $field_where, '', '', '');
		foreach ($field_list as $k => $field_data)
		{
			if (is_array($field_data))
			{
				$field_name = $field_data['field_name'];
				$field_type = $field_data['field_type'];
				if ($field_type == 'checkbox')
				{
					$value_data = $_POST[$field_name];
					if (is_array($value_data)) $param[$field_name] = implode(",", $value_data);
				}
			}
		}

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// yn 함수
	function check_yn()
	{
		global $_POST, $_SESSION, $sess_str;

		$sub_action = $_POST['sub_action'];
		$post_value = $_POST['post_value'];
		$ci_idx     = $_POST['idx'];

		$chk_where = " and ecs.ci_idx = '" . $ci_idx . "'";
		$chk_data = expert_client_search_data("view", $chk_where);

		if ($chk_data['total_num'] == 0)
		{
			$client_where = " and ci.ci_idx = '" . $ci_idx . "'";
			$client_data = client_info_data("view", $client_where);

			$command    = "insert"; //명령어
			$table      = "expert_client_search"; //테이블명
			$conditions = ""; //조건

			$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
			$param['reg_date'] = date("Y-m-d H:i:s");
			$param['comp_idx'] = $client_data['comp_idx'];
			$param['ci_idx']   = $ci_idx;
		}
		else
		{
			$ecs_idx = $chk_data['ecs_idx'];

			$command    = "update"; //명령어
			$table      = "expert_client_search"; //테이블명
			$conditions = "ecs_idx = '" . $ecs_idx . "'"; //조건

			$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
			$param['mod_date'] = date("Y-m-d H:i:s");
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