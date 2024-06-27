<?
/*
	생성 : 2012.05.07
	수정 : 2012.05.14
	위치 : 업무폴더 > 계약관리
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
		$chk_param['require'][] = array("field"=>"subject", "msg"=>"계약명");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$ci_idx   = $_POST['ci_idx'];

		$command    = "insert"; //명령어
		$table      = "contract_info"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']    = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date']  = date("Y-m-d H:i:s");
		$param['comp_idx']  = $comp_idx;
		$param['part_idx']  = $part_idx;
		$param['ci_idx']    = $ci_idx;
		$param['con_price']   = str_replace(',', '', $param['con_price']);
		$param['month_price'] = str_replace(',', '', $param['month_price']);
		if ($param['con_price_chk'] == '') $param['con_price_chk'] = 'N';
		if ($param['month_price_chk'] == '') $param['month_price_chk'] = 'N';

		$data = query_view("select max(con_idx) as con_idx from " . $table);
		$param['con_idx'] = ($data["con_idx"] == "") ? '1' : $data["con_idx"] + 1;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$con_idx  = $_POST['con_idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];

		$command    = "update"; //명령어
		$table      = "contract_info"; //테이블명
		$conditions = "con_idx = '" . $con_idx . "'"; //조건

		$param['mod_id']    = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date']  = date("Y-m-d H:i:s");
		$param['con_price']   = str_replace(',', '', $param['con_price']);
		$param['month_price'] = str_replace(',', '', $param['month_price']);
		if ($param['con_price_chk'] == '') $param['con_price_chk'] = 'N';
		if ($param['month_price_chk'] == '') $param['month_price_chk'] = 'N';

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

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_POST['code_part'];
		$con_idx  = $_POST['con_idx'];

		$command    = "update"; //명령어
		$table      = "contract_info"; //테이블명
		$conditions = "con_idx = '" . $con_idx . "'"; //조건

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
?>