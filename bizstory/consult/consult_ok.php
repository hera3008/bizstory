<?
/*
	생성 : 2012.09.25
	수정 : 2012.10.11
	위치 : 설정관리 > 에이전트관리 > 상담게시판 > 상담게시판 - 실행
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
		$chk_param['require'][] = array("field"=>"ci_idx", "msg"=>"거래처");
		$chk_param['require'][] = array("field"=>"writer", "msg"=>"작성자");
		$chk_param['require'][] = array("field"=>"tel_num", "msg"=>"연락처");
		$chk_param['require'][] = array("field"=>"consult_class", "msg"=>"분류");
		$chk_param['require'][] = array("field"=>"subject", "msg"=>"제목");
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
		$part_idx = $param['part_idx'];

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 저장
		$command    = "insert"; //명령어
		$table      = "consult_info"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");
		$param['comp_idx'] = $comp_idx;

		$chk_data = query_view("select max(cons_idx) as cons_idx from " . $table);
		$param['cons_idx'] = ($chk_data["cons_idx"] == "") ? '1' : $chk_data["cons_idx"] + 1;
		unset($chk_data);

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$cons_idx = $param['cons_idx'];

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		$file_num = $_POST['file_upload_num'];
		if ($file_num > 0)
		{
			global $tmp_path, $comp_consult_path;

			files_dir($comp_consult_path);
			$data_path = $comp_consult_path . '/' . $cons_idx;
			files_dir($data_path);

			$file_command    = "insert"; //명령어
			$file_table      = "consult_file"; //테이블명
			$file_conditions = ""; //조건

			$reg_id   = $param['reg_id'];
			$reg_date = $param['reg_date'];

			$query_str = '';
			$file_chk = 1;
			for ($i = 1; $i <= $file_num; $i++)
			{
				$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $cons_idx, 'consult');

				if ($upfile_data[$i]['f_name'] != '')
				{
					$chk_file_name = $upfile_data[$i]['f_name'];
					$new_file_name = $upfile_data[$i]['s_name'];
					$chk_file_size = $upfile_data[$i]['f_size'];
					$chk_file_type = $upfile_data[$i]['f_type'];
					$chk_file_ext  = $upfile_data[$i]['f_ext'];

				// 데이타 확인
					$file_where = " and consf.cons_idx = '" . $cons_idx . "' and consf.sort ='" . $i . "'";
					$file_data = consult_file_data('view', $file_where);

					if ($file_data['total_num'] == 0)
					{
						if ($file_chk == 1)
						{
							$query_str = "
								INSERT INTO " . $file_table . " (comp_idx, part_idx, cons_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
						}
						else $query_str .= ", ";

						$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($cons_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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
								del_yn = 'N' and cons_idx = '" . $cons_idx . "' and sort ='" . $i . "'";
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

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "consult_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['cons_idx']    = $cons_idx;
		$hi_param['status']      = '';
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '글이 등록되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "idx_num" : "' . $cons_idx . '", "f_class":"consult", "f_idx":"' . $cons_idx . '"}';
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
		$cons_idx = $_POST['cons_idx'];

		$command    = "update"; //명령어
		$table      = "consult_info"; //테이블명
		$conditions = "cons_idx = '" . $cons_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");
		if ($param["important"] == '') $param["important"] = 'RI01';

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		$file_num = $_POST['file_upload_num'];
		if ($file_num > 0)
		{
			global $tmp_path, $comp_consult_path;

			files_dir($comp_consult_path);
			$data_path = $comp_consult_path . '/' . $cons_idx;
			files_dir($data_path);

			$file_command    = "insert"; //명령어
			$file_table      = "consult_file"; //테이블명
			$file_conditions = ""; //조건

			$reg_id   = $param['reg_id'];
			$reg_date = $param['reg_date'];

			$query_str = '';
			$file_chk = 1;
			for ($i = 1; $i <= $file_num; $i++)
			{
				$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $cons_idx, 'consult');

				if ($upfile_data[$i]['f_name'] != '')
				{
					$chk_file_name = $upfile_data[$i]['f_name'];
					$new_file_name = $upfile_data[$i]['s_name'];
					$chk_file_size = $upfile_data[$i]['f_size'];
					$chk_file_type = $upfile_data[$i]['f_type'];
					$chk_file_ext  = $upfile_data[$i]['f_ext'];

				// 데이타 확인
					$file_where = " and consf.cons_idx = '" . $cons_idx . "' and consf.sort ='" . $i . "'";
					$file_data = consult_file_data('view', $file_where);

					if ($file_data['total_num'] == 0)
					{
						if ($file_chk == 1)
						{
							$query_str = "
								INSERT INTO " . $file_table . " (comp_idx, part_idx, cons_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
						}
						else $query_str .= ", ";

						$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($cons_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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
								del_yn = 'N' and cons_idx = '" . $cons_idx . "' and sort ='" . $i . "'";
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

		$str = '{"success_chk" : "Y", "idx_num" : "' . $cons_idx . '", "f_class":"consult", "f_idx":"' . $cons_idx . '"}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$cons_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "consult_info"; //테이블명
		$conditions = "cons_idx = '" . $cons_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string" :""}';
		echo $str;
		exit;
	}

// 파일삭제 함수
	function file_delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address, $comp_consult_path;

		$consf_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "consult_file"; //테이블명
		$conditions = "consf_idx = '" . $consf_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$data = query_view("select cons_idx, img_sname from " . $table . " where " . $conditions);
		$cons_idx  = $data['cons_idx'];
		$img_sname = $data["img_sname"];

		if ($img_sname != "") @unlink($comp_consult_path . '/' . $cons_idx . '/' . $img_sname);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "org_idx":"' . $cons_idx . '"}';
		echo $str;
		exit;
	}
?>