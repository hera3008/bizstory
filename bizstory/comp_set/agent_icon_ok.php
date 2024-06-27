<?
/*
	생성 : 2012.07.03
	수정 : 2012.10.31
	위치 : 설정관리 > 에이전트관리 > 아이콘관리 - 실행
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
		$chk_param['require'][] = array("field"=>"btn_name", "msg"=>"아이콘명");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_POST['code_part'];

		$command    = "insert"; //명령어
		$table      = "agent_button"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']     = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date']   = date('Y-m-d H:i:s');
		$param['comp_idx']   = $comp_idx;
		$param['part_idx']   = $part_idx;
		$param['agent_type'] = $_POST['code_agent'];

		for ($i = 1; $i <= 4; $i++)
		{
			$param['sort']     = $i;
			$param['btn_name'] = $_POST['btn_name_' . $i];
			$param['btn_type'] = $_POST['btn_type_' . $i];
			$param['link_url'] = $_POST['link_url_' . $i];

			chk_before($param);

			$query_str = make_sql($param, $command, $table, $conditions);
			db_query($query_str);
			query_history($query_str, $table, $command);
		}

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$command    = "update"; //명령어
		$table      = "agent_button"; //테이블명

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		for ($i = 1; $i <= 4; $i++)
		{
			$abu_idx           = $_POST['abu_idx_' . $i];
			$param['btn_name'] = $_POST['btn_name_' . $i];
			$param['btn_type'] = $_POST['btn_type_' . $i];
			$param['link_url'] = $_POST['link_url_' . $i];

			$conditions = "abu_idx = '" . $abu_idx . "'"; //조건

			chk_before($param);

			$query_str = make_sql($param, $command, $table, $conditions);
			db_query($query_str);
			query_history($query_str, $table, $command);
		}

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}
?>