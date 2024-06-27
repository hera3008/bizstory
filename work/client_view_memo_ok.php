<?
/*
	수정 : 2012.08.27
	위치 : 고객관리 > 거래처목록 - 보기 - 메모실행
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
		$chk_param['require'][] = array("field"=>"remark", "msg"=>"메모내용");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$param    = $_POST['param'];
		$comp_idx = $_POST['comp_idx'];
		$part_idx = $_POST['part_idx'];
		$ci_idx   = $_POST['ci_idx'];
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];

		$command    = "insert"; //명령어
		$table      = "client_memo"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $mem_idx;
		$param['reg_date'] = date('Y-m-d H:i:s');
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;
		$param['ci_idx']   = $ci_idx;
		$param['ip_addr']  = $ip_address;
		$param['mem_idx']  = $mem_idx;

		$chk_data = query_view("select max(cim_idx) as cim_idx, max(order_idx) as order_idx from " . $table);
		$param['cim_idx']   = ($chk_data['cim_idx'] == '') ? '1' : $chk_data['cim_idx'] + 1;
		$param['order_idx'] = ($chk_data['order_idx'] == '') ? '1' : $chk_data['order_idx'] + 1;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	//////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $comp_client_path;

		files_dir($comp_client_path);
		$data_path = $comp_client_path . '/' . $ci_idx;
		files_dir($data_path);

		$cim_idx  = $param['cim_idx'];
		$file_idx = $ci_idx . '_' . $cim_idx;

		$file_command    = "insert"; //명령어
		$file_table      = "client_memo_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['reg_id'];
		$reg_date = $param['reg_date'];

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $file_idx, 'client_memo');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and cimf.cim_idx = '" . $cim_idx . "' and cimf.sort ='" . $i . "'";
				$file_data = client_memo_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					if ($file_chk == 1)
					{
						$query_str = "
							INSERT INTO " . $file_table . " (comp_idx, part_idx, ci_idx, cim_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
					}
					else $query_str .= ", ";

					$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($ci_idx) . "', '" . string_input($cim_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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
							del_yn = 'N' and cim_idx = '" . $cim_idx . "' and sort ='" . $i . "'";
					query_history($query_update, $file_table, 'update');
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
		$comment_where = " and cim.ci_idx = '" . $ci_idx . "'";
		$comment_list = client_memo_data('list', $comment_where, '', '', '');

		$str = '{"success_chk" : "Y", "total_num":"[' . number_format($comment_list['total_num']) . ']", "f_class":"client_memo", "f_idx":"' . $cim_idx . '"}';
		echo $str;
		exit;
	}

// 메모 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$comp_idx = $_POST['comp_idx'];
		$part_idx = $_POST['part_idx'];
		$ci_idx   = $_POST['ci_idx'];
		$cim_idx  = $_POST['cim_idx'];
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];

		$command    = "update"; //명령어
		$table      = "client_memo"; //테이블명
		$conditions = "cim_idx='" . $cim_idx . "'"; //조건

		$param["mod_id"]   = $mem_idx;
		$param["mod_date"] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	//////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $comp_client_path;

		files_dir($comp_client_path);
		$data_path = $comp_client_path . '/' . $ci_idx;
		files_dir($data_path);
		$file_idx = $ci_idx . '_' . $cim_idx;

		$file_command    = "insert"; //명령어
		$file_table      = "client_memo_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['mod_id'];
		$reg_date = $param['mod_date'];

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $file_idx, 'client_memo');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and cimf.cim_idx = '" . $cim_idx . "' and cimf.sort ='" . $i . "'";
				$file_data = client_memo_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					if ($file_chk == 1)
					{
						$query_str = "
							INSERT INTO " . $file_table . " (comp_idx, part_idx, ci_idx, cim_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
					}
					else $query_str .= ", ";

					$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($ci_idx) . "', '" . string_input($cim_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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
							del_yn = 'N' and cim_idx = '" . $cim_idx . "' and sort ='" . $i . "'";
					query_history($query_update, $file_table, 'update');
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
		$memo_where = " and cim.ci_idx = '" . $ci_idx . "'";
		$memo_list = client_memo_data('list', $memo_where, '', '', '');

		$str = '{"success_chk" : "Y", "total_num":"[' . number_format($memo_list['total_num']) . ']", "f_class":"client_memo", "f_idx":"' . $cim_idx . '"}';
		echo $str;
		exit;
	}

// 메모 삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$ci_idx  = $_POST['ci_idx'];
		$cim_idx = $_POST['cim_idx'];
		$mem_idx = $_SESSION[$sess_str . '_mem_idx'];

		$command    = "update"; //명령어
		$table      = "client_memo"; //테이블명
		$conditions = "cim_idx = '" . $cim_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $mem_idx;
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	// 총수구하기
		$memo_where = " and cim.ci_idx = '" . $ci_idx . "'";
		$memo_list = client_memo_data('list', $memo_where, '', '', '');

		$str = '{"success_chk" : "Y", "total_num":"[' . number_format($memo_list['total_num']) . ']"}';
		echo $str;
		exit;
	}

// 파일삭제 함수
	function file_delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address, $comp_client_path;

		$comp_idx = $_POST['comp_idx'];
		$part_idx = $_POST['part_idx'];
		$cimf_idx = $_POST['idx'];
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];

		$command    = "update"; //명령어
		$table      = "client_memo_file"; //테이블명
		$conditions = "cimf_idx = '" . $cimf_idx . "'"; //조건

		$data = query_view("select ci_idx, img_sname, sort from " . $table . " where " . $conditions);

		$img_sname = $data["img_sname"];
		if ($img_sname != "") @unlink($comp_client_path . '/' . $data['ci_idx'] . '/' . $img_sname);

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $mem_idx;
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y"}';
		echo $str;
		exit;
	}
?>