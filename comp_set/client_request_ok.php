<?
/*
	수정 : 2013.03.37
	위치 : 설정폴더 > 거래처관리 > 거래처등록/수정 - 실행
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
		$chk_param['require'][] = array("field"=>"comp_client_code", "msg"=>"거래처코드");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param    = $_POST['param'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		//$part_idx = $param['part_idx'];
		$part_idx = '';

		$command    = "insert"; //명령어
		$table      = "client_request_data"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");
		$param['comp_idx'] = $comp_idx;

		unset($param['sc_code']);
		unset($param['sc_name']);
		unset($param['schul_code']);
		unset($param['schul_name']);
		unset($param['tel_num']);
		unset($param['address']);

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
		$part_idx = $param['part_idx'];
		$ci_idx   = $_POST['ci_idx'];

		$command    = "update"; //명령어
		$table      = "client_info"; //테이블명
		$conditions = "ci_idx = '" . $ci_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		$param['tel_num']      = $param['tel_num1'] . '-' . $param['tel_num2'] . '-' . $param['tel_num3'];
		$param['fax_num']      = $param['fax_num1'] . '-' . $param['fax_num2'] . '-' . $param['fax_num3'];
		$param['client_email'] = $param['client_email1'] . '@' . $param['client_email2'];
		$param['zip_code']     = $param['zip_code1'] . '-' . $param['zip_code2'];
		$param['address']      = $param['address1'] . '||' . $param['address2'];
		$param['tax_comp_num'] = $param['tax_comp_num1'] . '-' . $param['tax_comp_num2'] . '-' . $param['tax_comp_num3'];
		$param['tax_zip_code'] = $param['tax_zip_code1'] . '-' . $param['tax_zip_code2'];
		$param['tax_address']  = $param['tax_address1'] . '||' . $param['tax_address2'];
		$param["tax_email"]    = $param["tax_email1"] . '@' . $param["tax_email2"];
		$param["month_price"]  = str_replace(',', '', $param['month_price']);
		if ($param["price_chk"] == '') $param["price_chk"] = 'N';

	// 추가사항관련
	// 링크주소
		$post_link_url = $_POST['post_link_url'];
		if (is_array($post_link_url))
		{
			$param['link_url'] = implode(",", $post_link_url);
			$param['link_url'] = str_replace('http://', '', $param['link_url']);
		}

	// 아이피
		$post_ip_info = $_POST['post_ip_info'];
		if (is_array($post_ip_info)) $param['ip_info'] = implode(",", $post_ip_info);

	// 담당자
		$param['charge_info'] = '';
		$info_num = $_POST['post_charge_info_num'];

		$chk_num = 1;
		for ($i = 1; $i <= $info_num; $i++)
		{
			$charge_name  = $_POST['post_charge_name' . $i];
			$charge_tel1  = $_POST['post_charge_tel1_' . $i];
			$charge_tel2  = $_POST['post_charge_tel2_' . $i];
			$charge_email = $_POST['post_charge_email' . $i];

			if ($charge_name != '')
			{
				if ($chk_num == 1)
				{
					$param['charge_info'] = $charge_name . '/' . $charge_tel1 . '/' . $charge_email . '/' . $charge_tel2;
				}
				else
				{
					$param['charge_info'] .= '||' . $charge_name . '/' . $charge_tel1 . '/' . $charge_email . '/' . $charge_tel2;
				}
				$chk_num++;
			}
		}

		unset($param['tel_num1']);
		unset($param['tel_num2']);
		unset($param['tel_num3']);
		unset($param['fax_num1']);
		unset($param['fax_num2']);
		unset($param['fax_num3']);
		unset($param['client_email1']);
		unset($param['client_email2']);
		unset($param['zip_code1']);
		unset($param['zip_code2']);
		unset($param['address1']);
		unset($param['address2']);
		unset($param['tax_zip_code1']);
		unset($param['tax_zip_code2']);
		unset($param['tax_address1']);
		unset($param['tax_address2']);
		unset($param['tax_comp_num1']);
		unset($param['tax_comp_num2']);
		unset($param['tax_comp_num3']);
		unset($param['tax_email1']);
		unset($param['tax_email2']);

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

		$ci_idx   = $_POST['idx'];
		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];

		$command    = "update"; //명령어
		$table      = "client_info"; //테이블명
		$conditions = "ci_idx = '" . $ci_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	// 거래처코드 추가입력
		$sub_where = " and cci.comp_idx = '" . $comp_idx . "'";
		$chk_data = client_code_info_data('view', $sub_where);

		$start_num = $chk_data['total_num'] + 1;
		$client_code = $comp_idx . str_pad($start_num, 6, 0, STR_PAD_LEFT);

		$sub_where = " and cci.client_code = '" . $client_code . "'";
		$chk_data = client_code_info_data('view', $sub_where);
		if ($chk_data['total_num'] == 0)
		{
			$code_query = "
				insert into client_code_info set
					  comp_idx    = '" . $comp_idx . "'
					, part_idx    = '0'
					, ci_idx      = '0'
					, client_code = '" . $client_code . "'
					, reg_id      = '" . $_SESSION[$sess_str . '_mem_idx'] . "'
					, reg_date    = '" . $param['del_date'] . "'
			";
			db_query($code_query);
			query_history($code_query, 'client_code_info', 'insert');
		}

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// yn 함수
	function check_yn()
	{
		global $_POST, $_SESSION, $sess_str;

		$sub_action = $_POST['sub_action'];
		$post_value = $_POST['post_value'];
		$ci_idx     = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "client_info"; //테이블명
		$conditions = "ci_idx = '" . $ci_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($post_value == "Y") $param[$sub_action] = "N";
		else $param[$sub_action] = "Y";

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}

// yn 함수
	function sub_check_yn()
	{
		global $_POST, $_SESSION, $sess_str;

		$sub_action = $_POST['sub_action'];
		$cu_idx     = $_POST['idx'];
		$post_value = $_POST['post_value'];
		$comp_idx   = $_SESSION[$sess_str . '_comp_idx'];

		$command    = "update"; //명령어
		$table      = "client_user"; //테이블명
		$conditions = "cu_idx = '" . $cu_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($post_value == "Y") $param[$sub_action] = "N";
		else $param[$sub_action] = "Y";

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string":""}';
		echo $str;
		exit;
	}
?>