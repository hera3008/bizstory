<?
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
	function chk_before($param)
	{
	//필수검사
		$chk_param['require'][] = array("field"=>"mem_email", "msg"=>"이메일");

	//체크합니다.
		//param_check($param, $chk_param, 'json');
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param   = $_POST["param"];
		$mem_idx = $_SESSION[$sess_str . '_mem_idx'];

		$command    = "update"; //명령어
		$table      = "member_info"; //테이블명
		$conditions = "mem_idx = '" . $mem_idx . "'"; //조건

		$param["mod_id"]   = $_SESSION[$sess_str . "_mem_idx"];
		$param["mod_date"] = date("Y-m-d H:i:s");

		if ($param['mem_pwd'] == '') unset($param['mem_pwd']);
		else $param['mem_pwd'] = pass_change($param['mem_pwd'], $sess_str);

		$param['mem_email'] = $param['mem_email1'] . '@' . $param['mem_email2'];
		$param['zip_code']  = $param['zip_code1'] . '-' . $param['zip_code2'];
		$param['address']   = $param['address1'] . '||' . $param['address2'];
		$param['tel_num']   = $param['tel_num1'] . '-' . $param['tel_num2'] . '-' . $param['tel_num3'];
		$param['hp_num']    = $param['hp_num1'] . '-' . $param['hp_num2'] . '-' . $param['hp_num3'];

		unset($param['mem_email1']);
		unset($param['mem_email2']);
		unset($param['zip_code1']);
		unset($param['zip_code2']);
		unset($param['address1']);
		unset($param['address2']);
		unset($param['tel_num1']);
		unset($param['tel_num2']);
		unset($param['tel_num3']);
		unset($param['hp_num1']);
		unset($param['hp_num2']);
		unset($param['hp_num3']);

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $comp_member_path;

		$data_path = $comp_member_path . '/' . $mem_idx;
		files_dir($data_path);

		$file_command    = "insert"; //명령어
		$file_table      = "member_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['mod_id'];
		$reg_date = $param['mod_date'];

	// 총 저장 파일
		$j = 1;
		$file_num = $_POST['file_upload_num'];
		for($i=1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $mem_idx, 'member');

			if ($upfile_data[$i]['f_name'] != '')
			{	

				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];
				
			// 데이타 확인
				$file_where = " and mf.mem_idx = '" . $mem_idx . "' and mf.sort ='" . $j . "'";
				$file_data = member_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
					$part_idx = $_SESSION[$sess_str . '_part_idx'];
					$mem_name = $_SESSION[$sess_str . '_mem_name'];

					$query_str = "insert into " . $file_table . " set
							  comp_idx  = '" . string_input($comp_idx) . "'
							, part_idx  = '" . string_input($part_idx) . "'
							, mem_idx   = '" . string_input($mem_idx) . "'
							, sort      = '" . string_input($j) . "'
							, subject   = '" . $mem_name . " 사진'
							, img_fname = '" . string_input($chk_file_name) . "'
							, img_sname = '" . string_input($new_file_name) . "'
							, img_size  = '" . string_input($chk_file_size) . "'
							, img_type  = '" . string_input($chk_file_type) . "'
							, img_ext   = '" . string_input($chk_file_ext) . "'
							, reg_id    = '" . string_input($reg_id) . "'
							, reg_date  = '" . string_input($reg_date) . "'
					";
					db_query($query_str);
					query_history($query_str, $file_table, $file_command);
					$j ++;
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
							del_yn = 'N' and mem_idx = '" . $mem_idx . "' and sort ='" . $j . "'";
					db_query($query_update);
					query_history($query_update, $file_table, 'update');
					$j++;
				}
			}
		}

		$str = '{"success_chk" : "Y"}';
		echo $str;
		exit;
	}

// 파일삭제 함수
	function file_delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;
		global $comp_member_path;

		$mf_idx = $_POST['idx'];
		$sort   = $_POST['sort'];

		$file_where = " and mf.mf_idx = '" . $mf_idx . "'";
		$file_data = member_file_data('view', $file_where);

		$command    = "update"; //명령어
		$table      = "member_file"; //테이블명
		$conditions = "mf_idx = '" . $mf_idx . "'"; //조건
		
		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$delete_file = $comp_member_path . '/' . $mem_idx . '/' . $file_data['img_sname'];
		@unlink($delete_file);

		$str = '{"success_chk" : "Y"}';
		echo $str;
	}
?>