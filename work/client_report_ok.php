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

//기초값 검사
	function chk_before($param, $chk_type = 'json')
	{
	//필수검사
		$chk_param['require'][] = array("field"=>"ci_idx", "msg"=>"거래처");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 접수 점검추가
	function receipt_insert()
	{
		global $_POST, $_SESSION, $sess_str;

		$str = '{"success_chk" : "Y"}';
		echo $str;
		exit;
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $param['part_idx'];

//-------------------------------------- 점검보고서
		$command    = "insert"; //명령어
		$table      = "receipt_report"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']       = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date']     = date("Y-m-d H:i:s");
		$param['comp_idx']     = $comp_idx;
		$param['subject']      = '유지보수 정기 점검 보고서';
		$param['receipt_date'] = $param['receipt_year'] . '-' . $param['receipt_month'];
		$report_subject        = '[' . $param['client_name'] . '] ' . $param['receipt_year'] . '년 ' . $param['receipt_month'] . '월 유지보수 정기 점검 보고서';

		unset($param['receipt_year']);
		unset($param['receipt_month']);

		$data = query_view("select max(rr_idx) as rr_idx from " . $table);
		$param['rr_idx'] = ($data["rr_idx"] == "") ? '1' : $data["rr_idx"] + 1;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

//-------------------------------------- 점검보고서 상세
		$detail_command    = "insert"; //명령어
		$detail_table      = "receipt_report_detail"; //테이블명
		$detail_conditions = ""; //조건

		$detail_param['comp_idx'] = $comp_idx;
		$detail_param['part_idx'] = $param['part_idx'];
		$detail_param['ci_idx']   = $param['ci_idx'];
		$detail_param['rr_idx']   = $param['rr_idx'];
		$detail_param['reg_id']   = $param['reg_id'];
		$detail_param['reg_date'] = $param['reg_date'];

	// 접수목록
		$receipt_total = $_POST['receipt_total'];
		for ($chk_i = 1; $chk_i <= $receipt_total; $chk_i++)
		{
			$ri_idx  = $_POST['chk_ri_idx_' . $chk_i];
			$rid_idx = $_POST['chk_rid_idx_' . $chk_i];

			if ($rid_idx != '')
			{
				$receipt_where = " and rid.rid_idx = '" . $rid_idx . "'";
				$receipt_data = receipt_info_detail_data('view', $receipt_where);

				$detail_param['rrd_type']     = '1';
				$detail_param['ri_idx']       = $ri_idx;
				$detail_param['rid_idx']      = $rid_idx;
				$detail_param['writer']       = $receipt_data['writer'];
				$detail_param['receipt_date'] = $receipt_data['receipt_date'];
				$detail_param['mem_name']     = $receipt_data['mem_name'];
				$detail_param['end_date']     = $receipt_data['end_date'];
				$detail_param['code_idx']     = '0';
				$detail_param['menu_depth']   = '0';
				$detail_param['report_name']  = $receipt_data['subject'];
				$detail_param['remark']       = $receipt_data['remark_end'];
				$detail_param['input_type']   = 'receipt';
				$detail_param['input_value']  = '';
				$detail_param['report_value'] = 'RS90';
				$detail_param['sort']         = $chk_i;

				$query_str = make_sql($detail_param, $detail_command, $detail_table, $detail_conditions);
				db_query($query_str);
				query_history($query_str, $detail_table, $detail_command);
			}
		}

	// 점검항목
		$code_total = $_POST['code_total'];
		for ($chk_i = 1; $chk_i < $code_total; $chk_i++)
		{
			$code_idx    = $_POST['code_idx_' . $chk_i];
			$input_value = $_POST['input_value_' . $chk_i];

			$code_where = " and code.code_idx = '" . $code_idx . "'";
			$code_data = code_report_class_data('view', $code_where);

			$detail_param['rrd_type']     = '2';
			$detail_param['ri_idx']       = '';
			$detail_param['rid_idx']      = '';
			$detail_param['writer']       = '';
			$detail_param['receipt_date'] = '';
			$detail_param['mem_name']     = '';
			$detail_param['end_date']     = '';
			$detail_param['code_idx']     = $code_data['code_idx'];
			$detail_param['menu_depth']   = $code_data['menu_depth'];
			$detail_param['report_name']  = $code_data['code_name'];
			$detail_param['remark']       = '';
			$detail_param['input_type']   = $code_data['input_type'];
			$detail_param['input_value']  = $code_data['input_value'];
			$detail_param['report_value'] = $input_value;
			$detail_param['sort']         = $chk_i;

			$query_str = make_sql($detail_param, $detail_command, $detail_table, $detail_conditions);
			db_query($query_str);
			query_history($query_str, $detail_table, $detail_command);
			$detail_num++;
		}

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param  = $_POST['param'];
		$rr_idx = $_POST['rr_idx'];

		$command    = "update"; //명령어
		$table      = "receipt_report"; //테이블명
		$conditions = "rr_idx = '" . $rr_idx . "'"; //조건

		$param['mod_id']     = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date']   = date("Y-m-d H:i:s");

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$rr_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "receipt_report"; //테이블명
		$conditions = "rr_idx = '" . $rr_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	// 상세
		$d_command    = "update"; //명령어
		$d_table      = "receipt_report_detail"; //테이블명
		$d_conditions = "rr_idx = '" . $rr_idx . "'"; //조건

		$d_param['del_yn']   = "Y";
		$d_param['del_ip']   = $ip_address;
		$d_param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$d_param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($d_param, $d_command, $d_table, $d_conditions);
		db_query($query_str);
		query_history($query_str, $d_table, $d_command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}
?>