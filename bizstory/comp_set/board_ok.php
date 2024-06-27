<?
/*
	수정 : 2012.05.15
	위치 : 설정폴더 > 컨텐츠관리 > 게시판관리 - 실행
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
		$chk_param['require'][] = array("field"=>"subject", "msg"=>"게시판제목");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

	//입력처리 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $param['part_idx'];

		$command    = "insert"; //명령어
		$table      = "board_set"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");
		$param['comp_idx'] = $comp_idx;

	// 직원그룹권한
		if (is_array($_POST['gauth_w'])) $param['gauth_w'] = implode(",", $_POST['gauth_w']);
		if (is_array($_POST['gauth_r'])) $param['gauth_r'] = implode(",", $_POST['gauth_r']);
		if (is_array($_POST['gauth_d'])) $param['gauth_d'] = implode(",", $_POST['gauth_d']);
		if (is_array($_POST['gauth_reply_w'])) $param['gauth_reply_w'] = implode(",", $_POST['gauth_reply_w']);
		if (is_array($_POST['gauth_reply_r'])) $param['gauth_reply_r'] = implode(",", $_POST['gauth_reply_r']);
		if (is_array($_POST['gauth_reply_d'])) $param['gauth_reply_d'] = implode(",", $_POST['gauth_reply_d']);
		if (is_array($_POST['gauth_comment_w'])) $param['gauth_comment_w'] = implode(",", $_POST['gauth_comment_w']);
		if (is_array($_POST['gauth_comment_r'])) $param['gauth_comment_r'] = implode(",", $_POST['gauth_comment_r']);
		if (is_array($_POST['gauth_comment_d'])) $param['gauth_comment_d'] = implode(",", $_POST['gauth_comment_d']);

		chk_before($param);

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
		global $_POST, $_SESSION, $field_str, $this_page, $site_info, $sess_str;

		$param  = $_POST['param'];
		$bs_idx = $_POST['bs_idx'];

		chk_before($param);

		$command    = "update"; //명령어
		$table      = "board_set"; //테이블명
		$conditions = "bs_idx = '" . $bs_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

	// 직원그룹권한
		if (is_array($_POST['gauth_w'])) $param['gauth_w'] = implode(",", $_POST['gauth_w']);
		else $param['gauth_w'] = '';
		if (is_array($_POST['gauth_r'])) $param['gauth_r'] = implode(",", $_POST['gauth_r']);
		else $param['gauth_r'] = '';
		if (is_array($_POST['gauth_d'])) $param['gauth_d'] = implode(",", $_POST['gauth_d']);
		else $param['gauth_d'] = '';
		if (is_array($_POST['gauth_reply_w'])) $param['gauth_reply_w'] = implode(",", $_POST['gauth_reply_w']);
		else $param['gauth_reply_w'] = '';
		if (is_array($_POST['gauth_reply_r'])) $param['gauth_reply_r'] = implode(",", $_POST['gauth_reply_r']);
		else $param['gauth_reply_r'] = '';
		if (is_array($_POST['gauth_reply_d'])) $param['gauth_reply_d'] = implode(",", $_POST['gauth_reply_d']);
		else $param['gauth_reply_d'] = '';
		if (is_array($_POST['gauth_comment_w'])) $param['gauth_comment_w'] = implode(",", $_POST['gauth_comment_w']);
		else $param['gauth_comment_w'] = '';
		if (is_array($_POST['gauth_comment_r'])) $param['gauth_comment_r'] = implode(",", $_POST['gauth_comment_r']);
		else $param['gauth_comment_r'] = '';
		if (is_array($_POST['gauth_comment_d'])) $param['gauth_comment_d'] = implode(",", $_POST['gauth_comment_d']);
		else $param['gauth_comment_d'] = '';

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

	//삭제처리 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$bs_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "board_set"; //테이블명
		$conditions = "bs_idx='" . $bs_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}
?>