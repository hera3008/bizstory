<?
	include "../common/setting.php";
	include "../common/no_direct.php";

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
		if($param['comp_class'] )
		{
			$chk_param["require"][] = array("field"=>"schul_code", "msg"=>"학교코드");

		//중복검사
			$chk_param["unique"][] = array("table"=>"member_info", "field"=>"mem_id", "where"=>"del_yn = 'N'", "msg"=>"이미 사용된 아이디입니다.");
			$chk_param["unique"][] = array("table"=>"company_info", "field"=>"schul_code", "where"=>"del_yn = 'N'", "msg"=>"이미 가입된 학교 입니다.");
			$chk_param["unique"][] = array("table"=>"company_info", "field"=>"comp_num", "where"=>"del_yn = 'N'", "msg"=>"이미 사용된 사업자등록번호입니다.");
		}
		else
		{
	//필수검사
			$chk_param["require"][] = array("field"=>"mem_email", "msg"=>"이메일");
			$chk_param["require"][] = array("field"=>"comp_num", "msg"=>"사업자등록번호");

	//중복검사
			$chk_param["unique"][] = array("table"=>"member_info", "field"=>"mem_email", "where"=>"del_yn = 'N'", "msg"=>"이미 사용된 이메일입니다.");
			$chk_param["unique"][] = array("table"=>"company_info", "field"=>"comp_num", "where"=>"del_yn = 'N'", "msg"=>"이미 사용된 사업자등록번호입니다.");
		}
	
	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

//등록 함수
function reg_post()
{
	global $_POST;
	

	$param = $_POST["param"];

// 업체정보
	$command    = "insert"; //명령어
	$table      = "company_info"; //테이블명
	$conditions = ""; //조건

	$param["reg_id"]   = '';
	$param["reg_date"] = date("Y-m-d H:i:s");

	$param["comp_email"] = $param["mem_email1"] . '@' . $param["mem_email2"];
	$param["tel_num"]    = $param["tel_num1"] . '-' . $param["tel_num2"] . '-' . $param["tel_num3"];
	$param["hp_num"]     = $param["hp_num1"] . '-' . $param["hp_num2"] . '-' . $param["hp_num3"];
	$param["comp_num"]   = $param["comp_num1"] . '-' . $param["comp_num2"] . '-' . $param["comp_num3"];
	$param["address"]    = $param["address1"] . '||' . $param["address2"];
	$param['comp_name']  = !$param['comp_name'] ? $param['sc_name'] : $param['comp_name'];
	$param['boss_name']  = !$param['boss_name'] ? $param['sc_name'] : $param['boss_name'];

	$comp_data = query_view("select max(comp_idx) as comp_idx from " . $table);
	$param["comp_idx"] = ($comp_data["comp_idx"] == "") ? "1" : $comp_data["comp_idx"] + 1;

	if($param['comp_class'] == '2') 
	{
		$mem_id = $param["comp_email"];
		$param['comp_code']     = $param['comp_num1'] . $param['comp_num2'] . $param['comp_num3'];
	}
	else
	{
		$mem_id = $param['mem_id'];
		$comp_code = $param['sc_code'] . $param['schul_code'];
		$param['comp_code']  = str_pad($comp_code, 10 , "0");
	}

	

	unset($param['mem_id']);
	unset($param['mem_email1']);
	unset($param['mem_email2']);
	unset($param['tel_num1']);
	unset($param['tel_num2']);
	unset($param['tel_num3']);
	unset($param['hp_num1']);
	unset($param['hp_num2']);
	unset($param['hp_num3']);
	unset($param['address1']);
	unset($param['address2']);

	if($param["comp_class"] == "1")
	{
		unset($param['comp_num']);
		unset($param['comp_num1']);
		unset($param['comp_num2']);
		unset($param['comp_num3']);
		unset($param['upjong']);
		unset($param['uptae']);			
	}
	else
	{
		unset($param['sc_name']);
		unset($param['sc_code']);
		unset($param['schul_name']);
		unset($param['schul_code']);
		unset($param['charge_name']);	
		unset($param['comp_num1']);
		unset($param['comp_num2']);
		unset($param['comp_num3']);
	}
	
	//$boss_name = $param['boss_name'];
	chk_before($param);


	$query_str = make_sql($param, $command, $table, $conditions);	
	db_query($query_str);
	query_history($query_str, $table, $command);

	if(trim($mem_id)) regist_member($mem_id, $param);

////////////////////////////////////////////////////////////////////////////////////////////////////////
// 대표님께 알림건
	//charge_push_send('', '2', '', 'reg_ok', $param['demo_name'], '', '', '');

	$str = '{"success_chk" : "Y"}';
	echo $str;
	exit;
}

// 이메일중복확인 함수
	function double_email()
	{
		global $_POST;

		$mem_email = $_POST['mem_email'];
		$mem_email = string_input($mem_email);

		if ($mem_email == '')
		{
			$str = '{"success_chk" : "N", "error_string"  : "이메일주소를 입력하세요."}';
		}
		else
		{
			$mem_where = " and mem.mem_id = '" . $mem_email . "'";
			$mem_data = member_info_data('page', $mem_where);

			if ($mem_data['total_num'] == 0)
			{
				$str = '{"success_chk" : "Y", "double_chk"  : "N"}';
			}
			else
			{
				$str = '{"success_chk" : "Y", "double_chk"  : "Y"}';
			}
		}
		echo $str;
		exit;
	}

// 사업자등록번호중복확인 함수
	function double_comp_num()
	{
		global $_POST;

		$comp_num = $_POST['comp_num'];
		$comp_num = string_input($comp_num);

		if ($comp_num == '')
		{
			$str = '{"success_chk" : "N", "error_string"  : "사업자등록번호를 입력하세요."}';
		}
		else
		{
			$comp_where = " and comp.comp_num = '" . $comp_num . "'";
			$comp_data =company_info_data('page', $comp_where);

			if ($comp_data['total_num'] == 0)
			{
				$str = '{"success_chk" : "Y", "double_chk"  : "N"}';
			}
			else
			{
				$str = '{"success_chk" : "Y", "double_chk"  : "Y"}';
			}
		}
		echo $str;
		exit;
	}

// 아이디찾기
	function find_id()
	{
		global $_POST;

		$param = $_POST["param"];
		$param = string_input($param);

		$comp_num = $param['comp_num1'] . '-' . $param['comp_num2'] . '-' . $param['comp_num3'];
		$hp_num   = $param['hp_num1'] . '-' . $param['hp_num2'] . '-' . $param['hp_num3'];

		$mem_where = " and comp.comp_num = '" . $comp_num . "' and mem.mem_name = '" . $param['mem_name'] . "' and mem.hp_num = '" . $hp_num . "'";
		$mem_data = member_info_data('view', $mem_where);

		if ($mem_data['total_num'] == 0)
		{
			$str = '{"success_chk" : "N", "error_string" : "일치하는 내용이 없습니다."}';
		}
		else
		{
			$str = '{"success_chk" : "Y", "message" : "찾으시는 아이디는 <br /><strong>' . $mem_data['mem_id'] . '<br /></strong>입니다."}';
		}
		echo $str;
		exit;
	}

// 비밀번호찾기
	function find_pass()
	{
		global $_POST;

		$param = $_POST["param"];
		$param = string_input($param);

		$comp_num = $param['comp_num1'] . '-' . $param['comp_num2'] . '-' . $param['comp_num3'];
		$hp_num   = $param['hp_num1'] . '-' . $param['hp_num2'] . '-' . $param['hp_num3'];

		$mem_where = " and mem.mem_id = '" . $param['mem_id'] . "' and comp.comp_num = '" . $comp_num . "' and mem.mem_name = '" . $param['mem_name'] . "' and mem.hp_num = '" . $hp_num . "'";
		$mem_data = member_info_data('view', $mem_where);

		if ($mem_data['total_num'] == 0)
		{
			$str = '{"success_chk" : "N", "error_string" : "일치하는 내용이 없습니다."}';
		}
		else
		{
			$str = '{"success_chk" : "Y", "message" : "' . $mem_data['mem_idx'] . '"}';
		}
		echo $str;
		exit;
	}

// 비밀번호 재설정
	function pass_reset()
	{
		global $_POST, $sess_str;

		$mem_idx = $_POST["mem_idx"];
		$param   = $_POST["param"];

		if ($param['mem_pwd'] == '')
		{
			$str = '{"success_chk" : "N", "error_string"  : "비밀번호를 입력하세요."}';
		}
		else if ($param['mem_pwd2'] == '')
		{
			$str = '{"success_chk" : "N", "error_string"  : "비밀번호확인을 입력하세요."}';
		}
		else
		{
			$command    = "update"; //명령어
			$table      = "member_info"; //테이블명
			$conditions = "mem_idx = '" . $mem_idx . "'"; //조건

			$param["mod_id"]   = '';
			$param["mod_date"] = date("Y-m-d H:i:s");
			$param['mem_pwd']  = pass_change($param['mem_pwd'], $sess_str);

			unset($param['mem_pwd2']);

			$query_str = make_sql($param, $command, $table, $conditions);
			db_query($query_str);
			query_history($query_str, $table, $command);

			$str = '{"success_chk" : "Y", "message"  : "비밀번호가 변경되었습니다."}';
		}

		echo $str;
		exit;
	}
?>