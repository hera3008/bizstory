<?
/*
	생성 : 2012.05.16
	위치 : 게시판 - 보기 - 실행
*/
	require_once "../../bizstory/common/setting.php";
	require_once $local_path . "/cms/include/client_chk.php";
	require_once $local_path . "/cms/include/no_direct.php";

	$set_where = " and bs.bs_idx = '" . $bs_idx . "'";
	$set_board = pro_board_set_data("view", $set_where);
	$set_board['name_db'] = 'pro_board_biz_' . $set_board['comp_idx'];

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
		global $_POST, $_SESSION, $sess_str, $ip_address, $comp_pro_board_path, $set_board;

		$bcof_idx = $_POST['idx'];
		$comp_idx = $_POST['comp_idx'];
		$part_idx = $_POST['part_idx'];
		$ci_idx   = $_POST['ci_idx'];
		$bs_idx   = $_POST['bs_idx'];
		$b_idx    = $_POST['b_idx'];

		$command    = "update"; //명령어
		$table      = "pro_board_comment_file"; //테이블명
		$conditions = "bcof_idx = '" . $bcof_idx . "'"; //조건

		$data = query_view("select img_sname, sort from " . $table . " where " . $conditions);

		$img_sname = $data["img_sname"];
		if ($img_sname != "") @unlink($set_board['bbs_path'] . '/' . $data['b_idx'] . '/' . $img_sname);
		
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
		global $_POST, $_SESSION, $sess_str, $ip_address, $set_board;

		$param    = $_POST['param'];
		$comp_idx = $_POST['comp_idx'];
		$part_idx = $_POST['part_idx'];
		$ci_idx   = $_POST['ci_idx'];
		$bs_idx   = $_POST['bs_idx'];
		$b_idx    = $_POST['b_idx'];

		$command    = "insert"; //명령어
		$table      = "pro_board_comment"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date('Y-m-d H:i:s');
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;
		$param['ci_idx']   = $ci_idx;
		$param['bs_idx']   = $bs_idx;
		$param['b_idx']    = $b_idx;
		$param['mem_idx']  = $_SESSION[$sess_str . '_mem_idx'];
		$param['ip_addr']  = $ip_address;

		$chk_data = query_view("select max(bco_idx) as bco_idx from " . $table);
		$param['bco_idx'] = ($chk_data['bco_idx'] == '') ? '1' : $chk_data['bco_idx'] + 1;

	// 회원정보
		$sub_where = " and mem.mem_idx = '" . $_SESSION[$sess_str . '_mem_idx'] . "'";
		$sub_data = member_info_data('view', $sub_where);
		if ($param['writer'] == "") $param['writer'] = $sub_data['mem_name'];

		chk_before($param);

	//////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $comp_pro_board_path;

		$bco_idx   = $param['bco_idx'];
		$file_idx = $b_idx . '_' . $bco_idx;
		$board_path = $set_board['bbs_path'] . '/' . $b_idx;
		files_dir($board_path);

		$file_command    = "insert"; //명령어
		$file_table      = "pro_board_comment_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['reg_id'];
		$reg_date = $param['reg_date'];

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $board_path, $_POST, $file_idx, 'pro_board_comment');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and bcof.bco_idx = '" . $bco_idx . "' and bcof.sort ='" . $i . "'";
				$file_data = pro_board_comment_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					if ($file_chk == 1)
					{
						$query_str = "
							INSERT INTO " . $file_table . " (comp_idx, part_idx, ci_idx, bs_idx, b_idx, bco_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
					}
					else $query_str .= ", ";

					$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($ci_idx) . "', '" . string_input($bs_idx) . "', '" . string_input($b_idx) . "', '" . string_input($bco_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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
							del_yn = 'N' and bco_idx = '" . $bco_idx . "' and sort ='" . $i . "'";
					db_query($query_update);
				}
			}
		}
		if ($query_str != '')
		{
			db_query($query_str);
			query_history($query_str, $file_table, $file_command);
		}

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	// 총수구하기
		$comment_where = " and bco.bs_idx = '" . $bs_idx . "' and bco.b_idx = '" . $b_idx . "'";
		$comment_list = pro_board_comment_data('list', $comment_where, '', '', '');

		$str = '{"success_chk" : "Y", "total_num":"[' . number_format($comment_list['total_num']) . ']"}';
		echo $str;
		exit;
	}

// 댓글서 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str, $set_board;

		$param    = $_POST['param'];
		$comp_idx = $_POST['comp_idx'];
		$part_idx = $_POST['part_idx'];
		$ci_idx   = $_POST['ci_idx'];
		$bs_idx   = $_POST['bs_idx'];
		$b_idx    = $_POST['b_idx'];
		$bco_idx  = $_POST['bco_idx'];

		$command    = "update"; //명령어
		$table      = "pro_board_comment"; //테이블명
		$conditions = "bco_idx='" . $bco_idx . "'"; //조건

		$param["mod_id"]   = $_SESSION[$sess_str . '_mem_idx'];
		$param["mod_date"] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	//////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $comp_pro_board_path;

		$file_idx = $b_idx . '_' . $bco_idx;
		$board_path = $set_board['bbs_path'] . '/' . $b_idx;
		files_dir($board_path);

		$file_command    = "insert"; //명령어
		$file_table      = "pro_board_comment_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['mod_id'];
		$reg_date = $param['mod_date'];

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $board_path, $_POST, $file_idx, 'pro_board_comment');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and bcof.bco_idx = '" . $bco_idx . "' and bcof.sort ='" . $i . "'";
				$file_data = pro_board_comment_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					if ($file_chk == 1)
					{
						$query_str = "
							INSERT INTO " . $file_table . " (comp_idx, part_idx, ci_idx, bs_idx, b_idx, bco_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
					}
					else $query_str .= ", ";

					$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($ci_idx) . "', '" . string_input($bs_idx) . "', '" . string_input($b_idx) . "', '" . string_input($bco_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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
							del_yn = 'N' and bco_idx = '" . $bco_idx . "' and sort ='" . $i . "'";
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
		$comment_where = " and bco.bs_idx = '" . $bs_idx . "' and bco.b_idx = '" . $b_idx . "'";
		$comment_list = pro_board_comment_data('list', $comment_where, '', '', '');

		$str = '{"success_chk" : "Y", "total_num":"[' . number_format($comment_list['total_num']) . ']"}';
		echo $str;
		exit;
	}

// 댓글서 삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address, $set_board;

		$comp_idx = $_POST['comp_idx'];
		$part_idx = $_POST['part_idx'];
		$ci_idx   = $_POST['ci_idx'];
		$bs_idx   = $_POST['bs_idx'];
		$b_idx    = $_POST['b_idx'];
		$bco_idx  = $_POST['bco_idx'];

		$command    = "update"; //명령어
		$table      = "pro_board_comment"; //테이블명
		$conditions = "bco_idx = '" . $bco_idx . "'"; //조건
		
		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	// 총수구하기
		$comment_where = " and bco.bs_idx = '" . $bs_idx . "' and bco.b_idx = '" . $b_idx . "'";
		$comment_list = pro_board_comment_data('list', $comment_where, '', '', '');

		$str = '{"success_chk" : "Y", "total_num":"[' . number_format($comment_list['total_num']) . ']"}';
		echo $str;
		exit;
	}
?>