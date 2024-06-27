<?
/*
	생성 : 2012.04.19
	수정 : 2013.02.22
	위치 : 업무폴더 > 나의업무 > 업무 - 실행
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
		$chk_param['require'][] = array("field"=>"subject", "msg"=>"업무제목");
		$chk_param['require'][] = array("field"=>"remark", "msg"=>"업무내용");

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

		$command    = "insert"; //명령어
		$table      = "work_info"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date('Y-m-d H:i:s');
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;
		$param['mem_idx']  = $_SESSION[$sess_str . '_mem_idx'];

		if ($param["work_type"] == '') $param["work_type"] = 'WT01';
		if ($param["open_yn"] == '') $param["open_yn"] = 'Y';
		if ($param["important"] == '') $param["important"] = 'WI01';

	// 요청업무일 경우
		if ($param["work_type"] == 'WT03')
		{ }
		else $param["apply_idx"] = '';

	// 본인업무일 경우
		if ($param["work_type"] == 'WT01')
		{
			$param["charge_idx"] = $param['mem_idx'];
		}

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

	// 기한, 담당자가 없을 경우 업무대기로 설정
		if ($param['deadline_date'] == '' || $param['charge_idx'] == '')
		{
			$param["work_status"] = 'WS01'; // 업무대기
		}
		else
		{
			$param["work_status"] = 'WS02'; // 업무진행
		}

	// 담당자 정리
		$chk_charge_idx = ',' . $param['charge_idx'] . ',';
		$chk_charge_arr = explode(',', $chk_charge_idx);
		sort($chk_charge_arr);
		$total_charge = '';
		foreach ($chk_charge_arr as $k => $v)
		{
			if ($v != '')
			{
				if ($old_charge != $v)
				{
					$total_charge .= ',' . $v;
				}
				$old_charge = $v;
			}
		}
		$total_charge = substr($total_charge, 1, strlen($total_charge)-1);
		$param['charge_idx'] = $total_charge;

		$chk_data = query_view("select max(wi_idx) as wi_idx, max(order_idx) as order_idx from " . $table);
		$param['wi_idx']    = ($chk_data['wi_idx'] == '') ? '1' : $chk_data['wi_idx'] + 1;
		$param['order_idx'] = ($chk_data['order_idx'] == '') ? '1' : $chk_data['order_idx'] + 1;
		$param['gno']       = $param['wi_idx'];
		$param['tgno']      = 0;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $comp_work_path;

		$wi_idx    = $param['wi_idx'];
		$work_path = $comp_work_path . '/' . $wi_idx;
		files_dir($work_path);

		$file_command    = "insert"; //명령어
		$file_table      = "work_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['reg_id'];
		$reg_date = $param['reg_date'];

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			//echo $work_path;
			$upfile_data = upload_file_save($i, $tmp_path, $work_path, $_POST, $wi_idx, 'work');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and wf.wi_idx = '" . $wi_idx . "' and wf.sort ='" . $i . "'";
				$file_data = work_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					if ($file_chk == 1)
					{
						$query_str = "
							INSERT INTO " . $file_table . " (comp_idx, part_idx, wi_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
					}
					else $query_str .= ", ";

					$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($wi_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
					$file_chk++;
				}
				else
				{
					$query_update = "update " . $file_table . " set
							img_fname = '" . string_input($chk_file_name) . "',
							img_sname = '" . string_input($new_file_name) . "',
							img_size  = '" . string_input($chk_file_size) . "',
							img_type  = '" . string_input($chk_file_type) . "',
							img_ext   = '" . string_input($chk_file_ext) . "',
							mod_id    = '" . string_input($reg_id) . "',
							mod_date  = '" . string_input($reg_date) . "'
						where
							del_yn = 'N' and wi_idx = '" . $wi_idx . "' and sort ='" . $i . "'";
					db_query($query_update);
				}
			}
		}
//exit;
		if ($query_str != '')
		{
			db_query($query_str);
			query_history($query_str, $file_table, $file_command);
		}

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
		$hi_param['wi_idx']      = $param['wi_idx'];
		$hi_param['status']      = $param["work_status"];
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '업무가 등록되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 알림건
		$mem_idx = $_SESSION[$sess_str . '_mem_idx'];
		charge_push_send($mem_idx, $param['charge_idx'], $param['apply_idx'], 'work', $param['subject'], '', '', '');

		$str = '{"success_chk" : "Y", "idx_num" : "' . $param['wi_idx'] . '", "f_class":"work", "f_idx":"' . $param['wi_idx'] . '"}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param      = $_POST['param'];
		$wi_idx     = $_POST['wi_idx'];
		$old_status = $_POST['old_status'];
		$comp_idx   = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx   = $_SESSION[$sess_str . '_part_idx'];

		$command    = "update"; //명령어
		$table      = "work_info"; //테이블명
		$conditions = "wi_idx = '" . $wi_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($param["open_yn"] == '') $param["open_yn"] = 'Y';
		if ($param["important"] == '') $param["important"] = 'WI01';

	// 요청업무일 경우
		if ($param["work_type"] != '')
		{
			if ($param["work_type"] == 'WT03')
			{ }
			else $param["apply_idx"] = '';
		}

	// 본인업무일 경우
		if ($param["work_type"] == 'WT01')
		{
			$param["charge_idx"] = $_SESSION[$sess_str . '_mem_idx'];
		}

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

	// 기한, 담당자가 없을 경우 업무대기로 설정
		if ($param['deadline_date'] == '' || $param['charge_idx'] == '')
		{
			$param["work_status"] = 'WS01'; // 업무대기
		}
		else
		{
			if ($old_status == 'WS01')
			{
				$param["work_status"] = 'WS02'; // 업무진행

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
			}
		}

	// 담당자 정리
		$chk_charge_idx = ',' . $param['charge_idx'] . ',';
		$chk_charge_arr = explode(',', $chk_charge_idx);
		sort($chk_charge_arr);
		$total_charge = '';
		foreach ($chk_charge_arr as $k => $v)
		{
			if ($v != '')
			{
				if ($old_charge != $v)
				{
					$total_charge .= ',' . $v;
				}
				$old_charge = $v;
			}
		}
		$total_charge = substr($total_charge, 1, strlen($total_charge)-1);
		$param['charge_idx'] = $total_charge;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $comp_work_path;

		$work_path = $comp_work_path . '/' . $wi_idx;
		files_dir($work_path);

		$file_command    = "insert"; //명령어
		$file_table      = "work_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['mod_id'];
		$reg_date = $param['mod_date'];

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $work_path, $_POST, $wi_idx, 'work');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and wf.wi_idx = '" . $wi_idx . "' and wf.sort ='" . $i . "'";
				$file_data = work_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					if ($file_chk == 1)
					{
						$query_str = "
							INSERT INTO " . $file_table . " (comp_idx, part_idx, wi_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
					}
					else $query_str .= ", ";

					$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($wi_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
					$file_chk++;
				}
				else
				{
					$query_update = "update " . $file_table . " set
							img_fname = '" . string_input($chk_file_name) . "',
							img_sname = '" . string_input($new_file_name) . "',
							img_size  = '" . string_input($chk_file_size) . "',
							img_type  = '" . string_input($chk_file_type) . "',
							img_ext   = '" . string_input($chk_file_ext) . "',
							mod_id    = '" . string_input($reg_id) . "',
							mod_date  = '" . string_input($reg_date) . "'
						where
							del_yn = 'N' and wi_idx = '" . $wi_idx . "' and sort ='" . $i . "'";
					db_query($query_update);
				}
			}
		}
		if ($query_str != '')
		{
			db_query($query_str);
			query_history($query_str, $file_table, $file_command);
		}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 업무관련 수정 히스토리
		global $set_work_type;
		$total_history = '';

		$old_work_type     = $_POST['old_work_type'];
		$old_deadline_date = $_POST['old_deadline_date'];
		$old_deadline_str  = $_POST['old_deadline_str'];
		$old_charge_idx    = $_POST['old_charge_idx'];
	// 업무종류
		if ($param['work_type'] != $old_work_type && $param['work_type'] != '')
		{
			$total_history .= '업무종류 ' . $set_work_type[$old_work_type] . '(에)서 ' . $set_work_type[$param['work_type']] . '(으)로 변경되었습니다. ';
		}
	// 기한
		if ($param['deadline_date'] != $old_deadline_date)
		{
			$total_history .= '업무기한 ' . $old_deadline_date . '(에)서 ' . $param['deadline_date'] . '(으)로 변경되었습니다. ';
		}
	// 덧붙이기
		if ($param['deadline_str'] != $old_deadline_str)
		{
			$total_history .= '기한의 덧붙이기 ' . $old_deadline_str . '(에)서 ' . $param['deadline_str'] . '(으)로 변경되었습니다. ';
		}
	// 담당자
		if ($param['charge_idx'] != $old_charge_idx)
		{
		// 담당자명 구하기 - 예전
			$old_charge_arr = explode(',', $old_charge_idx);
			$old_charge = '';
			foreach ($old_charge_arr as $old_k => $old_v)
			{
				$mem_where = " and mem.mem_idx = '" . $old_v . "'";
				$mem_data = member_info_data('view', $mem_where);
				$old_charge .= $mem_data['mem_name'];
				if ($old_k < count($old_charge_arr)-1)
				{
					$old_charge .= ', ';
				}
			}

		// 담당자명 구하기 - 새로
			$new_charge_idx = $param['charge_idx'];
			$new_charge_arr = explode(',', $new_charge_idx);
			$new_charge = '';
			foreach ($new_charge_arr as $new_k => $new_v)
			{
				$mem_where = " and mem.mem_idx = '" . $new_v . "'";
				$mem_data = member_info_data('view', $mem_where);
				$new_charge .= $mem_data['mem_name'];
				if ($new_k < count($new_charge_arr)-1)
				{
					$new_charge .= ', ';
				}
			}

			$total_history .= '업무담당자 ' . $old_charge . '(에)서 ' . $new_charge . '(으)로 변경되었습니다. ';
		}

		if ($total_history != '')
		{
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
			$hi_param['status_memo'] = $total_history;

			$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
			db_query($query_str);
			query_history($query_str, $hi_table, $hi_command);
		}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 알림건
		$mem_idx = $_SESSION[$sess_str . '_mem_idx'];
		charge_push_send($mem_idx, $param['charge_idx'], $param['apply_idx'], 'work', $param['subject'], $old_charge_idx, $old_apply_idx, '');

		$str = '{"success_chk" : "Y", "idx_num" : "' . $wi_idx . '", "f_class":"work", "f_idx":"' . $wi_idx . '"}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$wi_idx   = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "work_info"; //테이블명
		$conditions = "wi_idx = '" . $wi_idx . "'"; //조건

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

// 파일삭제 함수
	function file_delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address, $comp_work_path;

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$wf_idx   = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "work_file"; //테이블명
		$conditions = "wf_idx = '" . $wf_idx . "'"; //조건

		$data = query_view("select * from " . $table . " where " . $conditions);

		$img_sname = $data["img_sname"];
		if ($img_sname != "") @unlink($comp_work_path . '/' . $data['wi_idx'] . '/' . $img_sname);

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
?>