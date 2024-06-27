<?
	include "../common/setting.php";
	//include "../common/no_direct.php";

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
		$chk_param["require"][] = array("field"=>"mem_email", "msg"=>"이메일");
		$chk_param["require"][] = array("field"=>"comp_num", "msg"=>"사업자등록번호");
		$chk_param["require"][] = array("field"=>"schul_code", "msg"=>"학교");

	//중복검사
		$chk_param["unique"][] = array("table"=>"member_info", "field"=>"mem_email", "where"=>"del_yn = 'N'", "msg"=>"이미 사용된 이메일입니다.");
		$chk_param["unique"][] = array("table"=>"company_info", "field"=>"comp_num", "where"=>"del_yn = 'N'", "msg"=>"이미 사용된 사업자등록번호입니다.");
		$chk_param["unique"][] = array("table"=>"company_info", "field"=>"schul_code", "where"=>"del_yn = 'N'", "msg"=>"이미 등록된 학교입니다.");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
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

//등록 함수
	function reg_post()
	{
		global $_POST;
		

		$param = $_POST["param"];

	// 업체정보
		$command    = "insert"; //명령어
		$table      = "demo_info"; //테이블명
		$conditions = ""; //조건

		$param["reg_id"]   = '';
		$param["reg_date"] = date("Y-m-d H:i:s");

		$param["comp_email"] = $param["mem_email1"] . '@' . $param["mem_email2"];
		$param["tel_num"]    = $param["tel_num1"] . '-' . $param["tel_num2"] . '-' . $param["tel_num3"];
		$param["hp_num"]     = $param["hp_num1"] . '-' . $param["hp_num2"] . '-' . $param["hp_num3"];
		$param["comp_num"]   = $param["comp_num1"] . '-' . $param["comp_num2"] . '-' . $param["comp_num3"];
		$param["address"]    = $param["address1"] . '||' . $param["address2"];

		$demo_data = query_view("select max(demo_idx) as demo_idx from " . $table);
		$param["demo_idx"] = ($demo_data["demo_idx"] == "") ? "1" : $demo_data["demo_idx"] + 1;
		
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

		if($param["demo_type"] == "school")
		{
			unset($param['comp_name']);
			unset($param['boss_name']);
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
		
		$demo_name = $param['sc_name'] ? $param['sc_name'] : $param['comp_name'];
		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 대표님께 알림건
		//charge_push_send('', '2', '', 'reg_ok', $param['demo_name'], '', '', '');

		$str = '{"success_chk" : "Y"}';
		echo $str;
		exit;
	}
?>