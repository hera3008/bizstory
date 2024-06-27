<?
/*
	생성 : 2012.12.27
	수정 : 2013.02.05
	위치 : 업무폴더 > 프로젝트관리 - 보기 - 업무 - 실행
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
		$chk_param['require'][] = array("field"=>"work_type", "msg"=>"업무종류");
		$chk_param['require'][] = array("field"=>"charge_idx", "msg"=>"담당자");
		$chk_param['require'][] = array("field"=>"subject", "msg"=>"업무제목");
		$chk_param['require'][] = array("field"=>"deadline_date", "msg"=>"기한");
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
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];

		$command    = "insert"; //명령어
		$table      = "work_info"; //테이블명
		$conditions = ""; //조건

		$param['org_reg_id'] = $mem_idx;
		$param['reg_id']   = $mem_idx;
		$param['reg_date'] = date('Y-m-d H:i:s');
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;
		$param['mem_idx']  = $mem_idx;

		if ($param["open_yn"] == '') $param["open_yn"] = 'Y';
		if ($param["important"] == '') $param["important"] = 'WI01';

	// 요청업무일 경우
		if ($param["work_type"] == 'WT03')
		{ }
		else $param["apply_idx"] = '';

	// 본인업무일 경우
		if ($param["work_type"] == 'WT01')
		{
			$param['reg_id']  = $param["charge_idx"];
			$param['mem_idx'] = $param["charge_idx"];
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
					query_history($query_update, $file_table, 'update');
				}
			}
		}
		if ($query_str != '')
		{
			db_query($query_str);
			query_history($query_str, $file_table, $file_command);
		}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장 - 업무
		$hi_command    = "insert"; //명령어
		$hi_table      = "work_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $mem_idx;
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $mem_idx;
		$hi_param['wi_idx']      = $param['wi_idx'];
		$hi_param['status']      = $param["work_status"];
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '업무가 등록되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장 - 프로젝트
		$phi_command    = "insert"; //명령어
		$phi_table      = "project_status_history"; //테이블명
		$phi_conditions = ""; //조건

		$phi_param['reg_id']      = $mem_idx;
		$phi_param['reg_date']    = date('Y-m-d H:i:s');
		$phi_param['comp_idx']    = $comp_idx;
		$phi_param['part_idx']    = $part_idx;
		$phi_param['mem_idx']     = $mem_idx;
		$phi_param['pro_idx']     = $param['pro_idx'];
		$phi_param['proc_idx']    = $param['proc_idx'];
		$phi_param['wi_idx']      = $param['wi_idx'];
		$phi_param['status']      = $param["work_status"];
		$phi_param['status_date'] = date('Y-m-d H:i:s');
		$phi_param['status_memo'] = '업무가 등록되었습니다.';

		$query_str = make_sql($phi_param, $phi_command, $phi_table, $phi_conditions);
		db_query($query_str);
		query_history($query_str, $phi_table, $phi_command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 알림건
		$mem_idx = $_SESSION[$sess_str . '_mem_idx'];
		charge_push_send($mem_idx, $param['charge_idx'], $param['apply_idx'], 'project_work', $param['subject'], '', '', '');

		$str = '{"success_chk" : "Y", "error_string" : "", "f_class":"work", "f_idx":"' . $wi_idx . '"}';
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