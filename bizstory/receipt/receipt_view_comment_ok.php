<?
/*
	수정 : 2012.07.09
	위치 : 고객관리 > 접수목록 - 보기 - 댓글 - 실행
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
		$chk_param['require'][] = array("field"=>"remark", "msg"=>"댓글내용");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 파일삭제 함수
	function file_delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address, $comp_receipt_path;

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$rcf_idx  = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "receipt_comment_file"; //테이블명
		$conditions = "rcf_idx = '" . $rcf_idx . "'"; //조건

		$data = query_view("select ri_idx, img_sname, sort from " . $table . " where " . $conditions);

		$img_sname = $data["img_sname"];
		if ($img_sname != "") @unlink($comp_receipt_path . '/' . $data['ri_idx'] . '/' . $img_sname);

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

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$ri_idx   = $_POST['ri_idx'];

		$command    = "insert"; //명령어
		$table      = "receipt_comment"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date('Y-m-d H:i:s');
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;
		$param['ri_idx']   = $ri_idx;
		$param['ip_addr']  = $ip_address;
		$param['mem_idx']  = $_SESSION[$sess_str . '_mem_idx'];

		$chk_data = query_view("select max(rc_idx) as rc_idx, max(order_idx) as order_idx from " . $table);
		$param['rc_idx'] = ($chk_data['rc_idx'] == '') ? '1' : $chk_data['rc_idx'] + 1;
		$param['order_idx'] = ($chk_data['order_idx'] == '') ? '1' : $chk_data['order_idx'] + 1;

	// 회원정보
		$sub_where = " and mem.mem_idx = '" . $_SESSION[$sess_str . '_mem_idx'] . "'";
		$sub_data = member_info_data('view', $sub_where);
		if ($param['writer'] == "") $param['writer'] = $sub_data['mem_name'];

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	//////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $comp_receipt_path;

		$ri_idx    = $param['ri_idx'];
		$rc_idx    = $param['rc_idx'];
		$file_idx  = $ri_idx . '_' . $rc_idx;
		$data_path = $comp_receipt_path . '/' . $ri_idx;
		files_dir($data_path);

		$file_command    = "insert"; //명령어
		$file_table      = "receipt_comment_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['reg_id'];
		$reg_date = $param['reg_date'];

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $file_idx, 'receipt_comment');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and rcf.rc_idx = '" . $rc_idx . "' and rcf.sort ='" . $i . "'";
				$file_data = receipt_comment_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					if ($file_chk == 1)
					{
						$query_str = "
							INSERT INTO " . $file_table . " (comp_idx, part_idx, ri_idx, rc_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
					}
					else $query_str .= ", ";

					$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($ri_idx) . "', '" . string_input($rc_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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
							del_yn = 'N' and rc_idx = '" . $rc_idx . "' and sort ='" . $i . "'";
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
	// 히스토리저장
		$receipt_where = " and ri.ri_idx = '" . $ri_idx . "'";
		$receipt_data = receipt_info_data('view', $receipt_where);

		$hi_command    = "insert"; //명령어
		$hi_table      = "receipt_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['ri_idx']      = $ri_idx;
		$hi_param['status']      = '';
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '댓글이 등록되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

	// 총수구하기
		$comment_where = " and rc.ri_idx = '" . $ri_idx . "'";
		$comment_list = receipt_comment_data('list', $comment_where, '', '', '');

		$str = '{"success_chk" : "Y", "total_num":"[' . number_format($comment_list['total_num']) . ']", "f_class":"receipt_comment", "f_idx":"' . $rc_idx . '"}';
		echo $str;
		exit;
	}

// 댓글서 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$ri_idx   = $_POST['ri_idx'];
		$rc_idx   = $_POST['rc_idx'];

		$command    = "update"; //명령어
		$table      = "receipt_comment"; //테이블명
		$conditions = "rc_idx='" . $rc_idx . "'"; //조건

		$param["mod_id"]   = $_SESSION[$sess_str . '_mem_idx'];
		$param["mod_date"] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	//////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $comp_receipt_path;

		$file_idx = $ri_idx . '_' . $ri_idx;
		$data_path = $comp_receipt_path . '/' . $ri_idx;
		files_dir($data_path);

		$file_command    = "insert"; //명령어
		$file_table      = "receipt_comment_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['mod_id'];
		$reg_date = $param['mod_date'];

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $file_idx, 'receipt_comment');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and rcf.ri_idx = '" . $ri_idx . "' and rcf.sort ='" . $i . "'";
				$file_data = receipt_comment_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					if ($file_chk == 1)
					{
						$query_str = "
							INSERT INTO " . $file_table . " (comp_idx, part_idx, ri_idx, rc_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
					}
					else $query_str .= ", ";

					$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($ri_idx) . "', '" . string_input($rc_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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
							del_yn = 'N' and ri_idx = '" . $ri_idx . "' and sort ='" . $i . "'";
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
		$comment_where = " and rc.ri_idx = '" . $ri_idx . "'";
		$comment_list = receipt_comment_data('list', $comment_where, '', '', '');

		$str = '{"success_chk" : "Y", "error_string" : "", "total_num":"[' . number_format($comment_list['total_num']) . ']", "f_class":"receipt_comment", "f_idx":"' . $rc_idx . '"}';
		echo $str;
		exit;
	}

// 댓글서 삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$ri_idx = $_POST['ri_idx'];
		$rc_idx = $_POST['rc_idx'];

		$command    = "update"; //명령어
		$table      = "receipt_comment"; //테이블명
		$conditions = "rc_idx = '" . $rc_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	// 총수구하기
		$comment_where = " and rc.ri_idx = '" . $ri_idx . "'";
		$comment_list = receipt_comment_data('list', $comment_where, '', '', '');

		$str = '{"success_chk" : "Y", "error_string" : "", "total_num":"[' . number_format($comment_list['total_num']) . ']"}';
		echo $str;
		exit;
	}
?>