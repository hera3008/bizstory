<?
/*
	생성 : 2012.09.10
	위치 : 접수실행
*/
	require_once "../common/setting.php";
	require_once $local_path . "/bizstory/mobile/process/mobile_setting.php";
	require_once $mobile_path . "/process/member_chk.php";
	require_once $mobile_path . "/process/no_direct.php";

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
		$chk_param['require'][] = array("field"=>"detail_receipt_class", "msg"=>"접수분류");
		$chk_param['require'][] = array("field"=>"detail_mem_idx", "msg"=>"담당자");
		$chk_param['require'][] = array("field"=>"detail_end_pre_date", "msg"=>"완료예정일");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 단일 함수
	function singular_post()
	{
		global $_POST, $_SESSION, $sess_str, $set_receipt_status;

		$code_mem = $_SESSION[$sess_str . '_mem_idx'];

		$ri_idx         = $_POST['ri_idx'];
		$rid_idx        = $_POST['rid_idx'];
		$receipt_class  = $_POST['detail_receipt_class'];
		$mem_idx        = $_POST['detail_mem_idx'];
		$end_pre_date   = $_POST['detail_end_pre_date'];
		$receipt_type   = '1';

	// 접수정보
		$receipt_where = " and ri.ri_idx = '" . $ri_idx . "'";
		$receipt_data = receipt_info_data('view', $receipt_where);

		$comp_idx = $receipt_data['comp_idx'];
		$part_idx = $receipt_data['part_idx'];
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
			$status_memo    = '접수가 승인되었습니다.';

			$data = query_view("select max(rid_idx) as rid_idx from " . $table);
			$param["rid_idx"] = ($data["rid_idx"] == "") ? "1" : $data["rid_idx"] + 1;

			$param['comp_idx']       = $comp_idx;
			$param['part_idx']       = $part_idx;
			$param['ri_idx']         = $ri_idx;
			$param['receipt_status'] = $receipt_status;
			$param['remark']         = $receipt_data['subject'];
			$param['reg_id']         = $code_mem;
			$param['reg_date']       = date("Y-m-d H:i:s");

			$rid_idx = $param["rid_idx"];

			$query_str = make_sql($param, $command, $table, $conditions);
			db_query($query_str);
			query_history($query_str, $table, $command);

		// 본 접수상태변경
			$org_command    = "update"; //명령어
			$org_table      = "receipt_info"; //테이블명
			$org_conditions = "ri_idx = '" . $ri_idx . "'"; //조건

			$org_param['mod_id']         = $code_mem;
			$org_param['mod_date']       = date("Y-m-d H:i:s");
			$org_param['receipt_status'] = $receipt_status;
			$org_param['charge_mem_idx'] = $mem_idx;

			$query_str = make_sql($org_param, $org_command, $org_table, $org_conditions);
			db_query($query_str);
			query_history($query_str, $org_table, $org_command);

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 푸시건 - 담당자에게
			if ($code_mem != $mem_idx)
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

				$status_memo .= '접수분류 ' . $old_code_data['code_name'] . '(에)서 ' . $new_code_data['code_name'] . '(으)로 변경되었습니다. ';
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
				$message = '[접수 담당자변경] ' . string_cut($message, 20);

				if ($mem_idx != $code_mem && $mem_idx > 0)
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

			$param['mod_id']   = $code_mem;
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

		$hi_param['reg_id']      = $code_mem;
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $code_mem;
		$hi_param['ri_idx']      = $ri_idx;
		$hi_param['rid_idx']     = $rid_idx;
		$hi_param['status']      = $receipt_status;
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = $status_memo;

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

	// 접수상태
		global $set_receipt_status;
		$receipt_where = " and ri.ri_idx = '" . $ri_idx . "'";
		$receip_data = receipt_info_data('view', $receipt_where);

		$status_name = $set_receipt_status[$receip_data['receipt_status']];
		$status_str = "<span style='";
		if ($receip_data['receipt_status_bold'] == 'Y') $status_str .= 'font-weight:900;';
		if ($receip_data['receipt_status_color'] != '') $status_str .= 'color:' . $receip_data['receipt_status_color'] . ';';
		$status_str .= "'>" . $receip_data['receipt_status_str'] . '</span>';

		if ($receip_data['status_del_yn'] == 'Y') $status_str = "<span style='color:#CCCCCC'>" . $receip_data['receipt_status_str'] . '</span>';
		$status_str = '';

		$str = '{"success_chk" : "Y", "error_string":"", "receipt_status_check":"' . $status_str . '"}';
		echo $str;
		exit;
	}

// 접수상태 함수
	function status_modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$mem_idx    = $_SESSION[$sess_str . '_mem_idx'];
		$ri_idx     = $_POST['ri_idx'];
		$rid_idx    = $_POST['rid_idx'];
		$status     = $_POST['detail_receipt_status'];
		$remark_end = $_POST['detail_remark_end'];

	// 변경전 접수상태
		$info_where = " and rid.rid_idx = '" . $rid_idx . "'";
		$info_data = receipt_info_detail_data('view', $info_where);
		$before_status = $info_data['receipt_status'];
		$comp_idx      = $info_data['comp_idx'];
		$part_idx      = $info_data['part_idx'];

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

		// 접수상태
			global $set_receipt_status;
			$receipt_where = " and ri.ri_idx = '" . $ri_idx . "'";
			$receip_data = receipt_info_data('view', $receipt_where);

			$status_name = $set_receipt_status[$receip_data['receipt_status']];
			$status_str = "<span style='";
			if ($receip_data['receipt_status_bold'] == 'Y') $status_str .= 'font-weight:900;';
			if ($receip_data['receipt_status_color'] != '') $status_str .= 'color:' . $receip_data['receipt_status_color'] . ';';
			$status_str .= "'>" . $receip_data['receipt_status_str'] . '</span>';

			if ($receip_data['status_del_yn'] == 'Y') $status_str = "<span style='color:#CCCCCC'>" . $receip_data['receipt_status_str'] . '</span>';
			//$status_str = '';

			$str = '{"success_chk" : "Y", "error_string" :"", "receipt_status_check":"' . $status_str . '"}';
			echo $str;
			exit;
		}
	}
?>