<?
/*
	수정 : 2013.04.18
	위치 : 설정폴더 > 직원관리 > 직원등록/수정 - 실행
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
		$chk_param['require'][] = array("field"=>"part_idx", "msg"=>"지사");
		$chk_param['require'][] = array("field"=>"mem_name", "msg"=>"이름");
		$chk_param['require'][] = array("field"=>"mem_email", "msg"=>"이메일");
		$chk_param['require'][] = array("field"=>"hp_num", "msg"=>"핸드폰번호");

	//중복검사
		if ($sub_type == 'post')
		{
			$chk_param['unique'][] = array("table"=>"member_info", "field"=>"mem_email", "where"=>"del_yn = 'N'", "msg"=>"이미 사용된 이메일주소입니다.");
		}

	//체크합니다.
		param_check($param, $chk_param, $chk_type);
	}

// 등록 함수
	function post()
	{
		global $_POST, $_SESSION, $sess_str;

		$param     = $_POST['param'];
		$comp_idx  = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx  = $param['part_idx'];

		$command    = "insert"; //명령어
		$table      = "member_info"; //테이블명
		$conditions = ""; //조건

		$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['reg_date'] = date("Y-m-d H:i:s");
		$param['comp_idx'] = $comp_idx;

		if ($param['login_yn'] == '') $param['login_yn'] = 'Y';
		if ($param['ubstory_yn'] == '') $param['ubstory_yn'] = 'N';
		if ($param['mem_pwd'] == '') $param['mem_pwd'] = $param['hp_num3'];

		$param['mem_email'] = $param['mem_email1'] . '@' . $param['mem_email2'];
		$param['mem_id']    = strtolower($param['mem_email']);
		$param['zip_code']  = $param['zip_code1'] . '-' . $param['zip_code2'];
		$param['address']   = $param['address1'] . '||' . $param['address2'];
		$param['tel_num']   = $param['tel_num1'] . '-' . $param['tel_num2'] . '-' . $param['tel_num3'];
		$param['hp_num']    = $param['hp_num1'] . '-' . $param['hp_num2'] . '-' . $param['hp_num3'];
		$param['mem_pwd']   = pass_change($param['mem_pwd'], $sess_str);

		$sub_data = query_view("select max(mem_idx) as mem_idx from " . $table);
		$param["mem_idx"] = ($sub_data["mem_idx"] == "") ? "1" : $sub_data["mem_idx"] + 1;
        $mem_idx = $param["mem_idx"];

		if ($param['ubstory_yn'] == 'Y') // 관리자일 경우
		{
			$param['ubstory_level'] = '21';
		}
		else
		{
			$param['ubstory_level'] = '91';
		}

		unset($param['mem_email1']);
		unset($param['mem_email2']);
		unset($param['zip_code1']);
		unset($param['zip_code2']);
		unset($param['address1']);
		unset($param['address2']);
		unset($param['tel_num1']);
		unset($param['tel_num2']);
		unset($param['tel_num3']);
		unset($param['hp_num1']);
		unset($param['hp_num2']);
		unset($param['hp_num3']);

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // 파일저장 - 사진정보
        global $tmp_path, $comp_member_path;
        
        $data_path = $comp_member_path . '/' . $mem_idx;
        files_dir($data_path);

        $file_command    = "insert"; //명령어
        $file_table      = "member_file"; //테이블명
        $file_conditions = ""; //조건

        $reg_id   = $param['mod_id'];
        $reg_date = $param['mod_date'];

    // 총 저장 파일
        $i = 1;
        $upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $mem_idx, 'member');

        if ($upfile_data[$i]['f_name'] != '')
        {
            $chk_file_name = $upfile_data[$i]['f_name'];
            $new_file_name = $upfile_data[$i]['s_name'];
            $chk_file_size = $upfile_data[$i]['f_size'];
            $chk_file_type = $upfile_data[$i]['f_type'];
            $chk_file_ext  = $upfile_data[$i]['f_ext'];

        // 데이타 확인
            $file_where = " and mf.mem_idx = '" . $mem_idx . "' and mf.sort ='" . $i . "'";
            $file_data = member_file_data('view', $file_where);

            if ($file_data['total_num'] == 0)
            {
                $query_str = "insert into " . $file_table . " set
                          comp_idx  = '" . string_input($comp_idx) . "'
                        , part_idx  = '" . string_input($part_idx) . "'
                        , mem_idx   = '" . string_input($mem_idx) . "'
                        , sort      = '" . string_input($i) . "'
                        , subject   = '" . $mem_data['mem_name'] . " 사진'
                        , img_fname = '" . string_input($chk_file_name) . "'
                        , img_sname = '" . string_input($new_file_name) . "'
                        , img_size  = '" . string_input($chk_file_size) . "'
                        , img_type  = '" . string_input($chk_file_type) . "'
                        , img_ext   = '" . string_input($chk_file_ext) . "'
                        , reg_id    = '" . string_input($reg_id) . "'
                        , reg_date  = '" . string_input($reg_date) . "'
                ";
                db_query($query_str);
                query_history($query_str, $file_table, $file_command);
            }
            else
            {
                $query_update = "update " . $file_table . " set
                        img_fname = '" . string_input($chk_file_name) . "',
                        img_sname = '" . string_input($new_file_name) . "',
                        img_size  = '" . string_input($chk_file_size) . "',
                        img_type  = '" . string_input($chk_file_type) . "',
                        img_ext   = '" . string_input($chk_file_ext) . "',
                        mod_id    = '" . string_input($reg_id) . "',
                        mod_date  = '" . string_input($reg_date) . "'
                    where
                        del_yn = 'N' and mem_idx = '" . $mem_idx . "' and sort ='" . $i . "'";
                db_query($query_update);
                query_history($query_update, $file_table, 'update');
            }
        }

    // 그외 저장 파일
        $file_num = $_POST['file_upload_num'];
        $query_str = '';
        $chk_num = 1;
        for ($i = 2; $i <= $file_num; $i++)
        {
            $chk_file_subject = $_POST['file_subject' . $i];
            $chk_file_save    = $_POST['file_fname' . $i . '_save_name'];
            if ($i == 1) $chk_file_subject = $mem_data['mem_name'] . ' 사진';

            if ($chk_file_save != '')
            {
                $chk_file_name = $_POST['file_fname' . $i . '_file_name'];
                $chk_file_size = $_POST['file_fname' . $i . '_file_size'];
                $chk_file_size = str_replace(',', '', $chk_file_size);
                $chk_file_type = $_POST['file_fname' . $i . '_file_type'];
                $chk_file_ext  = $_POST['file_fname' . $i . '_file_ext'];

                $chk_file_size = str_replace(',', '', $chk_file_size);
                $new_file_name = $mem_idx . '_' . $i . '_' . time() . '.' . $chk_file_ext;

                $old_file = $tmp_path . '/' . $chk_file_save;
                $new_file = $data_path . '/' . $new_file_name;

                if (file_exists($old_file))
                {
                    if(!copy($old_file, $new_file))
                    {
                        $str = '{"success_chk" : "N", "error_string" : "저장시 오류가 생겼습니다. <br />다시 확인하고 파일을 올리세요."}';
                        exit;
                    }

                // 데이타가 있는 확인 있을 경우 업데이트를 한다.
                    $file_where = " and mf.comp_idx = '" . $comp_idx . "' and mf.mem_idx = '" . $mem_idx . "' and mf.sort ='" . $i . "'";
                    $file_data = member_file_data('view', $file_where);
                    if ($file_data['total_num'] == 0)
                    {
                        if ($chk_num == 1)
                        {
                            $query_str = "
                                INSERT INTO " . $file_table . " (comp_idx, part_idx, mem_idx, sort, subject, img_sname, img_fname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
                        }
                        else $query_str .= ",";

                        $query_str .= "('" . $comp_idx . "', '" . $part_idx . "', '" . $mem_idx . "', '" . $i . "', '" . $chk_file_subject . "', '" . $new_file_name . "', '" . $chk_file_name . "', '" . $chk_file_size . "', '" . $chk_file_type . "', '" . $chk_file_ext . "', '" . $reg_id . "', '" . $reg_date . "')";

                        $chk_num++;
                    }
                    else
                    {
                    // 기존등록된 파일삭제
                        $delete_file = $data_path . '/' . $file_data['img_sname'];
                        unlink($delete_file);

                        $query_update = "update " . $file_table . " set
                                subject   = '" . $chk_file_subject . "',
                                img_sname = '" . $new_file_name . "',
                                img_fname = '" . $chk_file_name . "',
                                img_size  = '" . $chk_file_size . "',
                                img_type  = '" . $chk_file_type . "',
                                img_ext   = '" . $chk_file_ext . "',
                                mod_id    = '" . $reg_id . "',
                                mod_date  = '" . $reg_date . "'
                            where
                                del_yn = 'N' and comp_idx = '" . $comp_idx . "' and mem_idx = '" . $mem_idx . "' and sort ='" . $i . "';";
                        db_query($query_update);
                    }
                    unlink($old_file);
                }
            }
            else
            {
                $query_update = "update " . $file_table . " set
                        subject   = '" . $chk_file_subject . "',
                        mod_id    = '" . $reg_id . "',
                        mod_date  = '" . $reg_date . "'
                    where
                        del_yn = 'N' and comp_idx = '" . $comp_idx . "' and mem_idx = '" . $mem_idx . "' and sort ='" . $i . "';";
                db_query($query_update);
            }
        }
        if ($query_str != '')
        {
            db_query($query_str);
            query_history($query_str, $file_table, $file_command);
        }

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 해당메뉴권한설정
		//$auth_where = " and mac.comp_idx = '" . $comp_idx . "' and mac.view_yn = 'Y' and mac.default_yn = 'Y'";
        $auth_where = " and mc.comp_idx = '" . $comp_idx . "' and mc.part_idx = '" . $part_idx . "' and mac.comp_idx = '" . $comp_idx . "' ";
		$auth_list = menu_auth_company_default('list', $auth_where, '', '', '');
		$auth_num = 1;
		foreach ($auth_list as $auth_k => $auth_data)
		{
			if (is_array($auth_data))
			{
				//$sub_where = " and mc.comp_idx = '" . $comp_idx . "' and mc.part_idx = '" . $part_idx . "' and mc.mi_idx = '" . $auth_data['mi_idx'] . "' and mc.default_yn = 'Y'";
				//$sub_data = menu_company_data('view', $sub_where);

				//if ($sub_data['total_num'] > 0)
				//{
					if ($auth_num == 1) $menu_query = "INSERT INTO menu_auth_member (comp_idx, part_idx, mem_idx, mi_idx, reg_id, reg_date, yn_list, yn_int, yn_mod, yn_del, yn_view, yn_print, yn_down) VALUES ";
					else $menu_query .= ",";
					$menu_query .= "('" . $comp_idx . "', '" . $part_idx . "', '" . $param['mem_idx'] . "', '" . $auth_data['mi_idx'] . "', 'system', '" . $param['reg_date'] . "', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y')";
					$auth_num++;
				//}
			}
		}
		if ($menu_query != '')
		{
			query_history($menu_query, 'menu_auth_member', 'insert');
			db_query($menu_query);
		}

		$str = '{"success_chk" : "Y", "error_string" : "", "mem_idx": "' . $param['mem_idx'] . '"}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param     = $_POST['param'];
		$comp_idx  = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx  = $param['part_idx'];
		$mem_idx   = $_POST['mem_idx'];
		$chk_level = $_POST['ubstory_level'];

		$command    = "update"; //명령어
		$table      = "member_info"; //테이블명
		$conditions = "mem_idx = '" . $mem_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($param['login_yn'] == '') $param['login_yn'] = 'Y';
		if ($param['ubstory_yn'] == '') $param['ubstory_yn'] = 'N';
		if ($param['mem_pwd'] == '') unset($param['mem_pwd']);
		else $param['mem_pwd'] = pass_change($param['mem_pwd'], $sess_str);

		$param['mem_email'] = $param['mem_email1'] . '@' . $param['mem_email2'];
		$param['zip_code']  = $param['zip_code1'] . '-' . $param['zip_code2'];
		$param['address']   = $param['address1'] . '||' . $param['address2'];
		$param['tel_num']   = $param['tel_num1'] . '-' . $param['tel_num2'] . '-' . $param['tel_num3'];
		$param['hp_num']    = $param['hp_num1'] . '-' . $param['hp_num2'] . '-' . $param['hp_num3'];

		if ($chk_level > 11)
		{
			if ($param['ubstory_yn'] == 'Y') // 관리자일 경우
			{
				$param['ubstory_level'] = '21';
			}
			else
			{
				$param['ubstory_level'] = '91';
			}
		}

		unset($param['mem_email1']);
		unset($param['mem_email2']);
		unset($param['zip_code1']);
		unset($param['zip_code2']);
		unset($param['address1']);
		unset($param['address2']);
		unset($param['tel_num1']);
		unset($param['tel_num2']);
		unset($param['tel_num3']);
		unset($param['hp_num1']);
		unset($param['hp_num2']);
		unset($param['hp_num3']);

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일저장 - 사진정보
		global $tmp_path, $comp_member_path;

		$data_path = $comp_member_path . '/' . $mem_idx;
		files_dir($data_path);

		$file_command    = "insert"; //명령어
		$file_table      = "member_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['mod_id'];
		$reg_date = $param['mod_date'];

	// 총 저장 파일
		$i = 1;
		$upfile_data = upload_file_save($i, $tmp_path, $data_path, $_POST, $mem_idx, 'member');

		if ($upfile_data[$i]['f_name'] != '')
		{
			$chk_file_name = $upfile_data[$i]['f_name'];
			$new_file_name = $upfile_data[$i]['s_name'];
			$chk_file_size = $upfile_data[$i]['f_size'];
			$chk_file_type = $upfile_data[$i]['f_type'];
			$chk_file_ext  = $upfile_data[$i]['f_ext'];

		// 데이타 확인
			$file_where = " and mf.mem_idx = '" . $mem_idx . "' and mf.sort ='" . $i . "'";
			$file_data = member_file_data('view', $file_where);

			if ($file_data['total_num'] == 0)
			{
				$query_str = "insert into " . $file_table . " set
						  comp_idx  = '" . string_input($comp_idx) . "'
						, part_idx  = '" . string_input($part_idx) . "'
						, mem_idx   = '" . string_input($mem_idx) . "'
						, sort      = '" . string_input($i) . "'
						, subject   = '" . $mem_data['mem_name'] . " 사진'
						, img_fname = '" . string_input($chk_file_name) . "'
						, img_sname = '" . string_input($new_file_name) . "'
						, img_size  = '" . string_input($chk_file_size) . "'
						, img_type  = '" . string_input($chk_file_type) . "'
						, img_ext   = '" . string_input($chk_file_ext) . "'
						, reg_id    = '" . string_input($reg_id) . "'
						, reg_date  = '" . string_input($reg_date) . "'
				";
				db_query($query_str);
				query_history($query_str, $file_table, $file_command);
			}
			else
			{
				$query_update = "update " . $file_table . " set
						img_fname = '" . string_input($chk_file_name) . "',
						img_sname = '" . string_input($new_file_name) . "',
						img_size  = '" . string_input($chk_file_size) . "',
						img_type  = '" . string_input($chk_file_type) . "',
						img_ext   = '" . string_input($chk_file_ext) . "',
						mod_id    = '" . string_input($reg_id) . "',
						mod_date  = '" . string_input($reg_date) . "'
					where
						del_yn = 'N' and mem_idx = '" . $mem_idx . "' and sort ='" . $i . "'";
				db_query($query_update);
				query_history($query_update, $file_table, 'update');
			}
		}

	// 그외 저장 파일
		$file_num = $_POST['upload_fnum'];
		$query_str = '';
		$chk_num = 1;
		for ($i = 2; $i <= $file_num; $i++)
		{
			$chk_file_subject = $_POST['file_subject' . $i];
			$chk_file_save    = $_POST['file_fname' . $i . '_save_name'];
			if ($i == 1) $chk_file_subject = $mem_data['mem_name'] . ' 사진';

			if ($chk_file_save != '')
			{
				$chk_file_name = $_POST['file_fname' . $i . '_file_name'];
				$chk_file_size = $_POST['file_fname' . $i . '_file_size'];
				$chk_file_size = str_replace(',', '', $chk_file_size);
				$chk_file_type = $_POST['file_fname' . $i . '_file_type'];
				$chk_file_ext  = $_POST['file_fname' . $i . '_file_ext'];

				$chk_file_size = str_replace(',', '', $chk_file_size);
				$new_file_name = $mem_idx . '_' . $i . '_' . time() . '.' . $chk_file_ext;

				$old_file = $tmp_path . '/' . $chk_file_save;
				$new_file = $data_path . '/' . $new_file_name;

				if (file_exists($old_file))
				{
					if(!copy($old_file, $new_file))
					{
						$str = '{"success_chk" : "N", "error_string" : "저장시 오류가 생겼습니다. <br />다시 확인하고 파일을 올리세요."}';
						exit;
					}

				// 데이타가 있는 확인 있을 경우 업데이트를 한다.
					$file_where = " and mf.comp_idx = '" . $comp_idx . "' and mf.mem_idx = '" . $mem_idx . "' and mf.sort ='" . $i . "'";
					$file_data = member_file_data('view', $file_where);
					if ($file_data['total_num'] == 0)
					{
						if ($chk_num == 1)
						{
							$query_str = "
								INSERT INTO " . $file_table . " (comp_idx, part_idx, mem_idx, sort, subject, img_sname, img_fname, img_size, img_type, img_ext, reg_id, reg_date) VALUES ";
						}
						else $query_str .= ",";

						$query_str .= "('" . $comp_idx . "', '" . $part_idx . "', '" . $mem_idx . "', '" . $i . "', '" . $chk_file_subject . "', '" . $new_file_name . "', '" . $chk_file_name . "', '" . $chk_file_size . "', '" . $chk_file_type . "', '" . $chk_file_ext . "', '" . $reg_id . "', '" . $reg_date . "')";

						$chk_num++;
					}
					else
					{
					// 기존등록된 파일삭제
						$delete_file = $data_path . '/' . $file_data['img_sname'];
						unlink($delete_file);

						$query_update = "update " . $file_table . " set
								subject   = '" . $chk_file_subject . "',
								img_sname = '" . $new_file_name . "',
								img_fname = '" . $chk_file_name . "',
								img_size  = '" . $chk_file_size . "',
								img_type  = '" . $chk_file_type . "',
								img_ext   = '" . $chk_file_ext . "',
								mod_id    = '" . $reg_id . "',
								mod_date  = '" . $reg_date . "'
							where
								del_yn = 'N' and comp_idx = '" . $comp_idx . "' and mem_idx = '" . $mem_idx . "' and sort ='" . $i . "';";
						db_query($query_update);
					}
					unlink($old_file);
				}
			}
			else
			{
				$query_update = "update " . $file_table . " set
						subject   = '" . $chk_file_subject . "',
						mod_id    = '" . $reg_id . "',
						mod_date  = '" . $reg_date . "'
					where
						del_yn = 'N' and comp_idx = '" . $comp_idx . "' and mem_idx = '" . $mem_idx . "' and sort ='" . $i . "';";
				db_query($query_update);
			}
		}
		if ($query_str != '')
		{
			db_query($query_str);
			query_history($query_str, $file_table, $file_command);
		}

		$str = '{"success_chk" : "Y", "error_string" : "", "mem_idx" : "' . $mem_idx . '"}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;

		$mem_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "member_info"; //테이블명
		$conditions = "mem_idx = '" . $mem_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	// 업무자동 처리 - 완료안된것들
		$chk_mem  = $_SESSION[$sess_str . '_mem_idx'];
		$chk_date = date("Y-m-d H:i:s");

		$total_query = "";
		$work_where = " and wi.work_status != 'WS90' and wi.work_status != 'WS99' and (concat(',', wi.charge_idx, ',') like '%," . $mem_idx . ",%' or wi.apply_idx = '" . $mem_idx . "' or wi.reg_id = '" . $mem_idx . "')";
		$work_list = work_info_data('list', $work_where, '', '', '');
		foreach ($work_list as $work_k => $work_data)
		{
			if (is_array($work_data))
			{
				$wi_idx    = $work_data['wi_idx'];
				$comp_idx  = $work_data['comp_idx'];
				$part_idx  = $work_data['part_idx'];
				$work_type = $work_data['work_type'];

			// 본인
				if ($work_type == 'WT01')
				{
					$total_query .= "
						update work_info set
							  work_status = 'WS90'
							, end_date    = '" . $chk_date . "'
							, mod_id      = '" . $chk_mem . "'
							, mod_date    = '" . $chk_date . "'
						where
							wi_idx = '" . $wi_idx . "';
					";

					$total_query .= "insert into work_status_history (comp_idx, part_idx, mem_idx, wi_idx, status, status_date, status_memo, reg_id, reg_date) values";
					$total_query .= " ('" . $comp_idx . "', '" . $part_idx . "', '" . $chk_mem . "', '" . $wi_idx . "', 'WS90', '" . $chk_date . "', '업무가 완료되었습니다.', '" . $chk_mem . "', '" . $chk_date . "');";
				}
			// 요청
				else if ($work_type == 'WT02')
				{

				}
			// 승인
				else if ($work_type == 'WT03')
				{

				}
			// 알림
				else if ($work_type == 'WT04')
				{

				}
			}
		}
		if ($total_query != '')
		{
			//db_query($total_query);
			//query_history($total_query, 'work_info work_status_history', 'update insert');
		}

		$str = '{"success_chk" : "Y", "error_string" : "1. 직원을 삭제하시면 \"퇴사직원\" 메뉴로 이동되며 그동안 진행중인 업무는 자동으로 완료 처리가 됩니다.<br />2. 유지보수 담당자일 경우에는 \"해당사항\" 없음으로 처리됩니다."}';
		echo $str;
		exit;
	}

// yn 함수
	function check_yn()
	{
		global $_POST, $_SESSION, $sess_str;

		$sub_action = $_POST['sub_action'];
		$post_value = $_POST['post_value'];
		$mem_idx    = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "member_info"; //테이블명
		$conditions = "mem_idx = '" . $mem_idx . "'"; //조건

		$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($post_value == "Y") $param[$sub_action] = "N";
		else $param[$sub_action] = "Y";

		if ($sub_action == 'ubstory_yn') // 관리자일 경우
		{
			$mem_where = " and mem.mem_idx = '" . $mem_idx . "'";
			$mem_data = member_info_data("view", $mem_where);
			if ($mem_data['ubstory_level'] == '11') $param['ubstory_level'] = '11';
			else
			{
				if ($param['ubstory_yn'] == 'Y') $param['ubstory_level'] = '21';
				else $param['ubstory_level'] = '91';
			}
		}

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 이메일중복확인 함수
	function double_email()
	{
		global $_POST;

		$mem_id = $_POST['mem_email'];
		$mem_id = string_input($mem_id);

		if ($mem_id == '')
		{
			$str = '{"success_chk" : "N", "error_string"  : "이메일주소를 입력하세요."}';
		}
		else
		{
			$mem_where = " and mem.mem_id = '" . $mem_id . "'";
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

// 메뉴권한 함수
	function auth_menu()
	{
		global $_POST, $_SESSION, $sess_str;

		$comp_idx   = $_POST['comp_idx'];
		$part_idx   = $_POST['part_idx'];
		$mem_idx    = $_POST['mem_idx'];
		$mi_idx     = $_POST['idx'];
		$post_value = $_POST['post_value'];
		$sub_action = $_POST['sub_action'];

	// 데이타 확인
		$where = " and mam.comp_idx = '" . $comp_idx . "' and mam.mem_idx = '" . $mem_idx . "' and mam.mi_idx = '" . $mi_idx . "'";
		$data = menu_auth_member_data('page', $where);

	// 데이타가 없을 경우 등록
		if ($data['total_num'] == 0)
		{
			$command    = "insert"; //명령어
			$table      = "menu_auth_member"; //테이블명
			$conditions = ""; //조건

			$param['reg_id']   = $_SESSION[$sess_str . '_mem_idx'];
			$param['reg_date'] = date("Y-m-d H:i:s");
            
            switch ($sub_action) {
                case 'yn_list':
                    $param['yn_list'] = "Y";
                    $param['yn_view'] = "N";
                    $param['yn_int'] = "N";
                    $param['yn_mod'] = "N";
                    $param['yn_del'] = "N";
                    $param['yn_print'] = "N";
                    $param['yn_down'] = "N";
                    break;
                case 'yn_view':
        			$param['yn_list'] = "Y";
        			$param['yn_view'] = "Y";
                    $param['yn_int'] = "N";
                    $param['yn_mod'] = "N";
                    $param['yn_del'] = "N";
                    $param['yn_print'] = "N";
                    $param['yn_down'] = "N";
                    break;
                case 'yn_int':
                    $param['yn_list'] = "Y";
                    $param['yn_view'] = "Y";
                    $param['yn_int'] = "Y";
                    $param['yn_mod'] = "N";
                    $param['yn_del'] = "N";
                    $param['yn_print'] = "N";
                    $param['yn_down'] = "N";
                    break;
                case 'yn_mod':
                    $param['yn_list'] = "Y";
                    $param['yn_view'] = "Y";
                    $param['yn_int'] = "Y";
                    $param['yn_mod'] = "Y";
                    $param['yn_del'] = "N";
                    $param['yn_print'] = "N";
                    $param['yn_down'] = "N";
                    break;
                case 'yn_del':
                    $param['yn_list'] = "Y";
                    $param['yn_view'] = "Y";
                    $param['yn_int'] = "Y";
                    $param['yn_mod'] = "Y";
                    $param['yn_del'] = "Y";
                    $param['yn_print'] = "N";
                    $param['yn_down'] = "N";
                    break;
                case 'yn_print':
                    $param['yn_list'] = "Y";
                    $param['yn_view'] = "Y";
                    $param['yn_int'] = "Y";
                    $param['yn_mod'] = "Y";
                    $param['yn_del'] = "Y";
                    $param['yn_print'] = "Y";
                    $param['yn_down'] = "N";
                    break;
                case 'yn_down':
                    $param['yn_list'] = "Y";
                    $param['yn_view'] = "Y";
                    $param['yn_int'] = "Y";
                    $param['yn_mod'] = "Y";
                    $param['yn_del'] = "Y";
                    $param['yn_print'] = "Y";
                    $param['yn_down'] = "Y";
                    break;
            } 

			$param['comp_idx'] = $comp_idx;
			$param['part_idx'] = $part_idx;
			$param['mem_idx']  = $mem_idx;
			$param['mi_idx']   = $mi_idx;
		}
		else
		{
			$command    = "update"; //명령어
			$table      = "menu_auth_member"; //테이블명
			$conditions = "comp_idx = '" . $comp_idx . "' and mem_idx = '" . $mem_idx . "' and mi_idx = '" . $mi_idx . "'"; //조건

			if ($post_value == 'Y') $post_value = 'N';
            else $post_value = 'Y';
            
            switch ($sub_action) {
                case 'yn_list':
                    if ($post_value == 'Y') {
                        $param['yn_list'] = "Y";
                    } else {
                        $param['yn_list'] = "N";
                        $param['yn_view'] = "N";
                        $param['yn_int'] = "N";
                        $param['yn_mod'] = "N";
                        $param['yn_del'] = "N";
                        $param['yn_print'] = "N";
                        $param['yn_down'] = "N";
                    }
                    break;
                case 'yn_view':
                    if ($post_value == 'Y') {
                        $param['yn_list'] = "Y";
                        $param['yn_view'] = "Y";
                    } else {
                        $param['yn_view'] = "N";
                        $param['yn_int'] = "N";
                        $param['yn_mod'] = "N";
                        $param['yn_del'] = "N";
                        $param['yn_print'] = "N";
                        $param['yn_down'] = "N";
                    }
                    break;
                case 'yn_int':
                    if ($post_value == 'Y') {
                        $param['yn_list'] = "Y";
                        $param['yn_view'] = "Y";
                        $param['yn_int'] = "Y";
                    } else {
                        $param['yn_int'] = "N";
                        $param['yn_mod'] = "N";
                        $param['yn_del'] = "N";
                    }
                    break;
                case 'yn_mod':
                    if ($post_value == 'Y') {
                        $param['yn_list'] = "Y";
                        $param['yn_view'] = "Y";
                        $param['yn_int'] = "Y";
                        $param['yn_mod'] = "Y";
                    } else {
                        $param['yn_mod'] = "N";
                        $param['yn_del'] = "N";
                    }
                    break;
                case 'yn_del':
                    if ($post_value == 'Y') {
                        $param['yn_list'] = "Y";
                        $param['yn_view'] = "Y";
                        $param['yn_int'] = "Y";
                        $param['yn_mod'] = "Y";
                        $param['yn_del'] = "Y";
                    } else {
                        $param['yn_del'] = "N";
                    }
                    break;
                case 'yn_print':
                    if ($post_value == 'Y') {
                        $param['yn_list'] = "Y";
                        $param['yn_view'] = "Y";
                        $param['yn_print'] = "Y";
                    } else {
                        $param['yn_print'] = "N";
                    }
                    break;
                case 'yn_down':
                    if ($post_value == 'Y') {
                        $param['yn_list'] = "Y";
                        $param['yn_view'] = "Y";
                        $param['yn_down'] = "Y";
                    } else {
                        $param['yn_down'] = "N";
                    }
                    break;
            }

			$param['mod_id']   = $_SESSION[$sess_str . '_mem_idx'];
			$param['mod_date'] = date("Y-m-d H:i:s");
			$param['part_idx'] = $part_idx;
		}

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "idx" : "' . $mem_idx . '"}';
		echo $str;
		exit;
	}

// 파일삭제 함수
	function file_delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address;
		global $comp_member_path;

		$mem_idx  = $_POST['org_idx'];
		$mf_idx   = $_POST['idx'];

		$file_where = " and mf.mf_idx = '" . $mf_idx . "'";
		$file_data = member_file_data('view', $file_where);

		$command    = "update"; //명령어
		$table      = "member_file"; //테이블명
		$conditions = "mf_idx = '" . $mf_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $_SESSION[$sess_str . '_mem_idx'];
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$delete_file = $comp_member_path . '/' . $mem_idx . '/' . $file_data['img_sname'];
		@unlink($delete_file);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}
?>