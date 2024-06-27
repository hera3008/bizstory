<?
/*
	생성 : 2012.04.25
	수정 : 2012.06.19
	위치 : 업무폴더 > 나의업무 > 업무 - 상태실행
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
		$chk_param['require'][] = array("field"=>"deadline_date", "msg"=>"기한");
		$chk_param['require'][] = array("field"=>"charge_idx", "msg"=>"담당자");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 대기 -> 업무진행으로 변경
	function status02()
	{
		global $_POST, $_SESSION, $sess_str;

		$wi_idx   = $_POST['wi_idx'];
		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];

		$charge_idx     = $_POST['status_charge_idx'];
		$deadline_date1 = $_POST['status_deadline_date1'];
		$deadline_date2 = $_POST['status_deadline_date2'];
		$deadline_str1  = $_POST['status_deadline_str1'];
		$deadline_str2  = $_POST['status_deadline_str2'];

		$command    = "update"; //명령어
		$table      = "work_info"; //테이블명
		$conditions = "wi_idx = '" . $wi_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

	// 기한
		$deadline_date1 = $_POST['deadline_date1'];
		if ($deadline_date1 != '')
		{
			$deadline_date2 = $_POST['deadline_date2'];
			if ($deadline_date1 == 'select')
			{
				$param['deadline_date'] = $deadline_date2;
			}
			else if ($deadline_date1 == '-')
			{
				$param['deadline_date'] = '';
			}
			else
			{
				$param['deadline_date'] = $deadline_date1;
			}
		// 기한 - 덧붙이기
			$deadline_str1 = $_POST['deadline_str1'];
			$deadline_str2 = $_POST['deadline_str2'];
			if ($deadline_str1 == 'select')
			{
				$param['deadline_str'] = $deadline_str2;
			}
			else if ($deadline_str1 == '-')
			{
				$param['deadline_str'] = '';
			}
			else
			{
				$param['deadline_str'] = $deadline_str1;
			}
		}
		$param["work_status"] = 'WS02'; // 업무진행

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "work_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['wi_idx']      = $wi_idx;
		$hi_param['status']      = $param["work_status"];
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '업무가 진행되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 업무완료로 변경
	function status90()
	{
		global $_POST, $_SESSION, $sess_str;

		$wi_idx   = $_POST['wi_idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];

		$command    = "update"; //명령어
		$table      = "work_info"; //테이블명
		$conditions = "wi_idx = '" . $wi_idx . "'"; //조건

		$param['mod_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date']    = date("Y-m-d H:i:s");
		$param["work_status"] = 'WS90'; // 업무완료
		$param["end_date"]    = date("Y-m-d H:i:s"); // 업무완료

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "work_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['wi_idx']      = $wi_idx;
		$hi_param['status']      = $param["work_status"];
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '업무가 완료되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 업무반려로 변경
	function status70()
	{
		global $_POST, $_SESSION, $sess_str;

		$wi_idx   = $_POST['wi_idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$contents = $_POST['status_contents'];

		$command    = "update"; //명령어
		$table      = "work_info"; //테이블명
		$conditions = "wi_idx = '" . $wi_idx . "'"; //조건

		$param['mod_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date']    = date("Y-m-d H:i:s");
		$param["work_status"] = 'WS70'; // 업무반려

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "work_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['wi_idx']      = $wi_idx;
		$hi_param['status']      = $param["work_status"];
		$hi_param["contents"]    = $contents; // 반려사유
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '업무가 반려되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 업무보류로 변경
	function status80()
	{
		global $_POST, $_SESSION, $sess_str;

		$wi_idx   = $_POST['wi_idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$contents = $_POST['status_contents'];

		$command    = "update"; //명령어
		$table      = "work_info"; //테이블명
		$conditions = "wi_idx = '" . $wi_idx . "'"; //조건

		$param['mod_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date']    = date("Y-m-d H:i:s");
		$param["work_status"] = 'WS80'; // 업무보류

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리수정
		$uhi_command    = "update"; //명령어
		$uhi_table      = "work_status_history"; //테이블명
		$uhi_conditions = "wi_idx = '" . $wi_idx . "' and (status = 'WS20' or status = 'WS30') and status_type = 'new'"; //조건 - 요청, 승인

		$uhi_param['mod_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$uhi_param['mod_date']    = date('Y-m-d H:i:s');
		$uhi_param['status_type'] = 'old';

		$query_str = make_sql($uhi_param, $uhi_command, $uhi_table, $uhi_conditions);
		db_query($query_str);
		query_history($query_str, $uhi_table, $uhi_command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "work_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['wi_idx']      = $wi_idx;
		$hi_param['status']      = $param["work_status"];
		$hi_param["contents"]    = $contents; // 업무보류
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '업무가 보류되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 업무보류에서 업무진행으로 변경
	function status80_02()
	{
		global $_POST, $_SESSION, $sess_str;

		$wi_idx   = $_POST['wi_idx'];
		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];

		$charge_idx     = $_POST['status_charge_idx'];
		$deadline_date1 = $_POST['status_deadline_date1'];
		$deadline_date2 = $_POST['status_deadline_date2'];
		$deadline_str1  = $_POST['status_deadline_str1'];
		$deadline_str2  = $_POST['status_deadline_str2'];

		$command    = "update"; //명령어
		$table      = "work_info"; //테이블명
		$conditions = "wi_idx = '" . $wi_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

	// 기한
		$deadline_date1 = $_POST['deadline_date1'];
		$deadline_date2 = $_POST['deadline_date2'];
		if ($deadline_date1 == 'select')
		{
			$param['deadline_date'] = $deadline_date2;
		}
		else if ($deadline_date1 == '-')
		{
			$param['deadline_date'] = '';
		}
		else
		{
			$param['deadline_date'] = $deadline_date1;
		}
	// 기한 - 덧붙이기
		$deadline_str1 = $_POST['deadline_str1'];
		$deadline_str2 = $_POST['deadline_str2'];
		if ($deadline_str1 == 'select')
		{
			$param['deadline_str'] = $deadline_str2;
		}
		else if ($deadline_str1 == '-')
		{
			$param['deadline_str'] = '';
		}
		else
		{
			$param['deadline_str'] = $deadline_str1;
		}
		$param["work_status"] = 'WS02'; // 업무진행

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "work_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['wi_idx']      = $wi_idx;
		$hi_param['status']      = $param["work_status"];
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '업무가 진행되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 업무진행에서 승인요청일 경우
	function status20()
	{
		global $_POST, $_SESSION, $sess_str;

		$wi_idx   = $_POST['wi_idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];

	// 담당자 수만큼 승인요청확인
		$chk_where = " and wi.wi_idx = '" . $wi_idx . "'";
		$chk_data = work_info_data('view', $chk_where);
		$charge_idx = $chk_data['charge_idx'];

		$total_charge = 0; $request_charge = 0;
		if ($charge_idx != '')
		{
			$charge_arr = explode(',', $charge_idx);
			foreach ($charge_arr as $charge_k => $charge_v)
			{
				if ($charge_v != '')
				{
					$mem_where = " and mem.comp_idx = '" . $comp_idx . "' and mem.mem_idx = '" . $charge_v . "'";
					$mem_data = member_info_data('view', $mem_where, '', '', '');
					if ($mem_data['total_num'] == 0)
					{
						$request_charge++;
					}
					else
					{
						$status_where = " and wsh.wi_idx = '" . $wi_idx . "' and wsh.status = 'WS20' and wsh.mem_idx = '" . $charge_v . "' and wsh.status_type = 'new'";
						$status_data = work_status_history_data('page', $status_where);

						if ($status_data['total_num'] > 0)
						{
							$request_charge++;
						}
					}
					if ($charge_v == $mem_idx)
					{
						$request_charge++;
					}
					$total_charge++;
				}
			}
		}

		if ($total_charge == $request_charge)
		{
			$param["work_status"] = 'WS20'; // 승인대기
		}
		else
		{
			$param["work_status"] = 'WS02'; // 진행중
		}

		$command    = "update"; //명령어
		$table      = "work_info"; //테이블명
		$conditions = "wi_idx = '" . $wi_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "work_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['wi_idx']      = $wi_idx;
		$hi_param['status']      = 'WS20';
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '업무를 승인요청했습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 승인대기에서 업무완료로 변경
	function status20_90()
	{
		global $_POST, $_SESSION, $sess_str;

		$wi_idx   = $_POST['wi_idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];

		$command    = "update"; //명령어
		$table      = "work_info"; //테이블명
		$conditions = "wi_idx = '" . $wi_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");
		$param["work_status"] = 'WS90'; // 업무완료
		$param["end_date"]    = date("Y-m-d H:i:s"); // 업무완료

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "work_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['wi_idx']      = $wi_idx;
		$hi_param['status']      = $param["work_status"];
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '업무가 완료되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 승인대기에서 승인요청취소 변경
	function status20_02()
	{
		global $_POST, $_SESSION, $sess_str;

		$wi_idx   = $_POST['wi_idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$contents = $_POST['status_contents'];

		$command    = "update"; //명령어
		$table      = "work_info"; //테이블명
		$conditions = "wi_idx = '" . $wi_idx . "'"; //조건

		$param['mod_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date']    = date("Y-m-d H:i:s");
		$param["work_status"] = 'WS02'; // 업무완료
		$param["end_date"]    = date("Y-m-d H:i:s"); // 업무완료

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리수정
		$uhi_command    = "update"; //명령어
		$uhi_table      = "work_status_history"; //테이블명
		$uhi_conditions = "wi_idx = '" . $wi_idx . "' and status = 'WS20' and mem_idx = '" . $_SESSION[$sess_str . '_mem_idx'] . "' and status_type = 'new'"; //조건

		$uhi_param['mod_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$uhi_param['mod_date']    = date('Y-m-d H:i:s');
		$uhi_param['status_type'] = 'old';

		$query_str = make_sql($uhi_param, $uhi_command, $uhi_table, $uhi_conditions);
		db_query($query_str);
		query_history($query_str, $uhi_table, $uhi_command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "work_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['wi_idx']      = $wi_idx;
		$hi_param['status']      = 'WS20_60';
		$hi_param["contents"]    = $contents; // 취소사유
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '업무가 승인요청취소되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 승인대기중 -> 승인요청반려 변경
	function status20_70()
	{
		global $_POST, $_SESSION, $sess_str;

		$wi_idx   = $_POST['wi_idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$contents = $_POST['status_contents'];

		$command    = "update"; //명령어
		$table      = "work_info"; //테이블명
		$conditions = "wi_idx = '" . $wi_idx . "'"; //조건

		$param['mod_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date']    = date("Y-m-d H:i:s");
		$param["work_status"] = 'WS70';

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리수정
		$uhi_command    = "update"; //명령어
		$uhi_table      = "work_status_history"; //테이블명
		$uhi_conditions = "wi_idx = '" . $wi_idx . "' and status = 'WS20' and status_type = 'new'"; //조건

		$uhi_param['mod_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$uhi_param['mod_date']    = date('Y-m-d H:i:s');
		$uhi_param['status_type'] = 'old';

		$query_str = make_sql($uhi_param, $uhi_command, $uhi_table, $uhi_conditions);
		db_query($query_str);
		query_history($query_str, $uhi_table, $uhi_command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "work_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['wi_idx']      = $wi_idx;
		$hi_param['status']      = $param["work_status"];
		$hi_param["contents"]    = $contents; // 반려사유
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '업무가 승인요청반려되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 업무진행에서 완료요청일 경우
	function status30()
	{
		global $_POST, $_SESSION, $sess_str;

		$wi_idx   = $_POST['wi_idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];

	// 담당자 수만큼 완료요청확인
		$chk_where = " and wi.wi_idx = '" . $wi_idx . "'";
		$chk_data = work_info_data('view', $chk_where);
		$charge_idx = $chk_data['charge_idx'];
		$reg_id     = $chk_data['reg_id'];

		$total_charge = 0; $request_charge = 0;
		if ($charge_idx != '')
		{
			$charge_arr = explode(',', $charge_idx);
			foreach ($charge_arr as $charge_k => $charge_v)
			{
				if ($charge_v != '')
				{
					$mem_where = " and mem.comp_idx = '" . $comp_idx . "' and mem.mem_idx = '" . $charge_v . "'";
					$mem_data = member_info_data('view', $mem_where, '', '', '');
					if ($mem_data['total_num'] == 0)
					{
						$request_charge++;
					}
					else
					{
						if ($charge_v == $reg_id)
						{
							$request_charge++;
						}
						else
						{
							$status_where = " and wsh.wi_idx = '" . $wi_idx . "' and wsh.status = 'WS30' and wsh.mem_idx = '" . $charge_v . "' and wsh.status_type = 'new'";
							$status_data = work_status_history_data('page', $status_where);

							if ($status_data['total_num'] > 0)
							{
								$request_charge++;
							}
						}
					}
					if ($charge_v == $mem_idx)
					{
						$request_charge++;
					}
					$total_charge++;
				}
			}
		}

		if ($total_charge == $request_charge)
		{
			$param["work_status"] = 'WS30'; // 요청대기
		}
		else
		{
			$param["work_status"] = 'WS02'; // 진행중
		}

		$command    = "update"; //명령어
		$table      = "work_info"; //테이블명
		$conditions = "wi_idx = '" . $wi_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "work_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['wi_idx']      = $wi_idx;
		$hi_param['status']      = 'WS30';
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '업무를 요청완료했습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 요청대기에서 완료요청취소 변경
	function status30_02()
	{
		global $_POST, $_SESSION, $sess_str;

		$wi_idx   = $_POST['wi_idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$contents = $_POST['status_contents'];

		$command    = "update"; //명령어
		$table      = "work_info"; //테이블명
		$conditions = "wi_idx = '" . $wi_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");
		$param["work_status"] = 'WS02'; // 업무완료
		$param["end_date"]    = date("Y-m-d H:i:s"); // 업무완료

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리수정
		$uhi_command    = "update"; //명령어
		$uhi_table      = "work_status_history"; //테이블명
		$uhi_conditions = "wi_idx = '" . $wi_idx . "' and status = 'WS30' and mem_idx = '" . $_SESSION[$sess_str . '_mem_idx'] . "' and status_type = 'new'"; //조건

		$uhi_param['mod_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$uhi_param['mod_date']    = date('Y-m-d H:i:s');
		$uhi_param['status_type'] = 'old';

		$query_str = make_sql($uhi_param, $uhi_command, $uhi_table, $uhi_conditions);
		db_query($query_str);
		query_history($query_str, $uhi_table, $uhi_command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "work_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['wi_idx']      = $wi_idx;
		$hi_param['status']      = 'WS30_60';
		$hi_param["contents"]    = $contents; // 취소사유
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '업무가 완료요청취소되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 요청대기에서 업무완료로 변경
	function status30_90()
	{
		global $_POST, $_SESSION, $sess_str;

		$wi_idx   = $_POST['wi_idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];

		$command    = "update"; //명령어
		$table      = "work_info"; //테이블명
		$conditions = "wi_idx = '" . $wi_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");
		$param["work_status"] = 'WS90'; // 업무완료
		$param["end_date"]    = date("Y-m-d H:i:s"); // 업무완료

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "work_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['wi_idx']      = $wi_idx;
		$hi_param['status']      = $param["work_status"];
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '업무가 완료되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 요청대기중 -> 요청완료반려 변경
	function status30_70()
	{
		global $_POST, $_SESSION, $sess_str;

		$wi_idx   = $_POST['wi_idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$contents = $_POST['status_contents'];

		$command    = "update"; //명령어
		$table      = "work_info"; //테이블명
		$conditions = "wi_idx = '" . $wi_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");
		$param["work_status"] = 'WS70';

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리수정
		$uhi_command    = "update"; //명령어
		$uhi_table      = "work_status_history"; //테이블명
		$uhi_conditions = "wi_idx = '" . $wi_idx . "' and status = 'WS30' and status_type = 'new'"; //조건

		$uhi_param['mod_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$uhi_param['mod_date']    = date('Y-m-d H:i:s');
		$uhi_param['status_type'] = 'old';

		$query_str = make_sql($uhi_param, $uhi_command, $uhi_table, $uhi_conditions);
		db_query($query_str);
		query_history($query_str, $uhi_table, $uhi_command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "work_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['wi_idx']      = $wi_idx;
		$hi_param['status']      = $param["work_status"];
		$hi_param["contents"]    = $contents; // 반려사유
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '업무가 요청완료반려되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}
?>