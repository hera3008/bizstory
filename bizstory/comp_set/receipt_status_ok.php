<?
/*
	수정 : 2013.03.27
	위치 : 설정관리 > 접수관리 > 접수상태 - 실행
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
		$chk_param['require'][] = array("field"=>"code_name", "msg"=>"접수상태명");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
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
			$page_data = code_receipt_status_data("page", $where);
			if ($page_data['total_num'] > 0)
			{
				$str = '{"success_chk" : "N", "error_string" : "설정된 기본값이 있습니다."}';
				echo $str;
				exit;
			}
		}

		$command    = "update"; //명령어
		$table      = "code_receipt_status"; //테이블명
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

		$where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "'";
		data_sort_action($table, 'code_idx', $where);

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
		$table      = "code_receipt_status"; //테이블명
		$conditions = "code_idx = '" . $code_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

	// 기본값설정
		if ($sub_action == "default_yn" && $post_value == "N")
		{
			$where = " and code.comp_idx = '" . $comp_idx . "' and code.part_idx = '" . $part_idx . "' and code.default_yn = 'Y'";
			$page_data = code_receipt_status_data("page", $where);
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