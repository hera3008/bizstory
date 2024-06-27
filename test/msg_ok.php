<?
/*
	수정 : 2014.04.03
	위치 : 업무관리 > 나의 업무 > 쪽지 - 실행
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
		$chk_param['require'][] = array("field"=>"remark", "msg"=>"내용");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 선택삭제 함수
	function delete_select()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 받은 쪽지삭제
		$receive_command    = "update"; //명령어
		$receive_table      = "message_receive"; //테이블명

		$receive_param['del_yn']   = "Y";
		$receive_param['del_ip']   = $ip_address;
		$receive_param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$receive_param['del_date'] = date("Y-m-d H:i:s");

		$chk_mr_idx = $_POST['chk_mr_idx'];
		if (is_array($chk_mr_idx))
		{
			foreach ($chk_mr_idx as $k => $v)
			{
				if ($v != '')
				{
					$receive_conditions = "mr_idx = '" . $v . "'"; //조건

					$receive_query_str = make_sql($receive_param, $receive_command, $receive_table, $receive_conditions);
					db_query($receive_query_str);
					query_history($receive_query_str, $receive_table, $receive_command);
				}
			}
		}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 보낸 쪽지삭제
		$send_command    = "update"; //명령어
		$send_table      = "message_send"; //테이블명

		$send_param['send_del'] = "Y";
		$send_param['del_ip']   = $ip_address;
		$send_param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$send_param['del_date'] = date("Y-m-d H:i:s");

		$chk_ms_idx = $_POST['chk_ms_idx'];
		if (is_array($chk_ms_idx))
		{
			foreach ($chk_ms_idx as $k => $v)
			{
				if ($v != '')
				{
					$send_conditions = "ms_idx = '" . $v . "'"; //조건

					$send_query_str = make_sql($send_param, $send_command, $send_table, $send_conditions);
					db_query($send_query_str);
					query_history($send_query_str, $send_table, $send_command);
				}
			}
		}

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 받은쪽지 삭제 함수
	function delete_receive()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$mr_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "message_receive"; //테이블명
		$conditions = "mr_idx = '" . $mr_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 보낸쪽지 삭제 함수
	function delete_send()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$ms_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "message_send"; //테이블명
		$conditions = "ms_idx = '" . $ms_idx . "'"; //조건

		$param['send_del'] = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 보낸쪽지
		$command    = "insert"; //명령어
		$table      = "message_send"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']    = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date']  = date("Y-m-d H:i:s");
		$param['comp_idx']  = $comp_idx;
		$param['part_idx']  = $part_idx;
		$param['mem_idx']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['send_date'] = date("Y-m-d H:i:s");
		if ($param['send_save'] == '') $param['send_save'] = 'N';

		$mem_where = " and mem.mem_idx = '" . $param['mem_idx'] . "'";
		$mem_data = member_info_data('view', $mem_where);

		$param['mem_id']   = $mem_data['mem_id'];
		$param['mem_name'] = $mem_data['mem_name'];

		$data = query_view("select max(ms_idx) as ms_idx from " . $table);
		$param["ms_idx"] = ($data["ms_idx"] == "") ? '1' : $data["ms_idx"] + 1;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 받는쪽지
		$re_command    = "insert"; //명령어
		$re_table      = "message_receive"; //테이블명
		$re_conditions = ""; //조건

		$reg_id   = $_SESSION[$sess_str . '_mem_idx'];
		$reg_date = date("Y-m-d H:i:s");

		$receive_arr = $_POST['receive_idx'];
		foreach ($receive_arr as $k => $v)
		{
			$mem_idx = $v;

			$mem_where = " and mem.mem_idx = '" . $mem_idx . "'";
			$mem_data = member_info_data('view', $mem_where);

			$mem_id   = $mem_data['mem_id'];
			$mem_name = $mem_data['mem_name'];

			if ($k == 0)
			{
				$query_str = "
					INSERT INTO " . $re_table . " (comp_idx, part_idx, ms_idx, mem_idx, mem_id, mem_name, reg_id, reg_date) VALUES
					('" . $comp_idx . "', '" . $part_idx . "', '" . $param["ms_idx"] . "', '" . $mem_idx . "', '" . $mem_id . "', '" . $mem_name . "', '" . $reg_id . "', '" . $reg_date . "')";
			}
			else
			{
				$query_str .= ",('" . $comp_idx . "', '" . $part_idx . "', '" . $param["ms_idx"] . "', '" . $mem_idx . "', '" . $mem_id . "', '" . $mem_name . "', '" . $reg_id . "', '" . $reg_date . "')";
			}

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 알림건
			$reg_idx = $_SESSION[$sess_str . '_mem_idx'];
			charge_push_send($reg_idx, $mem_idx, '', 'message', $param['remark'], '', '', '');
		}
		db_query($query_str);
		query_history($query_str, $re_table, $re_command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $comp_msg_path;

		$ms_idx       = $param['ms_idx'];
		$message_path = $comp_msg_path . '/' . $ms_idx;
		files_dir($message_path);

		$file_command    = "insert"; //명령어
		$file_table      = "message_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['reg_id'];
		$reg_date = $param['reg_date'];

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $message_path, $_POST, $ms_idx, 'message');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];

				if ($file_chk == 1)
				{
					$query_str = "
						INSERT INTO " . $file_table . " (comp_idx, part_idx, ms_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
				}
				else $query_str .= ", ";

				$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($ms_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
				$file_chk++;
			}
		}

		if ($query_str != '')
		{
			db_query($query_str);
			query_history($query_str, $file_table, $file_command);
		}

		$str = '{"success_chk" : "Y", "error_string" : "", "f_class":"message", "f_idx":"' . $ms_idx . '"}';
		echo $str;
		exit;
	}
?>