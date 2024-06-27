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

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST["param"];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];

		$command    = "update"; //명령어
		$table      = "company_info"; //테이블명
		$conditions = "comp_idx = '" . $comp_idx . "'"; //조건

		$param["mod_id"]   = $_SESSION[$sess_str . "_mem_idx"];
		$param["mod_date"] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	// 파일저장
		global $tmp_path, $comp_company_path, $set_comp_file;

		$file_num  = $_POST['upload_fnum'];
		$data_path = $comp_company_path;

		$file_command    = "insert"; //명령어
		$file_table      = "company_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $_SESSION[$sess_str . '_mem_idx'];
		$reg_date = date("Y-m-d H:i:s");

		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $comp_idx, 'company');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_subject    = $_POST['file_subject' . $i];
				$chk_file_class = $_POST['file_class' . $i];

				$chk_file_name  = $upfile_data[$i]['f_name'];
				$new_file_name  = $upfile_data[$i]['s_name'];
				$chk_file_size  = $upfile_data[$i]['f_size'];
				$chk_file_type  = $upfile_data[$i]['f_type'];
				$chk_file_ext   = $upfile_data[$i]['f_ext'];

				if ($chk_file_class == '') $chk_file_class = $set_comp_file[$i]['0'];
				if ($chk_subject == '') $chk_subject = $set_comp_file[$i]['1'];

			// 데이타 확인
				$file_where = " and cf.comp_idx = '" . $comp_idx . "' and cf.sort ='" . $i . "'";
				$file_data = company_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					$query_str = "insert into " . $file_table . " set
							  comp_idx   = '" . string_input($comp_idx) . "'
							, part_idx   = '" . string_input($part_idx) . "'
							, file_class = '" . string_input($chk_file_class) . "'
							, sort       = '" . string_input($i) . "'
							, subject    = '" . string_input($chk_subject) . "'
							, img_fname  = '" . string_input($chk_file_name) . "'
							, img_sname  = '" . string_input($new_file_name) . "'
							, img_size   = '" . string_input($chk_file_size) . "'
							, img_type   = '" . string_input($chk_file_type) . "'
							, img_ext    = '" . string_input($chk_file_ext) . "'
							, reg_id     = '" . string_input($reg_id) . "'
							, reg_date   = '" . string_input($reg_date) . "'
					";
					db_query($query_str);
					query_history($query_str, $file_table, $file_command);
				}
				else
				{
					$query_update = "update " . $file_table . " set
							subject   = '" . string_input($chk_subject) . "',
							img_fname = '" . string_input($chk_file_name) . "',
							img_sname = '" . string_input($new_file_name) . "',
							img_size  = '" . string_input($chk_file_size) . "',
							img_type  = '" . string_input($chk_file_type) . "',
							img_ext   = '" . string_input($chk_file_ext) . "',
							mod_id    = '" . string_input($reg_id) . "',
							mod_date  = '" . string_input($reg_date) . "'
						where
							del_yn = 'N' and comp_idx = '" . $comp_idx . "' and sort ='" . $i . "'";
					db_query($query_update);
					query_history($query_update, $file_table, 'update');
				}
			}
		}

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// 파일삭제 함수
	function file_delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;
		global $comp_company_path;

		$comp_idx = $_POST['comp_idx'];
		$cf_idx   = $_POST['idx'];

		$file_where = " and cf.cf_idx = '" . $cf_idx . "'";
		$file_data = company_file_data('view', $file_where);

		$command    = "update"; //명령어
		$table      = "company_file"; //테이블명
		$conditions = "cf_idx = '" . $cf_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$delete_file = $comp_company_path . '/' . $file_data['img_sname'];
		@unlink($delete_file);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}
?>