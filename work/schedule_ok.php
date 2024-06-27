<?
/*
	생성 : 2013.01.02
	수정 : 2013.01.02
	위치 : 업무폴더 > 나의업무 > 일정 - 실행
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
		$chk_param['require'][] = array("field"=>"subject", "msg"=>"제목");
	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];

		$repeat_chk = $_POST['repeat_class_chk'];

		$command    = "insert"; //명령어
		$table      = "schedule_info"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $mem_idx;
		$param['reg_date'] = date('Y-m-d H:i:s');
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;
		$param['mem_idx']  = $mem_idx;

		if ($param['sche_type'] == '') $param['sche_type'] = 'personal';
		if ($param['open_type'] == '') $param['open_type'] = 'all';
		if ($param['notify_type'] == '') $param['notify_type'] = 'N';

		if ($repeat_chk == 'Y') // 반복일 경우
		{
			$repeat_class      = $_POST['repeat_class'];
			$repeat_start_date = $_POST['repeat_start_date'];
			$repeat_end_date   = $_POST['repeat_end_date'];
			$repeat_unlimit    = $_POST['repeat_unlimit'];
			$repeat_start_time = $_POST['repeat_start_time'];
			$repeat_end_time   = $_POST['repeat_end_time'];
			$repeat_for        = $_POST['repeat_for'];
			$repeat_all_day    = $_POST['repeat_all_day'];
			$day_repeat_num    = $_POST['day_repeat_num'];
			$week_repeat_num   = $_POST['week_repeat_num'];
			$month_repeat_num  = $_POST['month_repeat_num'];
			$repeat_week       = $_POST['repeat_week'];

		// 무한반복
			if ($repeat_unlimit == 'Y')
			{
				$param['end_date'] = '9999-12-31';
			}
			else
			{
				if ($repeat_end_date != '') $param['end_date'] = $repeat_end_date; // 종료일
				else $param['end_date'] = '9999-12-31';
			}

		// 종일일 경우
			if ($repeat_all_day == 'Y')
			{
				$param['start_time'] = '';
				$param['end_time']   = '';
			}
			else
			{
				$param['start_time'] = $repeat_start_time;
				$param['end_time']   = $repeat_end_time;
			}

		// 주기
			if ($repeat_class == 'day')
			{
				$repeat_num  = $day_repeat_num;
				$repeat_week = '';
			}
			else if ($repeat_class == 'week')
			{
				$repeat_num = $week_repeat_num;
			}
			else if ($repeat_class == 'month')
			{
				$repeat_num  = $month_repeat_num;
				$repeat_week = '';
			}
			else
			{
				$repeat_num  = '';
				$repeat_week = '';
			}

			$param['start_date']   = $repeat_start_date; // 일정-시작일
			$param['repeat_class'] = $repeat_class;      // 반복설정
			$param['repeat_num']   = $repeat_num;        // 일, 주, 월 마다
			$param['repeat_week']  = $repeat_week;       // 선택요일
			$param['repeat_for']   = $repeat_for;        // 당일, 1일뒤 등
		}
		else
		{
			$param['repeat_class'] = 'N';
			if ($param['end_date'] == '') $param['end_date'] = $param['start_date'];

			$all_day = $_POST['all_day'];

		// 종일일 경우
			if ($all_day == 'Y')
			{
				$param['start_time'] = '';
				$param['end_time']   = '';
			}
		}

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 미리알림 - 쪽지
		$notify_type = $param['notify_type'];
		$notify_time = $param['notify_time'];

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$sche_idx = $_POST['sche_idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];

		$command    = "update"; //명령어
		$table      = "schedule_info"; //테이블명
		$conditions = "sche_idx = '" . $sche_idx . "'"; //조건

		$param['mod_id']   = $mem_idx;
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($param['sche_type'] == '') $param['sche_type'] = 'personal';
		if ($param['open_type'] == '') $param['open_type'] = 'all';
		if ($param['notify_type'] == '') $param['notify_type'] = 'N';

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];
		$sche_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "schedule_info"; //테이블명
		$conditions = "sche_idx = '" . $sche_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $mem_idx;
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}
?>