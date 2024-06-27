<?
/*
	생성 : 2012.08.07
	수정 : 2012.10.12
	위치 : 댓글 - 실행
*/
	include "../bizstory/common/setting.php";
	include "../bizstory/common/no_direct.php";
	include $local_path . "/agent/include/agent_chk.php";

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

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$param          = $_POST['param'];
		$ri_idx         = $_POST['ri_idx'];
		$client_idx     = $_POST['client_idx'];
		$macaddress     = $_POST['macaddress'];
		$comment_writer = $_POST['comment_writer'];

	// 거래처정보
		$client_where = " and ci.ci_idx = '" . $client_idx . "'";
		$client_data = client_info_data('view', $client_where);

		$comp_idx = $client_data['comp_idx'];
		$part_idx = $client_data['part_idx'];

		$command    = "insert"; //명령어
		$table      = "receipt_comment"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']     = $comment_writer;
		$param['reg_date']   = date('Y-m-d H:i:s');
		$param['comp_idx']   = $comp_idx;
		$param['part_idx']   = $part_idx;
		$param['ri_idx']     = $ri_idx;
		$param['ip_addr']    = $ip_address;
		$param['macaddress'] = $macaddress;
		$param['writer']     = $comment_writer;

		$chk_data = query_view("select max(rc_idx) as rc_idx, max(order_idx) as order_idx from " . $table);
		$param['rc_idx'] = ($chk_data['rc_idx'] == '') ? '1' : $chk_data['rc_idx'] + 1;
		$param['order_idx'] = ($chk_data['order_idx'] == '') ? '1' : $chk_data['order_idx'] + 1;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	//////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $receipt_path;

		$ri_idx    = $param['ri_idx'];
		$rc_idx    = $param['rc_idx'];
		$file_idx  = $ri_idx . '_' . $rc_idx;
		$data_path = $receipt_path . '/' . $ri_idx;
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
		$receipt_where = " and ri.ri_idx = '" . $ri_idx . "'";
		$receipt_data = receipt_info_data('view', $receipt_where);

		$hi_command    = "insert"; //명령어
		$hi_table      = "receipt_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $comment_writer;
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
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

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 푸시건 - 담당자에게
		receipt_push($ri_idx);

		$str = '{"success_chk" : "Y", "total_num":"[' . number_format($comment_list['total_num']) . ']", "f_class":"receipt_comment", "f_idx":"' . $rc_idx . '"}';
		echo $str;
		exit;
	}
?>