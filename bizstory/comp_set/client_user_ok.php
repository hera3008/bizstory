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

//기초값 검사
	function chk_before($param, $chk_type = 'json')
	{
	//필수검사
		$chk_param['require'][] = array("field"=>"mem_id", "msg"=>"아이디");
		$chk_param['require'][] = array("field"=>"mem_pwd", "msg"=>"비밀번호");

	//중복검사
		$chk_param['unique'][] = array("table"=>"client_user", "field"=>"mem_id", "where"=>"del_yn = 'N'", "msg"=>"이미 사용된 아이디입니다.<br />");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$ci_idx   = $_POST['ci_idx'];

	// 등록
		$command    = "insert"; //명령어
		$table      = "client_user"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");
		$param['comp_idx'] = $comp_idx;
		$param['ci_idx']   = $ci_idx;
		$param['mem_pwd']  = pass_change($param['mem_pwd'], $sess_str);
		$param['mem_email'] = $param['mem_email1'] . '@' . $param['mem_email2'];
		unset($param['mem_email1']);
		unset($param['mem_email2']);

		if ($param['login_yn'] == '') $param['login_yn'] = 'Y';

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$cu_idx   = $_POST['cu_idx'];
		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];

	// 수정
		$command    = "update"; //명령어
		$table      = "client_user"; //테이블명
		$conditions = "cu_idx = '" . $cu_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($param['login_yn'] == '') $param['login_yn'] = 'N';
		$param['mem_email'] = $param['mem_email1'] . '@' . $param['mem_email2'];
		unset($param['mem_email1']);
		unset($param['mem_email2']);
		if ($param['mem_pwd'] == '') unset($param['mem_pwd']);
		else $param['mem_pwd'] = pass_change($param['mem_pwd'], $sess_str);

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$cu_idx   = $_POST['cu_idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];

		$command    = "update"; //명령어
		$table      = "client_user"; //테이블명
		$conditions = "cu_idx = '" . $cu_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

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
		$cu_idx     = $_POST['cu_idx'];
		$post_value = $_POST['post_value'];
		$comp_idx   = $_SESSION[$sess_str . '_comp_idx'];

		$command    = "update"; //명령어
		$table      = "client_user"; //테이블명
		$conditions = "cu_idx = '" . $cu_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($post_value == "Y") $param[$sub_action] = "N";
		else $param[$sub_action] = "Y";

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// 아이디중복 함수d
	function double_id()
	{
		global $_GET;

		$mem_id = $_GET['mem_id'];
		$mem_id = string_input($mem_id);

		if ($mem_id == '')
		{
			$str = '{"success_chk" : "N", "error_string"  : "아이디를 입력하세요."}';
		}
		else
		{
            $mem_where = " and cu.mem_id = '" . $mem_id . "'";
            $mem_data = client_user_data('page', $mem_where);

			if ($mem_data['total_num'] == 0)
			{
				$str = '{"success_chk" : "Y", "double_chk"  : "N"}';
			}
			else
			{
				$str = '{"success_chk" : "Y", "double_chk"  : "Y"}';
			}
		}
		echo $str;
		exit;
	}
?>