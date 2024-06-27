<?
/*
	생성 : 2012.12.24
	수정 : 2012.12.24
	위치 : 직원정보 - 쪽지보내기 - 쪽지실행
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

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 보낸쪽지
		$command    = "insert"; //명령어
		$table      = "message_send"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']    = $mem_idx;
		$param['reg_date']  = date("Y-m-d H:i:s");
		$param['comp_idx']  = $comp_idx;
		$param['part_idx']  = $part_idx;
		$param['mem_idx']   = $mem_idx;
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

		$re_param['reg_id']    = $mem_idx;
		$re_param['reg_date']  = date("Y-m-d H:i:s");
		$re_param['comp_idx']  = $comp_idx;
		$re_param['part_idx']  = $part_idx;
		$re_param['ms_idx']    = $param['ms_idx'];
		$re_param['mem_idx']   = $_POST['receive_idx'];

		$mem_where = " and mem.mem_idx = '" . $re_param['mem_idx'] . "'";
		$mem_data = member_info_data('view', $mem_where);

		$re_param['mem_id']   = $mem_data['mem_id'];
		$re_param['mem_name'] = $mem_data['mem_name'];

		$query_str = make_sql($re_param, $re_command, $re_table, $re_conditions);
		db_query($query_str);
		query_history($query_str, $re_table, $re_command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 푸시건
		$receiver = $re_param['mem_id'];
		$msg_type = 'message';
		$message = strip_tags($param["remark"]);
		$message = '[쪽지] ' . string_cut($message, 20);

		$push   = new PUSH("bizstory_push");
		$result = $push->push_send($sender, $comp_idx, $part_idx, $re_param['mem_idx'], $receiver, $msg_type, $message);

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