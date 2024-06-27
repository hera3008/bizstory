<?
/*
	생성 : 2012.07.03
	수정 : 2012.10.24
	위치 : 설정관리 > 접수관리 > 에이전트관리 > 알림관리 > 알림게시판 - 실행
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
		$chk_param['require'][] = array("field"=>"remark", "msg"=>"내용");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $param['part_idx'];

		$command    = "insert"; //명령어
		$table      = "agent_bnotice"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date('Y-m-d H:i:s');
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;
		$param['ip_addr']  = $ip_address;

		if ($param["important"] == '') $param["important"] = 'BNI01';
		if ($param["client_type"] == "") $param["client_type"] = "2";

		$chk_data = query_view("select max(abn_idx) as abn_idx from " . $table);
		$param['abn_idx']   = ($chk_data['abn_idx'] == '') ? '1' : $chk_data['abn_idx'] + 1;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$abn_idx = $param['abn_idx'];

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		$file_num = $_POST['file_upload_num'];
		if ($file_num > 0)
		{
			global $tmp_path, $comp_bnotice_path;

			files_dir($comp_bnotice_path);
			$data_path = $comp_bnotice_path . '/' . $abn_idx;
			files_dir($data_path);

			$file_command    = "insert"; //명령어
			$file_table      = "agent_bnotice_file"; //테이블명
			$file_conditions = ""; //조건

			$reg_id   = $param['reg_id'];
			$reg_date = $param['reg_date'];

			$query_str = '';
			$file_chk = 1;
			for ($i = 1; $i <= $file_num; $i++)
			{
				$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $abn_idx, 'agent_bnotice');

				if ($upfile_data[$i]['f_name'] != '')
				{
					$chk_file_name = $upfile_data[$i]['f_name'];
					$new_file_name = $upfile_data[$i]['s_name'];
					$chk_file_size = $upfile_data[$i]['f_size'];
					$chk_file_type = $upfile_data[$i]['f_type'];
					$chk_file_ext  = $upfile_data[$i]['f_ext'];

				// 데이타 확인
					$file_where = " and abnf.abn_idx = '" . $abn_idx . "' and abnf.sort ='" . $i . "'";
					$file_data = agent_bnotice_file_data('view', $file_where);

					if ($file_data['total_num'] == 0)
					{
						if ($file_chk == 1)
						{
							$query_str = "
								INSERT INTO " . $file_table . " (comp_idx, part_idx, abn_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
						}
						else $query_str .= ", ";

						$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($abn_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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
								del_yn = 'N' and abn_idx = '" . $abn_idx . "' and sort ='" . $i . "'";
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
		}

		$str = '{"success_chk" : "Y", "error_string" : "", "f_class":"bnotice", "f_idx":"' . $abn_idx . '"}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $param['part_idx'];
		$abn_idx  = $_POST['abn_idx'];

		$command    = "update"; //명령어
		$table      = "agent_bnotice"; //테이블명
		$conditions = "abn_idx = '" . $abn_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($param["important"] == '') $param["important"] = 'BNI01';
		if ($param["client_type"] == "") $param["client_type"] = "2";

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		$file_num = $_POST['file_upload_num'];
		if ($file_num > 0)
		{
			global $tmp_path, $comp_bnotice_path;

			files_dir($comp_bnotice_path);
			$data_path = $comp_bnotice_path . '/' . $abn_idx;
			files_dir($data_path);

			$file_command    = "insert"; //명령어
			$file_table      = "agent_bnotice_file"; //테이블명
			$file_conditions = ""; //조건

			$reg_id   = $param['mod_id'];
			$reg_date = $param['mod_date'];

			$query_str = '';
			$file_chk = 1;
			for ($i = 1; $i <= $file_num; $i++)
			{
				$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $abn_idx, 'agent_bnotice');

				if ($upfile_data[$i]['f_name'] != '')
				{
					$chk_file_name = $upfile_data[$i]['f_name'];
					$new_file_name = $upfile_data[$i]['s_name'];
					$chk_file_size = $upfile_data[$i]['f_size'];
					$chk_file_type = $upfile_data[$i]['f_type'];
					$chk_file_ext  = $upfile_data[$i]['f_ext'];

				// 데이타 확인
					$file_where = " and abnf.abn_idx = '" . $abn_idx . "' and abnf.sort ='" . $i . "'";
					$file_data = agent_bnotice_file_data('view', $file_where);

					if ($file_data['total_num'] == 0)
					{
						if ($file_chk == 1)
						{
							$query_str = "
								INSERT INTO " . $file_table . " (comp_idx, part_idx, abn_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
						}
						else $query_str .= ", ";

						$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($abn_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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
								del_yn = 'N' and abn_idx = '" . $abn_idx . "' and sort ='" . $i . "'";
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
		}

		$str = '{"success_chk" : "Y", "error_string" : "", "f_class":"bnotice", "f_idx":"' . $abn_idx . '"}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$abn_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "agent_bnotice"; //테이블명
		$conditions = "abn_idx = '" . $abn_idx . "'"; //조건

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
		global $_POST, $_SESSION, $sess_str, $ip_address, $comp_bnotice_path;

		$abnf_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "agent_bnotice_file"; //테이블명
		$conditions = "abnf_idx = '" . $abnf_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$data = query_view("select abn_idx, img_sname from " . $table . " where " . $conditions);
		$abn_idx   = $data['abn_idx'];
		$img_sname = $data["img_sname"];

		if ($img_sname != "") @unlink($comp_bnotice_path . '/' . $abn_idx . '/' . $img_sname);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "org_idx":"' . $abn_idx . '"}';
		echo $str;
		exit;
	}
?>