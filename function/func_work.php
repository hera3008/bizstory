<?
////////////////////////////////////////////////////////////////////////////////////////////////////////
/////
///// 프로젝트관련 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------- 지사별 프로젝트상태
	function code_project_status_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "code.sort asc";
		if ($del_type == 1) $where = "code.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(code.code_idx)
			from
				code_project_status code
				left join company_part part on part.del_yn = 'N' and part.part_idx = code.part_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				code.*
				, part.part_name
			from
				code_project_status code
				left join company_part part on part.del_yn = 'N' and part.part_idx = code.part_idx
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}

//-------------------------------------- 프로젝트관련정보
	function project_list_data($data, $pro_idx, $mode = 'view')
	{
		global $local_dir, $_SESSION, $sess_str, $btn_view, $link_hash, $set_part_work_yn, $set_color_list2;

		$chk_comp  = $_SESSION[$sess_str . '_comp_idx'];
		$chk_part  = $_SESSION[$sess_str . '_part_idx'];
		$chk_mem   = $_SESSION[$sess_str . '_mem_idx'];
		$chk_level = $_SESSION[$sess_str . '_ubstory_level'];

		$subject       = $data['subject'];
		$charge_idx    = $data['charge_idx'];
		$charge_arr    = explode(',', $charge_idx);
		$apply_idx     = $data['apply_idx'];
		$start_date    = $data['start_date'];
		$deadline_date = $data['deadline_date'];
		$end_date      = $data['end_date'];
		$pro_status       = $data['pro_status'];
		$pro_status_str   = $data['pro_status_str'];
		$pro_status_bold  = $data['pro_status_bold'];
		$pro_status_color = $data['pro_status_color'];

		$display_type = $data['display_type']; // 이력일 경우 이 값을 가지고 구분을 한다.
		if ($display_type == 'display_main')
		{
			$reg_id = $data['project_reg_id'];
		}
		else
		{
			$reg_id = $data['reg_id'];
		}

	// 시작일
		$start_date1 = date_replace($start_date, 'Y.m.d');
		$start_week  = date_replace($start_date, 'w');
		if ($start_week != '')
		{
			if ($start_date1 == date('Y.m.d')) // 오늘과 시작일이 같을 경우
			{
				$start_date_str = '<span style="color:#FF0000;">' . $start_date1 . '(' . $start_week . ')</span>';
			}
			else
			{
				$start_date_str = $start_date1 . '(' . $start_week . ')';
			}
		}
		if ($start_date_str == '') $start_date_str = '미정';
		$data['start_date_str'] = $start_date_str;

	// 기한일
		$deadline_date1 = date_replace($deadline_date, 'Y.m.d');
		$deadline_week  = date_replace($deadline_date, 'w');
		if ($deadline_week != '')
		{
			if ($deadline_date1 == date('Y.m.d')) // 오늘과 기한일이 같을 경우
			{
				$deadline_date_str = '<span style="color:#FF0000;">' . $deadline_date1 . '(' . $deadline_week . ')</span>';
			}
			else
			{
				$deadline_date_str = $deadline_date1 . '(' . $deadline_week . ')';
			}
		}
		if ($deadline_date_str == '') $deadline_date_str = '미정';
		$data['deadline_date_str'] = $deadline_date_str;

	// 상태/완료일
		if ($pro_status == 'PS90') // 완료
		{
			$end_date1 = date_replace($end_date, 'Y.m.d');
			if ($end_date1 != '')
			{
				$end_week     = date_replace($end_date, 'w');
				$end_date_str = '<span class="num">' . $end_date1 . '(' . $end_week . ')</span>';
			}
			else
			{
				$end_date_str = '';
			}
			$status_view = '';
			$diff_day    = 0;

			$status_str = '<span style="';
			if ($pro_status_bold == 'Y') $status_str .= 'font-weight:900;';
			if ($pro_status_color != '') $status_str .= 'color:' . $pro_status_color . ';';
			$status_str .= '">' . $pro_status_str . '</span>';
		}
		else
		{
			$status_str = '<span style="';
			if ($pro_status_bold == 'Y') $status_str .= 'font-weight:900;';
			if ($pro_status_color != '') $status_str .= 'color:' . $pro_status_color . ';';
			$status_str .= '">' . $pro_status_str . '</span>';

			$end_date_str = $status_str;
			$status_view  = $status_str;

		// 기한이 지나면 지연으로 표시함 - 대기(PS01), 취소(PS60), 반려(PS70), 보류(PS80)
		    /*
			$today_date = date('Ymd');
			$chk_date   = date_replace($deadline_date, 'Ymd');
			$diff_day = 0;
			if ($chk_date < $today_date)
			{
				$date_query = "select datediff('" . $chk_date . "', '" . $today_date . "') as diff_date";
				$date_data = query_view($date_query);
				$diff_day = $date_data['diff_date'];
			}
            */
            $diff_day = $data['diff_date'];

			if ($diff_day < 0) // 지난경우
			{
				if ($pro_status == 'PS70') // 반려
				{
					$end_date_str = $status_str . '(<span style="color:#FF0000; font-weight:700;">지연</span> ' . $diff_day . '일)';
				}
				else if ($pro_status != 'PS01' && $pro_status != 'PS60' && $pro_status != 'PS80') // 대기, 취소, 보류 아닐경우
				{
					$end_date_str = $status_str . '(<span style="color:#FF0000; font-weight:700;">지연</span> ' . $diff_day . '일)';
				}
			}
		}

		$data['end_date_str'] = $end_date_str;
		$data['status_view']  = $status_view;
		$data['diff_day']     = $diff_day;
		$data['status_title'] = $status_str;

	// 자기 업무일 경우 표시
		$chk_charge = ',' . $charge_idx . ',';
		$chk_member = ',' . $chk_mem . ',';

	// 담당자
		if ($data['charge_mem_idx'] != '')
		{
			$charge_len = count($charge_arr);
			$charge_exp = $charge_len - 1;

			$charge_str = staff_layer_data($data, 'charge', '', $set_part_work_yn, $set_color_list2, 'proliststtaff', $data['pro_idx'], '');
			if ($charge_len > 1)
			{
				$charge_str .= '외 ' . $charge_exp . '명';
			}

		// 총담당자구하기
			$total_charge_str = '';
            if ($mode == 'view') {
    			foreach ($charge_arr as $charge_k => $charge_v)
    			{
    				if ($charge_v != '')
    				{
    					$total_charge_str .= ', ' . staff_layer_form($charge_v, '', $set_part_work_yn, $set_color_list2, 'proviewstaff', $data['pro_idx'], 'default');
    				}
    			}
    			$total_charge_str = substr($total_charge_str, 2, strlen($total_charge_str));
            }
		}
		else
		{
			$charge_str       = '미정';
			$total_charge_str = '미정';
		}
		$data['charge_str']       = $charge_str;
		$data['total_charge_str'] = $total_charge_str;

	// 책임자
		$data['apply_name'] = staff_layer_data($data, 'apply', '', $set_part_work_yn, $set_color_list2, 'proapplystaff', $data['pro_idx'], '');

        //echo $data['pro_idx'] . '<br>';
        
	//로그인한 사람이 등록자일 경우
	//담당자중에 다른 지사가 있을 경우 지사표시
		$part_img = '';
		if ($charge_idx != '')
		{
			if ($chk_mem == $reg_id)
			{
				foreach ($charge_arr as $charge_k => $charge_v)
				{
					if ($charge_v != '')
					{
						$mem_where = " and mem.mem_idx = '" . $charge_v . "'";
						$mem_data = member_info_data('view', $mem_where);

						if ($mem_data['total_num'] > 0)
						{
							$part_sort = $mem_data['part_sort'];
							$part_idx  = $mem_data['part_idx'];
							if ($part_idx != $chk_part)
							{
								if ($part_sort == 1)
								{
									$part_img = '<img src="' . $local_dir . '/bizstory/images/icon/head_icon.gif" alt="본사" />';
								}
								else
								{
									$part_img = '<img src="' . $local_dir . '/bizstory/images/icon/branch_icon.gif" alt="지사" />';
								}
							}
						}
						unset($mem_data);
					}
				}
			}
		//로그인한 사람이 담당자일 경우
		//등록자에 해당하는 지사를 표시한다.
			else
			{
			    /*
				$mem_where = " and mem.mem_idx = '" . $reg_id . "'";
				$mem_data = member_info_data('view', $mem_where);

				$part_sort = $mem_data['part_sort'];
				$part_idx  = $mem_data['part_idx'];
                */
				$part_sort = $data['reg_part_sort'];
				$part_idx = $data['reg_part_idx'];
				/*
				echo $part_sort . ' ?? ' . $data['reg_part_sort'] . '<br>';
                echo $part_idx . ' ?? ' . $data['reg_part_idx'] . '<br>';
                */
				foreach ($charge_arr as $charge_k => $charge_v)
				{
					if ($chk_mem == $charge_v && $charge_v != '')
					{
						if ($part_idx != $chk_part)
						{
							if ($part_sort == 1)
							{
								$part_img = '<img src="' . $local_dir . '/bizstory/images/icon/head_icon.gif" alt="본사" />';
							}
							else
							{
								$part_img = '<img src="' . $local_dir . '/bizstory/images/icon/branch_icon.gif" alt="지사" />';
							}
						}
					}
				}
				unset($mem_data);
			}
		}
		$data['part_img'] = $part_img;

	// 첨부파일
		/*$file_where = " and prof.pro_idx = '" . $pro_idx . "'";
		$file_page = project_file_data('page', $file_where);
		$data['total_file'] = $file_page['total_num'];*/
		if ($data['file_cnt'] > 0) $data['file_str'] = '<span class="attach" title="첨부파일">' . number_format($data['file_cnt']) . '</span>';
		else $data['file_str'] = '';
		unset($file_page);

	// 공개/비공개
		if ($data['open_yn'] == 'N')
		{
			$open_span = '<span class="private"></span>';
			$open_txt  = '비공개';
		}
		else
		{
			$open_span = '';
			$open_txt  = '';
		}
		$data['open_img'] = $open_span;
		$data['open_txt'] = $open_txt;
        if ($link_hash == '') $link_hash = 'javascript:void(0);';

		if ($chk_level <= 11) $data['open_yn'] = 'Y';
		if ($data['open_yn'] == 'N')
		{
			$charge_chk = 'N';
			foreach ($charge_arr as $charge_k => $charge_v)
			{
				if ($charge_v == $chk_mem)
				{
					$charge_chk = 'Y';
					break;
				}
			}
			if ($reg_id == $chk_mem || $apply_idx == $chk_mem || $charge_chk == 'Y')
			{
				$subject_url = '<a href="' . $link_hash . '" onclick="' . $btn_view . '"> ' . $subject . '</a>';
				$subject_txt = $subject;
				$subject_url_main = 'Y';
			}
			else
			{
				$subject_url = '비공개된 내용입니다.';
				$subject_txt = '비공개된 내용입니다.';
				$subject_url_main = 'N';
			}
		}
		else
		{
			$subject_url = '<a href="' . $link_hash . '" onclick="' . $btn_view . '">' . $subject . '</a>';
			$subject_txt = $subject;
			$subject_url_main = 'Y';
		}
		$data['subject_url'] = $subject_url;
		$data['subject_txt'] = $subject_txt;
		$data['subject_url_main'] = $subject_url_main;

	// 등록자
		$reg_name = staff_layer_data($data, 'reg', '', $set_part_work_yn, $set_color_list2, 'proregsttaff', $data['pro_idx'], '');
		$data['reg_name']      = $reg_name;
		$data['reg_name_view'] = $reg_name;

	// 새로등록된 업무 - new 이미지표현
		$new_day = date('YmdHis', time() - (60 * 60 * 24 * 1));
		if (date_replace($data['reg_date'], 'YmdHis') >= $new_day)
		{
			$data['new_img'] = '<img src="' . $local_dir . '/bizstory/images/icon/ico_new2.png" alt="새업무" />';
			$data['new_txt'] = '새업무';
		}
		else
		{
			$data['new_img'] = '';
			$data['new_txt'] = '';
		}

		Return $data;
	}



//-------------------------------------- 프로젝트 정보
	function project_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "pro.reg_date desc";
		if ($del_type == 1) $where = "pro.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(pro.pro_idx)
			from
				project_info pro
				left join code_project_status pros on pros.del_yn = 'N' and pros.comp_idx = pro.comp_idx and pros.part_idx = pro.part_idx and pros.code_value = pro.pro_status
				left join company_part part on part.del_yn = 'N' and part.part_idx = pro.part_idx
				left join member_info reg on reg.del_yn = 'N' and reg.comp_idx = pro.comp_idx and reg.mem_idx = pro.reg_id
				left join member_info app on app.del_yn = 'N' and app.comp_idx = pro.comp_idx and app.mem_idx = pro.apply_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				pro.*
				, pros.code_name as pro_status_str, pros.code_bold as pro_status_bold, pros.code_color as pro_status_color
				, part.part_name
				, reg.mem_name as reg_name, reg.del_yn as reg_del_yn
				, app.mem_name as apply_name, app.del_yn as apply_del_yn
				, p1.code_name menu1, p2.code_name menu2
			from
				project_info pro
				left join code_project_status pros on pros.del_yn = 'N' and pros.comp_idx = pro.comp_idx and pros.part_idx = pro.part_idx and pros.code_value = pro.pro_status
				left join company_part part on part.del_yn = 'N' and part.part_idx = pro.part_idx
				left join member_info reg on reg.mem_idx = pro.reg_id
				left join member_info app on app.mem_idx = pro.apply_idx
				left join code_project_class p1 on pro.menu1_code = p1.code_idx
				left join code_project_class p2 on pro.menu2_code = p2.code_idx
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}
	
//-------------------------------------- 프로젝트 정보
    function project_list_info($query_type, $where = '', $file_where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
    {
        if ($orderby == '') $orderby = "pro.reg_date desc";
        if ($del_type == 1) $where = "pro.del_yn = 'N'" . $where;
        else $where = "1" . $where;

        $query_page = "
            select
                count(pro.pro_idx)
            from
                project_info pro
                left join code_project_status pros on pros.del_yn = 'N' and pros.comp_idx = pro.comp_idx and pros.part_idx = pro.part_idx and pros.code_value = pro.pro_status
                left join company_part part on part.del_yn = 'N' and part.part_idx = pro.part_idx
                left join member_info reg on reg.del_yn = 'N' and reg.comp_idx = pro.comp_idx and reg.mem_idx = pro.reg_id
                left join member_info app on app.del_yn = 'N' and app.comp_idx = pro.comp_idx and app.mem_idx = pro.apply_idx
            where
                " . $where . "
        ";
        //echo "<pre>" . $query_page . "</pre><br />";
        $query_string = "
        select
            m.mem_idx charge_mem_idx, cp.part_name charge_part_name, cp.sort charge_part_sort, m.part_idx charge_part_idx, csg.group_name charge_group_name, m.comp_idx charge_comp_idx, m.del_yn charge_del_yn, m.mem_name charge_mem_name, cpd.duty_name charge_duty_name
            , t.apply_idx apply_mem_idx, cp2.part_name apply_part_name, cp2.sort apply_part_sort, t.app_part_idx apply_part_idx, csg2.group_name apply_group_name, t.app_comp_idx apply_comp_idx
            , t.reg_id reg_mem_idx, cp3.part_name reg_part_name, cp3.sort reg_part_sort, cp3.part_idx reg_part_idx, csg3.group_name reg_group_name
            , t.* from (
            select
                case WHEN instr(pro.charge_idx, ',') > 0 then
                    substr(pro.charge_idx, 1, instr(pro.charge_idx, ',')-1)
                else 
                    charge_idx
                end first_charge_idx,
                pro.*
                , pros.code_name as pro_status_str, pros.code_bold as pro_status_bold, pros.code_color as pro_status_color
                , part.part_name
                , reg.comp_idx reg_comp_idx, reg.part_idx reg_part_idx, reg.csg_idx reg_csg_idx, reg.cpd_idx reg_cpd_idx, reg.mem_name as reg_mem_name, reg.del_yn as reg_del_yn
                , app.comp_idx app_comp_idx, app.part_idx app_part_idx, app.csg_idx app_csg_idx, app.cpd_idx app_cpd_idx, app.mem_name as apply_mem_name, app.del_yn as apply_del_yn
                , p1.code_name menu1, p2.code_name menu2
                , ifnull(file.file_cnt, 0) file_cnt
                , datediff(pro.deadline_date, date_format(now(), '%Y-%m-%d')) diff_date
            from
                project_info pro
                left join code_project_status pros on pros.del_yn = 'N' and pros.comp_idx = pro.comp_idx and pros.part_idx = pro.part_idx and pros.code_value = pro.pro_status
                left join company_part part on part.del_yn = 'N' and part.part_idx = pro.part_idx
                left join member_info reg on reg.mem_idx = pro.reg_id
                left join member_info app on app.mem_idx = pro.apply_idx
                left join code_project_class p1 on pro.menu1_code = p1.code_idx
                left join code_project_class p2 on pro.menu2_code = p2.code_idx
                left join (select pro_idx, count(*) file_cnt from project_file where del_yn='N' " . $file_where . " group by pro_idx) file on pro.pro_idx=file.pro_idx
            where
                " . $where . "
            order by
                " . $orderby . "
                ) t
            left join member_info m on t.first_charge_idx=m.mem_idx
            left join company_part cp on cp.part_idx=m.part_idx and cp.comp_idx=m.comp_idx and cp.del_yn='N'
            left join company_staff_group csg on csg.comp_idx=m.comp_idx and csg.csg_idx=m.csg_idx and csg.del_yn='N'
            left join company_part_duty cpd on cpd.del_yn = 'N' and cpd.comp_idx = m.comp_idx and cpd.cpd_idx = m.cpd_idx
            left join company_part cp2 on cp2.part_idx=t.app_part_idx and cp2.comp_idx=t.app_comp_idx and cp2.del_yn='N'
            left join company_staff_group csg2 on csg2.comp_idx=t.app_comp_idx and csg2.csg_idx=t.app_csg_idx and csg2.del_yn='N'
            left join company_part_duty cpd2 on cpd2.del_yn='N' and cpd2.comp_idx=t.app_comp_idx and cpd2.cpd_idx=t.app_cpd_idx
            left join company_part cp3 on cp3.part_idx=t.reg_part_idx and cp3.comp_idx=t.reg_comp_idx and cp3.del_yn='N'
            left join company_staff_group csg3 on csg3.comp_idx=t.reg_comp_idx and csg3.csg_idx=t.reg_csg_idx and csg3.del_yn='N'
            left join company_part_duty cpd3 on cpd3.del_yn='N' and cpd3.comp_idx=t.reg_comp_idx and cpd3.cpd_idx=t.reg_cpd_idx
        ";
        //echo "<pre>" . $query_string . "</pre><br />";

        if ($query_type == 'view') $data_info = query_view($query_string);
        else if ($query_type == 'page') $data_info = query_page($query_page);
        else
        {
            $data_sql['query_page']   = $query_page;
            $data_sql['query_string'] = $query_string;
            $data_sql['page_size']    = $page_size;
            $data_sql['page_num']     = $page_num;

            $data_info = query_list($data_sql);
        }

        Return $data_info;
    }
//-------------------------------------- 프로젝트파일
	function project_file_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "prof.sort asc";
		if ($del_type == 1) $where = "prof.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(prof.prof_idx)
			from
				project_file prof
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				prof.*
			from
				project_file prof
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}


//-------------------------------------- 지사별 프로젝트분류
    function code_project_class_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
    {
        if ($orderby == '') $orderby = "code.sort asc";
        if ($del_type == 1) $where = "code.del_yn = 'N'" . $where;
        else $where = "1" . $where;

        $query_page = "
            select
                count(code.code_idx)
            from
                code_project_class code
                left join company_part part on part.del_yn = 'N' and part.part_idx = code.part_idx
            where
                " . $where . "
        ";
        //echo "<pre>" . $query_page . "</pre><br />";
        $query_string = "
            select
                code.*
                , part.part_name
            from
                code_project_class code
                left join company_part part on part.del_yn = 'N' and part.part_idx = code.part_idx
            where
                " . $where . "
            order by
                " . $orderby . "
        ";
        //echo "<pre>" . $query_string . "</pre><br />";

        if ($query_type == 'view') $data_info = query_view($query_string);
        else if ($query_type == 'page') $data_info = query_page($query_page);
        else
        {
            $data_sql['query_page']   = $query_page;
            $data_sql['query_string'] = $query_string;
            $data_sql['page_size']    = $page_size;
            $data_sql['page_num']     = $page_num;

            $data_info = query_list($data_sql);
        }

        Return $data_info;
    }

    function project_part_data($where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
    {
        if ($orderby == '') $orderby = "part.sort asc";
        if ($del_type == 1) $where = "part.del_yn = 'N'" . $where;
        else $where = "1" . $where;

        $query_string = "
            select
                part.part_idx, part.part_name, part.sort
            from
                company_part part
                join member_info m on m.part_idx = part.part_idx
            where
                " . $where . "
            group by part.part_idx, part.part_name, part.sort
            order by
                " . $orderby . "
        ";
        //echo "<pre>" . $query_string . "</pre><br />";
        
        $row = db_query($query_string);
        $i = 0;
        while ($data = query_fetch_array($row))
        {
            $data_info[$i] = string_output($data);
            $i++;
        }
        
        $data_info['query_string'] = $query_string;

        return $data_info;
    }

//-------------------------------------- 프로젝트 분류 정보
	function project_class_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "proc.sort asc";
		if ($del_type == 1) $where = "proc.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(proc.proc_idx)
			from
				project_class proc
				left join code_project_status pros on pros.del_yn = 'N' and pros.comp_idx = proc.comp_idx and pros.part_idx = proc.part_idx and pros.code_value = proc.class_status
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				proc.*
				, pros.code_name as pro_status_str, pros.code_bold as pro_status_bold, pros.code_color as pro_status_color
			from
				project_class proc
				left join code_project_status pros on pros.del_yn = 'N' and pros.comp_idx = proc.comp_idx and pros.part_idx = proc.part_idx and pros.code_value = proc.class_status 
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}

//-------------------------------------- 프로젝트 이력 정보
	function project_status_history_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "prosh.reg_date desc";
		if ($del_type == 1) $where = "prosh.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(prosh.prosh_idx)
			from
				project_status_history prosh
				left join project_info pro on pro.del_yn = 'N' and pro.pro_idx = prosh.pro_idx
				left join member_info mem on mem.del_yn = 'N' and mem.mem_idx = prosh.mem_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				prosh.*
				, mem.mem_name, mem.mem_id, mem.del_yn as mem_del_yn
				, pro.subject, pro.open_yn, pro.charge_idx, pro.apply_idx, pro.reg_id as project_reg_id
				, pro.project_code, c1.code_name menu1, c2.code_name menu2
			from
				project_status_history prosh
				left join project_info pro on pro.del_yn = 'N' and pro.pro_idx = prosh.pro_idx
				left join member_info mem on mem.del_yn = 'N' and mem.mem_idx = prosh.mem_idx
				left join code_project_class c1 on pro.menu1_code = c1.code_idx
				left join code_project_class c2 on pro.menu2_code = c2.code_idx
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////
/////
///// 업무관련 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------- 지사별 업무분류
	function code_work_class_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "code.sort asc";
		if ($del_type == 1) $where = "code.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(code.code_idx)
			from
				code_work_class code
				left join company_part part on part.del_yn = 'N' and part.part_idx = code.part_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				code.*
				, part.part_name
			from
				code_work_class code
				left join company_part part on part.del_yn = 'N' and part.part_idx = code.part_idx
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}

//-------------------------------------- 지사별 업무상태
	function code_work_status_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "code.sort asc";
		if ($del_type == 1) $where = "code.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(code.code_idx)
			from
				code_work_status code
				left join company_part part on part.del_yn = 'N' and part.part_idx = code.part_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				code.*
				, part.part_name
			from
				code_work_status code
				left join company_part part on part.del_yn = 'N' and part.part_idx = code.part_idx
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}




//-------------------------------------- 업무정보
	function work_info_data_mobile($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		$code_comp = $_SESSION[$sess_str . '_comp_idx'];
		$code_part = search_company_part($code_part);
		$code_mem  = $_SESSION[$sess_str . '_mem_idx'];
		
		if ($orderby == '') $orderby = "wi.reg_date desc";
		if ($del_type == 1) $where = "wi.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(wi.wi_idx)
			from
				work_info wi
				left join member_info mem on mem.mem_idx = wi.mem_idx
				left join code_work_status ws on ws.del_yn = 'N' and ws.comp_idx = wi.comp_idx and ws.part_idx = wi.part_idx and ws.code_value = wi.work_status
				left join member_info reg on reg.mem_idx = wi.reg_id
				left join member_info app on app.mem_idx = wi.apply_idx
				left join company_part part on part.del_yn = 'N' and part.comp_idx = reg.comp_idx and part.part_idx = reg.part_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				wi.*
				, mem.mem_name, mem.mem_id, mem.del_yn as mem_del_yn
				, ws.code_name as work_status_str, ws.code_bold as work_status_bold, ws.code_color as work_status_color
				, reg.mem_name as reg_name, reg.del_yn as reg_del_yn
				, app.mem_name as apply_name, app.del_yn as app_del_yn
				, part.sort as part_sort, part.part_name, part.part_idx as mem_part_idx
				, ifnull(woi.wf_cnt, 0) as wf_cnt, ifnull(woi.wr_cnt, 0) as wr_cnt, ifnull(woi.wc_cnt, 0) as wc_cnt
			from
				work_info wi
				left join member_info mem on mem.mem_idx = wi.mem_idx
				left join code_work_status ws on ws.del_yn = 'N' and ws.comp_idx = wi.comp_idx and ws.part_idx = wi.part_idx and ws.code_value = wi.work_status
				left join member_info reg on reg.mem_idx = wi.reg_id
				left join member_info app on app.mem_idx = wi.apply_idx
				left join company_part part on part.del_yn = 'N' and part.comp_idx = reg.comp_idx and part.part_idx = reg.part_idx
				left join (
                	select wi_idx, sum(wf_cnt) as wf_cnt, sum(wr_cnt) wr_cnt, sum(wc_cnt) wc_cnt from (
                    	select wi.wi_idx, count(*) as wf_cnt, 0 as wr_cnt, 0 as wc_cnt
                        from work_info wi join work_file wf on wi.wi_idx = wf.wi_idx
                        where " . $where . "
                        and wf.del_yn = 'N'
                        group by wi.wi_idx
                        union all
                        select wi.wi_idx, 0 as wf_cnt, count(*) as wr_cnt, 0 as wc_cnt
                        from work_info wi join work_report wr on wi.wi_idx = wr.wi_idx
                        where " . $where . "
                        and wr.del_yn = 'N'
                        group by wi.wi_idx
                        union all
                        select wi.wi_idx, 0 as wc_cnt, 0 as wr_cnt, count(*) as wc_cnt
                        from work_info wi join work_comment wc on wi.wi_idx = wc.wi_idx
                        where " . $where . "
                        and wc.del_yn = 'N'
                        group by wi.wi_idx
                    ) t1
                    group by wi_idx
				) woi on woi.wi_idx = wi.wi_idx
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}




//-------------------------------------- 업무정보
	function work_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "wi.reg_date desc";
		if ($del_type == 1) $where = "wi.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(wi.wi_idx)
			from
				work_info wi
				left join member_info mem on mem.mem_idx = wi.mem_idx
				left join code_work_status ws on ws.del_yn = 'N' and ws.comp_idx = wi.comp_idx and ws.part_idx = wi.part_idx and ws.code_value = wi.work_status
				left join member_info reg on reg.mem_idx = wi.reg_id
				left join member_info app on app.mem_idx = wi.apply_idx
				left join company_part part on part.del_yn = 'N' and part.comp_idx = reg.comp_idx and part.part_idx = reg.part_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				wi.*
				, mem.mem_name, mem.mem_id, mem.del_yn as mem_del_yn
				, ws.code_name as work_status_str, ws.code_bold as work_status_bold, ws.code_color as work_status_color
				, reg.mem_name as reg_name, reg.del_yn as reg_del_yn
				, app.mem_name as apply_name, app.del_yn as app_del_yn
				, part.sort as part_sort, part.part_name, part.part_idx as mem_part_idx
				, pi.project_code, c1.code_name menu1, c2.code_name menu2
			from
				work_info wi
				left join member_info mem on mem.mem_idx = wi.mem_idx
				left join code_work_status ws on ws.del_yn = 'N' and ws.comp_idx = wi.comp_idx and ws.part_idx = wi.part_idx and ws.code_value = wi.work_status
				left join member_info reg on reg.mem_idx = wi.reg_id
				left join member_info app on app.mem_idx = wi.apply_idx
				left join company_part part on part.del_yn = 'N' and part.comp_idx = reg.comp_idx and part.part_idx = reg.part_idx
				left join project_info pi on pi.pro_idx = wi.pro_idx and pi.del_yn='N'
				left join code_project_class c1 on c1.code_idx = pi.menu1_code
				left join code_project_class c2 on c2.code_idx = pi.menu2_code
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}
	
//-------------------------------------- 업무정보
    function work_list_info($query_type, $where = '', $file_where = '', $mem_idx = 0, $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
    {
        if ($orderby == '') $orderby = "wi.reg_date desc";
        if ($del_type == 1) $where = "wi.del_yn = 'N'" . $where;
        else $where = "1" . $where;

        $query_page = "
            select
                count(wi.wi_idx)
            from
                work_info wi
                left join member_info mem on mem.mem_idx = wi.mem_idx
                left join code_work_status ws on ws.del_yn = 'N' and ws.comp_idx = wi.comp_idx and ws.part_idx = wi.part_idx and ws.code_value = wi.work_status
                left join member_info reg on reg.mem_idx = wi.reg_id
                left join member_info app on app.mem_idx = wi.apply_idx
                left join company_part part on part.del_yn = 'N' and part.comp_idx = reg.comp_idx and part.part_idx = reg.part_idx
            where
                " . $where . "
        ";
        //echo "<pre>" . $query_page . "</pre><br />";
        $query_string = "
           select    
           m.mem_idx charge_mem_idx, cp.part_name charge_part_name, cp.sort charge_part_sort, m.part_idx charge_part_idx, csg.group_name charge_group_name, m.comp_idx charge_comp_idx, m.del_yn charge_del_yn, m.mem_name charge_mem_name, cpd.duty_name charge_duty_name
           , t.apply_idx apply_mem_idx, cp2.part_name apply_part_name, cp2.sort apply_part_sort, t.app_part_idx apply_part_idx, csg2.group_name apply_group_name, t.app_comp_idx apply_comp_idx
           , t.reg_id reg_mem_idx, cp3.part_name reg_part_name, cp3.sort reg_part_sort, cp3.part_idx reg_part_idx, csg3.group_name reg_group_name           
           , t.* from (
           select
            case when instr(wi.charge_idx, ',') > 0 then
                substr(wi.charge_idx, 1, instr(wi.charge_idx, ',')-1)
            else 
                wi.charge_idx
            end first_charge_idx,
                wi.*
                , mem.mem_name, mem.mem_id, mem.del_yn as mem_del_yn
                , ws.code_name as work_status_str, ws.code_bold as work_status_bold, ws.code_color as work_status_color
                , reg.comp_idx reg_comp_idx, reg.part_idx reg_part_idx, reg.csg_idx reg_csg_idx, reg.cpd_idx reg_cpd_idx, reg.mem_name as reg_mem_name, reg.del_yn as reg_del_yn
                , app.comp_idx app_comp_idx, app.part_idx app_part_idx, app.csg_idx app_csg_idx, app.cpd_idx app_cpd_idx, app.mem_name as apply_mem_name, app.del_yn as apply_del_yn
                , part.sort as part_sort, part.part_name, part.part_idx as mem_part_idx
                , pi.project_code, c1.code_name menu1, c2.code_name menu2
                , ifnull(file.file_cnt, 0) file_cnt, ifnull(wr.report_cnt, 0) report_cnt, ifnull(wc.comment_cnt, 0) comment_cnt
                , datediff(wi.deadline_date, date_format(now(), '%Y-%m-%d')) diff_date
                , ifnull(wr2.wr_cnt, 0) wr_cnt
                , ifnull(wc2.wc_cnt, 0) wc_cnt
            from
                work_info wi
                left join member_info mem on mem.mem_idx = wi.mem_idx
                left join code_work_status ws on ws.del_yn = 'N' and ws.comp_idx = wi.comp_idx and ws.part_idx = wi.part_idx and ws.code_value = wi.work_status
                left join member_info reg on reg.mem_idx = wi.reg_id
                left join member_info app on app.mem_idx = wi.apply_idx
                left join company_part part on part.del_yn = 'N' and part.comp_idx = reg.comp_idx and part.part_idx = reg.part_idx
                left join project_info pi on pi.pro_idx = wi.pro_idx and pi.del_yn='N'
                left join code_project_class c1 on c1.code_idx = pi.menu1_code
                left join code_project_class c2 on c2.code_idx = pi.menu2_code
                left join (select wi_idx, count(*) file_cnt from work_file where del_yn='N' " . $file_where . " group by wi_idx) file on wi.wi_idx=file.wi_idx
                left join (select wi_idx, count(*) report_cnt from work_report where del_yn='N' " . $file_where . " group by wi_idx) wr on wi.wi_idx=wr.wi_idx
                left join (select wi_idx, count(*) comment_cnt from work_comment where del_yn='N' " . $file_where . " group by wi_idx) wc on wi.wi_idx=wc.wi_idx
                left join (select wi_idx, count(*) wr_cnt from work_report wr where del_yn='N' " . $file_where . " and mem_idx != '" . $mem_idx . "' and not exists (select '1' from work_check where wi_idx=wr.wi_idx and wr_idx=wr.wr_idx and mem_idx='" . $mem_idx . "') group by wi_idx) wr2 on wr2.wi_idx=wi.wi_idx
                left join (select wi_idx, count(*) wc_cnt from work_comment wc where del_yn='N' " . $file_where . " and mem_idx != '" . $mem_idx . "' and not exists (select '1' from work_check where wi_idx=wc.wi_idx and wc_idx=wc.wc_idx and mem_idx='" . $mem_idx . "') group by wi_idx) wc2 on wc2.wi_idx=wi.wi_idx 
            where
                " . $where . "
            order by
                " . $orderby . ") t
            left join member_info m on t.first_charge_idx=m.mem_idx
            left join company_part cp on cp.part_idx=m.part_idx and cp.comp_idx=m.comp_idx and cp.del_yn='N'
            left join company_staff_group csg on csg.comp_idx=m.comp_idx and csg.csg_idx=m.csg_idx and csg.del_yn='N'
            left join company_part_duty cpd on cpd.del_yn = 'N' and cpd.comp_idx = m.comp_idx and cpd.cpd_idx = m.cpd_idx
            left join company_part cp2 on cp2.part_idx=t.app_part_idx and cp2.comp_idx=t.app_comp_idx and cp2.del_yn='N'
            left join company_staff_group csg2 on csg2.comp_idx=t.app_comp_idx and csg2.csg_idx=t.app_csg_idx and csg2.del_yn='N'
            left join company_part_duty cpd2 on cpd2.del_yn='N' and cpd2.comp_idx=t.app_comp_idx and cpd2.cpd_idx=t.app_cpd_idx
            left join company_part cp3 on cp3.part_idx=t.reg_part_idx and cp3.comp_idx=t.reg_comp_idx and cp3.del_yn='N'
            left join company_staff_group csg3 on csg3.comp_idx=t.reg_comp_idx and csg3.csg_idx=t.reg_csg_idx and csg3.del_yn='N'
            left join company_part_duty cpd3 on cpd3.del_yn='N' and cpd3.comp_idx=t.reg_comp_idx and cpd3.cpd_idx=t.reg_cpd_idx
        ";
        //echo "<pre>" . $query_string . "</pre><br />";

        if ($query_type == 'view') $data_info = query_view($query_string);
        else if ($query_type == 'page') $data_info = query_page($query_page);
        else
        {
            $data_sql['query_page']   = $query_page;
            $data_sql['query_string'] = $query_string;
            $data_sql['page_size']    = $page_size;
            $data_sql['page_num']     = $page_num;

            $data_info = query_list($data_sql);
        }

        Return $data_info;
    }

//-------------------------------------- 업무파일
	function work_file_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "wf.sort asc";
		if ($del_type == 1) $where = "wf.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(wf.wf_idx)
			from
				work_file wf
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				wf.*
			from
				work_file wf
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}

//-------------------------------------- 업무 읽기여부
	function work_read_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "wre.reg_date desc";
		if ($del_type == 1) $where = "wre.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(wre.wre_idx)
			from
				work_read wre
				left join member_info mem on mem.del_yn = 'N' and mem.comp_idx = wre.comp_idx and mem.mem_idx = wre.mem_idx
				left join work_info wi on wi.del_yn = 'N' and wi.comp_idx = wre.comp_idx and wi.wi_idx = wre.wi_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				wre.*
				, mem.mem_name as read_name
			from
				work_read wre
				left join member_info mem on mem.del_yn = 'N' and mem.comp_idx = wre.comp_idx and mem.mem_idx = wre.mem_idx
				left join work_info wi on wi.del_yn = 'N' and wi.comp_idx = wre.comp_idx and wi.wi_idx = wre.wi_idx
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}

//-------------------------------------- 업무코멘트
	function work_comment_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "wc.order_idx desc";
		if ($del_type == 1) $where = "wc.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(wc.wc_idx)
			from
				work_comment wc
				left join work_info wi on wi.del_yn = 'N' and wi.wi_idx = wc.wi_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				wc.*
			from
				work_comment wc
				left join work_info wi on wi.del_yn = 'N' and wi.wi_idx = wc.wi_idx
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}

//-------------------------------------- 업무보고서
	function work_report_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "wr.reg_date desc";
		if ($del_type == 1) $where = "wr.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(wr.wr_idx)
			from
				 work_report wr
				 left join work_info wi on wi.del_yn = 'N' and wi.wi_idx = wr.wi_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				wr.*
			from
				 work_report wr
				 left join work_info wi on wi.del_yn = 'N' and wi.wi_idx = wr.wi_idx
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}

//-------------------------------------- 업무보고서 파일
	function work_report_file_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "wrf.sort asc";
		if ($del_type == 1) $where = "wrf.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(wrf.wrf_idx)
			from
				work_report_file wrf
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				wrf.*
			from
				work_report_file wrf
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}

//-------------------------------------- 업무 상태내역
	function work_status_history_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "wsh.reg_date desc";
		if ($del_type == 1) $where = "wsh.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(wsh.wsh_idx)
			from
				work_status_history wsh
				left join work_info wi on wi.del_yn = 'N' and wi.wi_idx = wsh.wi_idx
				left join member_info mem on mem.comp_idx = wsh.comp_idx and mem.mem_idx = wsh.mem_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
		select 
		cp3.part_name reg_part_name, cp3.sort reg_part_sort, cp3.part_idx reg_part_idx, csg3.group_name reg_group_name
		, t.* from (
			select
				wsh.*
				, mem.mem_name, mem.mem_id, mem.del_yn as mem_del_yn
				, wi.work_type, wi.subject, wi.open_yn, wi.charge_idx, wi.important, wi.apply_yn, wi.apply_idx
				, wi.reg_id reg_mem_idx
				, reg.comp_idx reg_comp_idx, reg.part_idx reg_part_idx, reg.csg_idx reg_csg_idx, reg.cpd_idx reg_cpd_idx, reg.mem_name as reg_mem_name, reg.del_yn as reg_del_yn				       
				, pi.project_code, c1.code_name menu1, c2.code_name menu2
			from
				work_status_history wsh
				left join work_info wi on wi.del_yn = 'N' and wi.wi_idx = wsh.wi_idx
				left join member_info mem on mem.comp_idx = wsh.comp_idx and mem.mem_idx = wsh.mem_idx
				left join member_info reg on reg.mem_idx = wi.reg_id
				left join project_info pi on pi.pro_idx = wi.pro_idx
				left join code_project_class c1 on c1.code_idx = pi.menu1_code
				left join code_project_class c2 on c2.code_idx = pi.menu2_code
			where
				" . $where . "
			order by
				" . $orderby . " ) t				
                left join company_part cp3 on cp3.part_idx=t.reg_part_idx and cp3.comp_idx=t.reg_comp_idx and cp3.del_yn='N'
                left join company_staff_group csg3 on csg3.comp_idx=t.reg_comp_idx and csg3.csg_idx=t.reg_csg_idx and csg3.del_yn='N'
                left join company_part_duty cpd3 on cpd3.del_yn='N' and cpd3.comp_idx=t.reg_comp_idx and cpd3.cpd_idx=t.reg_cpd_idx
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}

//-------------------------------------- 기한-날짜표현
	function deadline_date()
	{
		global $set_week, $set_work_deadline_txt;

		$where = " and wi.wi_idx = '" . $idx . "'";
		$data = work_info_data('view', $where);

		$deadline_date1 = $data['deadline_date'];
		$deadline_date2 = $data['deadline_date'];
		$deadline_str1  = $data['deadline_date'];
		$deadline_str2  = $data['deadline_date'];

		$today_time1 = time();
		$today_time2 = $today_time1 + (1 * 24 * 60 * 60);
		$today_time3 = $today_time1 + (2 * 24 * 60 * 60);
		$today_time4 = $today_time1 + (3 * 24 * 60 * 60);
		$today_time5 = $today_time1 + (4 * 24 * 60 * 60);

		$date_view['date'][1] = date('Y-m-d', $today_time1);
		$date_view['date'][2] = date('Y-m-d', $today_time2);
		$date_view['date'][3] = date('Y-m-d', $today_time3);
		$date_view['date'][4] = date('Y-m-d', $today_time4);
		$date_view['date'][5] = date('Y-m-d', $today_time5);

		$today_week_num1 = date('w', $today_time1); if ($today_week_num1 == 0) $today_week_num1 = 7;
		$today_week_num2 = date('w', $today_time2); if ($today_week_num2 == 0) $today_week_num2 = 7;
		$today_week_num3 = date('w', $today_time3); if ($today_week_num3 == 0) $today_week_num3 = 7;
		$today_week_num4 = date('w', $today_time4); if ($today_week_num4 == 0) $today_week_num4 = 7;
		$today_week_num5 = date('w', $today_time5); if ($today_week_num5 == 0) $today_week_num5 = 7;

		$date_view['week'][1] = '(오늘)';
		$date_view['week'][2] = '(내일)';
		$date_view['week'][3] = '(' . $set_week[$today_week_num3] . ')';
		$date_view['week'][4] = '(' . $set_week[$today_week_num4] . ')';
		$date_view['week'][5] = '(' . $set_week[$today_week_num5] . ')';

		Return $date_view;
	}

//-------------------------------------- 업무확인
	function work_check_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "wch.reg_date desc";
		if ($del_type == 1) $where = "wch.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(wch.wch_idx)
			from
				work_check wch
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				wch.*
			from
				work_check wch
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}

//-------------------------------------- 업무보고, 코멘트 읽은 확인
	function work_report_comment_check($wi_idx, $wr_idx, $wc_idx)
	{
		global $_SESSION, $sess_str;

		$code_comp = $_SESSION[$sess_str . '_comp_idx'];
		$code_part = search_company_part($code_part);
		$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

		if ($wr_idx != '')
		{
			$sub_where = " and wr.wr_idx = '" . $wr_idx . "'";
			$sub_data = work_report_data('view', $sub_where);

			$check_where = " and wch.wi_idx = '" . $wi_idx . "' and wch.wr_idx = '" . $wr_idx . "' and wch.mem_idx = '" . $code_mem . "'";
			$check_data = work_check_data('view', $check_where);
		}
		else if ($wc_idx != '')
		{
			$sub_where = " and wc.wc_idx = '" . $wc_idx . "'";
			$sub_data = work_comment_data('view', $sub_where);

			$check_where = " and wch.wi_idx = '" . $wi_idx . "' and wch.wc_idx = '" . $wc_idx . "' and wch.mem_idx = '" . $code_mem . "'";
			$check_data = work_check_data('view', $check_where);
		}

		if ($code_mem != $sub_data['mem_idx'])
		{
			if ($check_data['total_num'] == 0)
			{
				$insert_query = "
					insert into work_check set
						  comp_idx  = '" . $code_comp . "'
						, part_idx  = '" . $code_part . "'
						, wi_idx    = '" . $wi_idx . "'
						, wr_idx    = '" . $wr_idx . "'
						, wc_idx    = '" . $wc_idx . "'
						, mem_idx   = '" . $code_mem . "'
						, read_date = '" . date('Y-m-d H:i:s') . "'
						, reg_id    = '" . $code_mem . "'
						, reg_date  = '" . date('Y-m-d H:i:s') . "'
				";
				db_query($insert_query);
				query_history($insert_query, 'work_check', 'insert');
			}
		}
	}


//-------------------------------------- 업무보고, 코멘트 읽을 값
/*
    function work_read_check($wi_idx)
    {
        global $_SESSION, $sess_str;

        $code_comp = $_SESSION[$sess_str . '_comp_idx'];
        $code_part = search_company_part($code_part);
        $code_mem  = $_SESSION[$sess_str . '_mem_idx'];

        $work_check = 0; $work_no = 0; $report_no = 0; $comment_no = 0;

        if ($wi_idx == '') // 총값
        {
        // 업무
            $work_where = "
                and wi.comp_idx = '" . $code_comp . "' and wi.mem_idx != '" . $code_mem . "'
                and (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "')";

        // 업무보고
            $report_where = "
                and wr.comp_idx = '" . $code_comp . "' and wr.mem_idx != '" . $code_mem . "' and wi.del_yn = 'N'
                and (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "')";

        // 댓글
            $comment_where = "
                and wc.comp_idx = '" . $code_comp . "' and wc.mem_idx != '" . $code_mem . "' and wi.del_yn = 'N'
                and (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "')";
        }
        else
        {
        // 업무
            $work_where = "
                and wi.wi_idx = '" . $wi_idx . "' and wi.mem_idx != '" . $code_mem . "'
                and (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "')
            ";

        // 업무보고
            $report_where = "
                and wr.wi_idx = '" . $wi_idx . "' and wr.mem_idx != '" . $code_mem . "'
                and (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "')
            ";

        // 댓글
            $comment_where = "
                and wc.wi_idx = '" . $wi_idx . "' and wc.mem_idx != '" . $code_mem . "'
                and (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "')
            ";
        }

    // 업무
        $work_list = work_info_data('list', $work_where, '', '', '');
        foreach ($work_list as $work_k => $data)
        {
            if (is_array($data))
            {
                $reg_date = date_replace($data['reg_date'], 'Ymd');
                if ($reg_date >= '20130520') // 기준으로
                {
                    $check_where = " and wre.wi_idx = '" . $data['wi_idx'] . "' and wre.mem_idx = '" . $code_mem . "'";
                    $check_data = work_read_data('view', $check_where);
                    if ($check_data['total_num'] == 0) // 값일 없을 경우
                    {
                        $wi_idx = $data['wi_idx'];
                        $wi_chk[$wi_idx] = $wi_idx;
                        $work_no++;
                    }
                }
            }
        }

    // 업무보고
        $report_list = work_report_data('list', $report_where, '', '', '');
        foreach ($report_list as $report_k => $data)
        {
            if (is_array($data))
            {
                $check_where = " and wch.wi_idx = '" . $data['wi_idx'] . "' and wch.wr_idx = '" . $data['wr_idx'] . "' and wch.mem_idx = '" . $code_mem . "'";
                $check_data = work_check_data('view', $check_where);
                if ($check_data['total_num'] == 0)
                {
                    $wi_idx = $data['wi_idx'];
                    $wi_chk[$wi_idx] = $wi_idx;
                    $report_no++;
                }
            }
        }

    // 댓글
        $comment_list = work_comment_data('list', $comment_where, '', '', '');
        foreach ($comment_list as $comment_k => $data)
        {
            if (is_array($data))
            {
                $check_where = " and wch.wi_idx = '" . $data['wi_idx'] . "' and wch.wc_idx = '" . $data['wc_idx'] . "' and wch.mem_idx = '" . $code_mem . "'";
                $check_data = work_check_data('view', $check_where);
                if ($check_data['total_num'] == 0)
                {
                    $wi_idx = $data['wi_idx'];
                    $wi_chk[$wi_idx] = $wi_idx;
                    $comment_no++;
                }
            }
        }

        $work_check = $report_no + $comment_no;

        if ($work_check > 0)
        {
            $chk_num = 1;
            $add_where = " and (";
            foreach ($wi_chk as $k => $v)
            {
                if ($chk_num == 1)
                {
                    $add_where .= " wi.wi_idx = '" . $v . "'";
                }
                else
                {
                    $add_where .= " or wi.wi_idx = '" . $v . "'";
                }
                $chk_num++;
            }
            $add_where .= ')';
        }
        else
        {
            $add_where = " and 1 != 1";
        }
        //echo 'work_no -> ', $work_no, '<Br />';

        $str['work_num']     = $work_no;
        $str['work_report']  = $report_no;
        $str['work_comment'] = $comment_no;
        $str['work_check']   = $work_check;
        $str['work_where']   = $add_where;

        return $str;
    }
*/
    function work_read_check($wi_idx)
    {
        global $sess_str;

        $code_comp = $_SESSION[$sess_str . '_comp_idx'];
        $code_part = search_company_part($code_part);
        $code_mem  = $_SESSION[$sess_str . '_mem_idx'];

        $work_check = 0; $work_no = 0; $report_no = 0; $comment_no = 0;

        if ($wi_idx == '') // 총값
        {
        // 업무
            $work_where = "
                and wi.comp_idx = '" . $code_comp . "' and wi.mem_idx != '" . $code_mem . "'
                and (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "')";

        }
        else
        {
        // 업무
            $work_where = "
                and wi.wi_idx = '" . $wi_idx . "' and wi.mem_idx != '" . $code_mem . "'
                and (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "')
            ";
        }
        
        $sql = "
        select
            wi.wi_idx
            , (select count(*) from work_info t where del_yn='N' and wi_idx=wi.wi_idx and mem_idx != '" .$code_mem . "' and not exists(select '1' from work_read where wi_idx=t.wi_idx and mem_idx='" . $code_mem . "')) work_no
            , (select count(*) from work_report wr where del_yn='N' and wr.wi_idx=wi.wi_idx and mem_idx != '" .$code_mem . "' and not exists(select '1' from work_check where wi_idx=wi.wi_idx and wr_idx=wr.wr_idx and mem_idx='" . $code_mem . "')) report_no
            , (select count(*) from work_comment wc where del_yn='N' and wc.wi_idx=wi.wi_idx and mem_idx != '" .$code_mem . "' and not exists(select '1' from work_check where wi_idx=wi.wi_idx and wc_idx=wc.wc_idx and mem_idx='" . $code_mem . "')) comment_no
        from
            work_info wi
        where wi.del_yn = 'N'       
        " . $work_where;        

        //echo $sql . '<br>';
        $datas = db_query($sql);
        
        if ($datas) {
            while($item = query_fetch_array($datas)) {
                //print_r($item);
                //echo '<br>';
                if ($item['report_no'] > 0 || $item['comment_no'] > 0) {
                    $wi_idx = $item['wi_idx'];
                    $wi_chk[$wi_idx] = $wi_idx;
                }
                
                $work_no += $item['work_no'];           // 업무
                $report_no += $item['report_no'];       // 업무보고
                $comment_no += $item['comment_no'];     // 댓글
 
            }
        }
    
        $work_check = $report_no + $comment_no;

        if ($work_check > 0)
        {
            //echo 'wi_idx => ';
            //print_r($wi_chk);
            $chk_num = 1;
            $add_where = " and wi.wi_idx in (" . join(",", $wi_chk) . ")";
        }
        else
        {
            $add_where = " and 1 != 1";
        }
        //echo 'add_where  : ' . $add_where . '</br>';
        //echo 'work_no -> ', $work_no, '<Br />';
        

        $str['work_num']     = $work_no;
        $str['work_report']  = $report_no;
        $str['work_comment'] = $comment_no;
        $str['work_check']   = $work_check;
        $str['work_where']   = $add_where;

        return $str;
    }
 
//-------------------------------------- 업무관련정보
	function work_list_data($data, $wi_idx, $mode = 'view')
	{
		global $local_dir, $_SESSION, $sess_str, $btn_view, $set_part_work_yn, $set_color_list2;

		$chk_comp  = $_SESSION[$sess_str . '_comp_idx'];
		$chk_part  = $_SESSION[$sess_str . '_part_idx'];
		$chk_mem   = $_SESSION[$sess_str . '_mem_idx'];
		$chk_level = $_SESSION[$sess_str . '_ubstory_level'];

		$work_type     = $data['work_type'];
		$subject       = $data['subject'];
		$charge_idx    = $data['charge_idx'];
		$charge_arr    = explode(',', $charge_idx);
		$apply_idx     = $data['apply_idx'];
		$deadline_date = $data['deadline_date'];
		$end_date      = $data['end_date'];
		$mem_del_yn    = $data['mem_del_yn'];
		$mem_name      = $data['mem_name'];
		$work_status   = $data['work_status'];
		$work_status_str   = $data['work_status_str'];
		$work_status_bold  = $data['work_status_bold'];
		$work_status_color = $data['work_status_color'];

		$display_type = $data['display_type']; // 이력일 경우 이 값을 가지고 구분을 한다.
		$reg_id = $data['reg_mem_idx'];
        /*
		if ($display_type == 'display_main')
		{
			$reg_id = $data['work_reg_id'];
		}
		else
		{
			$reg_id = $data['reg_id'];
		}
        */
		// 지사
		$part_ok = 'N';
		if ($set_part_work_yn == 'Y')
		{
			$part_where = " and part.comp_idx = '" . $chk_comp . "'";
			$part_data = company_part_data('page', $part_where);

			if ($part_data['total_num'] > 1)
			{
				$part_ok = 'Y';
			}
			unset($part_data);
		}

	// 기한일
		$deadline_date1 = date_replace($deadline_date, 'Y.m.d');
		$deadline_week  = date_replace($deadline_date, 'w');
		if ($deadline_week != '')
		{
			if ($deadline_date1 == date('Y.m.d')) // 오늘과 기한일이 같을 경우
			{
				$deadline_date_str = '<span style="color:#FF0000;">' . $deadline_date1 . '(' . $deadline_week . ')</span>';
			}
			else
			{
				$deadline_date_str = $deadline_date1 . '(' . $deadline_week . ')';
			}
		}
		if ($deadline_date_str == '') $deadline_date_str = '미정';
		$data['deadline_date_str'] = $deadline_date_str;
	// 상태/완료일
		if ($work_status == 'WS90' || $work_status == 'WS99') // 완료, 종료
		{
			$end_date1 = date_replace($end_date, 'Y.m.d');
			if ($end_date1 != '')
			{
				$end_week     = date_replace($end_date, 'w');
				$end_date_str = '<span class="num">' . $end_date1 . '(' . $end_week . ')</span>';
			}
			else
			{
				$end_date_str = '';
			}
			$status_view = '';
			$diff_day    = 0;

			$status_str = '<span style="';
			if ($work_status_bold == 'Y') $status_str .= 'font-weight:900;';
			if ($work_status_color != '') $status_str .= 'color:' . $work_status_color . ';';
			$status_str .= '">' . $work_status_str . '</span>';

			$project_status = $status_str . '(<span style="font-weight:900;color:#0075c8;">' . $end_date_str . '</span>)';
		}
		else
		{

			if ($work_status == 'WS20' && $apply_idx == $chk_mem) // 승인대기 - 승인자
			{
				$status_str = '<span style="';
				if ($work_status_bold == 'Y') $status_str .= 'font-weight:900;';
				if ($work_status_color != '') $status_str .= 'color:' . $work_status_color . ';';
				$status_str .= '">승인요청</span>';
			}
			else if ($work_status == 'WS30' && $reg_id == $chk_mem) // 요청대기 - 등록자
			{
				$status_str = '<span style="';
				if ($work_status_bold == 'Y') $status_str .= 'font-weight:900;';
				if ($work_status_color != '') $status_str .= 'color:' . $work_status_color . ';';
				$status_str .= '">요청완료</span>';
			}
			else
			{
				$status_str = '<span style="';
				if ($work_status_bold == 'Y') $status_str .= 'font-weight:900;';
				if ($work_status_color != '') $status_str .= 'color:' . $work_status_color . ';';
				$status_str .= '">' . $work_status_str . '</span>';
			}
			$end_date_str = $status_str;
			$status_view  = $status_str;

		// 기한이 지나면 지연으로 표시함 - 승인대기(WS20), 요청대기(WS30), 대기(WS01), 취소(WS60), 반려(WS70), 보류(WS80)
		    /*
			$today_date = date('Ymd');
			$chk_date   = date_replace($deadline_date, 'Ymd');
			$diff_day = 0;
			if ($chk_date < $today_date)
			{
				$date_query = "select datediff('" . $chk_date . "', '" . $today_date . "') as diff_date";
				$date_data = query_view($date_query);
				$diff_day = $date_data['diff_date'];
			}
            */
            $diff_day = $data['diff_date'];

			if ($diff_day < 0) // 지난경우
			{
			    /*
				if ($work_status == 'WS20' || $work_status == 'WS30') // 승인대기, 요청대기
				{
					$end_date_str = $status_str . ' ' . $diff_day . '일';
				}
				else if ($work_status == 'WS70') // 반려
				{
					$end_date_str = $status_str . '(<span style="color:#FF0000; font-weight:700;">지연</span> ' . $diff_day . '일)';
				}
				else if ($work_status != 'WS01' && $work_status != 'WS60' && $work_status != 'WS80') // 대기, 취소, 보류 아닐경우
				{
					$end_date_str = $status_str . '(<span style="color:#FF0000; font-weight:700;">지연</span> ' . $diff_day . '일)';
				}
                */
                $end_date_str = $status_str . '(<span style="color:#FF0000; font-weight:700;">지연</span>' . $diff_day . '일)';
			}

			$project_status = $end_date_str;
		}
		$data['end_date_str']   = $end_date_str;
		$data['status_view']    = $status_view;
		$data['diff_day']       = $diff_day;
		$data['status_title']   = $status_str;
		$data['project_status'] = '[' . $project_status . ']';

	// 자기 업무일 경우 표시
		$chk_charge = ',' . $charge_idx . ',';
		$chk_member = ',' . $chk_mem . ',';
		if ($reg_id == $chk_mem || $apply_idx == $chk_mem || (strlen(stristr($chk_charge, $chk_member)) > 0))
		{
			if ($work_type == 'WT01')
			{
				$work_str = '<img src="' . $local_dir . '/bizstory/images/icon/work_icon1.gif" alt="본인" />';
				$work_txt = '본인';
			}
			else if ($work_type == 'WT02')
			{
				$work_str = '<img src="' . $local_dir . '/bizstory/images/icon/work_icon3.gif" alt="요청" />';
				$work_txt = '요청';
			}
			else if ($work_type == 'WT03')
			{
				$work_str = '<img src="' . $local_dir . '/bizstory/images/icon/work_icon2.gif" alt="승인" />';
				$work_txt = '승인';
			}
			else if ($work_type == 'WT04')
			{
				$work_str = '<img src="' . $local_dir . '/bizstory/images/icon/work_icon5.gif" alt="알림" />';
				$work_txt = '알림';
			}
		}
		else
		{
			$work_str = '<img src="' . $local_dir . '/bizstory/images/icon/work_icon4.gif" alt="무관" />';
			$work_txt = '무관';

			if ($work_type == 'WT01')
			{
				$work_str .= ' <img src="' . $local_dir . '/bizstory/images/icon/work_icon1.gif" alt="본인" />';
				$work_txt = '본인';
			}
			else if ($work_type == 'WT02')
			{
				$work_str .= ' <img src="' . $local_dir . '/bizstory/images/icon/work_icon3.gif" alt="요청" />';
				$work_txt = '요청';
			}
			else if ($work_type == 'WT03')
			{
				
				$work_str .= ' <img src="' . $local_dir . '/bizstory/images/icon/work_icon2.gif" alt="승인" />';
				$work_txt = '승인';
			}
			else if ($work_type == 'WT04')
			{
				$work_str .= ' <img src="' . $local_dir . '/bizstory/images/icon/work_icon5.gif" alt="알림" />';
				$work_txt = '알림';
			}
		}
		$data['work_img'] = $work_str;
		$data['work_txt'] = $work_txt;

	// 담당자
		if ($charge_idx != '')
		{
			$charge_len = count($charge_arr);
			$charge_exp = $charge_len - 1;

			$charge_str = staff_layer_data($data, 'charge', '', $set_part_work_yn, $set_color_list2, 'workliststtaff', $data['wi_idx'], '');
			if ($charge_len > 1)
			{
				$charge_str .= '외 ' . $charge_exp . '명';
			}
            
            if ($data['charge_mem_idx'] != '') {
                $charge_str = '(' . $data['charge_part_name'] . ' : ' . $charge_str . ')';
            }
            /*
            $mem_where = " and mem.mem_idx = '" . $charge_arr[0] . "'";
            $mem_data = member_info_data('view', $mem_where);
    
            if ($mem_data['total_num'] > 0)
            {
                $charge_str = '(' . $mem_data['part_name'] . ' : ' . $charge_str . ')';
            }
             * */
		// 총담당자구하기
            $total_charge_str = '';
            if ($mode == 'view') {
    			foreach ($charge_arr as $charge_k => $charge_v)
    			{
    				if ($charge_v != '')
    				{
    					$total_charge_str .= ', ' . staff_layer_form($charge_v, '', $set_part_work_yn, $set_color_list2, 'workviewstaff', $data['wi_idx'], 'default');
    				}
    			}
    			$total_charge_str = substr($total_charge_str, 2, strlen($total_charge_str));
            }         
		}
		else
		{
			$charge_str       = '미정';
			$total_charge_str = '미정';
		}
        
		$data['charge_str']       = $charge_str;
		$data['total_charge_str'] = $total_charge_str;

	// 책임자
		$data['apply_name'] = staff_layer_data($data, 'apply', '', $set_part_work_yn, $set_color_list2, 'workapplystaff', $data['wi_idx'], '');

        //echo 'work idx : ' . $data['wi_idx'] . '<br>';

	//로그인한 사람이 등록자일 경우
	//담당자중에 다른 지사가 있을 경우 지사표시
		$part_img = '';
		if ($charge_idx != '')
		{
			if ($chk_mem == $reg_id)
			{
				foreach ($charge_arr as $charge_k => $charge_v)
				{
					if ($charge_v != '')
					{
						$mem_where = " and mem.mem_idx = '" . $charge_v . "'";
						$mem_data = member_info_data('view', $mem_where);

						if ($mem_data['total_num'] > 0)
						{
							$part_sort = $mem_data['part_sort'];
							$part_idx  = $mem_data['part_idx'];
							if ($part_idx != $chk_part)
							{
								if ($part_sort == 1)
								{
									$part_img = '<img src="' . $local_dir . '/bizstory/images/icon/head_icon.gif" alt="본사" />';
								}
								else
								{
									$part_img = '<img src="' . $local_dir . '/bizstory/images/icon/branch_icon.gif" alt="지사" />';
								}
							}
						}
						unset($mem_data);
					}
				}
			}
		//로그인한 사람이 담당자일 경우
		//등록자에 해당하는 지사를 표시한다.
			else
			{
			    /*
				$mem_where = " and mem.mem_idx = '" . $reg_id . "'";
				$mem_data = member_info_data('view', $mem_where);

				$part_sort = $mem_data['part_sort'];
				$part_idx  = $mem_data['part_idx'];
                */
				$part_sort = $data['reg_part_sort'];
                $part_idx = $data['reg_part_idx'];
                /*
                echo $reg_id . ' : ' . $data['reg_mem_idx'] . '<br>';
                echo $part_sort . ' : ' . $data['reg_part_sort'] . '<br>';
                echo $part_idx . ' : ' . $data['reg_part_idx'] . '<br>';
                */
				foreach ($charge_arr as $charge_k => $charge_v)
				{
					if ($chk_mem == $charge_v && $charge_v != '')
					{
						if ($part_idx != $chk_part)
						{
							if ($part_sort == 1)
							{
								$part_img = '<img src="' . $local_dir . '/bizstory/images/icon/head_icon.gif" alt="본사" />';
							}
							else
							{
								$part_img = '<img src="' . $local_dir . '/bizstory/images/icon/branch_icon.gif" alt="지사" />';
							}
						}
					}
				}
				unset($mem_data);
			}
		}
		$data['part_img'] = $part_img;

	// 중요도
		if ($data['important'] == 'WI02')
		{
			$important_span = '<span class="btn_level_1"><span>상</span></span>';
			$important_txt  = '상';
		}
		else if ($data['important'] == 'WI03')
		{
			$important_span = '<span class="btn_level_2"><span>중</span></span>';
			$important_txt  = '중';
		}
		else if ($data['important'] == 'WI04')
		{
			$important_span = '<span class="btn_level_3"><span>하</span></span>';
			$important_txt  = '하';
		}
		else
		{
			$important_span = '';
			$important_txt  = '';
		}
		$data['important_img'] = $important_span;
		$data['important_txt'] = $important_txt;

	// 첨부파일
		//$file_where = " and wf.wi_idx = '" . $wi_idx . "'";
		//$file_page = work_file_data('page', $file_where);
		//$data['total_file'] = $file_page['total_num'];
        
		if ($data['file_cnt'] > 0) $data['file_str'] = '<span class="attach" title="첨부파일">' . number_format($data['file_cnt']) . '</span>';
		else $data['file_str'] = '';
		unset($file_page);

	// 업무보고서
		//$report_where = " and wr.wi_idx='" . $wi_idx . "'";
		//$report_page = work_report_data('page', $report_where);
		//$data['total_report'] = $report_page['total_num'];
		if ($data['report_cnt'] > 0) $data['report_str'] = '<span class="report" title="업무보고서">' . number_format($data['report_cnt']) . '</span>';
		else $data['report_str'] = '';
		unset($report_page);

	// 코멘트
		//$comment_where = " and wc.wi_idx='" . $wi_idx . "'";
		//$comment_page = work_comment_data('page', $comment_where);
		//$data['total_comment'] = $comment_page['total_num'];
		if ($data['comment_cnt'] > 0) $data['comment_str'] = '<span class="cmt" title="코멘트">' . number_format($data['comment_cnt']) . '</span>';
		else $data['comment_str'] = '';
		unset($comment_page);

	// 읽을 업무, 코멘트
		//$check_num = work_read_check($wi_idx);
		//$data['read_work']    = $check_num['work_check'];
		//$data['read_report']  = $check_num['work_report'];
		//$data['read_comment'] = $check_num['work_comment'];
		//$data['work_num']     = $check_num['work_num'];
		$data['read_work'] = $data['wr_cnt'] + $data['wc_cnt'];
		if ($data['read_work'] > 0) $data['read_work_str'] = '<span class="today_num" title="읽을 업무보고/코멘트"><em>' . number_format($data['read_work']) . '</em></span>';
		else $data['read_work_str'] = '';

	// 안 읽은 업무가 있을 경우
		if ($data['work_num'] > 0)
		{
			$data['new_img'] = '<img src="' . $local_dir . '/bizstory/images/icon/ico_new2.png" alt="새업무" />';
			$data['new_txt'] = '새업무';
		}
		else
		{
			$data['new_img'] = '';
			$data['new_txt'] = '';
		}

	// 공개/비공개
		if ($data['open_yn'] == 'N')
		{
			$open_span = '<span class="private"></span>';
			$open_txt  = '비공개';
		}
		else
		{
			$open_span = '';
			$open_txt  = '';
		}
		$data['open_img'] = $open_span;
		$data['open_txt'] = $open_txt;

		if ($chk_level <= 11)
		{
			$data['open_yn'] = 'Y';
		}
		if ($data['open_yn'] == 'N')
		{
			$charge_chk = 'N';
			foreach ($charge_arr as $charge_k => $charge_v)
			{
				if ($charge_v == $chk_mem)
				{
					$charge_chk = 'Y';
					break;
				}
			}
			if ($reg_id == $chk_mem || $apply_idx == $chk_mem || $charge_chk == 'Y')
			{
				$subject_string    = '<a href="javascript:void(0);" onclick="' . $btn_view . '"> ' . $subject . '</a>';
				$subject_main      = '[<a href="javascript:void(0)" onclick="location.href=\'' . $local_dir . '/index.php?fmode=work&smode=work&wi_idx=' . $data['wi_idx'] . '\'"><strong>' . $subject . '</strong></a>]';
				$subject_main_list = $subject;
				$subject_txt       = $subject;

				$view_link_main = 'location.href=\'' . $local_dir . '/index.php?fmode=work&smode=work&wi_idx=' . $data['wi_idx'] . '\'';
			}
			else
			{
				$subject_string    = ' 비공개된 내용입니다.';
				$subject_main      = '[비공개된 내용입니다.]';
				$subject_main_list = '비공개된 내용입니다.';
				$subject_txt       = '비공개된 내용입니다.';

				$view_link_main = '';
			}
		}
		else
		{
			$subject_string    = '<a href="javascript:void(0);" onclick="' . $btn_view . '">' . $subject . '</a>';
			$subject_main      = '[<a href="javascript:void(0)" onclick="location.href=\'' . $local_dir . '/index.php?fmode=work&smode=work&wi_idx=' . $data['wi_idx'] . '\'"><strong>' . $subject . '</strong></a>]';
			$subject_main_list = $subject;
			$subject_txt       = $subject;

			$view_link_main = 'location.href=\'' . $local_dir . '/index.php?fmode=work&smode=work&wi_idx=' . $data['wi_idx'] . '\'';
		}
		$data['subject_string']    = $subject_string;
		$data['subject_main']      = $subject_main;
		$data['subject_main_list'] = $subject_main_list;
		$data['subject_txt']       = $subject_txt;
		$data['view_link_main']    = $view_link_main;

	// 등록자
		$reg_name = staff_layer_data($data, 'reg', '', $set_part_work_yn, $set_color_list2, 'workregsttaff', $data['wi_idx'], '');
		$data['reg_name']      = $reg_name;
		$data['reg_name_view'] = $reg_name;

	// 새로등록된 업무 - new 이미지표현
	/*
		$new_day = date('YmdHis', time() - (60 * 60 * 24 * 1));
		if (date_replace($data['reg_date'], 'YmdHis') >= $new_day)
		{
			$data['new_img'] = '<img src="' . $local_dir . '/bizstory/images/icon/ico_new2.png" alt="새업무" />';
			$data['new_txt'] = '새업무';
		}
		else
		{
			$data['new_img'] = '';
			$data['new_txt'] = '';
	*/

	// 분류
		$work_class = $data['work_class'];
		$class_arr = explode(',', $work_class);
		$total_class_arr = '';
		foreach ($class_arr as $class_k => $class_v)
		{
			$class_where = " and code.code_idx = '" . $class_v . "'";
			$class_data = code_work_class_data('view', $class_where);
			$total_class_arr .= ', ' . $class_data['code_name'];
		}
		$total_class_arr = substr($total_class_arr, 1, strlen($total_class_arr));
		if ($total_class_arr == '') $total_class_arr = '미지정';

		$data['work_class_str'] = $total_class_arr;

	// 업무승인자
		if ($data['apply_name'] == '') $apply_value = '해당없음';
		else
		{
			if ($data['app_del_yn'] == 'Y')
			{
				$apply_value = '<span style="color:#CCCCCC">' . $data['apply_name'] . '</span>';
			}
			else
			{
				$apply_value = '<strong style="color:#ff6c00">' . $data['apply_name'] . '</strong>';
			}
		}
		$data['apply_name'] = $apply_value;


		//<strong style="color:#ff6c00">

	// 업무보고서, 코멘트 목록보기여부
		if ($work_status != 'WS01' && $work_status != 'WS60' && $work_status != 'WS90' && $work_status != 'WS99' && $work_status != 'WS20' && $work_status != 'WS30') // 대기, 취소, 완료, 종료, 승인대기, 요청대기
		{
			$data['report_yn']  = 'Y';
			$data['comment_yn'] = 'Y';
		}
		else
		{
			$data['report_yn']  = 'N';
			$data['comment_yn'] = 'Y';
		}

		if ($data['work_type'] == 'WT04') // 업무알림일 경우
		{
			$data['report_yn'] = 'N';
		}

	// 프로젝트일 경우
		if ($data['project_code'] != null)
		{
		    
            if ($data['menu1'] != '') {
                $project_code = $data['menu1'] . '-';
                if ($data['menu2'] != '') {
                    $project_code .= $data['menu2'] . '-';
                }
                $project_code .= $data['project_code'];
            } else {
                $project_code = $data['project_code'];    
            }
            $link_url = "/index.php?fmode=project&smode=project&pro_idx=" . $data['pro_idx'];

			$data['work_img'] .= '&nbsp;<img src="' . $local_dir . '/bizstory/images/icon/icon_p.gif" alt="프로젝트업무" /><span class="pro_code"><a href="javascript:void(0);" onclick="location=\'' . $link_url . '\'" >[' . $project_code . ']</a></span> ';
			$data['work_txt'] = '프로젝트업무';
			$data['work_class'] = ' class="work_project"';
		}
		else
		{
			$data['work_class'] = '';
		}

		Return $data;
	}

//-------------------------------------- 업무관련정보
	function work_list_data2($data, $wi_idx)
	{
		global $local_dir, $_SESSION, $sess_str, $btn_view, $set_part_work_yn, $set_color_list2;

		$chk_comp  = $_SESSION[$sess_str . '_comp_idx'];
		$chk_part  = $_SESSION[$sess_str . '_part_idx'];
		$chk_mem   = $_SESSION[$sess_str . '_mem_idx'];
		$chk_level = $_SESSION[$sess_str . '_ubstory_level'];

		$work_type     = $data['work_type'];
		$subject       = $data['subject'];
		$charge_idx    = $data['charge_idx'];
		$charge_arr    = explode(',', $charge_idx);
		$apply_idx     = $data['apply_idx'];
		$deadline_date = $data['deadline_date'];
		$end_date      = $data['end_date'];
		$mem_del_yn    = $data['mem_del_yn'];
		$mem_name      = $data['mem_name'];
		$work_status   = $data['work_status'];
		$work_status_str   = $data['work_status_str'];
		$work_status_bold  = $data['work_status_bold'];
		$work_status_color = $data['work_status_color'];

		$display_type = $data['display_type']; // 이력일 경우 이 값을 가지고 구분을 한다.
		$reg_id = $data['reg_mem_idx'];
        /*
		if ($display_type == 'display_main')
		{
			$reg_id = $data['work_reg_id'];
		}
		else
		{
			$reg_id = $data['reg_id'];
		}
        */
	// 지사
		$part_ok = 'N';
		if ($set_part_work_yn == 'Y')
		{
			$part_where = " and part.comp_idx = '" . $chk_comp . "'";
			$part_data = company_part_data('page', $part_where);

			if ($part_data['total_num'] > 1)
			{
				$part_ok = 'Y';
			}
			unset($part_data);
		}

	// 기한일
		$deadline_date1 = date_replace($deadline_date, 'Y.m.d');
		$deadline_week  = date_replace($deadline_date, 'w');
		if ($deadline_week != '')
		{
			if ($deadline_date1 == date('Y.m.d')) // 오늘과 기한일이 같을 경우
			{
				$deadline_date_str = '<span style="color:#FF0000;">' . $deadline_date1 . '(' . $deadline_week . ')</span>';
			}
			else
			{
				$deadline_date_str = $deadline_date1 . '(' . $deadline_week . ')';
			}
		}
		if ($deadline_date_str == '') $deadline_date_str = '미정';
		$data['deadline_date_str'] = $deadline_date_str;

	// 상태/완료일
		if ($work_status == 'WS90' || $work_status == 'WS99') // 완료, 종료
		{
			$end_date1 = date_replace($end_date, 'Y.m.d');
			if ($end_date1 != '')
			{
				$end_week     = date_replace($end_date, 'w');
				$end_date_str = '<span class="btn_state_gray">' . $end_date1 . '(' . $end_week . ')</span>';
			}
			else
			{
				$end_date_str = '';
			}
			$status_view = '';
			$diff_day    = 0;

			$status_str = '<span class="btn_state_purple" style="';
			//if ($work_status_bold == 'Y') $status_str .= 'font-weight:900;';
			//if ($work_status_color != '') $status_str .= 'color:' . $work_status_color . ';';
			$status_str .= '">' . $work_status_str . '</span>';

			$project_status = $status_str;
		}
		else
		{
			if ($work_status == 'WS20' && $apply_idx == $chk_mem) // 승인대기 - 승인자
			{
				$status_str = '<span class="btn_state_purple" style="';
				//if ($work_status_bold == 'Y') $status_str .= 'font-weight:900;';
				//if ($work_status_color != '') $status_str .= 'color:' . $work_status_color . ';';
				$status_str .= '">승인요청</span>';
			}
			else if ($work_status == 'WS30' && $reg_id == $chk_mem) // 요청대기 - 등록자
			{
				$status_str = '<span class="btn_state_green2" style="';
				//if ($work_status_bold == 'Y') $status_str .= 'font-weight:900;';
				//if ($work_status_color != '') $status_str .= 'color:' . $work_status_color . ';';
				$status_str .= '">요청완료</span>';
			}
			else
			{
				$status_str = '<span class="btn_state_green" style="';
				//if ($work_status_bold == 'Y') $status_str .= 'font-weight:900;';
				//if ($work_status_color != '') $status_str .= 'color:' . $work_status_color . ';';
				$status_str .= '">' . $work_status_str . '</span>';
			}
			$end_date_str = $status_str;
			$status_view  = $status_str;

		// 기한이 지나면 지연으로 표시함 - 승인대기(WS20), 요청대기(WS30), 대기(WS01), 취소(WS60), 반려(WS70), 보류(WS80)
			$today_date = date('Ymd');
			$chk_date   = date_replace($deadline_date, 'Ymd');
			$diff_day = 0;
			if ($chk_date < $today_date)
			{
				$date_query = "select datediff('" . $chk_date . "', '" . $today_date . "') as diff_date";
				$date_data = query_view($date_query);
				$diff_day = $date_data['diff_date'];
			}

			if ($diff_day < 0) // 지난경우
			{
				if ($work_status == 'WS20' || $work_status == 'WS30') // 승인대기, 요청대기
				{
					$end_date_str = $status_str . ' ' . $diff_day . '일';
				}
				else if ($work_status == 'WS70') // 반려
				{
					$end_date_str = '<span class="btn_state_red">지연</span>';
				}
				else if ($work_status != 'WS01' && $work_status != 'WS60' && $work_status != 'WS80') // 대기, 취소, 보류 아닐경우
				{
					$end_date_str = '<span class="btn_state_red">지연</span>';
				}
			}

			$project_status = $status_str;
		}
		$data['end_date_str']   = $end_date_str;
		$data['status_view']    = $status_view;
		$data['diff_day']       = $diff_day;
		$data['status_title']   = $status_str;
		$data['project_status'] = $project_status;

	// 자기 업무일 경우 표시
		$chk_charge = ',' . $charge_idx . ',';
		$chk_member = ',' . $chk_mem . ',';
		if ($reg_id == $chk_mem || $apply_idx == $chk_mem || (strlen(stristr($chk_charge, $chk_member)) > 0))
		{
			if ($work_type == 'WT01')
			{
				$work_str = '<span class="btn02 ml10">본인</span>';
				$work_txt = '본인';
			}
			else if ($work_type == 'WT02')
			{
				$work_str = '<span class="btn01 ml10">요청</span>';
				$work_txt = '요청';
			}
			else if ($work_type == 'WT03')
			{
				$work_str = '<span class="btn03 ml10">승인</span>';
				$work_txt = '승인';
			}
			else if ($work_type == 'WT04')
			{
				$work_str = '<span class="btn04 ml10">알림</span>';
				$work_txt = '알림';
			}
		}
		else
		{
			$work_str = '<span class="btn08 ml10">무관</span>';
			$work_txt = '무관';

			if ($work_type == 'WT01')
			{
				$work_str .= '<span class="btn02 ml10">본인</span>';
			}
			else if ($work_type == 'WT02')
			{
				$work_str .= '<span class="btn01 ml10">요청</span>';
			}
			else if ($work_type == 'WT03')
			{
				$work_str .= '<span class="btn03 ml10">승인</span>';
			}
			else if ($work_type == 'WT04')
			{
				$work_str .= '<span class="btn04 ml10">알림</span>';
			}
		}
		$data['work_img'] = $work_str;
		$data['work_txt'] = $work_txt;

	// 담당자
		if ($charge_idx != '')
		{
			$charge_len = count($charge_arr);
			$charge_exp = $charge_len - 1;

			$charge_str = staff_layer_form2($charge_arr[0], '', $set_part_work_yn, $set_color_list2, 'workliststtaff', $data['wi_idx'], 'worklist');
			if ($charge_len > 1)
			{
				$charge_str .= '외 ' . $charge_exp . '명</span>';
			}
			else
			{
				$charge_str .='</span>';
			}


		// 총담당자구하기
			$total_charge_str = '';
			foreach ($charge_arr as $charge_k => $charge_v)
			{
				if ($charge_v != '')
				{
					$total_charge_str .= ', ' . staff_layer_form2($charge_v, '', $set_part_work_yn, $set_color_list2, 'workviewstaff', $data['wi_idx'], 'default');
				}
			}
			$total_charge_str = substr($total_charge_str, 2, strlen($total_charge_str));
		}
		else
		{
			$charge_str       = '미정';
			$total_charge_str = '미정';
		}
		$data['charge_str']       = $charge_str;
		$data['total_charge_str'] = $total_charge_str;

	// 책임자
		$data['apply_name'] = staff_layer_form2($apply_idx, '', $set_part_work_yn, $set_color_list2, 'workapplystaff', $data['wi_idx'], '');

	//로그인한 사람이 등록자일 경우
	//담당자중에 다른 지사가 있을 경우 지사표시
		$part_img = '';
		if ($charge_idx != '')
		{
			if ($chk_mem == $reg_id)
			{
				foreach ($charge_arr as $charge_k => $charge_v)
				{
					if ($charge_v != '')
					{
						$mem_where = " and mem.mem_idx = '" . $charge_v . "'";
						$mem_data = member_info_data('view', $mem_where);

						if ($mem_data['total_num'] > 0)
						{
							$part_sort = $mem_data['part_sort'];
							$part_idx  = $mem_data['part_idx'];
							if ($part_idx != $chk_part)
							{
								if ($part_sort == 1)
								{
									$part_img = '<img src="' . $local_dir . '/bizstory/images/icon/head_icon.gif" alt="본사" />';
								}
								else
								{
									$part_img = '<img src="' . $local_dir . '/bizstory/images/icon/branch_icon.gif" alt="지사" />';
								}
							}
						}
						unset($mem_data);
					}
				}
			}
		//로그인한 사람이 담당자일 경우
		//등록자에 해당하는 지사를 표시한다.
			else
			{
				$mem_where = " and mem.mem_idx = '" . $reg_id . "'";
				$mem_data = member_info_data('view', $mem_where);

				$part_sort = $mem_data['part_sort'];
				$part_idx  = $mem_data['part_idx'];

				foreach ($charge_arr as $charge_k => $charge_v)
				{
					if ($chk_mem == $charge_v && $charge_v != '')
					{
						if ($part_idx != $chk_part)
						{
							if ($part_sort == 1)
							{
								$part_img = '<img src="' . $local_dir . '/bizstory/images/icon/head_icon.gif" alt="본사" />';
							}
							else
							{
								$part_img = '<img src="' . $local_dir . '/bizstory/images/icon/branch_icon.gif" alt="지사" />';
							}
						}
					}
				}
				unset($mem_data);
			}
		}
		$data['part_img'] = $part_img;

	// 중요도
		if ($data['important'] == 'WI02')
		{
			$important_span = '<span class="btn_level c_orange">상</span>';
			$important_txt  = '상';
		}
		else if ($data['important'] == 'WI03')
		{
			$important_span = '<span class="btn_level c_green">중</span>';
			$important_txt  = '중';
		}
		else if ($data['important'] == 'WI04')
		{
			$important_span = '<span class="btn_level">하</span>';
			$important_txt  = '하';
		}
		else
		{
			$important_span = '';
			$important_txt  = '';
		}
		$data['important_img'] = $important_span;
		$data['important_txt'] = $important_txt;

	// 첨부파일
		if ($data['wf_cnt'] > 0)
			$data['file_str'] = '<span class="attach" title="첨부파일">' . number_format($data['wf_cnt']) . '</span>';
		else
			$data['file_str'] = '';
		/*
		$file_where = " and wf.wi_idx = '" . $wi_idx . "'";
		$file_page = work_file_data('page', $file_where);
		$data['total_file'] = $file_page['total_num'];
		if ($data['total_file'] > 0) $data['file_str'] = '<span class="attach" title="첨부파일">' . number_format($data['total_file']) . '</span>';
		else $data['file_str'] = '';
		unset($file_page);
		*/
	// 업무보고서
		if ($data['wr_cnt'] > 0)
			$data['report_str'] = '<span class="report" title="업무보고서">' . number_format($data['wr_cnt']) . '</span>';
		else
			$data['report_str'] = '';
		
		/*
		$report_where = " and wr.wi_idx='" . $wi_idx . "'";
		$report_page = work_report_data('page', $report_where);
		$data['total_report'] = $report_page['total_num'];
		if ($data['total_report'] > 0) $data['report_str'] = '<span class="report" title="업무보고서">' . number_format($data['total_report']) . '</span>';
		else $data['report_str'] = '';
		unset($report_page);
		*/
	// 코멘트
		if ($data['wc_cnt'] > 0)
			$data['comment_str'] = '<span class="cmt" title="코멘트">' . number_format($data['wc_cnt']) . '</span>';
		else
			$data['comment_str'] = '';
	
		/*
		$comment_where = " and wc.wi_idx='" . $wi_idx . "'";
		$comment_page = work_comment_data('page', $comment_where);
		$data['total_comment'] = $comment_page['total_num'];
		if ($data['total_comment'] > 0) $data['comment_str'] = '<span class="cmt" title="코멘트">' . number_format($data['total_comment']) . '</span>';
		else $data['comment_str'] = '';
		unset($comment_page);
		*/
	// 읽을 업무, 코멘트
		$check_num = work_read_check($wi_idx);
		$data['read_work']    = $check_num['work_check'];
		$data['read_report']  = $check_num['work_report'];
		$data['read_comment'] = $check_num['work_comment'];
		$data['work_num']     = $check_num['work_num'];
		if ($data['read_work'] > 0) $data['read_work_str'] = '<span class="today_num" title="읽을 업무보고/코멘트"><em>' . number_format($data['read_work']) . '</em></span>';
		else $data['read_work_str'] = '';

	// 안 읽은 업무가 있을 경우
		if ($data['work_num'] > 0)
		{
			$data['new_img'] = '<img src="' . $local_dir . '/bizstory/images/icon/ico_new2.png" alt="새업무" />';
			$data['new_txt'] = '새업무';
		}
		else
		{
			$data['new_img'] = '';
			$data['new_txt'] = '';
		}

	// 공개/비공개
		if ($data['open_yn'] == 'N')
		{
			$open_span = '<span class="private"></span>';
			$open_txt  = '비공개';
		}
		else
		{
			$open_span = '';
			$open_txt  = '';
		}
		$data['open_img'] = $open_span;
		$data['open_txt'] = $open_txt;

		if ($chk_level <= 11)
		{
			$data['open_yn'] = 'Y';
		}
		if ($data['open_yn'] == 'N')
		{
			$charge_chk = 'N';
			foreach ($charge_arr as $charge_k => $charge_v)
			{
				if ($charge_v == $chk_mem)
				{
					$charge_chk = 'Y';
					break;
				}
			}
			if ($reg_id == $chk_mem || $apply_idx == $chk_mem || $charge_chk == 'Y')
			{
				$subject_string    = '<a href="javascript:void(0);" onclick="' . $btn_view . '"> ' . $subject . '</a>';
				$subject_main      = '[<a href="javascript:void(0)" onclick="location.href=\'' . $local_dir . '/index.php?fmode=work&smode=work&wi_idx=' . $data['wi_idx'] . '\'"><strong>' . $subject . '</strong></a>]';
				$subject_main_list = $subject;
				$subject_txt       = $subject;

				$view_link_main = 'location.href=\'' . $local_dir . '/index.php?fmode=work&smode=work&wi_idx=' . $data['wi_idx'] . '\'';
			}
			else
			{
				$subject_string    = ' 비공개된 내용입니다.';
				$subject_main      = '[비공개된 내용입니다.]';
				$subject_main_list = '비공개된 내용입니다.';
				$subject_txt       = '비공개된 내용입니다.';

				$view_link_main = '';
			}
		}
		else
		{
			$subject_string    = '<a href="javascript:void(0);" onclick="' . $btn_view . '">' . $subject . '</a>';
			$subject_main      = '[<a href="javascript:void(0)" onclick="location.href=\'' . $local_dir . '/index.php?fmode=work&smode=work&wi_idx=' . $data['wi_idx'] . '\'"><strong>' . $subject . '</strong></a>]';
			$subject_main_list = $subject;
			$subject_txt       = $subject;

			$view_link_main = 'location.href=\'' . $local_dir . '/index.php?fmode=work&smode=work&wi_idx=' . $data['wi_idx'] . '\'';
		}
		$data['subject_string']    = $subject_string;
		$data['subject_main']      = $subject_main;
		$data['subject_main_list'] = $subject_main_list;
		$data['subject_txt']       = $subject_txt;
		$data['view_link_main']    = $view_link_main;
		$data['wi_idx'] = $data['wi_idx'];

	// 등록자
		$reg_name = staff_layer_form2($data['reg_id'], '', $set_part_work_yn, $set_color_list2, 'workregsttaff', $data['wi_idx'], '');
		$data['reg_name']      = $reg_name;
		$data['reg_name_view'] = $reg_name;

	// 새로등록된 업무 - new 이미지표현
	/*
		$new_day = date('YmdHis', time() - (60 * 60 * 24 * 1));
		if (date_replace($data['reg_date'], 'YmdHis') >= $new_day)
		{
			$data['new_img'] = '<img src="' . $local_dir . '/bizstory/images/icon/ico_new2.png" alt="새업무" />';
			$data['new_txt'] = '새업무';
		}
		else
		{
			$data['new_img'] = '';
			$data['new_txt'] = '';
	*/

	// 분류
		$work_class = $data['work_class'];
		$class_arr = explode(',', $work_class);
		$total_class_arr = '';
		foreach ($class_arr as $class_k => $class_v)
		{
			$class_where = " and code.code_idx = '" . $class_v . "'";
			$class_data = code_work_class_data('view', $class_where);
			$total_class_arr .= ', ' . $class_data['code_name'];
		}
		$total_class_arr = substr($total_class_arr, 1, strlen($total_class_arr));
		if ($total_class_arr == '') $total_class_arr = '미지정';

		$data['work_class_str'] = $total_class_arr;

	// 업무승인자
		if ($data['apply_name'] == '') $apply_value = '해당없음';
		else
		{
			if ($data['app_del_yn'] == 'Y')
			{
				$apply_value = '<span style="color:#CCCCCC">' . $data['apply_name'] . '</span>';
			}
			else
			{
				$apply_value = '<strong style="color:#ff6c00">' . $data['apply_name'] . '</strong>';
			}
		}
		$data['apply_name'] = $apply_value;


		//<strong style="color:#ff6c00">

	// 업무보고서, 코멘트 목록보기여부
		if ($work_status != 'WS01' && $work_status != 'WS60' && $work_status != 'WS90' && $work_status != 'WS99' && $work_status != 'WS20' && $work_status != 'WS30') // 대기, 취소, 완료, 종료, 승인대기, 요청대기
		{
			$data['report_yn']  = 'Y';
			$data['comment_yn'] = 'Y';
		}
		else
		{
			$data['report_yn']  = 'N';
			$data['comment_yn'] = 'Y';
		}

		if ($data['work_type'] == 'WT04') // 업무알림일 경우
		{
			$data['report_yn'] = 'N';
		}

	// 프로젝트일 경우
		$project_where = " and pro.pro_idx = '" . $data['pro_idx'] . "'";
		$project_data = project_info_data('view', $project_where);
		if ($project_data['total_num'] > 0)
		{
			$data['work_img'] = '<img src="' . $local_dir . '/bizstory/images/icon/icon_p.gif" alt="프로젝트업무" /><span class="pro_code">[' . $project_data['project_code'] . ']</span> ';
			$data['work_txt'] = '프로젝트업무';
			$data['work_class'] = ' class="work_project"';
		}
		else
		{
			$data['work_class'] = '';
		}

		Return $data;
	}

//-------------------------------------- 업무읽기
	function check_work_type_status($data)
	{
		global $_SESSION, $sess_str;

		$code_comp   = $_SESSION[$sess_str . '_comp_idx'];
		$code_part   = $_SESSION[$sess_str . '_part_idx'];
		$code_mem    = $_SESSION[$sess_str . '_mem_idx'];
		$wi_idx      = $data['wi_idx'];
		$reg_id      = $data['reg_id'];
		$work_status = $data['work_status'];
		$work_type   = $data['work_type'];
		$apply_idx   = $data['apply_idx'];
		$charge_idx  = $data['charge_idx'];
		$charge_arr  = explode(',', $charge_idx);

	// 요청일 경우
		if ($work_type == 'WT02')
		{
			$chk_charge_num = 0; $charge_num = 0;
			foreach ($charge_arr as $charge_k => $charge_v)
			{
				if ($charge_v != $reg_id) // 등록자 제외
				{
					$mem_where = " and mem.mem_idx = '" . $charge_v . "'";
					$mem_data = member_info_data('view', $mem_where, '', '', '', 2);
					if ($mem_data['total_num'] > 0)
					{
						if ($mem_data['del_yn'] == 'Y') // 퇴사한 경우
						{
							$chk_charge_num++;
						}
						else
						{
							$status_where = " and wsh.wi_idx = '" . $wi_idx . "' and wsh.status = 'WS30' and wsh.mem_idx = '" . $charge_v . "' and wsh.status_type = 'new'"; // 요청대기
							$status_page = work_status_history_data('page', $status_where);
							if ($status_page['total_num'] > 0)
							{
								$chk_charge_num++;
							}
						}
					}
					else
					{
						$chk_charge_num++;
					}
					unset($mem_data);
				}
				else
				{
					$chk_charge_num++;
				}
				$charge_num++;
			}

		// 요청대기 다 할경우
			if ($chk_charge_num >= $charge_num)
			{
				if ($data['work_status'] == 'WS02')
				{
					$status_query = "
						update work_info set
							  work_status = 'WS30'
							, mod_id      = '" . $comp_mem . "'
							, mod_date    = '" . date('Y-m-d H:i:s') . "'
						where wi_idx = '" . $wi_idx . "'
					";
					db_query($status_query);
					query_history($status_query, 'work_info', 'update');

				// 히스토리저장
					$history_query = "
						insert into work_status_history set
							  comp_idx    = '" . $code_comp . "'
							, part_idx    = '" . $code_part . "'
							, wi_idx      = '" . $wi_idx . "'
							, mem_idx     = '" . $code_mem . "'
							, status      = 'WS30'
							, status_date = '" . date('Y-m-d H:i:s') . "'
							, status_memo = '업무를 완료요청(자동)했습니다.'
							, reg_id      = '" . $comp_mem . "'
							, reg_date    = '" . date('Y-m-d H:i:s') . "'
					";
					db_query($history_query);
					query_history($history_query, 'work_status_history', 'insert');
				}
			}
		}
	// 승인일 경우
		else if ($work_type == 'WT03')
		{
			$chk_charge_num = 0; $charge_num = 0;
			foreach ($charge_arr as $charge_k => $charge_v)
			{
				if ($charge_v != $apply_idx) // 승인자 제외
				{
					$mem_where = " and mem.mem_idx = '" . $charge_v . "'";
					$mem_data = member_info_data('view', $mem_where, '', '', '', 2);
					if ($mem_data['total_num'] > 0)
					{
						if ($mem_data['del_yn'] == 'Y') // 퇴사한 경우
						{
							$chk_charge_num++;
						}
						else
						{
							$status_where = " and wsh.wi_idx = '" . $wi_idx . "' and wsh.status = 'WS20' and wsh.mem_idx = '" . $charge_v . "' and wsh.status_type = 'new'"; // 승인대기
							$status_page = work_status_history_data('page', $status_where);
							if ($status_page['total_num'] > 0)
							{
								$chk_charge_num++;
							}
						}
					}
					else
					{
						$chk_charge_num++;
					}
					unset($mem_data);
				}
				else
				{
					$chk_charge_num++;
				}
				$charge_num++;
			}

		// 승인대기를 다 할경우
			if ($chk_charge_num >= $charge_num)
			{
				if ($data['work_status'] == 'WS02')
				{
					$status_query = "
						update work_info set
							  work_status = 'WS20'
							, mod_id      = '" . $comp_mem . "'
							, mod_date    = '" . date('Y-m-d H:i:s') . "'
						where wi_idx = '" . $wi_idx . "'
					";
					db_query($status_query);
					query_history($status_query, 'work_info', 'update');

				// 히스토리저장
					$history_query = "
						insert into work_status_history set
							  comp_idx    = '" . $code_comp . "'
							, part_idx    = '" . $code_part . "'
							, wi_idx      = '" . $wi_idx . "'
							, mem_idx     = '" . $code_mem . "'
							, status      = 'WS20'
							, status_date = '" . date('Y-m-d H:i:s') . "'
							, status_memo = '업무를 승인요청(자동)했습니다.'
							, reg_id      = '" . $comp_mem . "'
							, reg_date    = '" . date('Y-m-d H:i:s') . "'
					";
					db_query($history_query);
					query_history($history_query, 'work_status_history', 'insert');
				}
			}
		}
// 담당자, 읽은자 등록 - 알림일 경우
		else if ($work_type == 'WT04')
		{
			$charge_num = 0;
			$read_charge_num = 0;
			foreach ($charge_arr as $charge_k => $charge_v)
			{
				if ($charge_v != $reg_id) // 등록자 제외
				{
					$mem_where = " and mem.mem_idx = '" . $charge_v . "'";
					$mem_data = member_info_data('view', $mem_where, '', '', '');
					if ($mem_data['total_num'] > 0)
					{
						$charge_num++;

					// 읽음 표시
						$read_where = " and wre.wi_idx = '" . $wi_idx . "' and wre.mem_idx = '" . $charge_v . "'";
						$read_data = work_read_data('view', $read_where);
						if ($read_data['total_num'] == 0)
						{
							if ($charge_v == $code_mem)
							{
								$insert_query = "
									insert into work_read set
										  comp_idx  = '" . $code_comp . "'
										, part_idx  = '" . $code_part . "'
										, wi_idx    = '" . $wi_idx . "'
										, mem_idx   = '" . $code_mem . "'
										, read_date = '" . date('Y-m-d H:i:s') . "'
										, reg_id    = '" . $code_mem . "'
										, reg_date  = '" . date('Y-m-d H:i:s') . "'
								";
								db_query($insert_query);
								query_history($insert_query, 'work_read', 'insert');

								$read_charge_num++;

								$chk_num = 'Y';
							}
						}
						else
						{
							$read_charge_num++;
						}
					}
				}
			}

		// 다 읽으면 업무완료처리
			if ($read_charge_num >= $charge_num && $chk_num == 'Y')
			{
				if ($data['work_status'] != 'WS90')
				{
					$status_query = "
						update work_info set
							  work_status = 'WS90'
							, end_date    = '" . date('Y-m-d H:i:s') . "'
							, mod_id      = '" . $comp_mem . "'
							, mod_date    = '" . date('Y-m-d H:i:s') . "'
						where wi_idx = '" . $wi_idx . "'
					";
					db_query($status_query);
					query_history($status_query, 'work_info', 'update');

				// 히스토리저장
					$history_query = "
						insert into work_status_history set
							  comp_idx    = '" . $code_comp . "'
							, part_idx    = '" . $code_part . "'
							, wi_idx      = '" . $wi_idx . "'
							, mem_idx     = '" . $code_mem . "'
							, status      = 'WS90'
							, status_date = '" . date('Y-m-d H:i:s') . "'
							, status_memo = '업무가 완료되었습니다.'
							, reg_id      = '" . $comp_mem . "'
							, reg_date    = '" . date('Y-m-d H:i:s') . "'
					";
					db_query($history_query);
					query_history($history_query, 'work_status_history', 'insert');
				}
			}
		}
	}

//-------------------------------------- 프로젝트상태보기
    function project_status_view($data, $disp_type = '')
    {
        global $_SESSION, $sess_str;

        $chk_comp  = $_SESSION[$sess_str . '_comp_idx'];
        $chk_part  = $_SESSION[$sess_str . '_part_idx'];
        $chk_mem   = $_SESSION[$sess_str . '_mem_idx'];
        $chk_level = $_SESSION[$sess_str . '_ubstory_level'];

        $pro_idx          = $data['pro_idx'];
        $project_code      = $data['project_code'];
        $pro_status     = $data['pro_status'];
        $pro_status_str = $data['pro_status_str'];
        $end_date        = $data['end_date'];
        $reg_id          = $data['reg_id'];

        $status_view     = $data['status_title'];
        $diff_day        = $data['diff_day'];

    // 등록자, 마스터는 같은 권한
        if ($reg_id == $chk_mem || $chk_level <= 11)
        {
            $status_auth = 'Y';
        }
        else
        {
            $status_auth = 'N';
        }

    // 상태이미지
        if ($pro_status == 'PS60') // 취소
        {
            $status_title_bg  = 'ws20';
        }
        else if ($pro_status == 'PS70') // 보류
        {
            $status_title_bg  = 'ws70';
        }
        else if ($pro_status == 'PS80') // 보류
        {
            $status_title_bg  = 'ws80';
        }
        else if ($pro_status == 'PS90') // 완료, 종료
        {
            $status_title_bg  = 'ws90';
        }
        else
        {
            $status_title_bg  = 'ws02';
        }

    // 상태 타이틀
        if ($diff_day < 0)
        {
            if ($pro_status == 'PS01' || $pro_status == 'PS60' || $pro_status == 'PS80') // 대기, 취소, 보류
            {
                $status_title = '프로젝트가 ' . $status_view . ' 중입니다.';
            }
            else
            {
                $status_title_bg = 'delay';
                if ($disp_type == '') {
                    $status_title    = '프로젝트가 ' . $status_view . '<strong class="delay">(지연' . $diff_day . '일)</strong> 중입니다.';   
                } else {
                    $status_title    = '프로젝트가 ' . $status_view . '<strong class="c_orange fw700">(지연' . $diff_day . '일)</strong> 중입니다.';
                }
                
            }
        }
        else
        {
            if ($pro_status == 'PS90')
            {
                $status_title = '프로젝트가 ' . $status_view . ' 되었습니다.';
            }
            else
            {
                $status_title = '프로젝트가 ' . $status_view . ' 중입니다.';
            }
        }
        $str['status_title_bg'] = $status_title_bg;
        $str['status_title']    = $status_title;

    // 상태이력
        if ($pro_status != 'PS01' && $pro_status != 'PS02')
        {
            $status_comment = '';
            $mem_where = " and mem.mem_idx = '" . $reg_id . "'";
            $mem_data = member_info_data('view', $mem_where, '', '', '', 2);
            
            if ($mem_data['total_num'] > 0)
            {
                if ($mem_data['del_yn'] == 'Y') // 퇴사한 경우
                {
                    $charge_name     = '<span style="color:#afafaf;text-decoration:line-through">' . $mem_data['mem_name'] . '</span>';
                    $status_comment .= '<div><span class="icon01"></span> ' . $charge_name . ' 퇴직한 직원입니다.</div>';
                }
                else
                {
                    $status_where = " and pro.pro_idx = '" . $pro_idx . "' and prosh.status like 'PS%'";
                    $status_order = "prosh.reg_date asc";
                    $status_list = project_status_history_data('list', $status_where, $status_order, '', '');
                   
                    foreach ($status_list as $status_k => $status_data)
                    {
                        if (is_array($status_data))
                        {
                            if ($status_data['status'] == 'PS60') // 취소
                            {
                                $pro_status_str  = '취소';
                            }
                            else if ($status_data['status'] == 'PS70') // 반려
                            {
                                $pro_status_str  = '반려';
                            }
                            else if ($status_data['status'] == 'PS80') // 보류
                            {
                                $pro_status_str  = '보류';
                            }
                            else if ($status_data['status'] == 'PS90') // 완료, 종료
                            {
                                if ($status_data['force_yn'] == 'Y')
                                    $pro_status_str = '강제완료';
                                else
                                    $pro_status_str  = '완료';
                            }
                            else
                            {
                                $pro_status_str  = '진행';
                            }
                            $status_comment .= '
                                <div><span class="icon01"></span>' . $pro_status_str . ' : ' . $status_data['mem_name'] . ' [' . $status_data['reg_date'] . ']</div>';
                            if ($status_data['contents'] != '')
                            {
                                $status_comment .= '
                                <div class="status_str"><span class="icon02"></span> ' . $status_data['contents'] . '</div>';
                            }
                        }
                    }
                    unset($status_data);
                    unset($status_list);
                }
            }
            unset($mem_data);
        }
        
        $str['status_comment'] = $status_comment;

        Return $str;
    }
//-------------------------------------- 업무상태보기
	function work_status_view($data, $disp_type = '')
	{
		global $_SESSION, $sess_str;

		$chk_comp  = $_SESSION[$sess_str . '_comp_idx'];
		$chk_part  = $_SESSION[$sess_str . '_part_idx'];
		$chk_mem   = $_SESSION[$sess_str . '_mem_idx'];
		$chk_level = $_SESSION[$sess_str . '_ubstory_level'];

		$wi_idx          = $data['wi_idx'];
		$work_type       = $data['work_type'];
		$work_status     = $data['work_status'];
		$work_status_str = $data['work_status_str'];
		$end_date        = $data['end_date'];
		$reg_id          = $data['reg_id'];
		$apply_idx       = $data['apply_idx'];
		$charge_idx      = $data['charge_idx'];
		$charge_arr      = explode(',', $charge_idx);

		$status_view     = $data['status_title'];
		$diff_day        = $data['diff_day'];
        
	// 프로젝트정보
		$pro_idx = $data['pro_idx'];
		if ($pro_idx != '')
		{
			$pro_where = " and pro.pro_idx = '" . $pro_idx . "'";
			$pro_data = project_info_data('view', $pro_where);
		}

	// 등록자, 마스터는 같은 권한
		if ($reg_id == $chk_mem || $apply_idx == $chk_mem || $chk_level <= 11)
		{
			$status_auth = 'Y';
		}
		else
		{
			$status_auth = 'N';
		}
        
	// 상태이미지
		if ($work_status == 'WS20' || $work_status == 'WS30') // 승인대기, 요청대기
		{
			$status_title_bg  = 'ws20';
		}
		else if ($work_status == 'WS80') // 보류
		{
			$status_title_bg  = 'ws80';
		}
		else if ($work_status == 'WS90' || $work_status == 'WS99') // 완료, 종료
		{
			$status_title_bg  = 'ws90';
		}
		else
		{
			$status_title_bg  = 'ws02';
		}

	// 상태 타이틀
		if ($diff_day < 0)
		{
			if ($work_status == 'WS01' || $work_status == 'WS60' || $work_status == 'WS80') // 대기, 취소, 보류
			{
				$status_title = '업무가 ' . $status_view . ' 중입니다.';
			}
			else
			{
				$status_title_bg = 'delay';
				if ($disp_type == '') {
					$status_title    = '업무가 <strong class="delay">지연(' . $status_view . ' ' . $diff_day . ')</strong> 중입니다.';	
				} else {
					$status_title    = '업무가 <strong class="c_orange fw700">지연(' . $status_view . ' ' . $diff_day . ')</strong> 중입니다.';
				}
				
			}
		}
		else
		{
			if ($work_status == 'WS90')
			{
				$status_title = '업무가 ' . $status_view . ' 되었습니다.';
			}
			else
			{
				$status_title = '업무가 ' . $status_view . ' 중입니다.';
			}
		}
		$str['status_title_bg'] = $status_title_bg;
		$str['status_title']    = $status_title;

	// 업무종류별 상태
		if ($work_type == 'WT01') // 본인
		{
			if ($status_auth == 'Y')
			{
				if ($work_status == 'WS01') // 대기
				{
					$button_ws02_value = '업무진행';
					$button_ws02_key   = 'WS02';
				}
				else if ($work_status == 'WS02') // 진행
				{
					$button_ws90_value = '업무완료';
					$button_ws90_key   = 'WS90';

					$button_ws80_value = '업무보류';
					$button_ws80_key   = 'WS80';
                    
				}
				else if ($work_status == 'WS70') // 반려
				{
					$button_ws90_value = '업무완료';
					$button_ws90_key   = 'WS90';

					$button_ws80_value = '업무보류';
					$button_ws80_key   = 'WS80';
				}
				else if ($work_status == 'WS80') // 보류
				{
					$button_ws02_value = '업무진행';
					$button_ws02_key   = 'WS02';
				}
				else if ($work_status == 'WS90') // 완료
				{
					$button_ws70_value = '업무반려';
					$button_ws70_key   = 'WS70';
				}
			}

		// 상태이력
			if ($work_status != 'WS01' && $work_status != 'WS02')
			{
				$status_comment = '';
				$mem_where = " and mem.mem_idx = '" . $charge_idx . "'";
				$mem_data = member_info_data('view', $mem_where, '', '', '', 2);

				if ($mem_data['total_num'] > 0)
				{
					if ($mem_data['del_yn'] == 'Y') // 퇴사한 경우
					{
						$charge_name     = '<span style="color:#afafaf;text-decoration:line-through">' . $mem_data['mem_name'] . '</span>';
						$status_comment .= '<div><span class="icon01"></span> ' . $charge_name . ' 퇴직한 직원입니다.</div>';
					}
					else
					{
						$status_where = " and wsh.wi_idx = '" . $wi_idx . "' and wsh.mem_idx = '" . $charge_idx . "' and wsh.status ='" . $work_status . "'";
						$status_order = "wsh.reg_date asc";
						$status_list = work_status_history_data('list', $status_where, $status_order, '', '');
						foreach ($status_list as $status_k => $status_data)
						{
							if (is_array($status_data))
							{
								$status_comment .= '
									<div><span class="icon01"></span>' . $work_status_str . ' : ' . $status_data['mem_name'] . ' [' . $status_data['reg_date'] . ']</div>';
								if ($status_data['contents'] != '')
								{
									$status_comment .= '
									<div class="status_str"><span class="icon02"></span> ' . $status_data['contents'] . '</div>';
								}
							}
						}
						unset($status_data);
						unset($status_list);
					}
				}
				unset($mem_data);
			}
		}
		else if ($work_type == 'WT04') // 알림
		{
			if ($status_auth == 'Y')
			{
				if ($work_status == 'WS01') // 대기
				{
					$button_ws02_value = '업무진행';
					$button_ws02_key   = 'WS02';
				}
				else if ($work_status == 'WS02') // 진행
				{
					$button_ws90_value = '업무완료';
					$button_ws90_key   = 'WS90';

					$button_ws80_value = '업무보류';
					$button_ws80_key   = 'WS80';
				}
				else if ($work_status == 'WS80') // 보류
				{
					$button_ws02_value = '업무진행';
					$button_ws02_key   = 'WS02';
				}
				else if ($work_status == 'WS90') // 완료
				{ }
			}

		// 상태이력
			$status_comment = '';
			if ($charge_idx != '')
			{
				foreach ($charge_arr as $charge_k => $charge_v)
				{
					if ($charge_v != $reg_id) // 등록자 제외
					{
					// 담당자명 구하기
						$mem_where = " and mem.mem_idx = '" . $charge_v . "'";
						$mem_data = member_info_data('view', $mem_where, '', '', '', 2);
						if ($mem_data['total_num'] > 0)
						{
							if ($mem_data['del_yn'] == 'Y') // 퇴사한 경우
							{
								$charge_name     = '<span style="color:#afafaf;text-decoration:line-through">' . $mem_data['mem_name'] . '</span>';
								$status_comment .= '<div><span class="icon01"></span> ' . $charge_name . ' 퇴직한 직원입니다.</div>';
							}
							else
							{
								$status_where = " and wre.wi_idx = '" . $wi_idx . "' and wre.mem_idx = '" . $charge_v . "'";
								$status_data = work_read_data('view', $status_where);

								if ($status_data['total_num'] == 0)
								{
									$status_comment .= '<div><span class="icon01"></span> ' . $mem_data['mem_name'] . ' 읽지 않았습니다.</div>';
								}
								else
								{
									$status_comment .= '<div><span class="icon01"></span> ' . $status_data['read_name'] . ' [' . $status_data['read_date'] . ']</div>';
								}
								unset($status_page);
							}
						}

						unset($mem_data);
					}
				}
			}
		}
		else if ($work_type == 'WT02') // 요청
		{
			if ($status_auth == 'Y')
			{
				if ($work_status == 'WS01') // 대기
				{
					$button_ws02_value = '업무진행';
					$button_ws02_key   = 'WS02';
				}
				else if ($work_status == 'WS02') // 진행
				{
					$button_ws80_value = '업무보류';
					$button_ws80_key   = 'WS80';
				}
				else if ($work_status == 'WS30') // 요청대기
				{
					$button_ws90_value = '요청완료반려';
					$button_ws90_key   = 'WS30_WS70';

					$button_ws80_value = '업무완료';
					$button_ws80_key   = 'WS30_WS90';
				}
				else if ($work_status == 'WS70') // 반려
				{
					$button_ws80_value = '업무보류';
					$button_ws80_key   = 'WS80';
				}
				else if ($work_status == 'WS80') // 보류
				{
					$button_ws02_value = '업무진행';
					$button_ws02_key   = 'WS02';
				}
				else if ($work_status == 'WS90') // 완료
				{
					$button_ws70_value = '업무반려';
					$button_ws70_key   = 'WS70';
				}
			}

		// 상태이력
			$status_comment = '';
			$remain_charge = ',' . $charge_idx . ','; // 담당자
			$remain_charge = str_replace(',' . $reg_id . ',', ',', $remain_charge);
			$chk_charge = ''; // 요청한 사람

			$status_where = " and wsh.wi_idx = '" . $wi_idx . "' and wsh.status != '' and wsh.status != 'WS01' and wsh.status != 'WS02'"; // 대기, 진행은 제외
			$status_order = "wsh.reg_date asc";
			$status_list = work_status_history_data('list', $status_where, $status_order, '', '');
			foreach ($status_list as $status_k => $status_data)
			{			    
				if (is_array($status_data))
				{
				// 상태이름
					if ($status_data['status'] == 'WS30_60')
					{
						$status_string = '요청취소';
					}
					else if ($status_data['status'] == 'WS70')
					{
						$status_string = '요청반려';
					}
					else if ($status_data['status'] == 'WS80')
					{
						$status_string = '요청보류';
					}
					else if ($status_data['status'] == 'WS90')
					{
						$status_string = '요청완료';
					}
					else
					{
						$status_string = '완료요청';
					}

				// 직원
					if ($status_data['mem_del_yn'] == 'Y')
					{
						$status_name = '<span style="color:#CCCCCC">' . $status_data['mem_name'] . '</span>';
					}
					else
					{
						$status_name = $status_data['mem_name'];
					}
					$status_comment .= '<div><span class="icon01"></span> ' . $status_string . ' : ' . $status_name . ' [' . $status_data['reg_date'] . ']</div>';
					//$status_comment .= '<div><span class="icon01"></span> ' . $status_string . $status_data['status'] . ' : ' . $status_name . ' [' . $status_data['reg_date'] . ']</div>';
					if ($status_data['contents'] != '')
					{
						$status_comment .= '
						<div class="status_str"><span class="icon02"></span> ' . $status_data['contents'] . '</div>';
					}

					if ($status_data['status'] == 'WS30_60')
					{
						$remain_charge .= $status_data['mem_idx'] . ',';
						$chk_charge = str_replace(',' . $status_data['mem_idx'] . ',', ',', $chk_charge);
					}
					else
					{
						$remain_charge = str_replace(',' . $status_data['mem_idx'] . ',', ',', $remain_charge);
						$chk_charge .= $status_data['mem_idx'] . ',';
					}
				}
			}

		// 완료요청을 위해서
			if ($work_status == 'WS70') // 반려
			{
				$remain_charge = $charge_idx;
				$chk_charge    = '';
			}
			else
			{
				$remain_charge = substr($remain_charge, 1, strlen($remain_charge)-2);
			}
			$remain_arr = explode(',', $remain_charge);
			if ($remain_charge != '')
			{
				foreach ($remain_arr as $charge_k => $charge_v)
				{
					if ($charge_v != $reg_id) // 등록자 제외
					{
					// 담당자명 구하기
						$mem_where = " and mem.mem_idx = '" . $charge_v . "'";
						$mem_data = member_info_data('view', $mem_where, '', '', '', 2);
						if ($mem_data['total_num'] > 0)
						{
							if ($mem_data['del_yn'] == 'Y') // 퇴사한 경우
							{
								$charge_name     = '<span style="color:#afafaf;text-decoration:line-through">' . $mem_data['mem_name'] . '</span>';
								$status_comment .= '<div><span class="icon01"></span> ' . $charge_name . ' 퇴직한 직원입니다.</div>';
							}
							else
							{
								$status_comment .= '<div><span class="icon01"></span> 완료요청 : ' . $mem_data['mem_name'] . ' 완료요청하지 않았습니다.</div>';
								if ($charge_v == $chk_mem)
								{
									$button_ws30_value = '완료요청';
									$button_ws30_key   = 'WS30';
								}
							}
						}
						unset($mem_data);
					}
				}
			}
		// 완료요청취소를 위해서
			$remain_arr = explode(',', $chk_charge);
			if ($chk_charge != '')
			{
				foreach ($remain_arr as $charge_k => $charge_v)
				{
					if ($charge_v != $reg_id) // 등록자 제외
					{
						if ($charge_v == $chk_mem && $work_status != 'WS90')
						{
							$button_ws30_02_value = '완료요청취소';
							$button_ws30_02_key   = 'WS30_WS02';
						}
					}
				}
			}
		}
		else if ($work_type == 'WT03') // 승인
		{
			if ($status_auth == 'Y')
			{
				if ($work_status == 'WS01') // 대기
				{
					$button_ws02_value = '업무진행';
					$button_ws02_key   = 'WS02';
				}
				else if ($work_status == 'WS02') // 진행
				{
					$button_ws80_value = '업무보류';
					$button_ws80_key   = 'WS80';
				}
				else if ($work_status == 'WS20') // 승인대기
				{
					$button_ws90_value = '승인요청반려';
					$button_ws90_key   = 'WS20_WS70';

					$button_ws80_value = '승인';
					$button_ws80_key   = 'WS20_WS90';
				}
				else if ($work_status == 'WS70') // 반려
				{
					$button_ws80_value = '업무보류';
					$button_ws80_key   = 'WS80';
				}
				else if ($work_status == 'WS80') // 보류
				{
					$button_ws02_value = '업무진행';
					$button_ws02_key   = 'WS02';
				}
				else if ($work_status == 'WS90') // 완료
				{
					$button_ws70_value = '업무반려';
					$button_ws70_key   = 'WS70';
				}
			}

		// 상태이력
			$status_comment = '';
			$remain_charge = ',' . $charge_idx . ',';
			//$remain_charge = str_replace(',' . $reg_id . ',', ',', $remain_charge);

			$status_where = " and wsh.wi_idx = '" . $wi_idx . "' and wsh.status != '' and wsh.status != 'WS01' and wsh.status != 'WS02'"; // 대기, 진행은 제외
			$status_order = "wsh.reg_date asc";
			$status_list = work_status_history_data('list', $status_where, $status_order, '', '');
			foreach ($status_list as $status_k => $status_data)
			{
				if (is_array($status_data))
				{
				// 상태이름
					if ($status_data['status'] == 'WS20_60')
					{
						$status_string = '승인취소';
					}
					else if ($status_data['status'] == 'WS70')
					{
						$status_string = '승인반려';
					}
					else if ($status_data['status'] == 'WS80')
					{
						$status_string = '승인보류';
					}
					else if ($status_data['status'] == 'WS90')
					{
                        if ($status_data['force_yn'] == 'Y') {
                            $status_string = '강제완료';
                        } else {
                            $status_string = '승인완료';
                        }
					}
					else
					{
						$status_string = '승인요청';
					}

				// 직원
					if ($status_data['mem_del_yn'] == 'Y')
					{
						$status_name = '<span style="color:#CCCCCC">' . $status_data['mem_name'] . '</span>';
					}
					else
					{
						$status_name = $status_data['mem_name'];
					}
					$status_comment .= '<div><span class="icon01"></span> ' . $status_string . ' : ' . $status_name . ' [' . $status_data['reg_date'] . ']</div>';
					//$status_comment .= '<div><span class="icon01"></span> ' . $status_string . $status_data['status'] . ' : ' . $status_name . ' [' . $status_data['reg_date'] . ']</div>';
					if ($status_data['contents'] != '')
					{
						$status_comment .= '
						<div class="status_str"><span class="icon02"></span> ' . $status_data['contents'] . '</div>';
					}

					if ($status_data['status'] == 'WS20_60')
					{
						$remain_charge .= $status_data['mem_idx'] . ',';
						$chk_charge = str_replace(',' . $status_data['mem_idx'] . ',', ',', $chk_charge);
					}
					else
					{
						$remain_charge = str_replace(',' . $status_data['mem_idx'] . ',', ',', $remain_charge);
						$chk_charge .= $status_data['mem_idx'] . ',';
					}
				}
			}

		// 승인요청을 위해서
			if ($work_status == 'WS70') // 반려
			{
				$remain_charge = $charge_idx;
				$chk_charge    = '';
			}
			else
			{
				$remain_charge = substr($remain_charge, 1, strlen($remain_charge)-2);
			}
			$remain_arr = explode(',', $remain_charge);
			if ($remain_charge != '')
			{
				foreach ($remain_arr as $charge_k => $charge_v)
				{
					if ($charge_v != $apply_idx) // 승인자 제외
					{
					// 담당자명 구하기
						$mem_where = " and mem.mem_idx = '" . $charge_v . "'";
						$mem_data = member_info_data('view', $mem_where, '', '', '', 2);
						if ($mem_data['total_num'] > 0)
						{
							if ($mem_data['del_yn'] == 'Y') // 퇴사한 경우
							{
								$charge_name     = '<span style="color:#afafaf;text-decoration:line-through">' . $mem_data['mem_name'] . '</span>';
								$status_comment .= '<div><span class="icon01"></span> ' . $charge_name . ' 퇴직한 직원입니다.</div>';
							}
							else
							{
								$status_comment .= '<div><span class="icon01"></span> 승인요청 : ' . $mem_data['mem_name'] . ' 승인요청하지 않았습니다.</div>';
								if ($charge_v == $chk_mem)
								{
									$button_ws30_value = '승인요청';
									$button_ws30_key   = 'WS20';
								}
							}
						}
						unset($mem_data);
					}
				}
			}
		// 승인요청취소를 위해서
			$remain_arr = explode(',', $chk_charge);
			if ($chk_charge != '')
			{
				foreach ($remain_arr as $charge_k => $charge_v)
				{
					if ($charge_v != $apply_idx) // 승인자 제외
					{
						if ($charge_v == $chk_mem && $work_status != 'WS90')
						{
							$button_ws20_02_value = '승인요청취소';
							$button_ws20_02_key   = 'WS20_WS02';
						}
					}
				}
			}
		}

		//echo 'wi_idx -> ', $wi_idx, '<br />';
		$str['button_ws02_value']    = $button_ws02_value;
		$str['button_ws02_key']      = $button_ws02_key;

		$str['button_ws20_value']    = $button_ws20_value;
		$str['button_ws20_key']      = $button_ws20_key;
		$str['button_ws20_02_value'] = $button_ws20_02_value;
		$str['button_ws20_02_key']   = $button_ws20_02_key;
		//$str['button_ws20_70_value'] = $button_ws20_70_value;
		//$str['button_ws20_70_key']   = $button_ws20_70_key;
		//$str['button_ws20_90_value'] = $button_ws20_90_value;
		//$str['button_ws20_90_key']   = $button_ws20_90_key;

		$str['button_ws30_value']    = $button_ws30_value;
		$str['button_ws30_key']      = $button_ws30_key;
		$str['button_ws30_02_value'] = $button_ws30_02_value;
		$str['button_ws30_02_key']   = $button_ws30_02_key;
		//$str['button_ws30_70_value'] = $button_ws30_70_value;
		//$str['button_ws30_70_key']   = $button_ws30_70_key;
		//$str['button_ws30_90_value'] = $button_ws30_90_value;
		//$str['button_ws30_90_key']   = $button_ws30_90_key;

		$str['button_ws70_value']    = $button_ws70_value;
		$str['button_ws70_key']      = $button_ws70_key;

		$str['button_ws80_value']    = $button_ws80_value;
		$str['button_ws80_key']      = $button_ws80_key;

		$str['button_ws90_value']    = $button_ws90_value;
		$str['button_ws90_key']      = $button_ws90_key;

		$str['status_comment'] = $status_comment;

		Return $str;
	}
?>