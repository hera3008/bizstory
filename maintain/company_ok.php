<?
/*
	수정 : 2013.05.22
	위치 : 설정폴더(총관리자용) > 업체관리 > 업체목록 - 실행
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
		global $sub_type;

	//필수검사
		$chk_param['require'][] = array("field"=>"comp_name", "msg"=>"상호명");
		$chk_param['require'][] = array("field"=>"boss_name", "msg"=>"대표자명");
		$chk_param['require'][] = array("field"=>"comp_num", "msg"=>"사업자등록번호");
		$chk_param['require'][] = array("field"=>"tel_num", "msg"=>"전화번호");
		$chk_param['require'][] = array("field"=>"comp_email", "msg"=>"이메일");

		if ($sub_type == 'post')
		{
		//중복검사
			$chk_param['unique'][] = array("table"=>"company_info", "field"=>"comp_code", "where"=>"del_yn = 'N'", "msg"=>"이미 사용된 업체입니다.");
			$chk_param['unique'][] = array("table"=>"company_info", "field"=>"comp_num", "where"=>"del_yn = 'N'", "msg"=>"이미 사용된 사업자등록번호입니다.");
		}


	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$comp_category = $_POST['comp_category'];
		$param = $_POST['param'];

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 업체정보
		$command    = "insert"; //명령어
		$table      = "company_info"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");

		$comp_data = query_view("select max(comp_idx) as comp_idx from " . $table);
		$param["comp_idx"] = ($comp_data["comp_idx"] == "") ? "1" : $comp_data["comp_idx"] + 1;

		
		$param['comp_num']     = $param['comp_num1'] . '-' . $param['comp_num2'] . '-' . $param['comp_num3'];
		$param['distinct_num'] = $param['distinct_num1'] . '-' . $param['distinct_num2'];
		$param['zip_code']     = $param['zip_code1'] . '-' . $param['zip_code2'];
		$param['address']      = $param['address1'] . '||' . $param['address2'];
		$param['tel_num']      = $param['tel_num1'] . '-' . $param['tel_num2'] . '-' . $param['tel_num3'];
		$param['fax_num']      = $param['fax_num1'] . '-' . $param['fax_num2'] . '-' . $param['fax_num3'];
		$param['comp_email']   = $param['comp_email1'] . '@' . $param['comp_email2'];
		$param['hp_num']       = $param['hp_num1'] . '-' . $param['hp_num2'] . '-' . $param['hp_num3'];
		$param['carecon_type']   = 'A'; 

		if($param['comp_class'] == '2') 
		{
			$param['comp_code']     = $param['comp_num1'] . $param['comp_num2'] . $param['comp_num3'];
		}
		else
		{
			$comp_code = $param['sc_code'].($comp_category == 4 ? $param['org_code'] : $param['schul_code']) ;
			$param['comp_code']  = str_pad($comp_code, 10 , "0");
			$param['comp_name'] = $param['schul_name']?$param['schul_name'] : ($param['org_name'] ? $param['org_name']: $param['sc_name']);
			$param['boss_name'] = $param['comp_name'];
		}
		

		unset($param['comp_num1']);
		unset($param['comp_num2']);
		unset($param['comp_num3']);
		unset($param['distinct_num1']);
		unset($param['distinct_num2']);
		unset($param['zip_code1']);
		unset($param['zip_code2']);
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

	
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 업체설정
		$comps_command    = "insert"; //명령어
		$comps_table      = "company_setting"; //테이블명
		$comps_conditions = ""; //조건

		$comps_param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$comps_param["reg_date"] = date("Y-m-d H:i:s");

		$comps_param["comp_idx"]     = $param['comp_idx'];
		$comps_param["part_yn"]      = 'N';
		$comps_param["part_work_yn"] = 'N';
		$comps_param["agent_yn"]     = 'N';
		$comps_param["viewer_yn"]    = 'N';

		$query_str = make_sql($comps_param, $comps_command, $comps_table, $comps_conditions);
		db_query($query_str);
		query_history($query_str, $comps_table, $comps_command);

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 회원정보
		
		if($param['comp_class'] == '1')
		{
			if(strpos($param['home_page'], 'www') !== false)
			{
				$mem_id = (explode('.', $param['home_page']))[1];
			} 
			else
			{
				$home_page = (explode('//', $param['home_page']))[1];
				$mem_id = (explode('.', $home_page))[0];
			}
		}
		else
		{
			$mem_id = strtolower($param["comp_email"]);
		}


		if($mem_id != "") set_member($mem_id, $param);

		if($param['auth_yn'] == 'Y') company_default_setting($param['comp_idx']);
		
		$str = '{"success_chk" : "Y", "error_string" : "", "file_out" : "N", "comp_idx" : ""}';
		echo $str;
		exit;
		
	}



// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$comp_idx = $_POST['comp_idx'];
		$param    = $_POST['param'];

		$command    = "update"; //명령어
		$table      = "company_info"; //테이블명
		$conditions = "comp_idx = '" . $comp_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		$param['comp_num']     = $param['comp_num1'] . '-' . $param['comp_num2'] . '-' . $param['comp_num3'];
		$param['distinct_num'] = $param['distinct_num1'] . '-' . $param['distinct_num2'];
		$param['address']      = $param['address1'] . '||' . $param['address2'];
		$param['tel_num']      = $param['tel_num1'] . '-' . $param['tel_num2'] . '-' . $param['tel_num3'];
		$param['fax_num']      = $param['fax_num1'] . '-' . $param['fax_num2'] . '-' . $param['fax_num3'];
		$param['comp_email']   = $param['comp_email1'] . '@' . $param['comp_email2'];
		$param['hp_num']       = $param['hp_num1'] . '-' . $param['hp_num2'] . '-' . $param['hp_num3'];

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

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 업체설정값 수정
		$param_set = $_POST['param_set'];

		$command    = "update"; //명령어
		$table      = "company_setting"; //테이블명
		$conditions = "comp_idx = '" . $comp_idx . "'"; //조건

		$param_set['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param_set['mod_date'] = date("Y-m-d H:i:s");

		if (is_array($_POST["carecon_type"])) $param_set["carecon_type"] = implode(",", $_POST["carecon_type"]);
		else $param_set["carecon_type"] = "";

		$query_str = make_sql($param_set, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);
		

		if($param['auth_yn'] == 'Y') company_default_setting($comp_idx);

		$str = '{"success_chk" : "Y", "error_string" : "", "file_out" : "N", "comp_idx" : ""}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$comp_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "company_info"; //테이블명
		$conditions = "comp_idx = '" . $comp_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// yn 함수
	function check_yn()
	{
		global $_POST, $_SESSION, $sess_str;

		$sub_action = $_POST['sub_action'];
		$post_value = $_POST['post_value'];
		$comp_idx   = $_POST['idx'];
		
		$comp_where = " and comp.comp_idx = '" . $comp_idx . "'";
		$comp_data = company_info_data('view', $comp_where);

		$command    = "update"; //명령어
		$table      = "company_info"; //테이블명
		$conditions = "comp_idx = '" . $comp_idx . "'"; //조건


		if ($comp_data['auth_yn'] == 'N') // 승인이 안된 경우
		{
			if ($post_value == "N")
			{
				company_default_setting($comp_idx);

				if ($post_value == "Y") $param[$sub_action] = "N";
				else $param[$sub_action] = "Y";

				$query_str = make_sql($param, $command, $table, $conditions);
				db_query($query_str);
				query_history($query_str, $table, $command);
			}
			
		}
		else
		{
			if ($post_value == "Y") $param[$sub_action] = "N";
			else $param[$sub_action] = "Y";

				$query_str = make_sql($param, $command, $table, $conditions);
				db_query($query_str);
				query_history($query_str, $table, $command);

		}
		
		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 업체 기본값 설정
	function company_default_setting($comp_idx)
	{

		global $_POST, $_SESSION, $sess_str;
		$part_idx = '';


		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");
		
		// 승인이 된건지 확인
		$comp_where = " and comp.comp_idx = '" . $comp_idx . "'";
		$comp_data = company_info_data('view', $comp_where);

		$company_name = $comp_data['comp_name'];

		$end_time = time() + (365 * 24 * 60 * 60); // 365일
		$end_date = date('Y-m-d', $end_time);
		$reg_date = date("Y-m-d H:i:s");

		$param['auth_yn']    = "Y";
		$param['auth_date']  = $reg_date;
		$param['start_date'] = $param['auth_date'];
		$param['end_date']   = $end_date;

	// 업체설정
		$comp_where = " and cs.comp_idx = '" . $comp_idx . "'";
		$comp_set_data = company_setting_data("view", $comp_where);

	// 업체최고관리자
		$mem_where = "and mem.comp_idx = '" . $comp_idx . "' and mem.ubstory_level = '11'";
		$mem_data = member_info_data('view', $mem_where);
		
	// 접수 분류 추가
	if($comp_data['comp_class_sub'] == 3) set_receipt_class($comp_idx);
	
	// 거래처 분류 추가
	if($comp_data['clent_group_code']) set_company_group($comp_idx, $comp_data['clent_group_code']);

	// 사이트메뉴권한 - 기본값 같이 셋팅
	if ($comp_data['menu_code']) set_memu_class($comp_idx, $comp_data['menu_code']);
	else
	{
		$str = '{"success_chk" : "N", "error_string" : "등록된 업체분류 메뉴가 없습니다."}';
		echo $str;
		exit;
	}
					
	// 최고관리자권한주기
		$mem_query = "
			update member_info set
				login_yn  = 'Y',
				auth_yn   = 'Y',
				auth_date = '" . $param['auth_date'] . "',
				part_idx  = '" . $part_idx . "'
			where mem_idx = '" . $mem_data['mem_idx'] . "'";
		db_query($mem_query);

	// 기본값 설정
		default_code_setting($comp_idx, $part_idx);
				

	//데이터폴더생성 - /data/company/admin id/banner, board, company, icon_data, member, popup, receipt, work, message */
		global $comp_path;

		$file_path = $comp_path . '/' . $comp_idx;
		files_dir($file_path);
		$file_path = $comp_path . '/' . $comp_idx . '/banner'; // 배너
		files_dir($file_path);
		$file_path = $comp_path . '/' . $comp_idx . '/board'; // 게시판
		files_dir($file_path);
		$file_path = $comp_path . '/' . $comp_idx . '/company'; // 업체
		files_dir($file_path);
		$file_path = $comp_path . '/' . $comp_idx . '/icon_data'; // 에이전트
		files_dir($file_path);
		$file_path = $comp_path . '/' . $comp_idx . '/member'; // 직원(회원)
		files_dir($file_path);
		$file_path = $comp_path . '/' . $comp_idx . '/message'; // 쪽지
		files_dir($file_path);
		$file_path = $comp_path . '/' . $comp_idx . '/popup'; // 팝업창
		files_dir($file_path);
		$file_path = $comp_path . '/' . $comp_idx . '/receipt'; // 접수
		files_dir($file_path);
		$file_path = $comp_path . '/' . $comp_idx . '/work'; // 업무
		files_dir($file_path);
		$file_path = $comp_path . '/' . $comp_idx . '/bnotice'; // 알림게시판
		files_dir($file_path);
		$file_path = $comp_path . '/' . $comp_idx . '/client'; // 거래처메모
		files_dir($file_path);
		$file_path = $comp_path . '/' . $comp_idx . '/consult'; // 상담게시판
		files_dir($file_path);
		$file_path = $comp_path . '/' . $comp_idx . '/project'; // 프로젝트
		files_dir($file_path);


		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 메뉴권한 함수
	function auth_menu()
	{
		global $_POST, $_SESSION, $sess_str;

		$comp_idx   = $_POST['comp_idx'];
		$mi_idx     = $_POST['idx'];
		$post_value = $_POST['post_value'];

	// 데이타 확인
		$where = " and mac.comp_idx = '" . $comp_idx . "' and mac.mi_idx = '" . $mi_idx . "'";
		$data = menu_auth_company_data('page', $where);

	// 데이타가 없을 경우 등록
		if ($data['total_num'] == 0)
		{
			$command    = "insert"; //명령어
			$table      = "menu_auth_company"; //테이블명
			$conditions = ""; //조건

			$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
			$param['reg_date'] = date("Y-m-d H:i:s");

			if ($post_value == "Y") $param['view_yn'] = "N";
			else $param['view_yn'] = "Y";

			$param['comp_idx'] = $comp_idx;
			$param['mi_idx']   = $mi_idx;
		}
		else
		{
			$command    = "update"; //명령어
			$table      = "menu_auth_company"; //테이블명
			$conditions = "comp_idx = '" . $comp_idx . "' and mi_idx = '" . $mi_idx . "'"; //조건

			if ($post_value == "Y") $param['view_yn'] = "N";
			else $param['view_yn'] = "Y";

			$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
			$param['mod_date'] = date("Y-m-d H:i:s");
		}

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "idx" : "' . $comp_idx . '", "error_string" : ""}';
		echo $str;
		exit;
	}

//회원가입
	function set_member($mem_id, $param)
	{
		global $_SESSION, $sess_str;

		$mem_pwd = str_replace('-', '', $param['tel_num']);
		
		$mem_command    = "insert"; //명령어
		$mem_table      = "member_info"; //테이블명
		$mem_conditions = ""; //조건

		$mem_param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$mem_param["reg_date"]  = date("Y-m-d H:i:s");

		$mem_param["comp_idx"]  = $param['comp_idx'];
		$mem_param["mem_id"]    = strtolower($mem_id);
		$mem_param['mem_pwd']   = pass_change($mem_pwd, $sess_str);
		$mem_param["mem_name"]  = $param["boss_name"];
		$mem_param["mem_email"] = $param["comp_email"];
		$mem_param["tel_num"]   = $param["tel_num"];
		$mem_param["hp_num"]    = $param["hp_num"];
		$mem_param["auth_yn"]       = "N";
		$mem_param["ubstory_level"] = "11";
		$mem_param["ubstory_yn"]    = "Y";

		$query_str = make_sql($mem_param, $mem_command, $mem_table, $mem_conditions);
		db_query($query_str);
		query_history($query_str, $mem_table, $mem_command);


		//$str = '{"success_chk" : "Y", "error_string" : "", "file_out" : "N", "comp_idx" : ""}';
		//echo $str;
		return;
	}

// 접수 분류 추가
	function set_receipt_class($comp_idx)
	{
		global $sess_str;
		

		if(!$comp_idx) return false;
		
	//업체 정보
		$comp_where = " and comp.comp_idx = '" . $comp_idx . "'";
		$comp_data = company_info_data('view', $comp_where);

		$where = " and code.import_yn = 'Y'";
		$list = code_receipt_class_data('list', $where, '', '', '');

		$command    = "insert"; //명령어
		$table      = "code_receipt_class"; //테이블명
		$conditions = ""; //조건

		$up_code_idx ='';
		
		$receipt_code = array();

		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
				$code = $data["receipt_code"];

				if($data["menu_depth"] == 1)
				{
					$up_code_idx ='';
				}
				else
				{
					$up_code_idx = "";
					for($i=2; $i<=$data["menu_depth"]; $i++)
					{
						$up_code = substr($data["receipt_code"], 0,($i*2)-2);
						$up_code_idx .= ','.$receipt_code[$up_code];
						
					}
					
				}
				
				$code_param['reg_id']   	= $_SESSION[$sess_str . '_mem_idx'];
				$code_param["reg_date"]  	= date("Y-m-d H:i:s");

				$code_param["comp_idx"]  	= $comp_idx;
				$code_param["part_idx"]    	= '';
				$code_param["sc_code"]    	= $comp_data["sc_code"];
				$code_param["org_code"]    	= $comp_data["org_code"];
				$code_param["schul_code"]   = $comp_data["schul_code"];					
				$code_param['code_name']   	= $data['code_name'];
				$code_param["receipt_code"] = $data["receipt_code"];
				$code_param["up_code_idx"] 	= $up_code_idx;
				$code_param["menu_depth"]   = $data["menu_depth"];
				$code_param["menu_num"]    	= $data["menu_num"];
				$code_param["import_yn"]    = 'N';
				$code_param["view_yn"]    	= $data["view_yn"];
				$code_param["default_yn"]   = $data["default_yn"];
				$code_param["sort"] 		= $data["sort"];
				
				$query_str = make_sql($code_param, $command, $table, $conditions);
				db_query($query_str);
				$code_idx = query_insert_id();
				query_history($query_str, $table, $command);

				$receipt_code[$code] = $code_idx;
				
			}
		}

		return;
	}

// 거래처 분류 추가
	function set_company_group($comp_idx, $clent_group_code)
	{
		global $sess_str;
		
		if(!$comp_idx) return false;

		$comp_where = " and comp.comp_idx = '" . $comp_idx . "'";
		$comp_data = company_info_data('view', $comp_where);
		
		$clent_group_chk_data = query_view("select count(ccg_idx) as cnt from company_client_group  where comp_idx = '" . $comp_idx . "' and del_yn != 'Y' and view_yn = 'Y' ");
		if ($clent_group_chk_data['cnt'] == 0) // 값이 없을 경우 등록
		{
			
			$query_string = "
				select *
					, substring(group_code, LENGTH('" . $clent_group_code . "')+1, LENGTH(group_code)) AS clent_group_code 
				from 
					company_client_group 
				WHERE 
					del_yn != 'Y' 
					AND import_yn = 'Y'
					AND view_yn = 'Y' 
					AND group_code !='" . $clent_group_code . "' 
					AND group_code LIKE '" . $clent_group_code . "%' 
				ORDER BY group_code";
			$data_sql['query_string'] = $query_string;
			$list = query_list($data_sql);

			$command    = "insert"; //명령어
			$table      = "company_client_group"; //테이블명
			$conditions = ""; //조건

			$up_ccg_idx ='';
			
			$group_code = array();

			foreach($list as $k => $data)
			{
				if (is_array($data))
				{
					$code = $data["clent_group_code"];
					$menu_depth = strlen($data["clent_group_code"]) / 2;

					if($menu_depth == 1)
					{
						$up_ccg_idx ='';
					}
					else
					{
						$up_ccg_idx = "";
						for($i=2; $i<=$menu_depth; $i++)
						{
							$up_code = substr($data["clent_group_code"], 0,($i*2)-2);
							$up_ccg_idx .= ','.$group_code[$up_code];
							
						}
						
					}
					
					$code_param['reg_id']   	= $_SESSION[$sess_str . '_mem_idx'];
					$code_param["reg_date"]  	= date("Y-m-d H:i:s");

					$code_param["comp_idx"]  	= $comp_idx;
					$code_param["part_idx"]    	= '';
					$code_param["sc_code"]    	= $comp_data["sc_code"];
					$code_param["org_code"]    	= $comp_data["org_code"];
					$code_param["schul_code"]   = $comp_data["schul_code"];					
					$code_param['group_name']   = $data['group_name'];
					$code_param["group_code"] 	= $data["clent_group_code"];
					$code_param["up_ccg_idx"] 	= $up_ccg_idx;
					$code_param["menu_depth"]   = $menu_depth;
					$code_param["menu_num"]    	= $data["menu_num"];
					$code_param["import_yn"]    = 'N';
					$code_param["view_yn"]    	= $data["view_yn"];
					$code_param["default_yn"]   = $data["default_yn"];
					$code_param["sort"] 		= $data["sort"];
					
					$query_str = make_sql($code_param, $command, $table, $conditions);			
					db_query($query_str);
					$ccg_idx = query_insert_id();			
					query_history($query_str, $table, $command);
					$group_code[$code] = $ccg_idx;
					
				}
			}
		}
		
		return;
	}

// 메뉴 설정
	// 메뉴 설정
	function set_memu_class($comp_idx, $menu_code)
	{
		global $sess_str;
		
		$part_idx = "";
		
	//최고 관리자 권한
		$mem_where = "and mem.comp_idx = '" . $comp_idx . "' and mem.ubstory_level = '11'";
		$mem_data = member_info_data('view', $mem_where);	
		

	//업체 정보
		$comp_where = " and comp.comp_idx = '" . $comp_idx . "'";
		$comp_data = company_info_data('view', $comp_where);

	//업체 설정 메뉴
		$where = " and mi.menu_code != '" .$menu_code. "' and substr(mi.menu_code, 1, 2) = '" .$menu_code. "'";
		$list = menu_info_data('list', $where, 'mi.menu_code', '', '');

		$chk_menu_k = 1;
		$query1 = '';
		foreach($list as $k => $menu_data)
		{
			if (is_array($menu_data))
			{

				// 메뉴설정값 확인
				$menu_auth_chk_where = " and mac.comp_idx = '" . $comp_idx . "' and mac.mi_idx = '" . $menu_data['mi_idx'] . "'";
				$menu_auth_chk_data = menu_auth_company_data('page', $menu_auth_chk_where);
				if ($menu_auth_chk_data['total_num'] == 0) // 값이 없을 경우 등록
				{
					if ($menu_data['default_yn'] == 'Y') $default_yn = 'Y';
					else $default_yn = 'N';

					if ($chk_menu_k == 1)
					{
						$query1 = "INSERT INTO menu_auth_company (comp_idx, mi_idx, menu_code, view_yn, default_yn, reg_id, reg_date) VALUES ";
					}
					else $query1 .= ",";
					$query1 .= "('" . $comp_idx . "', '" . $menu_data['mi_idx'] . "', '" . $menu_data['menu_code'] . "', 'Y', '" . $default_yn . "', 'system', now())";
					
					$chk_menu_k++;
				}
			}

		}
		if ($query1 != '')
		{
			db_query($query1);
			query_history($query1, 'menu_auth_company', 'insert');
		}


		// 관리자메뉴권한
		$auth_where = " and mac.comp_idx = '" . $comp_idx . "' and mac.view_yn = 'Y'";
		$auth_list = menu_auth_company_data('list', $auth_where, '', '', '');

		$chk_menu_k = 1;
		$query2 = '';
		foreach ($auth_list as $auth_k => $auth_data)
		{
			if (is_array($auth_data))
			{
			// 메뉴설정값 확인
				$menu_auth_chk_where = " and mam.comp_idx = '" . $comp_idx . "' and mam.mem_idx = '" . $mem_data['mem_idx'] . "' and mam.mi_idx = '" . $auth_data['mi_idx'] . "'";
				$menu_auth_chk_data = menu_auth_member_data('page', $menu_auth_chk_where);
				if ($menu_auth_chk_data['total_num'] == 0) // 값이 없을 경우 등록
				{
					if ($chk_menu_k == 1)
					{
						$query2 = "
							INSERT INTO menu_auth_member (comp_idx, part_idx, mem_idx, mi_idx, reg_id, reg_date, yn_list, yn_int, yn_mod, yn_del, yn_view, yn_print, yn_down) VALUES ";
					}
					else $query2 .= ",";
					$query2 .= "('" . $comp_idx . "', '" . $part_idx . "', '" . $mem_data['mem_idx'] . "', '" . $auth_data['mi_idx'] . "', 'system', now(), 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y')";

					$chk_menu_k++;
				}
			}
		}

		if ($query2 != '')
		{
			db_query($query2);
			query_history($query2, 'menu_auth_member', 'insert');
		}

		return true;
	}
?>