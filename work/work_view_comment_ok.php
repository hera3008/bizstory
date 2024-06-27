<?
/*
	생성 : 2012.05.07
	수정 : 2013.02.22
	위치 : 업무폴더 > 나의업무 > 업무 - 보기 - 댓글 - 실행
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
		$chk_param['require'][] = array("field"=>"remark", "msg"=>"댓글내용");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$wi_idx   = $_POST['wi_idx'];

		$command    = "insert"; //명령어
		$table      = "work_comment"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date('Y-m-d H:i:s');
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;
		$param['wi_idx']   = $wi_idx;
		$param['ip_addr']  = $ip_address;
		$param['mem_idx']  = $_SESSION[$sess_str . '_mem_idx'];

		$chk_data = query_view("select max(wc_idx) as wc_idx, max(order_idx) as order_idx from " . $table);
		$param['wc_idx'] = ($chk_data['wc_idx'] == '') ? '1' : $chk_data['wc_idx'] + 1;
		$param['order_idx'] = ($chk_data['order_idx'] == '') ? '1' : $chk_data['order_idx'] + 1;

	// 회원정보
		$sub_where = " and mem.mem_idx = '" . $_SESSION[$sess_str . '_mem_idx'] . "'";
		$sub_data = member_info_data('view', $sub_where);
		if ($param['writer'] == "") $param['writer'] = $sub_data['mem_name'];

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$work_where = " and wi.wi_idx = '" . $wi_idx . "'";
		$work_data = work_info_data('view', $work_where);

		$hi_command    = "insert"; //명령어
		$hi_table      = "work_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['wi_idx']      = $wi_idx;
		$hi_param['status']      = '';
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '댓글이 등록되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 알림건
		$mem_idx = $_SESSION[$sess_str . '_mem_idx'];
		charge_push_send($mem_idx, $work_data['charge_idx'], $work_data['apply_idx'], 'work_comment', $work_data['subject'], '', '', $work_data['reg_id']);

	// 총수구하기
		$comment_where = " and wc.wi_idx = '" . $wi_idx . "'";
		$comment_list = work_comment_data('list', $comment_where, '', '', '');

		$str = '{"success_chk" : "Y", "total_num":"[' . number_format($comment_list['total_num']) . ']"}';
		echo $str;
		exit;
	}

// 댓글서 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$wi_idx   = $_POST['wi_idx'];
		$wc_idx   = $_POST['wc_idx'];

		$command    = "update"; //명령어
		$table      = "work_comment"; //테이블명
		$conditions = "wc_idx='" . $wc_idx . "'"; //조건

		$param["mod_id"]   = $_SESSION[$sess_str . '_mem_idx'];
		$param["mod_date"] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	// 총수구하기
		$comment_where = " and wc.wi_idx = '" . $wi_idx . "'";
		$comment_list = work_comment_data('list', $comment_where, '', '', '');

		$str = '{"success_chk" : "Y", "total_num":"[' . number_format($comment_list['total_num']) . ']"}';
		echo $str;
		exit;
	}

// 댓글서 삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$wi_idx = $_POST['wi_idx'];
		$wc_idx = $_POST['wc_idx'];

		$command    = "update"; //명령어
		$table      = "work_comment"; //테이블명
		$conditions = "wc_idx = '" . $wc_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	// 총수구하기
		$comment_where = " and wc.wi_idx = '" . $wi_idx . "'";
		$comment_list = work_comment_data('list', $comment_where, '', '', '');

		$str = '{"success_chk" : "Y", "total_num":"[' . number_format($comment_list['total_num']) . ']"}';
		echo $str;
		exit;
	}
?>