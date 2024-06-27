<?
/*
	생성 : 2012.12.20
	수정 : 2013.04.29
	위치 : 업무폴더 > 프로젝트관리 - 실행
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
		global $_SESSION, $sess_str, $sub_type;

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];

	//필수검사
		$chk_param['require'][] = array("field"=>"subject", "msg"=>"제목");
		$chk_param['require'][] = array("field"=>"start_date", "msg"=>"시작일");
		$chk_param['require'][] = array("field"=>"deadline_date", "msg"=>"종료일");
		$chk_param['require'][] = array("field"=>"apply_idx", "msg"=>"책임자");
		$chk_param['require'][] = array("field"=>"charge_idx", "msg"=>"담당자");
		$chk_param['require'][] = array("field"=>"remark", "msg"=>"내용");

		if ($sub_type == 'postform')
		{
		//중복검사
			$chk_param["unique"][] = array("table"=>"project_info", "field"=>"project_code", "where"=>"del_yn = 'N' and comp_idx = '" . $comp_idx . "'", "msg"=>"이미 사용된 프로젝트코드입니다.");
		}

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
		$set_file_class = $_POST['set_file_class'];

		$command    = "insert"; //명령어
		$table      = "project_info"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $mem_idx;
		$param['reg_date'] = date('Y-m-d H:i:s');
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;

		if ($param["open_yn"] == '') $param["open_yn"] = 'Y';

		$chk_data = query_view("select max(pro_idx) as pro_idx from " . $table);
		$param['pro_idx'] = ($chk_data['pro_idx'] == '') ? '1' : $chk_data['pro_idx'] + 1;

	// 기한, 담당자가 없을 경우 업무대기로 설정
		if ($param['deadline_date'] == '' || $param['charge_idx'] == '')
		{
			$param["pro_status"] = 'PS01'; // 업무대기
		}
		else
		{
			$param["pro_status"] = 'PS02'; // 업무진행
		}

	// 프로젝트코드
		if ($param['project_code'] == '')
		{
			$chk_data = query_page("
				select
					count(pro_idx)
				from
					" . $table . "
				where
					comp_idx = '" . $comp_idx ."'
					and date_format(start_date, '%Y') = '" . date('Y') . "'
			");
			$chk_total = $chk_data['total_num'] + 1;
			$chk_total = str_pad($chk_total, 3, '0', STR_PAD_LEFT);
			$param['project_code'] = 'P' . date('Y') . $chk_total;
		}
		else // 중복확인할 것
		{ }

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
		global $tmp_path, $comp_project_path;

		$pro_idx = $param['pro_idx'];
		files_dir($comp_project_path);
		$data_path = $comp_project_path . '/' . $pro_idx;
		files_dir($data_path);

		$file_command    = "insert"; //명령어
		$file_table      = "project_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['reg_id'];
		$reg_date = $param['reg_date'];

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $pro_idx, 'project');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and prof.pro_idx = '" . $pro_idx . "' and prof.sort ='" . $i . "'";
				$file_data = project_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					if ($file_chk == 1)
					{
						$query_str = "
							INSERT INTO " . $file_table . " (comp_idx, part_idx, pro_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
					}
					else $query_str .= ", ";

					$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($pro_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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
							del_yn = 'N' and pro_idx = '" . $pro_idx . "' and sort ='" . $i . "'";
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
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "project_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $mem_idx;
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $mem_idx;
		$hi_param['pro_idx']     = $param['pro_idx'];
		$hi_param['status']      = $param["pro_status"];
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '프로젝트가 등록되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 알림건
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];
		charge_push_send($mem_idx, $param['charge_idx'], $param['apply_idx'], 'project', $param['subject'], '', '', '');

		$str = '{"success_chk" : "Y", "error_string" : "", "f_class":"project", "f_idx":"' . $param['pro_idx'] . '"}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param      = $_POST['param'];
		$pro_idx    = $_POST['pro_idx'];
		$comp_idx   = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx   = $_SESSION[$sess_str . '_part_idx'];
		$mem_idx    = $_SESSION[$sess_str . '_mem_idx'];
		$old_deadline_date = $_POST['old_deadline_date'];
		$old_apply_idx     = $_POST['old_apply_idx'];
		$old_charge_idx    = $_POST['old_charge_idx'];
		$old_pro_status    = $_POST['old_pro_status'];

		$command    = "update"; //명령어
		$table      = "project_info"; //테이블명
		$conditions = "pro_idx = '" . $pro_idx . "'"; //조건

		$param['mod_id']   = $mem_idx;
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($param["open_yn"] == '') $param["open_yn"] = 'Y';

	// 기한, 담당자가 없을 경우 업무대기로 설정
		if ($param['deadline_date'] == '' || $param['charge_idx'] == '')
		{
			$param["pro_status"] = 'PS01'; // 업무대기
		}
		else
		{
			if ($old_pro_status == 'PS01')
			{
				$param["pro_status"] = 'PS02'; // 업무진행

			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// 히스토리저장
				$hi_command    = "insert"; //명령어
				$hi_table      = "project_status_history"; //테이블명
				$hi_conditions = ""; //조건

				$hi_param['reg_id']      = $mem_idx;
				$hi_param['reg_date']    = date('Y-m-d H:i:s');
				$hi_param['comp_idx']    = $comp_idx;
				$hi_param['part_idx']    = $part_idx;
				$hi_param['mem_idx']     = $mem_idx;
				$hi_param['pro_idx']     = $pro_idx;
				$hi_param['status']      = $param["pro_status"];
				$hi_param['status_date'] = date('Y-m-d H:i:s');
				$hi_param['status_memo'] = '프로젝트가 진행되었습니다.';

				$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
				db_query($query_str);
				query_history($query_str, $hi_table, $hi_command);
			}
		}

		chk_before($param);

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

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $comp_project_path;

		files_dir($comp_project_path);
		$data_path = $comp_project_path . '/' . $pro_idx;
		files_dir($data_path);

		$file_command    = "insert"; //명령어
		$file_table      = "project_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['mod_id'];
		$reg_date = $param['mod_date'];

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $pro_idx, 'project');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and prof.pro_idx = '" . $pro_idx . "' and prof.sort ='" . $i . "'";
				$file_data = project_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					if ($file_chk == 1)
					{
						$query_str = "
							INSERT INTO " . $file_table . " (comp_idx, part_idx, pro_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
					}
					else $query_str .= ", ";

					$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($pro_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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
							del_yn = 'N' and pro_idx = '" . $pro_idx . "' and sort ='" . $i . "'";
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
	// 업무관련 수정 히스토리
		$total_history = '';
	// 기한
		if ($param['deadline_date'] != $old_deadline_date)
		{
			$total_history .= '프로젝트 기한 ' . $old_deadline_date . '(에)서 ' . $param['deadline_date'] . '(으)로 변경되었습니다. ';
		}
	// 책임자
		if ($param['apply_idx'] != $old_apply_idx)
		{
			$old_mem_where = " and mem.mem_idx = '" . $old_apply_idx . "'";
			$old_mem_data = member_info_data('view', $old_mem_where);

			$mem_where = " and mem.mem_idx = '" . $param['apply_idx'] . "'";
			$mem_data = member_info_data('view', $mem_where);

			$total_history .= '프로젝트 책임자 ' . $old_mem_data['mem_name'] . '(에)서 ' . $mem_data['mem_name'] . '(으)로 변경되었습니다. ';
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
			$total_history .= '프로젝트 담당자 ' . $old_charge . '(에)서 ' . $new_charge . '(으)로 변경되었습니다. ';
		}

		if ($total_history != '')
		{
			$hi_command    = "insert"; //명령어
			$hi_table      = "project_status_history"; //테이블명
			$hi_conditions = ""; //조건

			$hi_param['reg_id']      = $mem_idx;
			$hi_param['reg_date']    = date('Y-m-d H:i:s');
			$hi_param['comp_idx']    = $comp_idx;
			$hi_param['part_idx']    = $part_idx;
			$hi_param['mem_idx']     = $mem_idx;
			$hi_param['pro_idx']     = $pro_idx;
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
		charge_push_send($mem_idx, $param['charge_idx'], $param['apply_idx'], 'project', $param['subject'], $old_charge_idx, $old_apply_idx, '');

		$str = '{"success_chk" : "Y", "error_string" : "", "f_class":"project", "f_idx":"' . $pro_idx . '"}';
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
		$pro_idx  = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "project_info"; //테이블명
		$conditions = "pro_idx = '" . $pro_idx . "'"; //조건

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

// 파일삭제 함수
	function file_delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address, $comp_project_path;

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];
		$prof_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "project_file"; //테이블명
		$conditions = "prof_idx = '" . $prof_idx . "'"; //조건

		$data = query_view("select * from " . $table . " where " . $conditions);

		$img_sname = $data["img_sname"];
		if ($img_sname != "") @unlink($comp_project_path . '/' . $data['pro_idx'] . '/' . $img_sname);

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