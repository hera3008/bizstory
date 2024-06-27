<?
/*
	생성 : 2012.05.03
	수정 : 2013.02.22
	위치 : 업무폴더 > 나의업무 > 업무 - 보기 - 업무보고 - 실행
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
		$chk_param['require'][] = array("field"=>"remark", "msg"=>"업무보고내용");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 파일삭제 함수
	function file_delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address, $comp_work_path;

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$wrf_idx  = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "work_report_file"; //테이블명
		$conditions = "wrf_idx = '" . $wrf_idx . "'"; //조건

		$data = query_view("select img_sname, sort from " . $table . " where " . $conditions);

		$img_sname = $data["img_sname"];
		if ($img_sname != "") @unlink($comp_work_path . '/' . $data['wi_idx'] . '/' . $img_sname);

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y"}';
		echo $str;
		exit;
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
		$table      = "work_report"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date('Y-m-d H:i:s');
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;
		$param['wi_idx']   = $wi_idx;
		$param['ip_addr']  = $ip_address;
		$param['mem_idx']  = $_SESSION[$sess_str . '_mem_idx'];

		$chk_data = query_view("select max(wr_idx) as wr_idx from " . $table);
		$param['wr_idx'] = ($chk_data['wr_idx'] == '') ? '1' : $chk_data['wr_idx'] + 1;

	// 회원정보
		$sub_where = " and mem.mem_idx = '" . $_SESSION[$sess_str . '_mem_idx'] . "'";
		$sub_data = member_info_data('view', $sub_where);
		if ($param['writer'] == "") $param['writer'] = $sub_data['mem_name'];

		chk_before($param);

	//////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $comp_work_path;

		$wr_idx   = $param['wr_idx'];
		$file_idx = $wi_idx . '_' . $wr_idx;
		$work_path = $comp_work_path . '/' . $wi_idx;
		files_dir($work_path);

		$file_command    = "insert"; //명령어
		$file_table      = "work_report_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['reg_id'];
		$reg_date = $param['reg_date'];

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $work_path, $_POST, $file_idx, 'work_report');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and wrf.wr_idx = '" . $wr_idx . "' and wrf.sort ='" . $i . "'";
				$file_data = work_report_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					if ($file_chk == 1)
					{
						$query_str = "
							INSERT INTO " . $file_table . " (comp_idx, part_idx, wi_idx, wr_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
					}
					else $query_str .= ", ";

					$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($wi_idx) . "', '" . string_input($wr_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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
							del_yn = 'N' and wr_idx = '" . $wr_idx . "' and sort ='" . $i . "'";
					db_query($query_update);
				}
			}
		}
		if ($query_str != '')
		{
			db_query($query_str);
			query_history($query_str, $file_table, $file_command);
		}

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
		$hi_param['status_memo'] = '업무보고가 등록되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 알림건
		$mem_idx = $_SESSION[$sess_str . '_mem_idx'];
		charge_push_send($mem_idx, $work_data['charge_idx'], $work_data['apply_idx'], 'work_report', $work_data['subject'], '', '', $work_data['reg_id']);

	// 총수구하기
		$report_where = " and wr.wi_idx = '" . $wi_idx . "'";
		$report_list = work_report_data('list', $report_where, '', '', '');

		$str = '{"success_chk" : "Y", "total_num":"[' . number_format($report_list['total_num']) . ']", "f_class":"work_report", "f_idx":"' . $wr_idx . '"}';
		echo $str;
		exit;
	}

// 업무보고서 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$wi_idx   = $_POST['wi_idx'];
		$wr_idx   = $_POST['wr_idx'];

		$command    = "update"; //명령어
		$table      = "work_report"; //테이블명
		$conditions = "wr_idx='" . $wr_idx . "'"; //조건

		$param["mod_id"]   = $_SESSION[$sess_str . '_mem_idx'];
		$param["mod_date"] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	//////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $comp_work_path;

		$file_idx = $wi_idx . '_' . $wr_idx;
		$work_path = $comp_work_path . '/' . $wi_idx;
		files_dir($work_path);

		$file_command    = "insert"; //명령어
		$file_table      = "work_report_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['mod_id'];
		$reg_date = $param['mod_date'];

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $work_path, $_POST, $file_idx, 'work_report');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and wrf.wr_idx = '" . $wr_idx . "' and wrf.sort ='" . $i . "'";
				$file_data = work_report_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					if ($file_chk == 1)
					{
						$query_str = "
							INSERT INTO " . $file_table . " (comp_idx, part_idx, wi_idx, wr_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
					}
					else $query_str .= ", ";

					$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($wi_idx) . "', '" . string_input($wr_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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
							del_yn = 'N' and wr_idx = '" . $wr_idx . "' and sort ='" . $i . "'";
					db_query($query_update);
				}
			}
		}
		if ($query_str != '')
		{
			db_query($query_str);
			query_history($query_str, $file_table, $file_command);
		}

	// 총수구하기
		$report_where = " and wr.wi_idx = '" . $wi_idx . "'";
		$report_list = work_report_data('list', $report_where, '', '', '');

		$str = '{"success_chk" : "Y", "total_num":"[' . number_format($report_list['total_num']) . ']", "f_class":"work_report", "f_idx":"' . $wr_idx . '"}';
		echo $str;
		exit;
	}

// 업무보고서 삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$wi_idx = $_POST['wi_idx'];
		$wr_idx = $_POST['wr_idx'];

		$command    = "update"; //명령어
		$table      = "work_report"; //테이블명
		$conditions = "wr_idx = '" . $wr_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	// 총수구하기
		$report_where = " and wr.wi_idx = '" . $wi_idx . "'";
		$report_list = work_report_data('list', $report_where, '', '', '');

		$str = '{"success_chk" : "Y", "total_num":"[' . number_format($report_list['total_num']) . ']"}';
		echo $str;
		exit;
	}
?>