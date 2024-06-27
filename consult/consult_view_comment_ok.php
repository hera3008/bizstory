<?
/*
	수정 : 2012.10.09
	위치 : 설정관리 > 에이전트관리 > 상담게시판 > 상담게시판 - 보기 - 실행
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
		global $_POST, $_SESSION, $sess_str, $ip_address, $comp_consult_path;

		$comp_idx   = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx   = $_SESSION[$sess_str . '_part_idx'];
		$conscf_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "consult_comment_file"; //테이블명
		$conditions = "conscf_idx = '" . $conscf_idx . "'"; //조건

		$data = query_view("select cons_idx, img_sname, sort from " . $table . " where " . $conditions);

		$img_sname = $data["img_sname"];
		if ($img_sname != "") @unlink($comp_consult_path . '/' . $data['cons_idx'] . '/' . $img_sname);

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
		$part_idx = $_POST['part_idx'];
		$cons_idx = $_POST['cons_idx'];

		$command    = "insert"; //명령어
		$table      = "consult_comment"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date('Y-m-d H:i:s');
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;
		$param['cons_idx'] = $cons_idx;
		$param['ip_addr']  = $ip_address;
		$param['mem_idx']  = $_SESSION[$sess_str . '_mem_idx'];

		$chk_data = query_view("select max(consc_idx) as consc_idx, max(order_idx) as order_idx from " . $table);
		$param['consc_idx'] = ($chk_data['consc_idx'] == '') ? '1' : $chk_data['consc_idx'] + 1;
		$param['order_idx'] = ($chk_data['order_idx'] == '') ? '1' : $chk_data['order_idx'] + 1;

	// 회원정보
		$sub_where = " and mem.mem_idx = '" . $_SESSION[$sess_str . '_mem_idx'] . "'";
		$sub_data = member_info_data('view', $sub_where);
		if ($param['writer'] == "") $param['writer'] = $sub_data['mem_name'];

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	// 일반글일 경우
		if ($param["gno"] == "")
		{
			$gno       = $param['consc_idx'];
			$tgno      = 0;
			$order_idx = $param['order_idx'];
		}
	// 답변글일 경우
		else
		{
			$data = query_view("select tgno, order_idx from " . $table . " where consc_idx ='" . $param["gno"] . "'");
			$order_idx = $data['order_idx'];
			$tgno      = $data['tgno'] + 1;
			$gno       = $param["gno"];

			$update_sql = "
				update " . $table . " set
					order_idx = order_idx + 1
				where
					order_idx >='" . $order_idx . "'
			";
			db_query($update_sql);
			query_history($update_sql, $table, 'update');
		}

		$re_sql = "
			update " . $table . " SET
				gno       = '" . $gno . "',
				tgno      = '" . $tgno . "',
				order_idx = '" . $order_idx . "'
			where
				consc_idx = '" . $param['consc_idx'] . "'
		";
		db_query($re_sql);
		query_history($re_sql, $table, 'update');

		$cons_idx  = $param['cons_idx'];
		$consc_idx = $param['consc_idx'];

	//////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		$file_num = $_POST['file_upload_num'];
		if ($file_num > 0)
		{
			global $tmp_path, $comp_consult_path;

			$file_idx  = $cons_idx . '_' . $consc_idx;

			files_dir($comp_consult_path);
			$data_path = $comp_consult_path . '/' . $cons_idx;
			files_dir($data_path);

			$file_command    = "insert"; //명령어
			$file_table      = "consult_comment_file"; //테이블명
			$file_conditions = ""; //조건

			$reg_id   = $param['reg_id'];
			$reg_date = $param['reg_date'];

			$query_str = '';
			$file_chk = 1;
			for ($i = 1; $i <= $file_num; $i++)
			{
				$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $file_idx, 'consult_comment');

				if ($upfile_data[$i]['f_name'] != '')
				{
					$chk_file_name = $upfile_data[$i]['f_name'];
					$new_file_name = $upfile_data[$i]['s_name'];
					$chk_file_size = $upfile_data[$i]['f_size'];
					$chk_file_type = $upfile_data[$i]['f_type'];
					$chk_file_ext  = $upfile_data[$i]['f_ext'];

				// 데이타 확인
					$file_where = " and conscf.consc_idx = '" . $consc_idx . "' and conscf.sort ='" . $i . "'";
					$file_data = consult_comment_file_data('view', $file_where);

					if ($file_data['total_num'] == 0)
					{
						if ($file_chk == 1)
						{
							$query_str = "
								INSERT INTO " . $file_table . " (comp_idx, part_idx, cons_idx, consc_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
						}
						else $query_str .= ", ";

						$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($cons_idx) . "', '" . string_input($consc_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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
								del_yn = 'N' and consc_idx = '" . $consc_idx . "' and sort ='" . $i . "'";
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
		$hi_param['status_memo'] = '댓글이 등록되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

	// 총수구하기
		$comment_where = " and consc.cons_idx = '" . $cons_idx . "'";
		$comment_list = consult_comment_data('page', $comment_where);

		$str = '{"success_chk" : "Y", "total_num":"[' . number_format($comment_list['total_num']) . ']", "f_class":"consult_comment", "f_idx":"' . $consc_idx . '"}';
		echo $str;
		exit;
	}

// 댓글서 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param     = $_POST['param'];
		$comp_idx  = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx  = $_POST['part_idx'];
		$cons_idx  = $_POST['cons_idx'];
		$consc_idx = $_POST['consc_idx'];

		$command    = "update"; //명령어
		$table      = "consult_comment"; //테이블명
		$conditions = "consc_idx='" . $consc_idx . "'"; //조건

		$param["mod_id"]   = $_SESSION[$sess_str . '_mem_idx'];
		$param["mod_date"] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	//////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		$file_num = $_POST['file_upload_num'];
		if ($file_num > 0)
		{
			global $tmp_path, $comp_consult_path;

			$file_idx  = $cons_idx . '_' . $cons_idx;

			files_dir($comp_consult_path);
			$data_path = $comp_consult_path . '/' . $consc_idx;
			files_dir($data_path);

			$file_command    = "insert"; //명령어
			$file_table      = "consult_comment_file"; //테이블명
			$file_conditions = ""; //조건

			$reg_id   = $param['mod_id'];
			$reg_date = $param['mod_date'];

			$query_str = '';
			$file_chk = 1;
			for ($i = 1; $i <= $file_num; $i++)
			{
				$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $file_idx, 'consult_comment');

				if ($upfile_data[$i]['f_name'] != '')
				{
					$chk_file_name = $upfile_data[$i]['f_name'];
					$new_file_name = $upfile_data[$i]['s_name'];
					$chk_file_size = $upfile_data[$i]['f_size'];
					$chk_file_type = $upfile_data[$i]['f_type'];
					$chk_file_ext  = $upfile_data[$i]['f_ext'];

				// 데이타 확인
					$file_where = " and conscf.cons_idx = '" . $cons_idx . "' and conscf.sort ='" . $i . "'";
					$file_data = consult_comment_file_data('view', $file_where);

					if ($file_data['total_num'] == 0)
					{
						if ($file_chk == 1)
						{
							$query_str = "
								INSERT INTO " . $file_table . " (comp_idx, part_idx, cons_idx, consc_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
						}
						else $query_str .= ", ";

						$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($cons_idx) . "', '" . string_input($consc_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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
								del_yn = 'N' and consc_idx = '" . $consc_idx . "' and sort ='" . $i . "'";
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

	// 총수구하기
		$comment_where = " and consc.cons_idx = '" . $cons_idx . "'";
		$comment_list = consult_comment_data('page', $comment_where);

		$str = '{"success_chk" : "Y", "total_num":"[' . number_format($comment_list['total_num']) . ']", "f_class":"consult_comment", "f_idx":"' . $consc_idx . '"}';
		echo $str;
		exit;
	}

// 댓글서 삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$cons_idx  = $_POST['cons_idx'];
		$consc_idx = $_POST['consc_idx'];

		$command    = "update"; //명령어
		$table      = "consult_comment"; //테이블명
		$conditions = "consc_idx = '" . $consc_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	// 총수구하기
		$comment_where = " and consc.cons_idx = '" . $cons_idx . "'";
		$comment_list = consult_comment_data('page', $comment_where);

		$str = '{"success_chk" : "Y", "total_num":"[' . number_format($comment_list['total_num']) . ']"}';
		echo $str;
		exit;
	}
?>