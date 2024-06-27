<?
/*
	수정 : 2013.02.28
	위치 : 접수 - 실행
*/
	include "../bizstory/common/setting.php";
	include "../bizstory/common/no_direct.php";
	include $local_path . "/agent/include/agent_chk.php";

	$field_str = str_replace("|", "&", $field_str);

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
		$chk_param['require'][] = array("field"=>"subject", "msg"=>"제목");
		$chk_param['require'][] = array("field"=>"remark", "msg"=>"내용");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST;

		$param      = $_POST['param'];
		$client_idx = $_POST['client_idx'];

	// 거래처정보
		$client_where = " and ci.ci_idx = '" . $client_idx . "'";
		$client_data = client_info_data('view', $client_where);

		$client_code = $client_data['client_code'];
		$code_comp   = $client_data['comp_idx'];
		$code_part   = $client_data['part_idx'];

		$command    = "insert"; //명령어
		$table      = "receipt_info"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $param["writer"];
		$param['reg_date'] = date("Y-m-d H:i:s");
		$param['comp_idx'] = $code_comp;
		$param['part_idx'] = $code_part;

		$data = query_view("select max(ri_idx) as ri_idx from " . $table);
		$param['ri_idx'] = ($data["ri_idx"] == "") ? '1' : $data["ri_idx"] + 1;

	// 접수상태 기본값 가지고 오기
		$sub_where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "' and code.view_yn = 'Y' and code.default_yn = 'Y'";
		$sub_data = code_receipt_status_data('view', $sub_where);
		$param["receipt_status"] = $sub_data["code_idx"];

		$param["receipt_status"] = 'RS01'; // 접수상태
		if ($param["important"] == '') $param["important"] = 'RI01';

	// 담당자넣기
		$ci_where = " and ci.ci_idx = '" . $param['ci_idx'] . "'";
		$ci_data = client_info_data('view', $ci_where);
		$param["charge_mem_idx"] = $ci_data["mem_idx"];

	// 메뉴단계구하기
		$depth_data = query_view("
			select max(menu_depth) as max_depth from code_receipt_class
			where del_yn = 'N' and comp_idx = '" . $code_comp . "' and part_idx = '" . $code_part . "' limit 1");
		if($depth_data["max_depth"] == "") $max_depth = 1;
		else $max_depth = $depth_data["max_depth"];
		for ($i = 1; $i <= $max_depth; $i++)
		{
			$receipt_class = $_POST['receipt_class_' . $i];
			if ($receipt_class != '')
			{
				$param['receipt_class'] = $receipt_class;
			}
		}

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "receipt_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']   = $param["writer"];
		$hi_param['reg_date'] = date("Y-m-d H:i:s");
		$hi_param['comp_idx'] = $code_comp;
		$hi_param['part_idx'] = $code_part;
		$hi_param['ri_idx']   = $param['ri_idx'];
		$hi_param['status']   = $param["receipt_status"];
		$hi_param['mem_idx']  = '';
		$hi_param['status_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

//-------------------------------------- 파일저장
		global $tmp_path, $receipt_path;

		$ri_idx    = $param['ri_idx'];
		$comp_idx  = $code_comp;
		$part_idx  = $code_part;
		$data_path = $receipt_path . '/' . $ri_idx;
		files_dir($data_path);

		$file_command    = "insert"; //명령어
		$file_table      = "receipt_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['reg_id'];
		$reg_date = $param['reg_date'];

	// 총 저장 파일
		$file_num = $_POST['upload_fnum'];
		$query_str = '';
		$file_chk = 1;
		for ($i = 1; $i <= $file_num; $i++)
		{
			$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $ri_idx);

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
	// 알림건
		$client_name = $client_data['client_name'];
		$charge_idx  = $client_data['mem_idx'];

		$message  = strip_tags($param['subject']);
		$message  = '[' . $client_name . '] ' . string_cut($message, 10);

	// 담당자
		charge_push_send('', $charge_idx, '', 'receipt', $message, '', '', '');

	// 알림담당자
		$comp_set_where = " and cs.comp_idx = '" . $comp_idx . "'";
		$comp_set_data  = company_set_data('view', $comp_set_where);
		$charge_idx = $comp_set_data['receipt_charge'];
		charge_push_send('', $charge_idx, '', 'receipt', $message, '', '', '');

		$str = '{"success_chk" : "Y", "idx_num" : "' . $ri_idx . '", "f_class":"receipt", "f_idx":"' . $ri_idx . '"}';
		echo $str;
		exit;
	}
?>