<?
/*
	수정 : 2012.11.22
	위치 : 회계업무 > 운영비관리 - 일괄등록저장
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

//입력처리 함수
	function insert_ok()
	{
		global $_POST, $_SESSION, $sess_str;

		$total_num = $_POST['data_total'];
		$comp_idx  = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx  = $_POST['part_idx'];

		$command    = "insert"; //명령어
		$table      = "account_info"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;

		for ($i = 1; $i <= $total_num; $i++)
		{
			$param['account_type']  = $_POST['account_type_' . $i];
			$param['account_date']  = $_POST['account_date_' . $i];
			$param['gubun_code']    = $_POST['gubun_code_' . $i];
			$param['card_code']     = $_POST['card_code_' . $i];
			$param['bank_code']     = $_POST['bank_code_' . $i];
			$param['class_code']    = $_POST['class_code_' . $i];
			$param['ci_idx']        = $_POST['client_idx_' . $i];
			$param['account_price'] = $_POST['account_price_' . $i];
			$param['account_price'] = str_replace(',', '', $param['account_price']);
			$param['charge_yn']     = $_POST['charge_yn_' . $i];
			$param['content']       = $_POST['content_' . $i];

			if ($param['account_type'] != '' && $param['account_date'] != '' && $param['gubun_code'] != '' && $param['account_price'] != '')
			{
				$query_str = make_sql($param, $command, $table, $conditions);
				db_query($query_str);
				query_history($query_str, $table, $command);
			}
		}

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}
?>