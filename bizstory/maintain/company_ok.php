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
		$chk_param['require'][] = array("field"=>"comp_email", "msg"=>"이메일");

		if ($sub_type == 'post')
		{
		//중복검사
			$chk_param['unique'][] = array("table"=>"company_info", "field"=>"comp_num", "where"=>"del_yn = 'N'", "msg"=>"이미 사용된 사업자등록번호입니다.");
		}


	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

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
		$mem_pwd = $param['tel_num3'];

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
		$mem_command    = "insert"; //명령어
		$mem_table      = "member_info"; //테이블명
		$mem_conditions = ""; //조건

		$mem_param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$mem_param["reg_date"]  = date("Y-m-d H:i:s");

		$mem_param["comp_idx"]  = $param['comp_idx'];
		$mem_param["mem_id"]    = strtolower($param["comp_email"]);
		$mem_param['mem_pwd']   = pass_change($mem_pwd, $sess_str);
		$mem_param["mem_name"]  = $param["boss_name"];
		$mem_param["mem_email"] = $param["comp_email"];
		$mem_param["tel_num"]   = $param["tel_num"];
		$mem_param["hp_num"]    = $param["hp_num"];
		$mem_param["auth_yn"]       = "N";
		$mem_param["ubstory_level"] = "11";
		$mem_param["ubstory_yn"]    = "Y";

		chk_before($mem_param);

		$query_str = make_sql($mem_param, $mem_command, $mem_table, $mem_conditions);
		db_query($query_str);
		query_history($query_str, $mem_table, $mem_command);

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
		$param['zip_code']     = $param['zip_code1'] . '-' . $param['zip_code2'];
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

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 업체설정값 수정
		$param_set = $_POST['param_set'];

		$command    = "update"; //명령어
		$table      = "company_setting"; //테이블명
		$conditions = "comp_idx = '" . $comp_idx . "'"; //조건

		$param_set['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param_set['mod_date'] = date("Y-m-d H:i:s");

		if (is_array($_POST["agent_type"])) $param_set["agent_type"] = implode(",", $_POST["agent_type"]);
		else $param_set["agent_type"] = "";

		$query_str = make_sql($param_set, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 거래처수만큼 코드생성
		$old_client_cnt = $_POST['old_client_cnt'];
		$sub_where = " and cci.comp_idx = '" . $comp_idx . "'";
		$chk_data = client_code_info_data('view', $sub_where);

		$start_num  = $chk_data['total_num'] + 1;
		$client_chk = $param_set['client_cnt'] - $old_client_cnt;
		if ($client_chk > 0)
		{
			$client_cnt = $chk_data['total_num'] + $client_chk;
		}
		else
		{
			$client_cnt = 0;
		}

		for ($i = $start_num; $i <= $client_cnt; $i++)
		{
			$client_code = $comp_idx . str_pad($i, 6, 0, STR_PAD_LEFT);

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
						, reg_id      = 'system'
						, reg_date    = '" . $param_set['mod_date'] . "'
				";
				db_query($code_query);
				query_history($code_query, 'client_code_info', 'insert');
			}
		}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일센터를 사용할 경우
		$file_out = '';
		if ($param_set['file_class'] == 'OUT') // 외부파일공간
		{
			if ($param_set['file_out_url'] != '') // 외부파일주소
			{
				$file_out = 'Y';
			}
		}

		$str = '{"success_chk" : "Y", "error_string" : "", "file_out" : "' . $file_out . '", "comp_idx" : "' . $comp_idx . '"}';
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
/*
	// 지사관련삭제
		$part_where = " and part.comp_idx = '" . $comp_idx . "'";
		$part_list = company_part_data('list', $part_where, '', '', '');
		foreach ($part_list as $part_k => $part_data)
		{
			if (is_array($part_data))
			{
				$part_idx = $part_data['part_idx'];

			// 관련정보 전부삭제
				delete_part_data($part_idx, $_SESSION[$sess_str . '_mem_idx']);
			}
		}

	// 지사삭제
		$delete_query = "
			update company_part set
				del_yn   = 'Y',
				del_ip   = '" . $ip_address . "',
				del_id   = '" . $_SESSION[$sess_str . '_mem_idx'] . "',
				del_date = '" . date("Y-m-d H:i:s") . "'
			where
				part_idx = '" . $part_idx . "'";
		db_query($delete_query);
		query_history($delete_query, $table_name, 'update');
*/
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

		$command    = "update"; //명령어
		$table      = "company_info"; //테이블명
		$conditions = "comp_idx = '" . $comp_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($sub_action == "auth_yn")
		{
		// 승인이 된건지 확인
			$comp_where = " and comp.comp_idx = '" . $comp_idx . "'";
			$comp_data = company_info_data('view', $comp_where);

			$company_name = $comp_data['comp_name'];

			if ($comp_data['auth_yn'] == 'N') // 승인이 안된 경우
			{
				if ($post_value == "N")
				{
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

				// 사이트메뉴권한 - 기본값 같이 셋팅
				// 분류별로 설정된 기본메뉴값을 가지고 온다.
					if ($comp_data['comp_class'] > 0)
					{
						$class_menu_where = " and ccm.code_idx = '" . $comp_data['comp_class'] . "' and ccm.view_yn = 'Y'";
						$class_menu_list = company_class_menu_data('list', $class_menu_where, '', '', '');
						if ($class_menu_list['total_num'] == 0)
						{
							$str = '{"success_chk" : "N", "error_string" : "등록된 업체분류 메뉴가 없습니다."}';
							echo $str;
							exit;
						}
						else
						{
							$chk_menu_k = 1;
							$query1 = '';
							foreach ($class_menu_list as $menu_k => $menu_data)
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
											$query1 = "INSERT INTO menu_auth_company (comp_idx, mi_idx, view_yn, default_yn, reg_id, reg_date) VALUES ";
										}
										else $query1 .= ",";
										$query1 .= "('" . $comp_idx . "', '" . $menu_data['mi_idx'] . "', 'Y', '" . $default_yn . "', 'system', '" . $reg_date . "')";

										$chk_menu_k++;
									}
								}
							}
							if ($query1 != '')
							{
								db_query($query1);
								query_history($query1, 'menu_auth_company', 'insert');
							}
						}
					}
					else
					{
						$menu_where = " and mi.view_yn = 'Y' and mi.mode_type != 'maintain'";
						$menu_list = menu_info_data('list', $menu_where, '', '', '');

						$chk_menu_k = 1;
						$query1 = '';
						foreach ($menu_list as $menu_k => $menu_data)
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
										$query1 = "INSERT INTO menu_auth_company (comp_idx, mi_idx, view_yn, default_yn, reg_id, reg_date) VALUES ";
									}
									else $query1 .= ",";
									$query1 .= "('" . $comp_idx . "', '" . $menu_data['mi_idx'] . "', 'Y', '" . $default_yn . "', 'system', '" . $reg_date . "')";

									$chk_menu_k++;
								}
							}
						}
						if ($query1 != '')
						{
							db_query($query1);
							query_history($query1, 'menu_auth_company', 'insert');
						}
					}

				// 지사등록 - 1개 임의 셋팅
				// 지사정보 확인
					$part_chk_where = " and part.comp_idx = '" . $comp_idx . "'";
					$part_chk_order = "part.reg_date asc";
					$part_chk_data = company_part_data('view', $part_chk_where, $part_chk_order);
					if ($part_chk_data['total_num'] == 0) // 값이 없을 경우 등록
					{
						$sub_data = query_view("select max(part_idx) as part_idx from company_part");
						$part_idx = ($sub_data["part_idx"] == "") ? "1" : $sub_data["part_idx"] + 1;

						$query3  = "insert into company_part (comp_idx, part_idx, part_name, view_yn, default_yn, sort, set_yn, reg_id, reg_date) values";
						$query3 .= "('" . $comp_idx . "', '" . $part_idx . "', '" . $company_name . "', 'Y', 'Y', '1', 'Y', 'system', '" . $reg_date . "')";
						db_query($query3);
						query_history($query3, 'company_part', 'insert');
					}
					else
					{
						$part_idx = $part_chk_data['part_idx'];
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
								$query2 .= "('" . $comp_idx . "', '" . $part_idx . "', '" . $mem_data['mem_idx'] . "', '" . $auth_data['mi_idx'] . "', 'system', '" . $reg_date . "'
								, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y')";

								$chk_menu_k++;
							}
						}
					}
					if ($query2 != '')
					{
						db_query($query2);
						query_history($query2, 'menu_auth_member', 'insert');
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

				// 거래처코드생성 - 테이블로 관리
					$chk_menu_k = 1;
					$query7 = '';
					$total_client = $comp_set_data['client_cnt'];
					for ($i = 1; $i <= $total_client; $i++)
					{
						$client_code = $comp_idx . str_pad($i, 6, 0, STR_PAD_LEFT);

					// 거래처코드값 확인
						$code_chk_where = " and cci.comp_idx = '" . $comp_idx . "' and cci.client_code = '" . $client_code . "'";
						$code_chk_data = client_code_info_data('page', $code_chk_where);
						if ($code_chk_data['total_num'] == 0) // 값이 없을 경우 등록
						{
							if ($chk_menu_k == 1)
							{
								$query7 = "
									INSERT INTO client_code_info (comp_idx, part_idx, ci_idx, client_code, reg_id, reg_date) VALUES ";
							}
							else $query7 .= ",";
							$query7 .= "('" . $comp_idx . "', '0', '0', '" . $client_code . "', 'system', '" . $reg_date . "')";

							$chk_menu_k++;
						}
					}
					if ($query7 != '')
					{
						db_query($query7);
						query_history($query7, 'client_code_info', 'insert');
					}

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
				}
			}
		}
		else
		{
			if ($post_value == "Y") $param[$sub_action] = "N";
			else $param[$sub_action] = "Y";
		}

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

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
?>