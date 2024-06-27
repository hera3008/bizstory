<?
/*
	수정 : 2013.03.22
	위치 : 설정관리 > 회사관리 > 회사정보 - 실행
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
		$chk_param["require"][] = array("field"=>"comp_name", "msg"=>"상호명");
		$chk_param["require"][] = array("field"=>"boss_name", "msg"=>"대표자명");
		$chk_param["require"][] = array("field"=>"comp_num", "msg"=>"사업자등록번호");
		$chk_param["require"][] = array("field"=>"comp_email", "msg"=>"이메일");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST["param"];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];

		$command    = "update"; //명령어
		$table      = "company_info"; //테이블명
		$conditions = "comp_idx = '" . $comp_idx . "'"; //조건

		$param["mod_id"]   = $_SESSION[$sess_str . "_mem_idx"];
		$param["mod_date"] = date("Y-m-d H:i:s");

		$param["comp_num"]     = $param["comp_num1"] . '-' . $param["comp_num2"] . '-' . $param["comp_num3"];
		$param["distinct_num"] = $param["distinct_num1"] . '-' . $param["distinct_num2"];
		$param["address"]      = $param["address1"] . '||' . $param["address2"];
		$param["tel_num"]      = $param["tel_num1"] . '-' . $param["tel_num2"] . '-' . $param["tel_num3"];
		$param["fax_num"]      = $param["fax_num1"] . '-' . $param["fax_num2"] . '-' . $param["fax_num3"];
		$param["comp_email"]   = $param["comp_email1"] . '@' . $param["comp_email2"];
		$param["hp_num"]       = $param["hp_num1"] . '-' . $param["hp_num2"] . '-' . $param["hp_num3"];

		unset($param['comp_num1']);
		unset($param['comp_num2']);
		unset($param['comp_num3']);
		unset($param['distinct_num1']);
		unset($param['distinct_num2']);
		unset($param['address1']);
		unset($param['address2']);
		unset($param['tel_num1']);
		unset($param['tel_num2']);
		unset($param['tel_num3']);
		unset($param['fax_num1']);
		unset($param['fax_num2']);
		unset($param['fax_num3']);
		unset($param['comp_email1']);
		unset($param['comp_email2']);
		unset($param['hp_num1']);
		unset($param['hp_num2']);
		unset($param['hp_num3']);

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}
?>