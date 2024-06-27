<?

//------------------------------------ 게시판 링크
	function bbs_link_insert($bs_idx, $b_idx, $_LINK, $set_bbs)
	{
		$link_num  = $_LINK["link_num"];
		$link_param  = $_LINK["link_param"];

		$bbs_where = " and b.bs_idx = '" . $bs_idx . "' and b.b_idx = '" . $b_idx . "'";
		$bbs_data = bbs_info_data('view', $bbs_where);

		$mem_idx = $bbs_data["mem_idx"];
		$reg_id  = $bbs_data["reg_id"];
		$mod_id  = $bbs_data["mod_id"];

		if ($reg_id == "") $link_id = $mem_idx;
		if ($mod_id == "") $link_id = $reg_id;
		else $link_id = $mod_id;

		$reg_date = date("Y-m-d H:i:s");

		for($i = 1; $i <= $link_num; $i++)
		{
			$link_name[$i]   = $link_param['link_name_' . $i];
			$link_url[$i]    = $link_param['link_url_' . $i];
			$link_url[$i]    = str_replace('&', '&amp;', $link_url[$i]);
			$link_url[$i]    = str_replace('http://', '', $link_url[$i]);
			$link_target[$i] = $link_param['link_target_' . $i];

			if ($link_url[$i] != "")
			{
				$where = " and bl.bs_idx = '" . $bs_idx . "' and bl.b_idx = '" . $b_idx . "' and bl.sort = '" . $i . "'";
				$data = bbs_link_data("view", $where);

				if ($data["total_num"] == 0)
				{
					$sql = "
						insert into bbs_link SET
							comp_idx    = '" . $set_bbs['comp_idx'] . "',
							part_idx    = '" . $set_bbs['part_idx'] . "',
							bs_idx      = '" . $bs_idx . "',
							b_idx       = '" . $b_idx . "',
							sort        = '" . $i . "',
							link_name   = '" . $link_name[$i] . "',
							link_url    = '" . $link_url[$i] . "',
							link_target = '" . $link_target[$i] . "',

							reg_id   = '" . $link_id . "',
							reg_date = '" . $reg_date . "'
					";
					db_query($sql);
					query_history($sql, 'bbs_link', 'insert');
				}
				else
				{
					$sql = "
						update bbs_link SET
							link_name   = '" . $link_name[$i] . "',
							link_url    = '" . $link_url[$i] . "',
							link_target = '" . $link_target[$i] . "',

							mod_id   = '" . $link_id . "',
							mod_date = '" . $reg_date . "'
						where
							del_yn = 'N'
							and bs_idx = '" . $bs_idx . "'
							and b_idx = '" . $b_idx . "'
							and sort = '" . $i . "'
					";
					db_query($sql);
					query_history($sql, 'bbs_link', 'update');
				}
			}
		}
	}

//-------------------------------------- 댓글
	function bbs_comment_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "bco.order_idx desc";
		if ($del_type == 1) $where = "bco.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(bco.bco_idx)
			from
				bbs_comment bco
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				bco.*
			from
				bbs_comment bco
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

//-------------------------------------- 파일
	function bbs_file_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "bf.sort asc";
		if ($del_type == 1) $where = "bf.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(bf.bf_idx)
			from
				bbs_file bf
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				bf.*
			from
				bbs_file bf
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

//-------------------------------------- 링크
	function bbs_link_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "bl.sort asc";
		if ($del_type == 1) $where = "bl.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(bl.bl_idx)
			from
				bbs_link bl
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				bl.*
			from
				bbs_link bl
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

//-------------------------------------- 공지글
	function bbs_notice_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "bn.sort asc";
		if ($del_type == 1) $where = "bn.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(bn.bn_idx)
			from
				bbs_notice bn
				left join bbs_info b on bn.del_yn = 'Y' and b.bs_idx = bn.bs_idx and b.b_idx = bn.b_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				bn.*
			from
				bbs_notice bn
				left join bbs_info b on bn.del_yn = 'Y' and b.bs_idx = bn.bs_idx and b.b_idx = bn.b_idx
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

//-------------------------------------- 카테고리
	function bbs_category_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "bc.sort asc";
		if ($del_type == 1) $where = "bc.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(bc.bc_idx)
			from
				bbs_category bc
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				bc.*
			from
				bbs_category bc
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

//-------------------------------------- 게시글
	function bbs_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "b.order_idx desc";
		if ($del_type == 1) $where = "b.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(b.b_idx)
			from
				bbs_info b
				left join bbs_setting bs on bs.del_yn = 'N' and bs.bs_idx = b.bs_idx
				left join bbs_category bc on bc.del_yn = 'N' and bc.bs_idx = b.bs_idx and bc.bc_idx = b.bc_idx
				left join bbs_notice bn on bn.del_yn = 'N' and bn.bs_idx = b.bs_idx and bn.b_idx = b.b_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				b.*
				, bs.subject as bbs_subject
				, bc.menu_name as cate_name
				, bn.bn_idx
			from
				bbs_info b
				left join bbs_setting bs on bs.del_yn = 'N' and bs.bs_idx = b.bs_idx
				left join bbs_category bc on bc.del_yn = 'N' and bc.bs_idx = b.bs_idx and bc.bc_idx = b.bc_idx
				left join bbs_notice bn on bn.del_yn = 'N' and bn.bs_idx = b.bs_idx and bn.b_idx = b.b_idx
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

//-------------------------------------- 게시판 설정
	function bbs_setting_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "bs.reg_date desc";
		if ($del_type == 1) $where = "bs.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(bs.bs_idx)
			from
				bbs_setting bs
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				bs.*
			from
				bbs_setting bs
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view')
		{
			$data_info = query_view($query_string);
		}
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
///// 총 게시판관련 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////
//-------------------------------------- 게시판 설정
	function comp_bbs_setting_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "bs.reg_date desc";
		if ($del_type == 1) $where = "bs.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(bs.bs_idx)
			from
				comp_bbs_setting bs
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				bs.*
			from
				comp_bbs_setting bs
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view')
		{
			$data_info = query_view($query_string);
		}
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

//-------------------------------------- 카테고리
	function comp_bbs_category_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "bc.sort asc";
		if ($del_type == 1) $where = "bc.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(bc.bc_idx)
			from
				comp_bbs_category bc
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				bc.*
			from
				comp_bbs_category bc
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

//-------------------------------------- 게시글
	function comp_bbs_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "b.order_idx desc";
		if ($del_type == 1) $where = "b.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(b.b_idx)
			from
				comp_bbs_info b
				left join comp_bbs_setting bs on bs.del_yn = 'N' and bs.bs_idx = b.bs_idx
				left join comp_bbs_category bc on bc.del_yn = 'N' and bc.bs_idx = b.bs_idx and bc.bc_idx = b.bc_idx
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = b.comp_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				b.*
				, bs.subject as bbs_subject
				, bc.menu_name as cate_name
				, comp.comp_name
			from
				comp_bbs_info b
				left join comp_bbs_setting bs on bs.del_yn = 'N' and bs.bs_idx = b.bs_idx
				left join comp_bbs_category bc on bc.del_yn = 'N' and bc.bs_idx = b.bs_idx and bc.bc_idx = b.bc_idx
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = b.comp_idx
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

//-------------------------------------- 파일
	function comp_bbs_file_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "bf.sort asc";
		if ($del_type == 1) $where = "bf.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(bf.bf_idx)
			from
				comp_bbs_file bf
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				bf.*
			from
				comp_bbs_file bf
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

//-------------------------------------- 댓글
	function comp_bbs_comment_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "bco.order_idx desc";
		if ($del_type == 1) $where = "bco.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(bco.bco_idx)
			from
				comp_bbs_comment bco
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				bco.*
			from
				comp_bbs_comment bco
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

//-------------------------------------- 링크
	function comp_bbs_link_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "bl.sort asc";
		if ($del_type == 1) $where = "bl.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(bl.bl_idx)
			from
				comp_bbs_link bl
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				bl.*
			from
				comp_bbs_link bl
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

//------------------------------------ 게시판 링크
	function comp_bbs_link_insert($bs_idx, $b_idx, $_LINK, $set_bbs)
	{
		$link_num   = $_LINK["link_num"];
		$link_param = $_LINK["link_param"];

		$bbs_where = " and b.bs_idx = '" . $bs_idx . "' and b.b_idx = '" . $b_idx . "'";
		$bbs_data = comp_bbs_info_data('view', $bbs_where);

		$mem_idx = $bbs_data["mem_idx"];
		$reg_id  = $bbs_data["reg_id"];
		$mod_id  = $bbs_data["mod_id"];

		if ($reg_id == "") $link_id = $mem_idx;
		if ($mod_id == "") $link_id = $reg_id;
		else $link_id = $mod_id;

		$reg_date = date("Y-m-d H:i:s");

		for($i = 1; $i <= $link_num; $i++)
		{
			$link_name[$i]   = $link_param['link_name_' . $i];
			$link_url[$i]    = $link_param['link_url_' . $i];
			$link_url[$i]    = str_replace('&', '&amp;', $link_url[$i]);
			$link_url[$i]    = str_replace('http://', '', $link_url[$i]);
			$link_target[$i] = $link_param['link_target_' . $i];

			if ($link_url[$i] != "")
			{
				$where = " and bl.bs_idx = '" . $bs_idx . "' and bl.b_idx = '" . $b_idx . "' and bl.sort = '" . $i . "'";
				$data = comp_bbs_link_data("view", $where);

				if ($data["total_num"] == 0)
				{
					$sql = "
						insert into comp_bbs_link SET
							bs_idx      = '" . $bs_idx . "',
							b_idx       = '" . $b_idx . "',
							sort        = '" . $i . "',
							link_name   = '" . $link_name[$i] . "',
							link_url    = '" . $link_url[$i] . "',
							link_target = '" . $link_target[$i] . "',

							reg_id   = '" . $link_id . "',
							reg_date = '" . $reg_date . "'
					";
					db_query($sql);
					query_history($sql, 'comp_bbs_link', 'insert');
				}
				else
				{
					$sql = "
						update comp_bbs_link SET
							link_name   = '" . $link_name[$i] . "',
							link_url    = '" . $link_url[$i] . "',
							link_target = '" . $link_target[$i] . "',

							mod_id   = '" . $link_id . "',
							mod_date = '" . $reg_date . "'
						where
							del_yn = 'N'
							and bs_idx = '" . $bs_idx . "'
							and b_idx = '" . $b_idx . "'
							and sort = '" . $i . "'
					";
					db_query($sql);
					query_history($sql, 'comp_bbs_link', 'update');
				}
			}
		}
	}

//-------------------------------------- 공지글
	function comp_bbs_notice_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "bn.sort asc";
		if ($del_type == 1) $where = "bn.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(bn.bn_idx)
			from
				comp_bbs_notice bn
				left join comp_bbs_info b on bn.del_yn = 'Y' and b.bs_idx = bn.bs_idx and b.b_idx = bn.b_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				bn.*
			from
				comp_bbs_notice bn
				left join comp_bbs_info b on bn.del_yn = 'Y' and b.bs_idx = bn.bs_idx and b.b_idx = bn.b_idx
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
///// 상담게시판 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------- 지사별 상담분류
	function code_consult_class_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "code.sort asc";
		if ($del_type == 1) $where = "code.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(code.code_idx)
			from
				code_consult_class code
				left join company_part part on part.del_yn = 'N' and part.comp_idx = code.comp_idx and part.part_idx = code.part_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				code.*
				, part.part_name
			from
				code_consult_class code
				left join company_part part on part.del_yn = 'N' and part.comp_idx = code.comp_idx and part.part_idx = code.part_idx
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

//-------------------------------------- 상담정보
	function consult_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "cons.reg_date desc";
		if ($del_type == 1) $where = "cons.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(cons.cons_idx)
			from
				consult_info cons
				left join client_info ci on ci.ci_idx = cons.ci_idx
				left join company_part part on part.del_yn = 'N' and part.part_idx = cons.part_idx
				left join code_consult_class code on code.comp_idx = cons.comp_idx and code.part_idx = cons.part_idx and code.code_idx = cons.consult_class
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				cons.*
				, ci.client_name, ci.del_yn as client_del_yn, ci.link_url
				, part.part_name
				, code.del_yn as class_del_yn
			from
				consult_info cons
				left join client_info ci on ci.ci_idx = cons.ci_idx
				left join company_part part on part.del_yn = 'N' and part.part_idx = cons.part_idx
				left join code_consult_class code on code.comp_idx = cons.comp_idx and code.part_idx = cons.part_idx and code.code_idx = cons.consult_class
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

//-------------------------------------- 상담파일
	function consult_file_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "consf.sort asc";
		if ($del_type == 1) $where = "consf.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(consf.consf_idx)
			from
				consult_file consf
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				consf.*
			from
				consult_file consf
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

//-------------------------------------- 상담댓글
	function consult_comment_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "consc.order_idx desc";
		if ($del_type == 1) $where = "consc.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(consc.consc_idx)
			from
				consult_comment consc
				left join consult_info cons on cons.del_yn = 'N' and cons.cons_idx = consc.cons_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				consc.*
			from
				consult_comment consc
				left join consult_info cons on cons.del_yn = 'N' and cons.cons_idx = consc.cons_idx
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

//-------------------------------------- 상담댓글 파일
	function consult_comment_file_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "conscf.sort asc";
		if ($del_type == 1) $where = "conscf.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(conscf.conscf_idx)
			from
				consult_comment_file conscf
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				conscf.*
			from
				consult_comment_file conscf
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

//-------------------------------------- 상담상태내역
	function consult_status_history_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "conssh.reg_date asc";
		if ($del_type == 1) $where = "conssh.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(conssh.conssh_idx)
			from
				consult_status_history conssh
				left join consult_info cons on cons.del_yn = 'N' and cons.cons_idx = conssh.cons_idx
				left join client_info ci on ci.ci_idx = cons.ci_idx
				left join member_info mem on mem.mem_idx = conssh.mem_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				conssh.*
				, cons.subject
				, ci.client_name, ci.ci_idx, ci.del_yn as client_del_yn
				, mem.mem_name, mem.del_yn as mem_del_yn
			from
				consult_status_history conssh
				left join consult_info cons on cons.del_yn = 'N' and cons.cons_idx = conssh.cons_idx
				left join client_info ci on ci.ci_idx = cons.ci_idx
				left join member_info mem on mem.mem_idx = conssh.mem_idx
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

//-------------------------------------- 상담확인
	function consult_check_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "consch.reg_date desc";
		if ($del_type == 1) $where = "consch.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(consch.consch_idx)
			from
				consult_check consch
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				consch.*
			from
				consult_check consch
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

//-------------------------------------- 지사별 상담분류 현위치
	function consult_class_view($code_idx)
	{
		$where = " and code.code_idx = '" . $code_idx . "'";
		$data = code_consult_class_data('view', $where);
		$arr_up = explode(",", $data["up_code_idx"]);

		$menu_depth = $data['menu_depth'];
		$str['code_name'][$menu_depth] = $data["code_name"];

		if ($menu_depth == 1) // 11단계
		{
			$str['first_class'] = $data["code_name"];
		}
		else if ($menu_depth == 2) // 2단계
		{
			$up_idx = $arr_up[1];

			$up_where = " and code.code_idx = '" . $up_idx . "'";
			$up_data = code_consult_class_data("view", $up_where);


			$up_menu_depth = $up_data['menu_depth'];
			$str['code_name'][$up_menu_depth] = $up_data["code_name"];
			$str['first_class']  = $up_data["code_name"];
			$str['second_class'] = $data["code_name"];
		}
		else // 3단계이후
		{
			for ($i = 1; $i < $menu_depth ; $i++)
			{
				$up_idx  = $arr_up[$i];

				$up_where = " and code.code_idx = '" . $up_idx . "'";
				$up_data = code_consult_class_data("view", $up_where);

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

//-------------------------------------- 상담정보
	function consult_list_data($idx, $data)
	{
		global $local_dir, $comp_dir;

		$comp_member_dir = $comp_dir . '/' . $data['comp_idx'] . '/member';

	// 거래처가 삭제된 경우
		if ($data['client_del_yn'] == 'Y')
		{
			$data['client_search'] = '<span style="color:#CCCCCC">삭제된 거래처</span>';
		}
		else
		{
			$data['client_search'] = '<a href="javascript:void(0);" onclick="search_client(\'ci.client_name\',\'' . $data['client_name'] . '\')">' . $data['client_name'] . '</a>';
		}

	// 분류
		$data['class_str'] = consult_class_view($data['consult_class']);

	// 중요도
		if ($data['important'] == 'CI02') $data['important_str'] = '<span class="btn_level_1"><span>상</span></span>';
		else if ($data['important'] == 'CI03') $data['important_str'] = '<span class="btn_level_2"><span>중</span></span>';
		else if ($data['important'] == 'CI04') $data['important_str'] = '<span class="btn_level_3"><span>하</span></span>';

	// 파일수
		$sub_where = " and consf.cons_idx='" . $data['cons_idx'] . "'";
		$sub_data = consult_file_data('page', $sub_where);
		$data['total_file'] = $sub_data['total_num'];
		if ($data['total_file'] > 0) $data['total_file_str'] = '<span class="attach" title="첨부파일">' . number_format($data['total_file']) . '</span>';
		else $data['total_file_str'] = '';

	// 코멘트수
		$sub_where = " and consc.cons_idx='" . $data['cons_idx'] . "'";
		$sub_data = consult_comment_data('page', $sub_where);
		$data['total_comment'] = $sub_data['total_num'];
		if ($data['total_comment'] > 0) $data['total_comment_str'] = '<span class="cmt" title="코멘트">' . number_format($data['total_comment']) . '</span>';
		else $data['total_comment_str'] = '';

	// 읽을 코멘트
		$cc_data['chk_ci']   = $data['ci_idx'];
		$cc_data['chk_mac']  = $data['macaddress'];
		$cc_data['cons_idx'] = $data['cons_idx'];
		$check_num = consult_read_check($cc_data);
		$data['read_comment'] = $check_num['read_comment'];
		if ($data['read_comment'] > 0) $data['read_comment_str'] = '<span class="today_num" title="읽을 코멘트"><em>' . number_format($data['read_comment']) . '</em></span>';
		else $data['read_comment_str'] = '';
		unset($cc_data);
		unset($check_num);

	// 상담자, 전화번호
		$tel_num = str_replace('-', '', $data['tel_num']);
		$tel_num = str_replace('.', '', $tel_num);
		if ($tel_num == '')
		{
			$data['consult_name'] = $data['writer'];
		}
		else
		{
			$data['consult_name'] = $data['writer'] . '<br />(' . $data['tel_num'] . ')';
		}

	// 담당직원
		$charge_idx = $data['charge_idx'];
		if ($charge_idx != '')
		{
			$charge_arr = explode(',', $charge_idx);
			$charge_len = count($charge_arr);
			$charge_exp = $charge_len - 1;

		// 총담당자구하기
			$total_charge_str = '';
			$charge_num = 1;
			foreach ($charge_arr as $charge_k => $charge_v)
			{
				if ($charge_v != '')
				{
					$mem_where = " and mem.mem_idx = '" . $charge_v . "'";
					$mem_data = member_info_data('view', $mem_where, '', '', '', 2);

					if ($mem_data['total_num'] > 0)
					{
						$charge_name = $mem_data['mem_name'];
						if ($part_ok == 'Y')
						{
							if ($mem_data['del_yn'] == 'Y')
							{
								$charge_name = '[<span style="color:' . $set_color_list2[$mem_data['part_sort']] . '">' . $mem_data['part_name'] . '</span>:' . $mem_data['group_name'] . '] <strong style="color:#CCCCCC">' . $mem_data['mem_name'] . '</strong>';
							}
							else
							{
								$charge_name = '[<span style="color:' . $set_color_list2[$mem_data['part_sort']] . '">' . $mem_data['part_name'] . '</span>:' . $mem_data['group_name'] . '] <strong style="color:#ff6c00">' . $mem_data['mem_name'] . '</strong>';
							}
						}
						else
						{
							if ($mem_data['del_yn'] == 'Y')
							{
								$charge_name = '<span style="color:#CCCCCC">' . $mem_data['mem_name'] . '</span>';
							}
							else
							{
								$charge_name = $mem_data['mem_name'];
							}
						}

						if ($charge_num == '1')
						{
							if ($mem_data['del_yn'] == 'Y')
							{
								$charge_list = '<span style="color:#CCCCCC">' . $mem_data['mem_name'] . '</span>';
							}
							else
							{
								$charge_list = $mem_data['mem_name'];
							}
							if ($charge_len > 1)
							{
								$charge_list .= '외 ' . $charge_exp . '명';
							}
						}

						$total_charge_str .= ', ' . $charge_name;
						$charge_num++;
					}
					unset($mem_data);
				}
			}
			$total_charge_str = substr($total_charge_str, 2, strlen($total_charge_str));
		}
		else
		{
			$charge_list      = '미정';
			$total_charge_str = '미정';
		}

		$data['charge_list']      = $charge_list;
		$data['total_charge_str'] = $total_charge_str;

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

	// 현재글
		if ($idx == $data['cons_idx'])
		{
			$data['number_str'] = '->';
		}
		else
		{
			$data['number_str'] = $num;
		}

		Return $data;
	}

//-------------------------------------- 상담푸쉬
	function consult_push($cons_idx, $cons_type = '')
	{
	// 상담정보
		$chk_where = " and cons.cons_idx = '" . $cons_idx . "'";
		$chk_data = consult_info_data('view', $chk_where);

		$comp_idx    = $chk_data['comp_idx'];
		$part_idx    = $chk_data['part_idx'];
		$charge_idx  = $chk_data['charge_idx'];
		$subject     = $chk_data['subject'];
		$client_name = $chk_data['client_name'];
		unset($chk_data);

		$msg_type = 'consult';
		$message  = strip_tags($subject);

		if ($cons_type == 'comment')
		{
			$message = '[' . $client_name . ':상담댓글] ' . string_cut($message, 10);
		}
		else
		{
			$message = '[' . $client_name . ':상담] ' . string_cut($message, 10);
		}

	// 담당자정보
		$charge_arr = explode(',', $charge_idx);
		foreach ($charge_arr as $k => $v)
		{
			if ($v != '')
			{
				$mem_where = " and mem.mem_idx = '" . $v . "'";
				$mem_data = member_info_data('view', $mem_where);
				if ($mem_data['total_num'] > 0)
				{
					$mem_idx  = $mem_data['mem_idx'];
					$receiver = $mem_data['mem_id'];

					//$push = new PUSH("bizstory_push");
					//$result = @$push->push_send($sender, $comp_idx, $part_idx, $mem_idx, $receiver, $msg_type, $message);
                    push_send($sender, $comp_idx, $part_idx, $mem_idx, $receiver, $msg_type, $message);
				}
				unset($mem_data);
			}
		}
	}

//-------------------------------------- 상담댓글 읽을 값
	function consult_read_check($chk_data)
	{
		$chk_ci   = $chk_data['chk_ci'];
		$chk_mac  = $chk_data['chk_mac'];
		$cons_idx = $chk_data['cons_idx'];

		$consult_check = 0; $comment_no = 0;

		if ($cons_idx == '') // 총값
		{
		// 댓글
			$comment_where = "
				and cons.ci_idx = '" . $chk_ci . "' and cons.macaddress = '" . $chk_mac . "' and consc.macaddress != '" . $chk_mac . "' and cons.del_yn = 'N'";
		}
		else
		{
		// 댓글
			$comment_where = "
				and cons.ci_idx = '" . $chk_ci . "' and consc.cons_idx = '" . $cons_idx . "' and consc.macaddress != '" . $chk_mac . "'
			";
		}

	// 댓글
		$comment_list = consult_comment_data('list', $comment_where, '', '', '');
		foreach ($comment_list as $comment_k => $data)
		{
			if (is_array($data))
			{
				$check_where = " and consch.cons_idx = '" . $data['cons_idx'] . "' and consch.consc_idx = '" . $data['consc_idx'] . "' and consch.macaddress = '" . $chk_mac . "'";
				$check_data = consult_check_data('view', $check_where);
				if ($check_data['total_num'] == 0)
				{
					$cons_idx = $data['cons_idx'];
					$ri_chk[$cons_idx] = $cons_idx;
					$comment_no++;
				}
				unset($check_data);
			}
		}
		unset($data);
		unset($comment_list);

	// 총값
		$consult_check = $comment_no;

		if ($consult_check > 0)
		{
			$chk_num = 1;
			$add_where = " and (";
			foreach ($ri_chk as $k => $v)
			{
				if ($chk_num == 1)
				{
					$add_where .= " cons.cons_idx = '" . $v . "'";
				}
				else
				{
					$add_where .= " or cons.cons_idx = '" . $v . "'";
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
		$str['consult_check'] = $consult_check;
		$str['consult_where'] = $add_where;

		Return $str;
	}

//-------------------------------------- 상담 코멘트 읽은 확인
	function consult_data_read($chk_data)
	{
		$chk_mac   = $chk_data['chk_mac'];
		$cons_idx  = $chk_data['cons_idx'];
		$consc_idx = $chk_data['consc_idx'];

		if ($consc_idx != '')
		{
			$sub_where = " and consc.consc_idx = '" . $consc_idx . "'";
			$sub_data = consult_comment_data('view', $sub_where);

			$check_where = " and consch.cons_idx = '" . $cons_idx . "' and consch.consc_idx = '" . $consc_idx . "' and consch.macaddress = '" . $chk_mac . "'";
			$check_data = consult_check_data('view', $check_where);
		}

		if ($chk_mac != $sub_data['macaddress']) // 내가 등록한것은 제외
		{
			if ($check_data['total_num'] == 0)
			{
				$insert_query = "
					insert into consult_check set
						  comp_idx   = '" . $sub_data['comp_idx'] . "'
						, part_idx   = '" . $sub_data['part_idx'] . "'
						, cons_idx   = '" . $cons_idx . "'
						, consc_idx  = '" . $consc_idx . "'
						, macaddress = '" . $chk_mac . "'
						, read_date  = '" . date('Y-m-d H:i:s') . "'
						, reg_id     = '" . $code_mem . "'
						, reg_date   = '" . date('Y-m-d H:i:s') . "'
				";
				db_query($insert_query);
				query_history($insert_query, 'consult_check', 'insert');
			}
		}
	}
?>