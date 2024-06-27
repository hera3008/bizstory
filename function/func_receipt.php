<?
////////////////////////////////////////////////////////////////////////////////////////////////////////
/////
///// 접수관련 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------- 지사별 접수분류
	function code_receipt_class_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "code.sort asc";
		if ($del_type == 1) $where = "code.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(code.code_idx)
			from
				code_receipt_class code
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
				code_receipt_class code
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

//-------------------------------------- 지사별 접수상태
	function code_receipt_status_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "code.sort asc";
		if ($del_type == 1) $where = "code.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(code.code_idx)
			from
				code_receipt_status code
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
				code_receipt_status code
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









//-------------------------------------- 지사별 점검항목 함수
	function code_report_class_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "code.sort asc";
		if ($del_type == 1) $where = "code.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(code.code_idx)
			from
				code_report_class code
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
				code_report_class code
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

//-------------------------------------- 거래처별 보고서 함수
	function receipt_report_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "rr.reg_date desc";
		if ($del_type == 1) $where = "rr.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(rr.rr_idx)
			from
				receipt_report rr
				left join company_part part on part.del_yn = 'N' and part.part_idx = rr.part_idx
				left join client_info ci on ci.del_yn = 'N' and ci.ci_idx = rr.ci_idx
				left join member_info mem on mem.del_yn = 'N' and mem.mem_idx = rr.reg_id
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				rr.*
				, part.part_name, part.tel_num
				, ci.client_name
				, mem.mem_name
			from
				receipt_report rr
				left join company_part part on part.del_yn = 'N' and part.part_idx = rr.part_idx
				left join client_info ci on ci.del_yn = 'N' and ci.ci_idx = rr.ci_idx
				left join member_info mem on mem.del_yn = 'N' and mem.mem_idx = rr.reg_id
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

//-------------------------------------- 거래처별 보고서상세 함수
	function receipt_report_detail_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "rrd.reg_date asc";
		if ($del_type == 1) $where = "rrd.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(rrd.rrd_idx)
			from
				receipt_report_detail rrd
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				rrd.*
			from
				receipt_report_detail rrd
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

//-------------------------------------- 지사별 접수분류 현위치
	function receipt_class_view($code_idx)
	{
		$where = " and code.code_idx = '" . $code_idx . "'";
		$data = code_receipt_class_data('view', $where);
		$arr_up = explode(",", $data["up_code_idx"]);

		$menu_depth = $data['menu_depth'];
		$str['code_name'][$menu_depth] = $data["code_name"];

		if ($menu_depth == 1)
		{
			$str['first_class'] = $data["code_name"];
		}
		else if ($menu_depth == 2)
		{
			$up_idx = $arr_up[1];

			$up_where = " and code.code_idx = '" . $up_idx . "'";
			$up_data = code_receipt_class_data("view", $up_where);

			$up_menu_depth = $up_data['menu_depth'];
			$str['code_name'][$up_menu_depth] = $up_data["code_name"];

			if ($up_menu_depth == 1)
			{
				$str['first_class'] = $up_data["code_name"];
			}

			$str['second_class'] = $data["code_name"];
		}
		else
		{
			for ($i = 1; $i < $menu_depth ; $i++)
			{
				$up_idx  = $arr_up[$i];

				$up_where = " and code.code_idx = '" . $up_idx . "'";
				$up_data = code_receipt_class_data("view", $up_where);

				$up_menu_depth = $up_data['menu_depth'];
				$str['code_name'][$up_menu_depth] = $up_data["code_name"];

				if ($up_menu_depth == 1)
				{
					$str['first_class'] = $up_data["code_name"];
				}
				if ($up_menu_depth == 2)
				{
					$str['second_class'] = $up_data["code_name"];
				}
			}
		}
		ksort($str['code_name']);
		Return $str;
	}

//-------------------------------------- 접수정보
	function receipt_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "ri.reg_date desc";
		if ($del_type == 1) $where = "ri.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ri.ri_idx)
			from
				receipt_info ri
				join client_info ci on ci.comp_idx = ri.comp_idx and ci.ci_idx = ri.ci_idx
				join company_part part on part.del_yn = 'N' and part.part_idx = ri.part_idx
				join member_info mem on mem.comp_idx = ri.comp_idx and mem.mem_idx = ri.charge_mem_idx
				join company_staff_group csg on csg.comp_idx = part.comp_idx and csg.part_idx = part.part_idx and csg.csg_idx = mem.csg_idx
				left join code_receipt_class code on code.comp_idx = ri.comp_idx and code.part_idx = ri.part_idx and code.code_idx = ri.receipt_class
				join code_receipt_status code2 on code2.comp_idx = ri.comp_idx and code2.part_idx = ri.part_idx and code2.code_value = ri.receipt_status
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ri.*
				, ci.client_name, ci.del_yn as client_del_yn, ci.link_url, ci.mem_idx as client_mem_idx
				, part.part_name
				, mem.mem_name, mem.del_yn as member_del_yn, mem.mem_idx
				, code.del_yn as class_del_yn
				, code2.code_name as receipt_status_str, code2.code_bold as receipt_status_bold, code2.code_color as receipt_status_color, code2.code_value as status_value
				, code2.del_yn as status_del_yn, code2.code_value as status_code_value
			from
				receipt_info ri
				join client_info ci on ci.comp_idx = ri.comp_idx and ci.ci_idx = ri.ci_idx
				join company_part part on part.del_yn = 'N' and part.part_idx = ri.part_idx
				join member_info mem on mem.comp_idx = ri.comp_idx and mem.mem_idx = ri.charge_mem_idx
				join company_staff_group csg on csg.comp_idx = part.comp_idx and csg.part_idx = part.part_idx and csg.csg_idx = mem.csg_idx
				left join code_receipt_class code on code.comp_idx = ri.comp_idx and code.part_idx = ri.part_idx and code.code_idx = ri.receipt_class
				join code_receipt_status code2 on code2.comp_idx = ri.comp_idx and code2.part_idx = ri.part_idx and code2.code_value = ri.receipt_status
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
	
	//-------------------------------------- 접수정보_모바
	function receipt_info_data_mobile($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		$code_comp = $_SESSION[$sess_str . '_comp_idx'];
		$code_part = search_company_part($code_part);
		$code_mem  = $_SESSION[$sess_str . '_mem_idx'];
		
		if ($orderby == '') $orderby = "ri.reg_date desc";
		if ($del_type == 1) $where = "ri.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ri.ri_idx)
			from
				receipt_info ri
				left join client_info ci on ci.comp_idx = ri.comp_idx and ci.ci_idx = ri.ci_idx
				left join company_part part on part.del_yn = 'N' and part.part_idx = ri.part_idx
				left join member_info mem on mem.comp_idx = ci.comp_idx and mem.mem_idx = ci.mem_idx
				left join code_receipt_class code on code.comp_idx = ri.comp_idx and code.part_idx = ri.part_idx and code.code_idx = ri.receipt_class
				left join code_receipt_status code2 on code2.comp_idx = ri.comp_idx and code2.part_idx = ri.part_idx and code2.code_value = ri.receipt_status
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ri.*
				, ci.client_name, ci.del_yn as client_del_yn, ci.link_url, ci.mem_idx as client_mem_idx
				, part.part_name
				, mem.mem_name, mem.del_yn as member_del_yn, mem.mem_idx
				, code.del_yn as class_del_yn
				, code2.code_name as receipt_status_str, code2.code_bold as receipt_status_bold, code2.code_color as receipt_status_color, code2.code_value as status_value
				, code2.del_yn as status_del_yn, code2.code_value as status_code_value
			from
				receipt_info ri
				left join client_info ci on ci.comp_idx = ri.comp_idx and ci.ci_idx = ri.ci_idx
				left join company_part part on part.del_yn = 'N' and part.part_idx = ri.part_idx
				left join member_info mem on mem.comp_idx = ci.comp_idx and mem.mem_idx = ci.mem_idx
				left join code_receipt_class code on code.comp_idx = ri.comp_idx and code.part_idx = ri.part_idx and code.code_idx = ri.receipt_class
				left join code_receipt_status code2 on code2.comp_idx = ri.comp_idx and code2.part_idx = ri.part_idx and code2.code_value = ri.receipt_status
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

//-------------------------------------- 접수파일
	function receipt_file_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "rf.sort asc";
		if ($del_type == 1) $where = "rf.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(rf.rf_idx)
			from
				receipt_file rf
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				rf.*
			from
				receipt_file rf
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

//-------------------------------------- 접수 나누기
	function receipt_info_detail_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "rid.reg_date desc";
		if ($del_type == 1) $where = "rid.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(rid.rid_idx)
			from
				receipt_info_detail rid
				left join receipt_info ri on ri.del_yn = 'N' and ri.ri_idx = rid.ri_idx
				left join member_info mem on mem.mem_idx = rid.mem_idx
				left join code_receipt_class code1 on code1.comp_idx = rid.comp_idx and code1.part_idx = rid.part_idx and code1.code_idx = rid.receipt_class
				left join code_receipt_status code2 on code2.comp_idx = rid.comp_idx and code2.part_idx = rid.part_idx and code2.code_value = rid.receipt_status
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				rid.*
				, mem.mem_name

				, code1.code_name as receipt_class_str, code1.del_yn as class_del_yn

				, code2.code_name as receipt_status_str, code2.code_bold as receipt_status_bold, code2.code_color as receipt_status_color
				, code2.code_value as status_value, code2.del_yn as status_del_yn

				, ri.subject, ri.writer, ri.reg_date as receipt_date
			from
				receipt_info_detail rid
				left join receipt_info ri on ri.del_yn = 'N' and ri.ri_idx = rid.ri_idx
				left join member_info mem on mem.mem_idx = rid.mem_idx
				left join code_receipt_class code1 on code1.comp_idx = rid.comp_idx and code1.part_idx = rid.part_idx and code1.code_idx = rid.receipt_class
				left join code_receipt_status code2 on code2.comp_idx = rid.comp_idx and code2.part_idx = rid.part_idx and code2.code_value = rid.receipt_status
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
		//print_r($data_info);
		Return $data_info;
	}

//-------------------------------------- 접수댓글
	function receipt_comment_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "rc.order_idx desc";
		if ($del_type == 1) $where = "rc.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(rc.rc_idx)
			from
				receipt_comment rc
				left join receipt_info ri on ri.del_yn = 'N' and ri.ri_idx = rc.ri_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				rc.*
			from
				receipt_comment rc
				left join receipt_info ri on ri.del_yn = 'N' and ri.ri_idx = rc.ri_idx
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

//-------------------------------------- 접수댓글 파일
	function receipt_comment_file_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "rcf.sort asc";
		if ($del_type == 1) $where = "rcf.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(rcf.rcf_idx)
			from
				receipt_comment_file rcf
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				rcf.*
			from
				receipt_comment_file rcf
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

//-------------------------------------- 접수상태내역
	function receipt_status_history_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "rsh.reg_date asc";
		if ($del_type == 1) $where = "rsh.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(rsh.rsh_idx)
			from
				receipt_status_history rsh
				left join receipt_info ri on ri.del_yn = 'N' and ri.ri_idx = rsh.ri_idx
				left join receipt_info_detail rid on rid.del_yn = 'N' and rid.rid_idx = rsh.rid_idx
				left join client_info ci on ci.del_yn = 'N' and ci.ci_idx = ri.ci_idx
				left join code_receipt_status code on code.comp_idx = rsh.comp_idx and code.part_idx = rsh.part_idx and code.code_idx = rsh.status
				left join member_info mem on mem.del_yn = 'N' and mem.comp_idx = rsh.comp_idx and mem.mem_idx = rsh.mem_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				rsh.*
				, code.code_value, code.code_name as status_str, code.del_yn as status_del_yn
				, mem.mem_name
				, ri.subject
				, ci.client_name, ci.ci_idx
			from
				receipt_status_history rsh
				left join receipt_info ri on ri.del_yn = 'N' and ri.ri_idx = rsh.ri_idx
				left join receipt_info_detail rid on rid.del_yn = 'N' and rid.rid_idx = rsh.rid_idx
				left join client_info ci on ci.del_yn = 'N' and ci.ci_idx = ri.ci_idx
				left join code_receipt_status code on code.comp_idx = rsh.comp_idx and code.part_idx = rsh.part_idx and code.code_idx = rsh.status
				left join member_info mem on mem.del_yn = 'N' and mem.comp_idx = rsh.comp_idx and mem.mem_idx = rsh.mem_idx
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

//-------------------------------------- 완료시 첨부파일
	function receipt_end_file_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "ref.sort asc";
		if ($del_type == 1) $where = "ref.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ref.ref_idx)
			from
				receipt_end_file ref
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ref.*
			from
				receipt_end_file ref
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

//-------------------------------------- 접수정보
	function receipt_list_data($idx, $data)
	{
		global $local_dir, $comp_dir, $set_receipt_status, $set_color_list2;
		
		$comp_member_dir = $comp_dir . '/' . $data['comp_idx'] . '/member';

	// 파일수
		$sub_where = " and rf.ri_idx='" . $data['ri_idx'] . "'";
		$sub_data = receipt_file_data('page', $sub_where);
		$data['total_file'] = $sub_data['total_num'];

	// 댓글수
		$sub_where = " and rc.ri_idx='" . $data['ri_idx'] . "'";
		$sub_data = receipt_comment_data('page', $sub_where);
		$data['total_comment'] = $sub_data['total_num'];

	// 중요도
		if ($data['important'] == 'RI02') $data['important_str'] = '<span class="btn_level_1"><span>상</span></span>';
		else if ($data['important'] == 'RI03') $data['important_str'] = '<span class="btn_level_2"><span>중</span></span>';
		else if ($data['important'] == 'RI04') $data['important_str'] = '<span class="btn_level_3"><span>하</span></span>';

	// 거래처가 삭제된 경우
		$client_where = " and ci.ci_idx = '" . $data['ci_idx'] . "'";
		$client_data = client_info_data('view', $client_where, '', '', '', 2);
		if ($client_data['del_yn'] == 'Y')
		{
			$data['client_name_str'] = '<span style="color:#CCCCCC">삭제된 거래처</span>';
		}
		else
		{
			$data['client_name_str'] = '<a href="javascript:void(0);" onclick="search_client(\'ci.client_name\',\'' . $data['client_name'] . '\')">' . $data['client_name'] . '</a>';
		}

	// 분류
		$data['receipt_class_str'] = receipt_class_view($data['receipt_class']);

	// 접수자, 전화번호
		$tel_num = str_replace('-', '', $data['tel_num']);
		$tel_num = str_replace('.', '', $tel_num);
		if ($tel_num == '')
		{
			$data['receipt_name'] = $data['writer'];
			$data['receipt_name_mobile'] = $data['writer'];
		}
		else
		{
			$data['receipt_name'] = $data['writer'] . '<br />(' . $data['tel_num'] . ')';
			$data['receipt_name_mobile'] = $data['writer'] . '(' . $data['tel_num'] . ')';
		}

	// 접수상태
		$status_name = $set_receipt_status[$data['receipt_status']];
		$status_str = '<span style="';
		if ($data['receipt_status_bold'] == 'Y') $status_str .= 'font-weight:900;';
		if ($data['receipt_status_color'] != '') $status_str .= 'color:' . $data['receipt_status_color'] . ';';
		$status_str .= '">' . $data['receipt_status_str'] . '</span>';

		if ($data['status_del_yn'] == 'Y') $status_str = '<span style="color:#CCCCCC">' . $data['receipt_status_str'] . '</span>';

		$data['receipt_status_str']  = $status_str;
		$data['receipt_status_name'] = $status_name;

	// 접수상태가 완료일 경우 완료일 보여줌
		if ($data['receipt_status'] == 'RS90')
		{
			$end_date = date_replace($data['end_date'], 'Y.m.d');
			$end_date_str = '<br /><span style="';
			if ($data['receipt_status_color'] != '') $end_date_str .= 'color:' . $data['receipt_status_color'] . ';';
			$end_date_str .= '">' . $end_date . '</span>';
		}
		else
		{
			$end_date_str = '';
		}
		$data['end_date_str']  = $end_date_str;

	// 담당직원
		$detail_where = " and rid.ri_idx = '" . $data['ri_idx'] . "'";
		$detail_order = "rid.reg_date asc";
		//$detail_data = receipt_info_detail_data('view', $detail_where, $detail_order, '', '');
		if ($detail_data['total_num'] == 0)
		{
			$charge_idx = $data['charge_mem_idx'];
		}
		else
		{
			$charge_idx = $detail_data['mem_idx'];
		}
		if ($charge_idx == '0' || $charge_idx == '') $charge_idx = $data['client_mem_idx'];

		$charge_str = staff_layer_form($charge_idx, '', 'N', $set_color_list2, 'fileliststtaff', $data['ri_idx'], '');
		$data['member_str'] = $charge_str . $chk_str;

	// 링크주소
		$link_url = $data['link_url'];
		$link_url_arr = explode(',', $link_url);
		if ($link_url_arr[0] != '')
		{
			$link_string = str_replace('http://', '', $link_url_arr[0]);
			$data['link_html'] = '<a href="http://' . $link_string . '" target="_blank"><img src="' . $local_dir . '/bizstory/images/icon/home.gif" alt="홈페이지로 이동합니다." /></a>';
		}
		else
		{
			$data['link_html'] = '';
		}

		if ($idx == $data['ri_idx'])
		{
			$data['number_str'] = '->';
		}
		else
		{
			$data['number_str'] = $num;
		}
		
		Return $data;
	}

//-------------------------------------- 접수정보_모바
	function receipt_list_data2($idx, $data)
	{
		global $local_dir, $comp_dir, $set_receipt_status, $set_color_list2;

		$comp_member_dir = $comp_dir . '/' . $data['comp_idx'] . '/member';

	// 파일수
		$sub_where = " and rf.ri_idx='" . $data['ri_idx'] . "'";
		$sub_data = receipt_file_data('page', $sub_where);
		$data['total_file'] = $sub_data['total_num'];
		if ($data['total_file'] > 0) $data['file_str'] = '<span class="attach" title="첨부파일">' . number_format($data['total_file']) . '</span>';
		else $data['file_str'] = '';
		unset($file_page);	

	// 댓글수
		$sub_where = " and rc.ri_idx='" . $data['ri_idx'] . "'";
		$sub_data = receipt_comment_data('page', $sub_where);
		$data['total_comment'] = $sub_data['total_num'];
		if ($data['total_comment'] > 0) $data['comment_str'] = '<span class="cmt" title="코멘트">' . number_format($data['total_comment']) . '</span>';
		else $data['comment_str'] = '';
		unset($comment_page);

	// 중요도
		if ($data['important'] == 'RI02') $data['important_str'] = '<span class="btn_level_1"><span>상</span></span>';
		else if ($data['important'] == 'RI03') $data['important_str'] = '<span class="btn_level_2"><span>중</span></span>';
		else if ($data['important'] == 'RI04') $data['important_str'] = '<span class="btn_level_3"><span>하</span></span>';

	// 거래처가 삭제된 경우
		$client_where = " and ci.ci_idx = '" . $data['ci_idx'] . "'";
		$client_data = client_info_data('view', $client_where, '', '', '', 2);
		if ($client_data['del_yn'] == 'Y')
		{
			$data['client_name_str'] = '<span style="color:#CCCCCC">삭제된 거래처</span>';
		}
		else
		{
			$data['client_name_str'] = '<a href="javascript:void(0);" onclick="search_client(\'ci.client_name\',\'' . $data['client_name'] . '\')">' . $data['client_name'] . '</a>';
		}

	// 분류
		$data['receipt_class_str'] = receipt_class_view($data['receipt_class']);

	// 접수자, 전화번호
		$tel_num = str_replace('-', '', $data['tel_num']);
		$tel_num = str_replace('.', '', $tel_num);
		if ($tel_num == '')
		{
			$data['receipt_name'] = $data['writer'];
			$data['receipt_name_mobile'] = $data['writer'];
		}
		else
		{
			$data['receipt_name'] = $data['writer'] . '<br />(' . $data['tel_num'] . ')';
			$data['receipt_name_mobile'] = $data['writer'] . '(' . $data['tel_num'] . ')';
		}

	// 접수상태
		$status_name = $set_receipt_status[$data['receipt_status']];
//echo $status_name;	
		if($data['receipt_status'] == 'RS90') {
			// 완료처리 
			$status_str = '<span class="btn07 ml10"';
			$status_str .= '">' . $data['receipt_status_str'] . '</span>';
			
		}else if($data['receipt_status'] == 'RS60') {
			// 취소처리 
			$status_str = '<span class="btn08 ml10"';
			$status_str .= '">' . $data['receipt_status_str'] . '</span>';
			
		}else if($data['receipt_status'] == 'RS01') {
			// 접수등록 
			$status_str = '<span class="btn06 ml10"';
			$status_str .= '">' . $data['receipt_status_str'] . '</span>';
			
		}else if($data['receipt_status'] == 'RS02') {
			// 접수승인 
			$status_str = '<span class="btn05 ml10"';
			$status_str .= '">' . $data['receipt_status_str'] . '</span>';
			
		}else if($data['receipt_status'] == 'RS80') {
			// 보류처리 
			$status_str = '<span class="btn03 ml10"';
			$status_str .= '">' . $data['receipt_status_str'] . '</span>';
			
		}else {
			// 진행 
			$status_str = '<span class="btn01 ml10"';
			$status_str .= '">' . $data['receipt_status_str'] . '</span>';
		}
		
//		echo $status_name;
//		$status_str = '<span style="';
//		if ($data['receipt_status_bold'] == 'Y') $status_str .= 'font-weight:900;';
//		if ($data['receipt_status_color'] != '') $status_str .= 'color:' . $data['receipt_status_color'] . ';';
//		$status_str .= '">' . $data['receipt_status_str'] . '</span>';

	//	if ($data['status_del_yn'] == 'Y') $status_str = '<span style="color:#CCCCCC">' . $data['receipt_status_str'] . '</span>';

		$data['receipt_status_str']  = $status_str;
		$data['receipt_status_name'] = $status_name;

	// 접수상태가 완료일 경우 완료일 보여줌
		if ($data['receipt_status'] == 'RS90')
		{
			$end_date = date_replace($data['end_date'], 'Y.m.d');
			$end_date_str = '<br /><span style="';
			if ($data['receipt_status_color'] != '') $end_date_str .= 'color:' . $data['receipt_status_color'] . ';';
			$end_date_str .= '">' . $end_date . '</span>';
		}
		else
		{
			$end_date_str = '';
		}
		$data['end_date_str']  = $end_date_str;

	
		if ($idx == $data['ri_idx'])
		{
			$data['number_str'] = '->';
		}
		else
		{
			$data['number_str'] = $num;
		}

		Return $data;
	}

//-------------------------------------- 접수푸쉬
	function receipt_push($ri_idx, $ri_type = '')
	{
		if ($ri_type == 'detail') // 다중일 경우 담당자에게 등록
		{
		// 접수정보
			$ri_where = " and rid.rid_idx = '" . $ri_idx . "'";
			$ri_data = receipt_info_detail_data('view', $ri_where);

			$comp_idx = $ri_data['comp_idx'];
			$part_idx = $ri_data['part_idx'];
			$ci_idx   = $ri_data['ci_idx'];
			$mem_idx  = $ri_data['mem_idx'];
			$subject  = $ri_data['remark'];
		}
		else
		{
		// 접수정보
			$ri_where = " and ri.ri_idx = '" . $ri_idx . "'";
			$ri_data = receipt_info_data('view', $ri_where);

			$comp_idx = $ri_data['comp_idx'];
			$part_idx = $ri_data['part_idx'];
			$ci_idx   = $ri_data['ci_idx'];
			$mem_idx  = $ri_data['charge_mem_idx'];
			$subject  = $ri_data['subject'];
		}
		unset($ri_data);

	// 거래처정보
		$ci_where = " and ci.ci_idx = '" . $ci_idx . "'";
		$ci_data = client_info_data('view', $ci_where);
		$client_name = $ci_data['client_name'];
		$mem_idx     = $ci_data['mem_idx'];
		unset($ci_data);
		

		$msg_type = 'receipt';
		$message  = strip_tags($subject);
		$message  = '[' . $client_name . '] ' . string_cut($message, 10);

	// 담당자정보
		$mem_where = " and mem.mem_idx = '" . $mem_idx . "'";
		$mem_data = member_info_data('view', $mem_where);
		$mem_id = $mem_data['mem_id'];
		unset($mem_data);

		if ($mem_id != '')
		{
			$receiver = $mem_id;
			
			//$push   = new PUSH("bizstory_push");
			//$result = @$push->push_send($sender, $comp_idx, $part_idx, $mem_idx, $receiver, $msg_type, $message);
			push_send($sender, $comp_idx, $part_idx, $mem_idx, $receiver, $msg_type, $message);
		}
	// 알림담당자
		$comp_set_where = " and cs.comp_idx = '" . $comp_idx . "'";
		$comp_set_data  = company_setting_data('view', $comp_set_where);
		$charge_idx = $comp_set_data['receipt_charge'];
		$charge_arr = explode(',', $charge_idx);
		
		foreach ($charge_arr as $charge_k => $charge_v)
		{
			if ($charge_v != '' && $charge_v != $mem_id)
			{
				$mem_where = " and mem.mem_idx = '" . $charge_v . "' and mem.part_idx = '" . $part_idx . "'";
				$mem_data = member_info_data('view', $mem_where);
				$mem_id = $mem_data['mem_id'];

				if ($mem_id != '')
				{
					$receiver = $mem_id;
					//$push   = new PUSH("bizstory_push");
					//$result = @$push->push_send($sender, $comp_idx, $part_idx, $mem_idx, $receiver, $msg_type, $message);
                    push_send($sender, $comp_idx, $part_idx, $mem_idx, $receiver, $msg_type, $message);
				}
				unset($mem_data);
			}
		}
		unset($comp_set_data);
	}

//-------------------------------------- 접수확인
	function receipt_check_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "rec.reg_date desc";
		if ($del_type == 1) $where = "rec.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(rec.rec_idx)
			from
				receipt_check rec
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				rec.*
			from
				receipt_check rec
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

//-------------------------------------- 접수댓글 읽을 값
	function receipt_read_check($chk_data)
	{
		$chk_ci  = $chk_data['chk_ci'];
		$chk_mac = $chk_data['chk_mac'];
		$ri_idx  = $chk_data['ri_idx'];

		$receipt_check = 0; $comment_no = 0;

		if ($ri_idx == '') // 총값
		{
		// 댓글
			$comment_where = "
				and ri.ci_idx = '" . $chk_ci . "' and rc.macaddress != '" . $chk_mac . "' and ri.macaddress = '" . $chk_mac . "' and ri.del_yn = 'N'";
		}
		else
		{
		// 댓글
			$comment_where = "
				and rc.ri_idx = '" . $ri_idx . "' and rc.macaddress != '" . $chk_mac . "' and ri.macaddress = '" . $chk_mac . "'
			";
		}

	// 댓글
		$comment_list =  receipt_comment_data('list', $comment_where, '', '', '');
		foreach ($comment_list as $comment_k => $data)
		{
			if (is_array($data))
			{
				$check_where = " and rec.ri_idx = '" . $data['ri_idx'] . "' and rec.rc_idx = '" . $data['rc_idx'] . "' and rec.macaddress = '" . $chk_mac . "'";
				$check_data = receipt_check_data('view', $check_where);
				if ($check_data['total_num'] == 0)
				{
					$ri_idx = $data['ri_idx'];
					$ri_chk[$ri_idx] = $ri_idx;
					$comment_no++;
				}
				unset($check_data);
			}
		}
		unset($data);
		unset($comment_list);

	// 총값
		$receipt_check = $comment_no;

		if ($receipt_check > 0)
		{
			$chk_num = 1;
			$add_where = " and (";
			foreach ($ri_chk as $k => $v)
			{
				if ($chk_num == 1)
				{
					$add_where .= " ri.ri_idx = '" . $v . "'";
				}
				else
				{
					$add_where .= " or ri.ri_idx = '" . $v . "'";
				}
				$chk_num++;
			}
			$add_where .= ')';
		}
		else
		{
			$add_where = " and 1 != 1";
		}

		$str['read_comment']  = $comment_no;
		$str['receipt_check'] = $receipt_check;
		$str['receipt_where'] = $add_where;

		Return $str;
	}

//-------------------------------------- 접수 코멘트 읽은 확인
	function receipt_data_read($chk_data)
	{
		$chk_mac = $chk_data['chk_mac'];
		$ri_idx  = $chk_data['ri_idx'];
		$rc_idx  = $chk_data['rc_idx'];

		if ($rc_idx != '')
		{
			$sub_where = " and rc.rc_idx = '" . $rc_idx . "'";
			$sub_data = receipt_comment_data('view', $sub_where);

			$check_where = " and rec.ri_idx = '" . $ri_idx . "' and rec.rc_idx = '" . $rc_idx . "' and rec.macaddress = '" . $chk_mac . "'";
			$check_data = receipt_check_data('view', $check_where);
		}

		if ($chk_mac != $sub_data['macaddress']) // 내가 등록한것은 제외
		{
			if ($check_data['total_num'] == 0)
			{
				$insert_query = "
					insert into receipt_check set
						  comp_idx   = '" . $sub_data['comp_idx'] . "'
						, part_idx   = '" . $sub_data['part_idx'] . "'
						, ri_idx     = '" . $ri_idx . "'
						, rc_idx     = '" . $rc_idx . "'
						, macaddress = '" . $chk_mac . "'
						, read_date  = '" . date('Y-m-d H:i:s') . "'
						, reg_id     = '" . $code_mem . "'
						, reg_date   = '" . date('Y-m-d H:i:s') . "'
				";
				db_query($insert_query);
				query_history($insert_query, 'receipt_check', 'insert');
			}
		}
	}
?>