<?
/*
	생성 : 2012.12.10
	수정 : 2012.12.10
	위치 : 설정폴더 > 설정관리 > 총판관리 - 실행
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
		$chk_param['require'][] = array("field"=>"sole_name", "msg"=>"총판명");
		$chk_param['require'][] = array("field"=>"sole_id", "msg"=>"아이디");
		$chk_param['require'][] = array("field"=>"sole_pwd", "msg"=>"비밀번호");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 아이디 함수
	function double_id()
	{
		global $_GET;

		$sole_id = $_GET['sole_id'];
		$sole_id = string_input($sole_id);

		if ($sole_id == '')
		{
			$str = '{"success_chk" : "N", "error_string"  : "아이디를 입력하세요."}';
		}
		else
		{
			$sole_where = " and sole.sole_id = '" . $sole_id . "'";
			$sole_data = sole_info_data('page', $sole_where);

			if ($sole_data['total_num'] == 0)
			{
				$str = '{"success_chk" : "Y", "double_chk"  : "N"}';
			}
			else
			{
				$str = '{"success_chk" : "Y", "double_chk"  : "Y"}';
			}
		}
		echo $str;
		exit;
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param = $_POST['param'];

		$command    = "insert"; //명령어
		$table      = "sole_info"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");

		if ($param['view_yn'] == '') $param['view_yn'] = 'N';
		$param['sole_pwd'] = pass_change($param['sole_pwd'], $sess_str);
		unset($param['sole_pwd2']);

		$param['comp_num']     = $param['comp_num1'] . '-' . $param['comp_num2'] . '-' . $param['comp_num3'];
		$param['zip_code']     = $param['zip_code1'] . '-' . $param['zip_code2'];
		$param['address']      = $param['address1'] . '||' . $param['address2'];
		$param['tel_num']      = $param['tel_num1'] . '-' . $param['tel_num2'] . '-' . $param['tel_num3'];
		$param['comp_email']   = $param['comp_email1'] . '@' . $param['comp_email2'];

		$sub_data = query_view("select max(sole_idx) as sole_idx from " . $table);
		$param["sole_idx"] = ($sub_data["sole_idx"] == "") ? "1" : $sub_data["sole_idx"] + 1;

		unset($param['comp_num1']);
		unset($param['comp_num2']);
		unset($param['comp_num3']);
		unset($param['zip_code1']);
		unset($param['zip_code2']);
		unset($param['address1']);
		unset($param['address2']);
		unset($param['tel_num1']);
		unset($param['tel_num2']);
		unset($param['tel_num3']);
		unset($param['comp_email1']);
		unset($param['comp_email2']);

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $sole_path;

		$file_num  = $_POST['upload_fnum'];
		$data_path = $sole_path;
		$sole_idx  = $param["sole_idx"];

		$file_command    = "insert"; //명령어
		$file_table      = "sole_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $_SESSION[$sess_str . '_mem_idx'];
		$reg_date = date("Y-m-d H:i:s");

		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $sole_idx, 'sole');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name  = $upfile_data[$i]['f_name'];
				$new_file_name  = $upfile_data[$i]['s_name'];
				$chk_file_size  = $upfile_data[$i]['f_size'];
				$chk_file_type  = $upfile_data[$i]['f_type'];
				$chk_file_ext   = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and solef.sole_idx = '" . $sole_idx . "' and solef.sort ='" . $i . "'";
				$file_data = sole_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					$query_str = "insert into " . $file_table . " set
							  sole_idx   = '" . string_input($sole_idx) . "'
							, sort       = '" . string_input($i) . "'
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
							img_fname = '" . string_input($chk_file_name) . "',
							img_sname = '" . string_input($new_file_name) . "',
							img_size  = '" . string_input($chk_file_size) . "',
							img_type  = '" . string_input($chk_file_type) . "',
							img_ext   = '" . string_input($chk_file_ext) . "',
							mod_id    = '" . string_input($reg_id) . "',
							mod_date  = '" . string_input($reg_date) . "'
						where
							del_yn = 'N' and sole_idx = '" . $sole_idx . "' and sort ='" . $i . "'";
					db_query($query_update);
					query_history($query_update, $file_table, 'update');
				}
			}
		}

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$sole_idx = $_POST['sole_idx'];
		$param    = $_POST['param'];

		$command    = "update"; //명령어
		$table      = "sole_info"; //테이블명
		$conditions = "sole_idx = '" . $sole_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($param['view_yn'] == '') $param['view_yn'] = 'N';

		if ($param['sole_pwd'] == '')
		{
			unset($param['sole_pwd']);
		}
		else
		{
			$param['sole_pwd'] = pass_change($param['sole_pwd'], $sess_str);
		}
		unset($param['sole_pwd2']);

		$param['comp_num']     = $param['comp_num1'] . '-' . $param['comp_num2'] . '-' . $param['comp_num3'];
		$param['zip_code']     = $param['zip_code1'] . '-' . $param['zip_code2'];
		$param['address']      = $param['address1'] . '||' . $param['address2'];
		$param['tel_num']      = $param['tel_num1'] . '-' . $param['tel_num2'] . '-' . $param['tel_num3'];
		$param['comp_email']   = $param['comp_email1'] . '@' . $param['comp_email2'];

		unset($param['comp_num1']);
		unset($param['comp_num2']);
		unset($param['comp_num3']);
		unset($param['zip_code1']);
		unset($param['zip_code2']);
		unset($param['address1']);
		unset($param['address2']);
		unset($param['tel_num1']);
		unset($param['tel_num2']);
		unset($param['tel_num3']);
		unset($param['comp_email1']);
		unset($param['comp_email2']);

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $sole_path;

		$file_num  = $_POST['upload_fnum'];
		$data_path = $sole_path;

		$file_command    = "insert"; //명령어
		$file_table      = "sole_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $_SESSION[$sess_str . '_mem_idx'];
		$reg_date = date("Y-m-d H:i:s");

		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $sole_idx, 'sole');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name  = $upfile_data[$i]['f_name'];
				$new_file_name  = $upfile_data[$i]['s_name'];
				$chk_file_size  = $upfile_data[$i]['f_size'];
				$chk_file_type  = $upfile_data[$i]['f_type'];
				$chk_file_ext   = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and solef.sole_idx = '" . $sole_idx . "' and solef.sort ='" . $i . "'";
				$file_data = sole_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					$query_str = "insert into " . $file_table . " set
							  sole_idx   = '" . string_input($sole_idx) . "'
							, sort       = '" . string_input($i) . "'
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
							img_fname = '" . string_input($chk_file_name) . "',
							img_sname = '" . string_input($new_file_name) . "',
							img_size  = '" . string_input($chk_file_size) . "',
							img_type  = '" . string_input($chk_file_type) . "',
							img_ext   = '" . string_input($chk_file_ext) . "',
							mod_id    = '" . string_input($reg_id) . "',
							mod_date  = '" . string_input($reg_date) . "'
						where
							del_yn = 'N' and sole_idx = '" . $sole_idx . "' and sort ='" . $i . "'";
					db_query($query_update);
					query_history($query_update, $file_table, 'update');
				}
			}
		}

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$sole_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "sole_info"; //테이블명
		$conditions = "sole_idx = '" . $sole_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// yn 함수
	function check_yn()
	{
		global $_POST, $_SESSION, $sess_str;

		$sub_action = $_POST['sub_action'];
		$sole_idx   = $_POST['idx'];
		$post_value = $_POST['post_value'];

		$command    = "update"; //명령어
		$table      = "sole_info"; //테이블명
		$conditions = "sole_idx = '" . $sole_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($post_value == "Y") $param[$sub_action] = "N";
		else $param[$sub_action] = "Y";

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// 파일삭제 함수
	function file_delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;
		global $sole_path;

		$sole_idx  = $_POST['sole_idx'];
		$solef_idx = $_POST['idx'];

		$file_where = " and solef.solef_idx = '" . $solef_idx . "'";
		$file_data = sole_file_data('view', $file_where);

		$command    = "update"; //명령어
		$table      = "sole_file"; //테이블명
		$conditions = "solef_idx = '" . $solef_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$delete_file = $sole_path . '/' . $file_data['img_sname'];
		@unlink($delete_file);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}
?>