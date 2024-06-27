<?

//-------------------------------------- 현메뉴명
	function manual_menu_name($code_comp, $code_part, $fmode, $smode)
	{
		$navi_where = " and mi.mode_folder = '" . $fmode . "' and mi.mode_file = '" . $smode . "'";
		$navi_data = menu_info_data("view", $navi_where);

	// 업체별로 메뉴명 가지고 오기
		$sub_where = " and mc.comp_idx = '" . $code_comp . "' and mc.part_idx = '" . $code_part . "' and mc.mi_idx = '" . $navi_data['mi_idx'] . "'";
		$sub_data = menu_company_data('view', $sub_where);

		$navi_name = $sub_data['menu_name'];
		if ($navi_name == '') $navi_name = $navi_data['menu_name'];

		Return $navi_name;
	}

//-------------------------------------- 왼쪽메뉴목록
	function manual_menu_list($comp_idx, $part_idx, $part_yn)
	{
		$data_sql['query_string'] = "
			select
				mc.*
				, mi.menu_name as org_menu_name
				, mi.up_mi_idx, mi.menu_depth, mi.icon_img, mi.mode_type, mi.mode_folder, mi.mode_file
			from
				menu_company mc
				left join menu_info mi on mi.del_yn = 'N' and mi.mi_idx = mc.mi_idx
				left join menu_auth_company mac on mac.del_yn = 'N' and mac.comp_idx = mc.comp_idx and mac.mi_idx = mc.mi_idx
			where
				mc.del_yn = 'N' and mc.comp_idx = '" . $comp_idx . "' and mc.part_idx = '" . $part_idx . "'
				and mi.del_yn = 'N' and mi.view_yn = 'Y'
				and mac.del_yn = 'N' and mac.view_yn = 'Y'
				and mi.mode_type != 'board'
			order by
				mc.sort asc
		";
		$data_sql['page_size'] = '';
		$data_sql['page_num'] = '';
		$auth_list = query_list($data_sql);

		$menu_total = $auth_list['total_num'];
		$left_str = '';

		$sort = 1;
		foreach ($auth_list as $auth_k => $auth_data)
		{
			if (is_array($auth_data))
			{
				$menu_idx       = $auth_data['mi_idx'];
				$menu_up        = $auth_data['up_mi_idx'];
				$menu_depth     = $auth_data['menu_depth'];
				$menu_name      = $auth_data['menu_name'];
				$part_menu_name = $auth_data['part_menu_name'];
				$menu_num       = $auth_data['menu_num'];
				$mode_type      = $auth_data['mode_type'];
				$mode_folder    = $auth_data['mode_folder'];
				$mode_file      = $auth_data['mode_file'];
				$icon_img       = $auth_data['icon_img'];
				$chk_menu_up    = $menu_up . ',' . $menu_idx;
				$chk_depth      = $menu_depth + 1;

				$chk_sort[$menu_depth][$menu_up]++;

				$a_class = '';
				$em_str = '';
				$li_class = '';
				if ($menu_depth == 1)
				{
					$a_class = ' class="icon' . $icon_img . '"';
					$em_str  = '<em></em>';
				}
				else if ($menu_depth == 2)
				{
					if ($chk_sort[$menu_depth][$menu_up] == 1) $li_class = ' class="frist"';
				}

				$chk_up_arr = explode(',', $chk_menu_up);
				$chk_up = '';
				foreach ($chk_up_arr as $chk_up_k => $chk_up_v)
				{
					if ($chk_up_k > 0)
					{
						if ($chk_up_k == 1) $chk_up = $chk_up_v;
						else $chk_up .= '_' . $chk_up_v;
					}
				}
				if ($chk_up == '') $li_id_str  = 'left_0_' . $chk_sort[$menu_depth][$menu_up];
				else $li_id_str  = 'left_' . $chk_up . '_' . $chk_sort[$menu_depth][$menu_up];

				if ($menu_depth == '1')
				{
					$menu_chk[$menu_depth][0][$sort]['menu_up']     = $menu_up;
					$menu_chk[$menu_depth][0][$sort]['chk_menu_up'] = $chk_menu_up;
					$menu_chk[$menu_depth][0][$sort]['menu_idx']    = $menu_idx;
					$menu_chk[$menu_depth][0][$sort]['menu_depth']  = $menu_depth;
					$menu_chk[$menu_depth][0][$sort]['chk_depth']   = $chk_depth;
					$menu_chk[$menu_depth][0][$sort]['menu_name']   = $menu_name;
					$menu_chk[$menu_depth][0][$sort]['menu_num']    = $menu_num;
					$menu_chk[$menu_depth][0][$sort]['menu_sort']   = $chk_sort[$menu_depth][$menu_up];
					$menu_chk[$menu_depth][0][$sort]['em_str']      = $em_str;
					$menu_chk[$menu_depth][0][$sort]['a_class']     = $a_class;
					$menu_chk[$menu_depth][0][$sort]['li_class']    = $li_class;
					$menu_chk[$menu_depth][0][$sort]['li_id_str']   = $li_id_str;
					$menu_chk[$menu_depth][0][$sort]['ul_id_str']   = 'submenu_' . $chk_up;
					$menu_chk[$menu_depth][0][$sort]['mode_folder'] = $mode_folder;
					$menu_chk[$menu_depth][0][$sort]['mode_file']   = $mode_file;
				}
				else
				{
					$menu_chk[$menu_depth][$menu_up][$sort]['menu_up']     = $menu_up;
					$menu_chk[$menu_depth][$menu_up][$sort]['chk_menu_up'] = $chk_menu_up;
					$menu_chk[$menu_depth][$menu_up][$sort]['menu_idx']    = $menu_idx;
					$menu_chk[$menu_depth][$menu_up][$sort]['menu_depth']  = $menu_depth;
					$menu_chk[$menu_depth][$menu_up][$sort]['chk_depth']   = $chk_depth;
					$menu_chk[$menu_depth][$menu_up][$sort]['menu_name']   = $menu_name;
					$menu_chk[$menu_depth][$menu_up][$sort]['menu_num']    = $menu_num;
					$menu_chk[$menu_depth][$menu_up][$sort]['menu_sort']   = $chk_sort[$menu_depth][$menu_up];
					$menu_chk[$menu_depth][$menu_up][$sort]['em_str']      = $em_str;
					$menu_chk[$menu_depth][$menu_up][$sort]['a_class']     = $a_class;
					$menu_chk[$menu_depth][$menu_up][$sort]['li_class']    = $li_class;
					$menu_chk[$menu_depth][$menu_up][$sort]['li_id_str']   = $li_id_str;
					$menu_chk[$menu_depth][$menu_up][$sort]['ul_id_str']   = 'submenu_' . $chk_up;
					$menu_chk[$menu_depth][$menu_up][$sort]['mode_folder'] = $mode_folder;
					$menu_chk[$menu_depth][$menu_up][$sort]['mode_file']   = $mode_file;
				}

				$sort++;
			}
		}

		$str['menu'] = $menu_chk;
		$str['sort'] = $chk_sort;

		Return $str;
	}

//-------------------------------------- 왼쪽메뉴보기
	function manual_menu_view($menu_val, $depth_val, $up_val, $id_val, $sort_val)
	{
		if (is_array($menu_val[$depth_val][$up_val]))
		{
			$left_str = '
			<ul id="' . $id_val . '">';
			$sort_num = 1;
			foreach ($menu_val[$depth_val][$up_val] as $chk_k => $menu_data)
			{
				$menu_idx    = $menu_data['menu_idx'];
				$menu_up     = $menu_data['menu_up'];
				$chk_menu_up = $menu_data['chk_menu_up'];
				$menu_depth  = $menu_data['menu_depth'];
				$chk_depth   = $menu_data['chk_depth'];
				$ul_id_str   = $menu_data['ul_id_str'];
				$li_id_str   = $menu_data['li_id_str'];
				$em_str      = $menu_data['em_str'];
				$menu_name   = $menu_data['menu_name'];
				$a_class     = $menu_data['a_class'];
				$li_class    = $menu_data['li_class'];
				$menu_num    = $menu_data['menu_num'];
				$mode_folder = $menu_data['mode_folder'];
				$mode_file   = $menu_data['mode_file'];

				if ($menu_depth == 2)
				{
					if ($sort_val[$menu_depth][$menu_up] == $sort_num) $li_class = ' class="end"';

					if ($sort_val[$menu_depth][$menu_up] == 1 && $sort_val[$menu_depth][$menu_up] == $sort_num) $li_class = ' class="frist end"';
				}

				$left_str .= '
				<li' . $li_class . ' id="' . $li_id_str . '">
					<a href="javascript:void(0);" onclick="location.href=\'index.php?fmode=' . $mode_folder . '&smode=' . $mode_file . '\'"' . $a_class . '>' . $em_str . $menu_name . '</a>';
				$left_str .= manual_menu_view($menu_val, $chk_depth, $chk_menu_up, $ul_id_str, $sort_val);

				$left_str .= '
				</li>';

				$sort_num++;
			}
			$left_str .= '
			</ul>';
		}
		Return $left_str;
	}
?>