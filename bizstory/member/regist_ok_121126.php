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
	function chk_before($param)
	{
	//필수검사
		$chk_param["require"][] = array("field"=>"mem_email", "msg"=>"이메일");
		$chk_param["require"][] = array("field"=>"comp_num", "msg"=>"사업자등록번호");

	//중복검사
		$chk_param["unique"][] = array("table"=>"member_info", "field"=>"mem_email", "where"=>"del_yn = 'N'", "msg"=>"이미 사용된 이메일입니다.");
		$chk_param["unique"][] = array("table"=>"company_info", "field"=>"comp_num", "where"=>"del_yn = 'N'", "msg"=>"이미 사용된 사업자등록번호입니다.");

	//체크합니다.
		//param_check($param, $chk_param, 'json');
	}

	$field_str = str_replace("|", "&", $field_str);

// 이메일중복확인 함수
	function double_email()
	{
		global $_GET;

		$mem_email = $_GET['mem_email'];
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
		global $_GET;

		$comp_num = $_GET['comp_num'];
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
		global $_POST, $sess_str;

		$comp_param = $_POST["param"];
		$mem_param  = $_POST["param"];

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 업체정보
		$comp_command    = "insert"; //명령어
		$comp_table      = "company_info"; //테이블명
		$comp_conditions = ""; //조건

		$comp_param["reg_id"]   = '';
		$comp_param["reg_date"] = date("Y-m-d H:i:s");

		$comp_param["tel_num"]      = $comp_param["tel_num1"] . '-' . $comp_param["tel_num2"] . '-' . $comp_param["tel_num3"];
		$comp_param["hp_num"]       = $comp_param["hp_num1"] . '-' . $comp_param["hp_num2"] . '-' . $comp_param["hp_num3"];
		$comp_param["comp_email"]   = $comp_param["mem_email1"] . '@' . $comp_param["mem_email2"];
		$comp_param["comp_num"]     = $comp_param["comp_num1"] . '-' . $comp_param["comp_num2"] . '-' . $comp_param["comp_num3"];
		$comp_param["zip_code"]     = $comp_param["zip_code1"] . '-' . $comp_param["zip_code2"];
		$comp_param["address"]      = $comp_param["address1"] . '||' . $comp_param["address2"];

		$comp_data = query_view("select max(comp_idx) as comp_idx from " . $comp_table);
		$comp_param["comp_idx"] = ($comp_data["comp_idx"] == "") ? "1" : $comp_data["comp_idx"] + 1;

		unset($comp_param['mem_email1']);
		unset($comp_param['mem_email2']);
		unset($comp_param['tel_num1']);
		unset($comp_param['tel_num2']);
		unset($comp_param['tel_num3']);
		unset($comp_param['hp_num1']);
		unset($comp_param['hp_num2']);
		unset($comp_param['hp_num3']);
		unset($comp_param['comp_num1']);
		unset($comp_param['comp_num2']);
		unset($comp_param['comp_num3']);
		unset($comp_param['zip_code1']);
		unset($comp_param['zip_code2']);
		unset($comp_param['address1']);
		unset($comp_param['address2']);

		chk_before($comp_param);

		$query_str = make_sql($comp_param, $comp_command, $comp_table, $comp_conditions);
		db_query($query_str);
		query_history($query_str, $comp_table, $comp_command);

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 업체설정
		$comps_command    = "insert"; //명령어
		$comps_table      = "company_set"; //테이블명
		$comps_conditions = ""; //조건

		$comps_param["reg_id"]   = '';
		$comps_param["reg_date"] = date("Y-m-d H:i:s");

		$comps_param["comp_idx"]          = $comp_param['comp_idx'];
		$comps_param["part_yn"]           = 'N';
		$comps_param["receipt_yn"]        = 'Y';
		$comps_param["receipt_inform_yn"] = 'Y';
		$comps_param["agent_yn"]          = 'N';
		$comps_param["tax_yn"]            = 'N';
		$comps_param["account_yn"]        = 'Y';
		$comps_param["work_yn"]           = 'Y';
		$comps_param["diligence_yn"]      = 'N';
		$comps_param["schedule_yn"]       = 'Y';
		$comps_param["message_yn"]        = 'Y';

	// 서비스
		$sub_where = "and default_yn = 'Y'";
		$sub_data = service_info_data('view', $sub_where);

		$comps_param["use_price"]  = $sub_data['use_price'];
		$comps_param["client_cnt"] = $sub_data['client_cnt'];
		$comps_param["part_cnt"]   = $sub_data['part_cnt'];
		$comps_param["banner_cnt"] = $sub_data['banner_cnt'];
		$comps_param["group_cnt"]  = $sub_data['group_cnt'];

		$query_str = make_sql($comps_param, $comps_command, $comps_table, $comps_conditions);
		db_query($query_str);
		query_history($query_str, $comps_table, $comps_command);

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 회원정보
		$mem_command    = "insert"; //명령어
		$mem_table      = "member_info"; //테이블명
		$mem_conditions = ""; //조건

		$mem_param["reg_id"]    = '';
		$mem_param["reg_date"]  = date("Y-m-d H:i:s");

		$mem_param["comp_idx"]  = $comp_param['comp_idx'];
		$mem_param["mem_id"]    = strtolower($comp_param["comp_email"]);
		$mem_param['mem_pwd']   = pass_change($mem_param['tel_num3'], $sess_str);
		$mem_param["mem_name"]  = $mem_param["boss_name"];
		$mem_param["mem_email"] = $comp_param["comp_email"];
		$mem_param["tel_num"]   = $comp_param["tel_num"];
		$mem_param["hp_num"]    = $comp_param["hp_num"];
		$mem_param["auth_yn"]       = "N";
		$mem_param["ubstory_level"] = "11";
		$mem_param["ubstory_yn"]    = "Y";

		unset($mem_param['mem_email1']);
		unset($mem_param['mem_email2']);
		unset($mem_param['tel_num1']);
		unset($mem_param['tel_num2']);
		unset($mem_param['tel_num3']);
		unset($mem_param['hp_num1']);
		unset($mem_param['hp_num2']);
		unset($mem_param['hp_num3']);
		unset($mem_param['boss_name']);
		unset($mem_param['comp_name']);
		unset($mem_param['comp_num1']);
		unset($mem_param['comp_num2']);
		unset($mem_param['comp_num3']);
		unset($mem_param['upjong']);
		unset($mem_param['uptae']);
		unset($mem_param['zip_code1']);
		unset($mem_param['zip_code2']);
		unset($mem_param['address1']);
		unset($mem_param['address2']);

		chk_before($mem_param);

		$query_str = make_sql($mem_param, $mem_command, $mem_table, $mem_conditions);
		db_query($query_str);
		query_history($query_str, $mem_table, $mem_command);

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 대표님께 알려주기
		$push = new PUSH("bizstory_push");

		$msg_type = 'reg_ok';
		$message = strip_tags($comp_param['comp_name']);
		$message = '[업체신청] ' . string_cut($message, 20);

		$mem_idx = 2;
		$mem_where = " and mem.mem_idx = '" . $mem_idx . "'";
		$mem_data = member_info_data('view', $mem_where);

		$comp_idx = $mem_data['comp_idx'];
		$part_idx = $mem_data['part_idx'];
		$receiver = $mem_data['mem_id'];

		$result = @$push->push_send($sender, $comp_idx, $part_idx, $mem_idx, $receiver, $msg_type, $message);
		unset($mem_data);

		$str = '{"success_chk" : "Y"}';
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