<?
/*
	수정 : 2012.10.09
	위치 : 설정폴더(관리자) > 업체관리 > 업체목록 - 실행
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
		$chk_param['require'][] = array("field"=>"comp_name", "msg"=>"상호명");
		$chk_param['require'][] = array("field"=>"boss_name", "msg"=>"대표자명");
		$chk_param['require'][] = array("field"=>"comp_num", "msg"=>"사업자등록번호");
		$chk_param['require'][] = array("field"=>"comp_email", "msg"=>"이메일");

	//중복검사
		//$chk_param['unique'][] = array("table"=>"company_info", "field"=>"comp_num", "where"=>"del_yn = 'N'", "msg"=>"이미 사용된 사업자등록번호입니다.");

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param = $_POST['param'];

		$command    = "insert"; //명령어
		$table      = "company_info"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");

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

		$str = '{"success_chk" : "Y", "error_string" : ""}';
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

	// 파일센터를 사용할 경우
		$part_where = " and part.comp_idx = '" . $comp_idx . "'";
		$part_list = company_part_data('list', $part_where, '', '', '');
		foreach ($part_list as $part_k => $part_data)
		{
			if (is_array($part_data))
			{
				$chk_part = $part_data['part_idx'];

			// Project
				$chk_where = " and fi.part_idx = '" . $chk_part . "' and fi.dir_file = 'folder' and fi.dir_depth = '1' and fi.file_name = 'Project'";
				$chk_data = filecenter_info_data('view', $chk_where);
				if ($chk_data['total_num'] == 0)
				{
					$vc_query  = "insert into filecenter_info (comp_idx, part_idx, dir_file, dir_depth, file_name, file_sname, set_type, reg_id, reg_date) values";
					$vc_query .= " ('" . $comp_idx . "', '" . $chk_part . "', 'folder', '1', 'Project', 'Project', 'fix', 'system', '" . time() . "')";
					db_query($vc_query);
					query_history($vc_query, 'filecenter_info', 'insert');
				}

			// V-Drive
				$chk_where1 = " and fi.part_idx = '" . $chk_part . "' and fi.dir_file = 'folder' and fi.dir_depth = '1' and fi.file_name = 'V-Drive'";
				$chk_data1 = filecenter_info_data('view', $chk_where1);
				if ($chk_data1['total_num'] == 0)
				{
					$fi_data1 = query_view("select max(fi_idx) as fi_idx from filecenter_info");
					$fi_idx1 = ($fi_data1['fi_idx'] == '') ? '1' : $fi_data1['fi_idx'] + 1;

					$vc_query  = "insert into filecenter_info (fi_idx, comp_idx, part_idx, dir_file, dir_depth, file_name, file_sname, set_type, reg_id, reg_date) values";
					$vc_query .= " ('" . $fi_idx1 . "','" . $comp_idx . "', '" . $chk_part . "', 'folder', '1', 'V-Drive', 'V-Drive', 'nofix', 'system', '" . time() . "')";
					db_query($vc_query);
					query_history($vc_query, 'filecenter_info', 'insert');
				}
				else
				{
					$fi_idx1 = $chk_data1['fi_idx'];
				}

			// V-Drive/Member
				$chk_where2 = " and fi.part_idx = '" . $chk_part . "' and fi.dir_file = 'folder' and fi.dir_depth = '2' and fi.file_name = 'Member' and fi.file_path = '/V-Drive'";
				$chk_data2 = filecenter_info_data('view', $chk_where2);
				if ($chk_data2['total_num'] == 0)
				{
					$fi_data2 = query_view("select max(fi_idx) as fi_idx from filecenter_info");
					$fi_idx2 = ($fi_data2['fi_idx'] == '') ? '1' : $fi_data2['fi_idx'] + 1;

					$vcm_query  = "insert into filecenter_info (fi_idx, comp_idx, part_idx, dir_file, dir_depth, up_fi_idx, file_path, file_rpath, file_name, file_sname, set_type, reg_id, reg_date) values";
					$vcm_query .= " ('" . $fi_idx2 . "', '" . $comp_idx . "', '" . $chk_part . "', 'folder', '2', '" . $fi_idx1 . "', '/V-Drive', '/V-Drive', 'Member', 'Member', 'fix', 'system', '" . time() . "')";
					db_query($vcm_query);
					query_history($vcm_query, 'filecenter_info', 'insert');
				}
				else
				{
					$fi_idx2 = $chk_data2['fi_idx'];
				}

			// 직원별로 폴더생성할것, 해당직원 권한도 같이 설정
				$up_fi_idx3 = $fi_idx1 . ',' . $fi_idx2;

				$mem_where = " and mem.part_idx = '" . $chk_part . "'";
				$mem_list = member_info_data('list', $mem_where, '', '', '');
				foreach ($mem_list as $mem_k => $mem_data)
				{
					if (is_array($mem_data))
					{
						$staff_idx  = $mem_data['mem_idx'];
						$staff_name = $mem_data['mem_name'];

					// V-Drive/Member/staff_name
						$fi_data3 = query_view("select max(fi_idx) as fi_idx from filecenter_info");
						$fi_idx3 = ($fi_data3['fi_idx'] == '') ? '1' : $fi_data3['fi_idx'] + 1;

						$chk_mem_where = " and fi.part_idx = '" . $chk_part . "' and fi.dir_file = 'folder' and fi.dir_depth = '3' and fi.file_sname = '" . $staff_idx . "' and fi.file_rpath = '/V-Drive/Member'";
						$chk_mem_data = filecenter_info_data('view', $chk_mem_where);
						if ($chk_mem_data['total_num'] == 0)
						{
							$vcmem_query  = "insert into filecenter_info (fi_idx, comp_idx, part_idx, dir_file, dir_depth, up_fi_idx, file_path, file_rpath, file_name, file_sname, set_type, reg_id, reg_date) values";
							$vcmem_query .= " ('" . $fi_idx3 . "', '" . $comp_idx . "', '" . $chk_part . "', 'folder', '3', '" . $up_fi_idx3 . "', '/V-Drive/Member', '/V-Drive/Member', '" . $staff_name . "', '" . $staff_idx . "', 'fix', 'system', '" . time() . "')";
							db_query($vcmem_query);
							query_history($vcmem_query, 'filecenter_info', 'insert');

							$vcauth_query  = "insert into filecenter_auth (comp_idx, part_idx, fi_idx, mem_idx, dir_view, dir_read, dir_write, reg_id, reg_date) values";
							$vcauth_query .= " ('" . $comp_idx . "', '" . $chk_part . "', '" . $fi_idx2 . "', '" . $staff_idx . "', '1', '1', '0', 'system', '" . time() . "')";
							db_query($vcauth_query);
							query_history($vcauth_query, 'filecenter_auth', 'insert');

							$vcauth_query  = "insert into filecenter_auth (comp_idx, part_idx, fi_idx, mem_idx, dir_view, dir_read, dir_write, reg_id, reg_date) values";
							$vcauth_query .= " ('" . $comp_idx . "', '" . $chk_part . "', '" . $fi_idx3 . "', '" . $staff_idx . "', '1', '1', '1', 'system', '" . time() . "')";
							db_query($vcauth_query);
							query_history($vcauth_query, 'filecenter_auth', 'insert');
						}
					}
				}
			}
		}

		$str = '{"success_chk" : "Y", "error_string" : ""}';
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

				// 값세팅해줄것
					$comp_where = " and cs.comp_idx = '" . $comp_idx . "'";
					$comp_set_data = company_setting_data("view", $comp_where);

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
							foreach ($class_menu_list as $menu_k => $menu_data)
							{
								if (is_array($menu_data))
								{
									if ($menu_data['default_yn'] == 'Y') $default_yn = 'Y';
									else $default_yn = 'N';

									if ($menu_k == 0)
									{
										$query1 = "INSERT INTO menu_auth_company (comp_idx, mi_idx, view_yn, default_yn, reg_id, reg_date) VALUES ";
									}
									else $query1 .= ",";
									$query1 .= "('" . $comp_idx . "', '" . $menu_data['mi_idx'] . "', 'Y', '" . $default_yn . "', 'system', '" . $reg_date . "')";
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
						foreach ($menu_list as $menu_k => $menu_data)
						{
							if (is_array($menu_data))
							{
								if ($menu_data['default_yn'] == 'Y') $default_yn = 'Y';
								else $default_yn = 'N';

								if ($menu_k == 0)
								{
									$query1 = "INSERT INTO menu_auth_company (comp_idx, mi_idx, view_yn, default_yn, reg_id, reg_date) VALUES ";
								}
								else $query1 .= ",";
								$query1 .= "('" . $comp_idx . "', '" . $menu_data['mi_idx'] . "', 'Y', '" . $default_yn . "', 'system', '" . $reg_date . "')";
							}
						}
						if ($query1 != '')
						{
							db_query($query1);
							query_history($query1, 'menu_auth_company', 'insert');
						}
					}

				// 지사등록 - 1개 임의 셋팅
					$sub_data = query_view("select max(part_idx) as part_idx from company_part");
					$part_idx = ($sub_data["part_idx"] == "") ? "1" : $sub_data["part_idx"] + 1;

					$query3  = "insert into company_part (comp_idx, part_idx, part_name, view_yn, default_yn, sort, set_yn, reg_id, reg_date) values";
					$query3 .= "('" . $comp_idx . "', '" . $part_idx . "', '" . $company_name . "', 'Y', 'Y', '1', 'Y', 'system', '" . $reg_date . "')";
					db_query($query3);
					query_history($query3, 'company_part', 'insert');

				// 관리자메뉴권한
					$auth_where = " and mac.comp_idx = '" . $comp_idx . "' and mac.view_yn = 'Y'";
					$auth_list = menu_auth_company_data('list', $auth_where, '', '', '');
					foreach ($auth_list as $auth_k => $auth_data)
					{
						if (is_array($auth_data))
						{
							if ($auth_k == 0)
							{
								$query2 = "
									INSERT INTO menu_auth_member (comp_idx, part_idx, mem_idx, mi_idx, reg_id, reg_date, yn_list, yn_int, yn_mod, yn_del, yn_view, yn_print, yn_down) VALUES ";
							}
							else $query2 .= ",";
							$query2 .= "('" . $comp_idx . "', '" . $part_idx . "', '" . $mem_data['mem_idx'] . "', '" . $auth_data['mi_idx'] . "', 'system', '" . $reg_date . "'
							, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y')";
						}
					}
					db_query($query2);
					query_history($query2, 'menu_auth_member', 'insert');

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
					$total_client = $comp_set_data['client_cnt'];
					for ($i = 1; $i <= $total_client; $i++)
					{
						$client_code = $comp_idx . str_pad($i, 6, 0, STR_PAD_LEFT);
						if ($i == 1)
						{
							$query7 = "
								INSERT INTO client_code_info (comp_idx, part_idx, ci_idx, client_code, reg_id, reg_date) VALUES ";
						}
						else $query7 .= ",";
						$query7 .= "('" . $comp_idx . "', '0', '0', '" . $client_code . "', 'system', '" . $reg_date . "')";
					}
					db_query($query7);
					query_history($query7, 'client_code_info', 'insert');

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

					$file_path = $comp_path . '/' . $comp_idx . '/board_project'; // 프로젝트게시판
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