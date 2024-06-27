<?
/*
	생성 : 2012.12.27
	수정 : 2013.02.05
	위치 : 업무폴더 > 프로젝트관리 - 보기 - 업무 - 실행
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
		$chk_param['require'][] = array("field"=>"work_type", "msg"=>"업무종류");
		$chk_param['require'][] = array("field"=>"charge_idx", "msg"=>"담당자");
		$chk_param['require'][] = array("field"=>"subject", "msg"=>"업무제목");
		$chk_param['require'][] = array("field"=>"deadline_date", "msg"=>"기한");
		$chk_param['require'][] = array("field"=>"remark", "msg"=>"업무내용");

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
		$table      = "work_info"; //테이블명
		$conditions = ""; //조건

		$param['org_reg_id'] = $mem_idx;
		$param['reg_id']   = $mem_idx;
		$param['reg_date'] = date('Y-m-d H:i:s');
		$param['comp_idx'] = $comp_idx;
		$param['part_idx'] = $part_idx;
		$param['mem_idx']  = $mem_idx;

		if ($param["open_yn"] == '') $param["open_yn"] = 'Y';
		if ($param["important"] == '') $param["important"] = 'WI01';

	// 요청업무일 경우
		if ($param["work_type"] == 'WT03')
		{ }
		else $param["apply_idx"] = '';

	// 본인업무일 경우
		if ($param["work_type"] == 'WT01')
		{
			$param['reg_id']  = $param["charge_idx"];
			$param['mem_idx'] = $param["charge_idx"];
		}

    // 기한
        $deadline_date1 = $_POST['deadline_date1'];
        $deadline_date2 = $_POST['deadline_date2'];
        if ($deadline_date1 == 'select')
        {
            $param['deadline_date'] = $deadline_date2;
        }
        else if ($deadline_date1 == '-')
        {
            $param['deadline_date'] = '';
        }
        else
        {
            $param['deadline_date'] = $deadline_date1;
        }
    // 기한 - 덧붙이기
        $deadline_str1 = $_POST['deadline_str1'];
        $deadline_str2 = $_POST['deadline_str2'];
        if ($deadline_str1 == 'select')
        {
            $param['deadline_str'] = $deadline_str2;
        }
        else if ($deadline_str1 == '-')
        {
            $param['deadline_str'] = '';
        }
        else
        {
            $param['deadline_str'] = $deadline_str1;
        }

	// 기한, 담당자가 없을 경우 업무대기로 설정
		if ($param['deadline_date'] == '' || $param['charge_idx'] == '')
		{
			$param["work_status"] = 'WS01'; // 업무대기
		}
		else
		{
			$param["work_status"] = 'WS02'; // 업무진행
		}

		$chk_data = query_view("select max(wi_idx) as wi_idx, max(order_idx) as order_idx from " . $table);
		$param['wi_idx']    = ($chk_data['wi_idx'] == '') ? '1' : $chk_data['wi_idx'] + 1;
		$param['order_idx'] = ($chk_data['order_idx'] == '') ? '1' : $chk_data['order_idx'] + 1;
		$param['gno']       = $param['wi_idx'];
		$param['tgno']      = 0;

		chk_before($param);

		$query_str = make_sql($param, $command, $table, $conditions);
		db_query($query_str);
		query_history($query_str, $table, $command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장 - 업무
		$hi_command    = "insert"; //명령어
		$hi_table      = "work_status_history"; //테이블명
		$hi_conditions = ""; //조건

		$hi_param['reg_id']      = $mem_idx;
		$hi_param['reg_date']    = date('Y-m-d H:i:s');
		$hi_param['comp_idx']    = $comp_idx;
		$hi_param['part_idx']    = $part_idx;
		$hi_param['mem_idx']     = $mem_idx;
		$hi_param['wi_idx']      = $param['wi_idx'];
		$hi_param['status']      = $param["work_status"];
		$hi_param['status_date'] = date('Y-m-d H:i:s');
		$hi_param['status_memo'] = '업무가 등록되었습니다.';

		$query_str = make_sql($hi_param, $hi_command, $hi_table, $hi_conditions);
		db_query($query_str);
		query_history($query_str, $hi_table, $hi_command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 히스토리저장 - 프로젝트
		$phi_command    = "insert"; //명령어
		$phi_table      = "project_status_history"; //테이블명
		$phi_conditions = ""; //조건

		$phi_param['reg_id']      = $mem_idx;
		$phi_param['reg_date']    = date('Y-m-d H:i:s');
		$phi_param['comp_idx']    = $comp_idx;
		$phi_param['part_idx']    = $part_idx;
		$phi_param['mem_idx']     = $mem_idx;
		$phi_param['pro_idx']     = $param['pro_idx'];
		$phi_param['proc_idx']    = $param['proc_idx'];
		$phi_param['wi_idx']      = $param['wi_idx'];
		$phi_param['status']      = $param["work_status"];
		$phi_param['status_date'] = date('Y-m-d H:i:s');
		$phi_param['status_memo'] = '업무가 등록되었습니다.';

		$query_str = make_sql($phi_param, $phi_command, $phi_table, $phi_conditions);
		db_query($query_str);
		query_history($query_str, $phi_table, $phi_command);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 알림건
		$mem_idx = $_SESSION[$sess_str . '_mem_idx'];
		charge_push_send($mem_idx, $param['charge_idx'], $param['apply_idx'], 'project_work', $param['subject'], '', '', '');

		$str = '{"success_chk" : "Y", "error_string" : "", "wi_idx":"' . $param['wi_idx'] . '"}';
		echo $str;
		exit;
	}

// 파일삭제 함수
	function file_delete()
	{
		global $_POST, $_SESSION, $sess_str, $ip_address, $comp_work_path;

		$comp_idx = $_SESSION[$sess_str . '_comp_idx'];
		$part_idx = $_SESSION[$sess_str . '_part_idx'];
		$wf_idx   = $_POST['idx'];

		$command    = "update"; //명령어
		$table      = "work_file"; //테이블명
		$conditions = "wf_idx = '" . $wf_idx . "'"; //조건

		$data = query_view("select * from " . $table . " where " . $conditions);

		$img_sname = $data["img_sname"];
		if ($img_sname != "") @unlink($comp_work_path . '/' . $data['wi_idx'] . '/' . $img_sname);

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

// 업무 내부저장
	function post_file()
	{
		global $_POST, $_SESSION, $sess_str, $comp_project_path, $comp_project_dir, $tmp_path;

		$idx_common = $_POST['idx_common'];
		$table_name = $_POST['table_name'];
		$table_idx  = $_POST['table_idx'];
		$pro_idx    = $_POST['pro_idx'];
		$wi_idx     = $_POST['wi_idx'];
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
			$new_dir  = $comp_work_dir  . '/' . $wi_idx;
			$new_path = $comp_work_path . '/' . $wi_idx;
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
							$new_file_sname  = 'P_' . $comp_idx . '_' . $part_idx . '_' . $pro_idx . '_' . $wi_idx . '_' . time();
							$new_file_rdir  .= '/' . $new_file_sname;
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

				// 파일이 있을 경우
					if (file_exists($old_img_sname))
					{
						$sort_data = query_view("select max(sort) as sort from work_file where wi_idx = '" . $wi_idx . "'");
						$file_sort = ($sort_data['sort'] == '') ? '1' : $sort_data['sort'] + 1;

						$new_img_sname = 'work_' . $wi_idx . '_' . $file_sort .'.' . $img_ext;
						$target_file   = $new_file_rpath . '/' . $new_img_sname;

						copy($old_img_sname, $target_file);

						$chk_file_dir  = str_replace($new_dir, '', $new_file_dir);
						$chk_file_rdir = str_replace($new_dir, '', $new_file_rdir);

					// 파일에 저장
						$insert_file_query = "
							insert into work_file set
								comp_idx  = '" . $comp_idx . "', part_idx  = '" . $part_idx . "', wi_idx   = '" . $wi_idx . "',
								sort      = '" . $file_sort . "',
								img_fname = '" . $img_fname . "',
								img_sname = '" . $new_img_sname . "',
								img_size  = '" . $img_size . "',
								img_type  = '" . $img_type . "',
								img_ext   = '" . $img_ext . "',
								in_out    = 'IN',
								img_path  = '" . $chk_file_path . "', img_rpath = '" . $chk_file_rpath . "',

								reg_id = '" . $chk_mem . "', reg_date = '" . time() . "'";
						db_query($insert_file_query);
						query_history($insert_file_query, 'work_file', 'insert', $chk_comp, $chk_part, $chk_mem);

						unlink($old_img_sname);
					}
				}
			}
		}

		$str = '{"success_chk" : "Y", "error_string" : ""}';
		echo $str;
		exit;
	}
?>