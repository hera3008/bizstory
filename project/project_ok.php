<?
/*
	생성 : 2012.12.20
	수정 : 2013.05.31
	위치 : 업무폴더 > 프로젝트관리 - 실행
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
		global $_SESSION, $sess_str, $sub_type;

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];

	//필수검사
		$chk_param['require'][] = array("field"=>"subject", "msg"=>"제목");
		$chk_param['require'][] = array("field"=>"start_date", "msg"=>"시작일");
		$chk_param['require'][] = array("field"=>"deadline_date", "msg"=>"종료일");
		$chk_param['require'][] = array("field"=>"apply_idx", "msg"=>"책임자");
		$chk_param['require'][] = array("field"=>"charge_idx", "msg"=>"담당자");
		$chk_param['require'][] = array("field"=>"remark", "msg"=>"내용");

		if ($sub_type == 'postform')
		{
		//중복검사
			$chk_param["unique"][] = array("table"=>"project_info", "field"=>"project_code", "where"=>"del_yn = 'N' and comp_idx = '" . $comp_idx . "'", "msg"=>"이미 사용된 프로젝트코드입니다.");
		}

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
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];

		$command    = "insert"; //명령어
		$table      = "project_info"; //테이블명
		$conditions = ""; //조건
		$param['menu1_code'] = $param['menu1'];
        $param['menu2_code'] = $param['menu2'];

		$param['reg_id']   = $mem_idx;
		$param['reg_date'] = date('Y-m-d H:i:s');
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;

        unset($param['menu1']);
        unset($param['menu2']);

		if ($param["open_yn"] == '') $param["open_yn"] = 'Y';

		$chk_data = query_view("select max(pro_idx) as pro_idx from " . $table);
		$param['pro_idx'] = ($chk_data['pro_idx'] == '') ? '1' : $chk_data['pro_idx'] + 1;

	// 기한, 담당자가 없을 경우 업무대기로 설정
		if ($param['deadline_date'] == '' || $param['charge_idx'] == '')
		{
			$param["pro_status"] = 'PS01'; // 업무대기
		}
		else
		{
			$param["pro_status"] = 'PS02'; // 업무진행
		}

	// 프로젝트코드
		if (trim($param['project_code']) == '')
		{
			$chk_data = query_page("select count(pro_idx) from " . $table . " where comp_idx = '" . $comp_idx ."' and date_format(start_date, '%Y') = '" . date('Y') . "'");
			$chk_total = $chk_data['total_num'] + 1;
			$chk_total = str_pad($chk_total, 3, '0', STR_PAD_LEFT);
			$param['project_code'] = 'P' . date('Y') . $chk_total;
		} else {
            $where = " and pro.menu1_code='" . $param['menu1_code'] . "' and pro.menu2_code='" . $param['menu2_code'] . "' and pro.project_code = '" .  trim($param['project_code']) . "' and pro.del_yn='N' ";
            $chk_data = project_info_data('view', $where);
            
            if ($chk_data['total_num'] > 0) {
                echo json_encode(array('success_chk'=>'N', 'error_string'=>'프로젝트코드가 중복됩니다.'));
                exit;
            }             
		}

	// 담당자 정리
		$chk_charge_idx = ',' . $param['charge_idx'] . ',';
		$chk_charge_arr = explode(',', $chk_charge_idx);
		sort($chk_charge_arr);
		$total_charge = '';
		foreach ($chk_charge_arr as $k => $v)
		{
			if ($v != '')
			{
				if ($old_charge != $v)
				{
					$total_charge .= ',' . $v;
				}
				$old_charge = $v;
			}
		}
		$total_charge = substr($total_charge, 1, strlen($total_charge)-1);
		$param['charge_idx'] = $total_charge;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장
		$hi_command    = "insert"; //명령어
		$hi_table      = "project_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $mem_idx;
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $mem_idx;
		$hi_param['pro_idx']     = $param['pro_idx'];
		$hi_param['status']      = $param["pro_status"];
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '[' . $param['subject'] . '] 프로젝트가 등록되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 알림건
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];
		charge_push_send($mem_idx, $param['charge_idx'], $param['apply_idx'], 'project', $param['subject'], '', '', '');

		$str = '{"success_chk" : "Y", "error_string" : "", "pro_idx": "' . $param['pro_idx'] . '"}';
		echo $str;
		exit;
	}

// 수정 함수
	function modify()
	{
		global $_POST, $_SESSION, $sess_str;

		$param      = $_POST['param'];
		$pro_idx    = $_POST['pro_idx'];
		$comp_idx   = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx   = $_SESSION[$sess_str . '_part_idx'];
		$mem_idx    = $_SESSION[$sess_str . '_mem_idx'];
		$old_deadline_date = $_POST['old_deadline_date'];
		$old_apply_idx     = $_POST['old_apply_idx'];
		$old_charge_idx    = $_POST['old_charge_idx'];
		$old_pro_status    = $_POST['old_pro_status'];

		$command    = "update"; //명령어
		$table      = "project_info"; //테이블명
		$conditions = "pro_idx = '" . $pro_idx . "'"; //조건
		
        //$param['menu1_code'] = $param['menu1'];
        //$param['menu2_code'] = $param['menu2'];
        unset($param['project_code']);
        unset($param['menu1']);
        unset($param['menu2']);
        
		$param['mod_id']   = $mem_idx;
		$param['mod_date'] = date("Y-m-d H:i:s");

		if ($param["open_yn"] == '') $param["open_yn"] = 'Y';

	// 기한, 담당자가 없을 경우 업무대기로 설정
		if ($param['deadline_date'] == '' || $param['charge_idx'] == '')
		{
			$param["pro_status"] = 'PS01'; // 업무대기
		}
		else
		{
			if ($old_pro_status == 'PS01')
			{
				$param["pro_status"] = 'PS02'; // 업무진행

			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// 히스토리저장
				$hi_command    = "insert"; //명령어
				$hi_table      = "project_status_history"; //테이블명
				$hi_conditions = ""; //조건

				$hi_param['reg_id']      = $mem_idx;
				$hi_param['reg_date']    = date('Y-m-d H:i:s');
				$hi_param['comp_idx']    = $comp_idx;
				$hi_param['part_idx']    = $part_idx;
				$hi_param['mem_idx']     = $mem_idx;
				$hi_param['pro_idx']     = $pro_idx;
				$hi_param['status']      = $param["pro_status"];
				$hi_param['status_date'] = date('Y-m-d H:i:s');
				$hi_param['status_memo'] = '프로젝트가 진행되었습니다.';

				$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
				db_query($query_str);
				query_history($query_str, $hi_table, $hi_command);
			}
		}

		chk_before($param);

	// 담당자 정리
		$chk_charge_idx = ',' . $param['charge_idx'] . ',';
		$chk_charge_arr = explode(',', $chk_charge_idx);
		sort($chk_charge_arr);
		$total_charge = '';
		foreach ($chk_charge_arr as $k => $v)
		{
			if ($v != '')
			{
				if ($old_charge != $v)
				{
					$total_charge .= ',' . $v;
				}
				$old_charge = $v;
			}
		}
		$total_charge = substr($total_charge, 1, strlen($total_charge)-1);
		$param['charge_idx'] = $total_charge;
        
        $where = " and pro.pro_idx = '" .  $pro_idx . "'";
        $old_data = project_info_data('view', $where);
        
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // 업무관련 수정 히스토리
        $total_history = '';
    //제목
        if ($param['subject'] != $old_data['subject'])
        {
            $total_history .= '프로젝트 제목을 ' . $old_data['subject'] . '(에)서 ' . $param['subject'] . '(으)로 변경되었습니다. ';
        }
    // 기한
        if ($param['deadline_date'] != $old_data['deadline_date'])
        {
            $total_history .= '프로젝트 기한 ' . $old_data['deadline_date'] . '(에)서 ' . $param['deadline_date'] . '(으)로 변경되었습니다. ';
        }
    // 책임자
        if ($param['apply_idx'] != $old_data['apply_idx'])
        {
            $old_mem_where = " and mem.mem_idx = '" . $old_data['apply_idx'] . "'";
            $old_mem_data = member_info_data('view', $old_mem_where);

            $mem_where = " and mem.mem_idx = '" . $param['apply_idx'] . "'";
            $mem_data = member_info_data('view', $mem_where);

            $total_history .= '프로젝트 책임자 ' . $old_mem_data['mem_name'] . '(에)서 ' . $mem_data['mem_name'] . '(으)로 변경되었습니다. ';
        }
    // 담당자
        if ($param['charge_idx'] != $old_data['charge_idx'])
        {
        // 담당자명 구하기 - 예전
            $old_charge_arr = explode(',', $old_data['charge_idx']);
            $old_charge = '';
            foreach ($old_charge_arr as $old_k => $old_v)
            {
                $mem_where = " and mem.mem_idx = '" . $old_v . "'";
                $mem_data = member_info_data('view', $mem_where);
                $old_charge .= $mem_data['mem_name'];
                if ($old_k < count($old_charge_arr)-1)
                {
                    $old_charge .= ', ';
                }
            }

        // 담당자명 구하기 - 새로
            $new_charge_idx = $param['charge_idx'];
            $new_charge_arr = explode(',', $new_charge_idx);
            $new_charge = '';
            foreach ($new_charge_arr as $new_k => $new_v)
            {
                $mem_where = " and mem.mem_idx = '" . $new_v . "'";
                $mem_data = member_info_data('view', $mem_where);
                $new_charge .= $mem_data['mem_name'];
                if ($new_k < count($new_charge_arr)-1)
                {
                    $new_charge .= ', ';
                }
            }
            $total_history .= '프로젝트 담당자 ' . $old_charge . '(에)서 ' . $new_charge . '(으)로 변경되었습니다. ';
        }

        

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 파일저장
		global $tmp_path, $comp_project_path;

		files_dir($comp_project_path);
		$data_path = $comp_project_path . '/' . $pro_idx;
		files_dir($data_path);

		$file_command    = "insert"; //명령어
		$file_table      = "project_file"; //테이블명
		$file_conditions = ""; //조건

		$reg_id   = $param['mod_id'];
		$reg_date = $param['mod_date'];

	
		if ($total_history != '')
		{
			$hi_command    = "insert"; //명령어
			$hi_table      = "project_status_history"; //테이블명
			$hi_conditions = ""; //조건

			$hi_param['reg_id']      = $mem_idx;
			$hi_param['reg_date']    = date('Y-m-d H:i:s');
			$hi_param['comp_idx']    = $comp_idx;
			$hi_param['part_idx']    = $part_idx;
			$hi_param['mem_idx']     = $mem_idx;
			$hi_param['pro_idx']     = $pro_idx;
			$hi_param['status']      = '';
			$hi_param['status_date'] = date('Y-m-d H:i:s');
			$hi_param['status_memo'] = $total_history;

			$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
			db_query($query_str);
			query_history($query_str, $hi_table, $hi_command);
		}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 알림건
		$mem_idx = $_SESSION[$sess_str . '_mem_idx'];
		charge_push_send($mem_idx, $param['charge_idx'], $param['apply_idx'], 'project', $param['subject'], $old_charge_idx, $old_apply_idx, '');

		$str = '{"success_chk" : "Y", "error_string" : "", "pro_idx": "' . $pro_idx . '"}';
		echo $str;
		exit;
	}

//삭제 함수
	function delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address, $set_filecneter_url;

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];
		$pro_idx  = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "project_info"; //테이블명
		$conditions = "pro_idx = '" . $pro_idx . "'"; //조건

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $mem_idx;
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);
        
        $command    = "update"; //명령어
        $table      = "work_info"; //테이블명
        $conditions = "pro_idx = '" . $pro_idx . "'"; //조건

        $param['del_yn']   = "Y";
        $param['del_ip']   = $ip_address;
        $param['del_id']   = $mem_idx;
        $param['del_date'] = date("Y-m-d H:i:s");

        $query_str = make_sql($param, $command, $table, $conditions);
        db_query($query_str);
        query_history($query_str, $table, $command);
        
        //폴더의 최상단 번호를 구한다.
        $sql = "select min(fi_idx) fi_idx from filecenter_info where pro_idx='" . $pro_idx . "' and comp_idx='" . $comp_idx . "' and del_yn=0 and menu_code is null and dir_file='folder' ";
        $rows = db_query($sql);
        $data = query_fetch_array($rows);
        $fi_data = string_output($data);
        //print_r($fi_data);

		$str = '{"success_chk" : "Y", "error_string" : "", "url" : "' . $set_filecneter_url . '", "fi_idx" : "' . $fi_data['fi_idx'] . '", "mem_idx" : "' . $mem_idx . '"}';
		echo $str;
		exit;
	}

// 파일삭제 함수
	function file_delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address, $comp_project_path;

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$mem_idx  = $_SESSION[$sess_str . '_mem_idx'];
		$prof_idx = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "project_file"; //테이블명
		$conditions = "prof_idx = '" . $prof_idx . "'"; //조건

		$data = query_view("select * from " . $table . " where " . $conditions);

		$img_sname = $data["img_sname"];
		if ($img_sname != "") @unlink($comp_project_path . '/' . $data['pro_idx'] . '/' . $img_sname);

		$param['del_yn']   = "Y";
		$param['del_ip']   = $ip_address;
		$param['del_id']   = $mem_idx;
		$param['del_date'] = date("Y-m-d H:i:s");

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

// 프로젝트 내부저장
	function post_file()
	{
		global $_POST, $_SESSION, $sess_str, $comp_project_path, $comp_project_dir, $tmp_path;

		$idx_common = $_POST['idx_common'];
		$table_name = $_POST['table_name'];
		$table_idx  = $_POST['table_idx'];
		$pro_idx    = $_POST['pro_idx'];
		$chk_comp   = $_SESSION[$sess_str . '_comp_idx'];
		$chk_part   = $_SESSION[$sess_str . '_part_idx'];
		$chk_mem    = $_SESSION[$sess_str . '_mem_idx'];

		if ($pro_idx != '')
		{
		// 프로젝트정보
			$pro_where = " and pro.pro_idx = '" . $pro_idx . "'";
			$pro_data = project_info_data('view', $pro_where);

			$comp_idx = $pro_data['comp_idx'];
			$part_idx = $pro_data['part_idx'];

		// 폴더생성
			$new_dir  = $comp_project_dir  . '/' . $pro_idx;
			$new_path = $comp_project_path . '/' . $pro_idx;
			files_dir($new_path);

		// 올린파일정보
			$old_query['query_string'] = "select * from temp_file_info where table_name = '" . $table_name . "' and table_idx = '" . $table_idx . "' order by reg_date asc";
			$old_query['page_num']     = '';
			$old_query['page_size']    = '';
			$old_list = query_list($old_query);
			foreach ($old_list as $old_k => $old_data)
			{
				if (is_array($old_data))
				{
				// 추가 폴더생성
					$old_img_sname  = trim($old_data['img_sname']);
					$new_folder     = str_replace($tmp_path . '/', '/', $old_img_sname);
					$new_folder_arr = explode('/', $new_folder);
					$new_folder_num = count($new_folder_arr) - 1;

					$new_file_dir   = $new_dir;  $new_file_rdir  = $new_dir;
					$new_file_path  = $new_path; $new_file_rpath = $new_path;
					foreach ($new_folder_arr as $folder_k => $folder_v)
					{
						if ($folder_v != '' && $folder_k < $new_folder_num)
						{
							$new_file_name   = $folder_v;
							$new_file_sname  = 'P_' . $comp_idx . '_' . $part_idx . '_' . $pro_idx . '_' . time();

							$new_file_dir  .= '/' . $new_file_name;
							$new_file_rdir .= '/' . $new_file_sname;

							$new_file_path  .= '/' . $new_file_name;
							$new_file_rpath .= '/' . $new_file_sname;

							files_dir($new_file_rpath);
						}
					}

				// 파일정보
					$img_fname = $old_data['img_fname'];
					$img_size  = $old_data['img_size'];
					$img_type  = $old_data['img_type'];
					$img_ext   = $old_data['img_ext'];
					$reg_date  = $old_data['reg_date'];
					$file_ex   = explode('.', $img_fname);

					$new_img_sname = 'P_' . $comp_idx . '_' . $part_idx . '_' . $pro_idx . '_' . time() . '_' . $old_k .'.' . $img_ext;
					$target_file   = $new_file_rpath . '/' . $new_img_sname;

				// 파일이 있을 경우
					if (file_exists($old_img_sname))
					{
						copy($old_img_sname, $target_file);
						unlink($old_img_sname);

						$chk_file_dir  = str_replace($new_dir, '', $new_file_dir);
						$chk_file_rdir = str_replace($new_dir, '', $new_file_rdir);

					// 프로젝트파일에 저장
					// 폴더등록
						$sort_data = query_view("select max(sort) as sort from project_file where pro_idx = '" . $pro_idx . "'");
						$file_sort = ($sort_data['sort'] == '') ? '1' : $sort_data['sort'] + 1;

						$insert_file_query = "
							insert into project_file set
								comp_idx  = '" . $comp_idx . "', part_idx  = '" . $part_idx . "',
								pro_idx   = '" . $pro_idx . "', sort      = '" . $file_sort . "',
								img_fname = '" . $img_fname . "', img_sname = '" . $new_img_sname . "',
								img_size  = '" . $img_size . "',
								img_type  = '" . $img_type . "',
								img_ext   = '" . $img_ext . "',
								in_out    = 'IN',
								img_path  = '" . $chk_file_dir . "', img_rpath = '" . $chk_file_rdir . "',

								reg_id = '" . $chk_mem . "', reg_date = '" . time() . "'";
						db_query($insert_file_query);
						query_history($insert_file_query, 'project_file', 'insert', $chk_comp, $chk_part, $chk_mem);
					}
				}
			}
		}

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}

//위 정렬 함수
    function sort_up()
    {
        global $_POST, $_SESSION, $sess_str;

        $table = "project_class"; //테이블명

        $proc_idx = $_POST['idx'];
        $comp_idx = $_SESSION[$sess_str . '_comp_idx'];
        $part_idx = $_SESSION[$sess_str . '_part_idx'];
        $mem_idx  = $_SESSION[$sess_str . '_mem_idx'];
        $pro_idx  = $_POST['pro_idx'];

        $project_class_where = " and proc.proc_idx = '" . $proc_idx . "'";
        $data = project_class_data('view', $project_class_where);
        
        $sort_where = " and proc.comp_idx = '" . $comp_idx . "' and proc.part_idx = '" . $part_idx . "' and proc.pro_idx = '" . $pro_idx . "' and proc.sort < '" . $data["sort"] . "'";
        $sort_order = "proc.sort desc";
        $prev_data = project_class_data('view', $sort_where, $sort_order);

        $sql = "update " . $table . " set sort = '" . $prev_data["sort"] . "' where proc_idx = '" . $proc_idx . "'";
        db_query($sql);
        query_history($sql, $table, "update");

        $sql = "update " . $table . " set sort = '" . $data["sort"] . "' where proc_idx = '" . $prev_data["proc_idx"] . "'";
        db_query($sql);
        query_history($sql, $table, "update");

        $sort_where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "' and pro_idx = '" . $pro_idx . "' ";
        data_sort_action($table, 'proc_idx', $sort_where);

        $str = '{"success_chk" : "Y", "error_string":""}';
        echo $str;
        exit;
    }

//아래 정렬 함수
    function sort_down()
    {
        global $_POST, $_SESSION, $sess_str;

        $table = "project_class"; //테이블명

        $proc_idx = $_POST['idx'];
        $comp_idx = $_SESSION[$sess_str . '_comp_idx'];
        $part_idx = $_SESSION[$sess_str . '_part_idx'];
        $mem_idx  = $_SESSION[$sess_str . '_mem_idx'];
        $pro_idx  = $_POST['pro_idx'];

        $where = " and proc.proc_idx = '" . $proc_idx . "'";
        $data = project_class_data('view', $where);

        $sort_where = " and proc.comp_idx = '" . $comp_idx . "' and proc.part_idx = '" . $part_idx . "' and proc.pro_idx = '" . $pro_idx . "' and proc.sort > '" . $data["sort"] . "'";
        $sort_order = "proc.sort asc";
        $next_data = project_class_data('view', $sort_where, $sort_order);

        $sql = "update " . $table . " set sort = '" . $next_data["sort"] . "' where proc_idx = '" . $proc_idx . "'";
        db_query($sql);
        query_history($sql, $table, "update");

        $sql = "update " . $table . " set sort = '" . $data["sort"] . "' where proc_idx = '" . $next_data["proc_idx"] . "'";
        db_query($sql);
        query_history($sql, $table, "update");

        $sort_where = " and comp_idx = '" . $comp_idx . "' and part_idx = '" . $part_idx . "' and pro_idx = '" . $pro_idx . "' ";
        data_sort_action($table, 'proc_idx', $sort_where);

        $str = '{"success_chk" : "Y", "error_string":""}';
        echo $str;
        exit;
    }

    function project_code_check()
    {
        global $_POST, $_SESSION, $sess_str;
        
        $table = "project_class"; //테이블명

        $menu1    = $_POST['menu1_code'];
        $menu2    = $_POST['menu2_code'];
        $proc_idx = $_POST['idx'];
        $comp_idx = $_SESSION[$sess_str . '_comp_idx'];
        $part_idx = $_SESSION[$sess_str . '_part_idx'];
        $mem_idx  = $_SESSION[$sess_str . '_mem_idx'];
        $param = $_POST['param'];

        $where = " and pro.menu1_code='" . $menu1 . "' and pro.menu2_code='" . $menu2 . "' and pro.project_code = '" .  trim($param['project_code']) . "' and pro.del_yn='N' ";
        $data = project_info_data('view', $where);
        
        if ($data['total_num'] > 0) {
            echo json_encode(array('success_chk'=>'N', 'error_string'=>''));
        } else {
            echo json_encode(array('success_chk'=>'Y', 'error_string'=>''));
        }
        exit;
    }
?>