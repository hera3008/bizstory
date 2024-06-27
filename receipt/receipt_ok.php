<?
/*
	수정 : 2013.02.22
	위치 : 고객관리 > 접수목록 - 실행
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
		$chk_param['require'][] = array("field"=>"receipt_class", "msg"=>"분류");
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
	// 접수저장
		$command    = "insert"; //명령어
		$table      = "receipt_info"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");
		$param['comp_idx'] = $comp_idx;

		$data = query_view("select max(ri_idx) as ri_idx from " . $table);
		$param['ri_idx'] = ($data["ri_idx"] == "") ? '1' : $data["ri_idx"] + 1;

		$param["receipt_status"] = 'RS01'; // 접수상태
		if ($param["important"] == '') $param["important"] = 'RI01';

	// 담당자넣기
		$ci_where = " and ci.ci_idx = '" . $param['ci_idx'] . "'";
		$ci_data = client_info_data('view', $ci_where);
		$param["charge_mem_idx"] = $ci_data["mem_idx"];

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $comp_receipt_path;

		$ri_idx    = $param['ri_idx'];
		$data_path = $comp_receipt_path . '/' . $ri_idx;
		files_dir($data_path);

		$file_command    = "insert"; //명령어
		$file_table      = "receipt_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['reg_id'];
		$reg_date = $param['reg_date'];

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $ri_idx, 'receipt');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and rf.ri_idx = '" . $ri_idx . "' and rf.sort ='" . $i . "'";
				$file_data = receipt_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					if ($file_chk == 1)
					{
						$query_str = "
							INSERT INTO " . $file_table . " (comp_idx, part_idx, ri_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
					}
					else $query_str .= ", ";

					$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($ri_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "receipt_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['ri_idx']      = $param['ri_idx'];
		$hi_param['status']      = $param["receipt_status"];
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '접수가 등록되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 푸시건 - 담당자에게
		receipt_push($param['ri_idx']);

		$str = '{"success_chk" : "Y", "idx_num" : "' . $param['ri_idx'] . '", "f_class":"receipt", "f_idx":"' . $ri_idx . '"}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$ri_idx   = $_POST['ri_idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $param['part_idx'];

		$command    = "update"; //명령어
		$table      = "receipt_info"; //테이블명
		$conditions = "ri_idx = '" . $ri_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");
		if ($param["important"] == '') $param["important"] = 'RI01';

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $comp_receipt_path;

		$data_path = $comp_receipt_path . '/' . $ri_idx;
		files_dir($data_path);

		$file_command    = "insert"; //명령어
		$file_table      = "receipt_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['mod_id'];
		$reg_date = $param['mod_date'];

	// 총 저장 파일
		$file_num = $_POST['file_upload_num'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $ri_idx, 'receipt');

			if ($upfile_data[$i]['f_name'] != '')
			{
				$chk_file_name = $upfile_data[$i]['f_name'];
				$new_file_name = $upfile_data[$i]['s_name'];
				$chk_file_size = $upfile_data[$i]['f_size'];
				$chk_file_type = $upfile_data[$i]['f_type'];
				$chk_file_ext  = $upfile_data[$i]['f_ext'];

			// 데이타 확인
				$file_where = " and rf.ri_idx = '" . $ri_idx . "' and rf.sort ='" . $i . "'";
				$file_data = receipt_file_data('view', $file_where);

				if ($file_data['total_num'] == 0)
				{
					if ($file_chk == 1)
					{
						$query_str = "
							INSERT INTO " . $file_table . " (comp_idx, part_idx, ri_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
					}
					else $query_str .= ", ";

					$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($ri_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
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

		$str = '{"success_chk" : "Y", "idx_num" : "' . $ri_idx . '", "f_class":"receipt", "f_idx":"' . $ri_idx . '"}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_POST['code_part'];
		$ri_idx   = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "receipt_info"; //테이블명
		$conditions = "ri_idx = '" . $ri_idx . "'"; //조건

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
		global $_POST, $_SESSION, $sess_str, $ip_address, $comp_receipt_path;

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$rf_idx   = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "receipt_file"; //테이블명
		$conditions = "rf_idx = '" . $rf_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$data = query_view("select ri_idx, img_sname from " . $table . " where " . $conditions);
		$ri_idx    = $data['ri_idx'];
		$img_sname = $data["img_sname"];

		if ($img_sname != "") @unlink($comp_receipt_path . '/' . $ri_idx . '/' . $img_sname);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "org_idx":"' . $ri_idx . '"}';
		echo $str;
		exit;
	}

// 단일 함수
	function singular_post()
	{
		global $_POST, $_SESSION, $sess_str, $set_receipt_status;

		$ri_idx         = $_POST['ri_idx'];
		$rid_idx        = $_POST['rid_idx'];
		$receipt_class  = $_POST['detail_receipt_class'];
		$mem_idx        = $_POST['detail_mem_idx'];
		$end_pre_date   = $_POST['detail_end_pre_date'];
		$receipt_type   = '1';

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_POST['part_idx'];

	// 접수정보
		$receipt_where = " and ri.ri_idx = '" . $ri_idx . "'";
		$receipt_data = receipt_info_data('view', $receipt_where);

		$param['ci_idx']        = $receipt_data['ci_idx'];
		$param['receipt_class'] = $receipt_class;
		$param['mem_idx']       = $mem_idx;
		$param['end_pre_date']  = $end_pre_date;
		$param['receipt_type']  = $receipt_type;

		$chk_where = "and rid.ri_idx = '" . $ri_idx . "' and rid.receipt_type = '" . $receipt_type . "'";
		$chk_data = receipt_info_detail_data('view', $chk_where);
		if ($chk_data['total_num'] == 0) // 없을 경우
		{
			$command    = "insert"; //명령어
			$table      = "receipt_info_detail"; //테이블명
			$conditions = ""; //조건

			$receipt_status = 'RS02';
			$status_memo    = '승인되었습니다.';

			$data = query_view("select max(rid_idx) as rid_idx from " . $table);
			$param["rid_idx"] = ($data["rid_idx"] == "") ? "1" : $data["rid_idx"] + 1;

			$param['comp_idx']       = $comp_idx;
			$param['part_idx']       = $part_idx;
			$param['ri_idx']         = $ri_idx;
			$param['receipt_status'] = $receipt_status;
			$param['remark']         = $receipt_data['subject'];
			$param['reg_id']         = $_SESSION[$sess_str . '_mem_idx'];
			$param['reg_date']       = date("Y-m-d H:i:s");

			$rid_idx = $param["rid_idx"];

			$query_str = make_sql($param, $command, $table, $conditions);
			db_query($query_str);
			query_history($query_str, $table, $command);

		// 본 접수상태변경
			$org_command    = "update"; //명령어
			$org_table      = "receipt_info"; //테이블명
			$org_conditions = "ri_idx = '" . $ri_idx . "'"; //조건

			$org_param['mod_id']         = $_SESSION[$sess_str . '_mem_idx'];
			$org_param['mod_date']       = date("Y-m-d H:i:s");
			$org_param['receipt_status'] = $receipt_status;
			$org_param['charge_mem_idx'] = $mem_idx;

			$query_str = make_sql($org_param, $org_command, $org_table, $org_conditions);
			db_query($query_str);
			query_history($query_str, $org_table, $org_command);

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 푸시건 - 담당자에게
			if ($_SESSION[$sess_str . '_mem_idx'] != $mem_idx)
			{
				receipt_push($rid_idx, 'detail');
			}
		}
		else
		{
		// 원래정보
			$detail_where = " and rid.rid_idx = '" . $rid_idx . "'";
			$detail_data = receipt_info_detail_data('view', $detail_where);

			$old_receipt_class  = $detail_data['receipt_class'];
			$old_mem_idx        = $detail_data['mem_idx'];
			$old_end_pre_date   = date_replace($detail_data['end_pre_date'], 'Y-m-d');

			$command    = "update"; //명령어
			$table      = "receipt_info_detail"; //테이블명
			$conditions = "rid_idx = '" . $rid_idx . "'"; //조건

			$status_memo = '';
			if ($old_receipt_class != $receipt_class)
			{
				$old_code_where = " and code.code_idx = '" . $old_receipt_class . "'";
				$old_code_data = code_receipt_class_data('view', $old_code_where);

				$new_code_where = " and code.code_idx = '" . $receipt_class . "'";
				$new_code_data = code_receipt_class_data('view', $new_code_where);

				$status_memo .= '분류 ' . $old_code_data['code_name'] . '(에)서 ' . $new_code_data['code_name'] . '(으)로 변경되었습니다. ';
			}
			if ($old_mem_idx != $mem_idx)
			{
				$old_mem_where = " and mem.mem_idx = '" . $old_mem_idx . "'";
				$old_mem_data = member_info_data('view', $old_mem_where);

				$new_mem_where = " and mem.mem_idx = '" . $mem_idx . "'";
				$new_mem_data = member_info_data('view', $new_mem_where);

				$status_memo .= '담당자 ' . $old_mem_data['mem_name'] . '(에)서 ' . $new_mem_data['mem_name'] . '(으)로 변경되었습니다. ';

			// 푸쉬전송
				//$push = new PUSH("bizstory_push");

				$msg_type = 'receipt';
				$message = strip_tags($detail_data['remark']);
				$message = '[담당자변경] ' . string_cut($message, 20);

				if ($mem_idx != $_SESSION[$sess_str . '_mem_idx'] && $mem_idx > 0)
				{
					$mem_where = " and mem.mem_idx = '" . $mem_idx . "'";
					$mem_data = member_info_data('view', $mem_where);

					$receiver = $mem_data['mem_id'];
					//$result = @$push->push_send($sender, $detail_data['comp_idx'], $detail_data['part_idx'], $mem_idx, $receiver, $msg_type, $message);
                    push_send($sender, $detail_data['comp_idx'], $detail_data['part_idx'], $mem_idx, $receiver, $msg_type, $message);
					unset($mem_data);
				}
			}
			if ($old_end_pre_date != $end_pre_date)
			{
				if ($old_end_pre_date == '')
				{
					$status_memo .= '완료예정일 ' . $end_pre_date . '(으)로 변경되었습니다. ';
				}
				else
				{
					$status_memo .= '완료예정일 ' . $old_end_pre_date . '(에)서 ' . $end_pre_date . '(으)로 변경되었습니다. ';
				}
			}

			$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
			$param['mod_date'] = date("Y-m-d H:i:s");

			$query_str = make_sql($param, $command, $table, $conditions);
			db_query($query_str);
			query_history($query_str, $table, $command);
		}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "receipt_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['ri_idx']      = $ri_idx;
		$hi_param['rid_idx']     = $rid_idx;
		$hi_param['status']      = $receipt_status;
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = $status_memo;

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// 다수 접수 함수
	function plural_post()
	{
		global $_POST, $_SESSION, $sess_str, $set_receipt_status;

		$ri_idx         = $_POST['ri_idx'];
		$rid_idx        = $_POST['detail_rid_idx'];
		$receipt_class  = $_POST['detail_receipt_class'];
		$mem_idx        = $_POST['detail_mem_idx'];
		$end_pre_date   = $_POST['detail_end_pre_date'];
		$receipt_status = $_POST['detail_receipt_status'];
		$remark         = $_POST['detail_remark'];
		$receipt_type   = '2';

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_POST['part_idx'];

	// 접수정보
		$receipt_where = " and ri.ri_idx = '" . $ri_idx . "'";
		$receipt_data = receipt_info_data('view', $receipt_where);

		$param['ci_idx']        = $receipt_data['ci_idx'];
		$param['receipt_type']  = $receipt_type;
		$param['receipt_class'] = $receipt_class;
		$param['mem_idx']       = $mem_idx;
		$param['end_pre_date']  = $end_pre_date;
		$param['remark']        = $remark;

		if ($rid_idx == 0 || $rid_idx == '') // 없을 경우
		{
			$command    = "insert"; //명령어
			$table      = "receipt_info_detail"; //테이블명
			$conditions = ""; //조건

			$receipt_status = 'RS02';
			$status_memo    = '승인되었습니다.';

			$chk_data = query_view("select max(rid_idx) as rid_idx from " . $table);
			$rid_idx = ($chk_data["rid_idx"] == "") ? "1" : $chk_data["rid_idx"] + 1;

			$param['comp_idx']       = $comp_idx;
			$param['part_idx']       = $part_idx;
			$param['ri_idx']         = $ri_idx;
			$param['rid_idx']        = $rid_idx;
			$param['receipt_status'] = $receipt_status;
			$param['reg_id']         = $_SESSION[$sess_str . '_mem_idx'];
			$param['reg_date']       = date("Y-m-d H:i:s");

			$query_str = make_sql($param, $command, $table, $conditions);
			db_query($query_str);
			query_history($query_str, $table, $command);

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 푸시건 - 담당자에게
			if ($_SESSION[$sess_str . '_mem_idx'] != $mem_idx)
			{
				receipt_push($rid_idx, 'detail');
			}
		}
		else
		{
		// 원래정보
			$detail_where = " and rid.rid_idx = '" . $rid_idx . "'";
			$detail_data = receipt_info_detail_data('view', $detail_where);

			$old_receipt_class = $detail_data['receipt_class'];
			$old_mem_idx       = $detail_data['mem_idx'];
			$old_end_pre_date  = date_replace($detail_data['end_pre_date'], 'Y-m-d');

			$command    = "update"; //명령어
			$table      = "receipt_info_detail"; //테이블명
			$conditions = "rid_idx = '" . $rid_idx . "'"; //조건

			$status_memo = '';
			if ($old_receipt_class != $receipt_class)
			{
				$old_code_where = " and code.code_idx = '" . $old_receipt_class . "'";
				$old_code_data = code_receipt_class_data('view', $old_code_where);

				$new_code_where = " and code.code_idx = '" . $receipt_class . "'";
				$new_code_data = code_receipt_class_data('view', $new_code_where);

				$status_memo .= '분류 ' . $old_code_data['code_name'] . '(에)서 ' . $new_code_data['code_name'] . '(으)로 변경되었습니다. ';
			}
			if ($old_mem_idx != $mem_idx)
			{
				$old_mem_where = " and mem.mem_idx = '" . $old_mem_idx . "'";
				$old_mem_data = member_info_data('view', $old_mem_where);

				$new_mem_where = " and mem.mem_idx = '" . $mem_idx . "'";
				$new_mem_data = member_info_data('view', $new_mem_where);

				$status_memo .= '담당자 ' . $old_mem_data['mem_name'] . '(에)서 ' . $new_mem_data['mem_name'] . '(으)로 변경되었습니다. ';

			// 푸쉬전송
				$push = new PUSH("bizstory_push");

				$msg_type = 'receipt';
				$message = strip_tags($detail_data['remark']);
				$message = '[담당자변경] ' . string_cut($message, 20);

				if ($mem_idx != $_SESSION[$sess_str . '_mem_idx'] && $mem_idx > 0)
				{
					$mem_where = " and mem.mem_idx = '" . $mem_idx . "'";
					$mem_data = member_info_data('view', $mem_where);

					$receiver = $mem_data['mem_id'];
					//$result = @$push->push_send($sender, $detail_data['comp_idx'], $detail_data['part_idx'], $mem_idx, $receiver, $msg_type, $message);
                    push_send($sender, $detail_data['comp_idx'], $detail_data['part_idx'], $mem_idx, $receiver, $msg_type, $message);
					unset($mem_data);
				}
			}
			if ($old_end_pre_date != $end_pre_date)
			{
				if ($old_end_pre_date == '')
				{
					$status_memo .= '완료예정일 ' . $end_pre_date . '(으)로 변경되었습니다. ';
				}
				else
				{
					$status_memo .= '완료예정일 ' . $old_end_pre_date . '(에)서 ' . $end_pre_date . '(으)로 변경되었습니다. ';
				}
			}

			$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
			$param['mod_date'] = date("Y-m-d H:i:s");

			$query_str = make_sql($param, $command, $table, $conditions);
			db_query($query_str);
			query_history($query_str, $table, $command);
		}

	// 본 접수상태변경
	// 처음으로 등록할 경우 승인으로 변경을 한다.
		if ($rid_idx == '')
		{
			$chk_where = " and rid.ri_idx = '" . $ri_idx . "'";
			$chk_data = receipt_info_detail_data('page', $chk_where);
			if ($chk_data['total_num'] == 0)
			{
				$org_command    = "update"; //명령어
				$org_table      = "receipt_info"; //테이블명
				$org_conditions = "ri_idx = '" . $ri_idx . "'"; //조건

				$org_param['mod_id']         = $mem_idx;
				$org_param['mod_date']       = date("Y-m-d H:i:s");
				$org_param['receipt_status'] = $receipt_status;
				$org_param['charge_mem_idx'] = $mem_idx;

				$query_str = make_sql($org_param, $org_command, $org_table, $org_conditions);
				db_query($query_str);
				query_history($query_str, $org_table, $org_command);
			}
		}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "receipt_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $_SESSION[$sess_str . '_mem_idx'];
		$hi_param['ri_idx']      = $ri_idx;
		$hi_param['rid_idx']     = $rid_idx;
		$hi_param['status']      = $param['receipt_status'];
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = $status_memo;

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// 다중접수 삭제 함수
	function plural_delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_POST['part_idx'];
		$rid_idx  = $_POST['rid_idx'];

		$command    = "update"; //명령어
		$table      = "receipt_info_detail"; //테이블명
		$conditions = "rid_idx = '" . $rid_idx . "'"; //조건

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

// 접수상태 함수
	function status_modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$comp_idx   = $_SESSION[$sess_str . '_comp_idx'];
		$mem_idx    = $_SESSION[$sess_str . '_mem_idx'];
		$part_idx   = $_POST['part_idx'];
		$ri_idx     = $_POST['ri_idx'];
		$rid_idx    = $_POST['rid_idx'];
		$status     = $_POST['detail_receipt_status_' . $rid_idx];
		$remark_end = $_POST['detail_remark_end_' . $rid_idx];

	// 변경전 접수상태
		$info_where = " and rid.rid_idx = '" . $rid_idx . "'";
		$info_data = receipt_info_detail_data('view', $info_where);
		$before_status = $info_data['receipt_status'];

		if ($before_status == 'RS90')
		{
			$str = '{"success_chk" : "N", "error_string" : "접수상태가 완료일 경우 변경이 안됩니다."}';
			echo $str;
			exit;
		}
		else
		{
		// 접수가 완료일 경우
			if ($status == 'RS90')
			{
				if ($remark_end == '')
				{
					$str = '{"success_chk" : "N", "error_string" : "완료일 경우 수정된 사항에 대해서 간단한 문구를 입력하세요<br />보고서 제출시 사용됩니다."}';
					echo $str;
					exit;
				}
				else
				{
					$param['end_date']   = date("Y-m-d H:i:s");
					$param['remark_end'] = $remark_end;
				}
			}

		// 접수상세상태
			$command    = "update"; //명령어
			$table      = "receipt_info_detail"; //테이블명
			$conditions = "rid_idx = '" . $rid_idx . "'"; //조건

			$param['mod_id']         = $mem_idx;
			$param['mod_date']       = date("Y-m-d H:i:s");
			$param['receipt_status'] = $status;

			chk_before($param);

			$query_str = make_sql($param, $command, $table, $conditions);
			db_query($query_str);
			query_history($query_str, $table, $command);

		// 본 접수에 상태수정
			$org_command    = "update"; //명령어
			$org_table      = "receipt_info"; //테이블명
			$org_conditions = "ri_idx = '" . $ri_idx . "'"; //조건

			$org_param['mod_id']         = $mem_idx;
			$org_param['mod_date']       = date("Y-m-d H:i:s");
			$org_param['receipt_status'] = $status;
			$org_param['remark_end']     = $param['remark_end'];
			$org_param['end_date']       = $param['end_date'];

			if ($status == 'RS90') // 완료일 경우 마지막 완료일 경우 적용을 한다.
			{
				$detail_where = " and rid.ri_idx = '" . $ri_idx . "' and rid.receipt_status != 'RS90'";
				$detail_page = receipt_info_detail_data('page', $detail_where);
				if ($detail_page['total_num'] == 0)
				{
					$query_str = make_sql($org_param, $org_command, $org_table, $org_conditions);
					db_query($query_str);
					query_history($query_str, $org_table, $org_command);
				}
			}
			else
			{
				$query_str = make_sql($org_param, $org_command, $org_table, $org_conditions);
				db_query($query_str);
				query_history($query_str, $org_table, $org_command);
			}

		// 완료일 경우 파일저장
			global $tmp_path, $comp_receipt_path;

			$data_path = $comp_receipt_path . '/' . $ri_idx;
			files_dir($data_path);

			$file_num = $_POST['file_upload_num'];
			$query_str = '';
			$file_chk = 1;
			for ($i = 1; $i <= $file_num; $i++)
			{
				$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $ri_idx . '_' . $rid_idx, 'receipt_end');

				if ($upfile_data[$i]['f_name'] != '')
				{
					$chk_file_name = $upfile_data[$i]['f_name'];
					$new_file_name = $upfile_data[$i]['s_name'];
					$chk_file_size = $upfile_data[$i]['f_size'];
					$chk_file_type = $upfile_data[$i]['f_type'];
					$chk_file_ext  = $upfile_data[$i]['f_ext'];

				// 데이타 확인
					$file_where = " and ref.ri_idx = '" . $ri_idx . "' and ref.rid_idx = '" . $rid_idx . "' and ref.sort ='" . $i . "'";
					$file_data = receipt_end_file_data('view', $file_where);

					if ($file_data['total_num'] == 0)
					{
						if ($file_chk == 1)
						{
							$query_str = "
								INSERT INTO receipt_end_file (comp_idx, part_idx, ri_idx, rid_idx, sort, img_fname, img_sname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
						}
						else $query_str .= ", ";

						$query_str .= "('" . string_input($comp_idx) . "', '" . string_input($part_idx) . "', '" . string_input($ri_idx) . "', '" . string_input($rid_idx) . "', '" . string_input($i) . "', '" . string_input($chk_file_name) . "', '" . string_input($new_file_name) . "', '" . string_input($chk_file_size) . "', '" . string_input($chk_file_type) . "', '" . string_input($chk_file_ext) . "', '" . string_input($reg_id) . "', '" . string_input($reg_date) . "')";
						$file_chk++;
					}
					else
					{
						$query_update = "update receipt_end_file set
								img_fname = '" . string_input($chk_file_name) . "',
								img_sname = '" . string_input($new_file_name) . "',
								img_size  = '" . string_input($chk_file_size) . "',
								img_type  = '" . string_input($chk_file_type) . "',
								img_ext   = '" . string_input($chk_file_ext) . "',
								mod_id    = '" . string_input($reg_id) . "',
								mod_date  = '" . string_input($reg_date) . "'
							where
								del_yn = 'N' and ri_idx = '" . $ri_idx . "' and rid_idx = '" . $rid_idx . "' and sort ='" . $i . "'";
						query_history($query_update, 'receipt_end_file', 'update');
						db_query($query_update);
					}
				}
			}
			if ($query_str != '')
			{
				db_query($query_str);
				query_history($query_str, $file_table, $file_command);
			}

		// 히스토리저장
			if ($status == 'RS02')
			{
				$status_memo = '작업이 승인되었습니다.';
			}
			else if ($status == 'RS03')
			{
				$status_memo = '작업이 진행중입니다.';
			}
			else if ($status == 'RS90')
			{
				$status_memo = '작업이 완료되었습니다.';
			}
			else if ($status == 'RS80')
			{
				$status_memo = '작업이 보류되었습니다.';
			}
			else if ($status == 'RS60')
			{
				$status_memo = '작업이 취소되었습니다.';
			}

			$hi_command    = "insert"; //명령어
			$hi_table      = "receipt_status_history"; //테이블명
			$hi_conditions = ""; //조건

			$hi_param['reg_id']      = $mem_idx;
			$hi_param['reg_date']    = date("Y-m-d H:i:s");
			$hi_param['comp_idx']    = $comp_idx;
			$hi_param['part_idx']    = $part_idx;
			$hi_param['ri_idx']      = $ri_idx;
			$hi_param['rid_idx']     = $rid_idx;
			$hi_param['status']      = $status;
			$hi_param['status_memo'] = $status_memo;
			$hi_param['mem_idx']     = $mem_idx;
			$hi_param['status_date'] = date("Y-m-d H:i:s");

			$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
			db_query($query_str);
			query_history($query_str, $hi_table, $hi_command);

			$str = '{"success_chk" : "Y", "error_string" :""}';
			echo $str;
			exit;
		}
	}
?>