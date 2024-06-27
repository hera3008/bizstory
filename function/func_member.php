<?

//-------------------------------------- 등록, 수정시 담당자폼
	function form_charge_view($input_name, $charge_idx, $part_list, $script_arr, $charge_type = 'default')
	{
		global $set_color_list2, $sess_str;

		$chk_part = $_SESSION[$sess_str . '_part_idx'];
		$chk_mem  = $_SESSION[$sess_str . '_mem_idx'];

		$charge_idx_arr = explode(',', $charge_idx);
		$script_chk_arr = explode(';', $script_arr);
		$script_view    = '';
		foreach ($script_chk_arr as $k => $v)
		{
			$script_view .= $v . ';';
		}

		$change_str = '';
        
	// 지사별
		foreach ($part_list as $part_k => $part_data)
		{
			if (is_array($part_data))
			{
				$part_idx     = $part_data['part_idx'];
				$part_name    = $part_data['part_name'];
				$part_sort    = $part_data['sort'];
				$part_color   = $set_color_list2[$part_sort];
				$part_check   = 'partidx' . $part_idx;
				$part_ul_id   = 'part_charge_view_' . $part_idx;
				$part_span_id = 'part_charge_btn_' . $part_idx;

				$change_str .= '
					<div class="charge_view_box left">
						<ul>
							<li class="first">
								<label for="' . $part_check . '">
									<input type="checkbox" class="type_checkbox" title="' . $part_name . '" name="' . $part_check . '" id="' . $part_check . '" onclick="check_all2(\'' . $part_check . '\', this, \'1\');' . $script_view . '" />
									<span style="color:' . $part_color . '">' . $part_name . '</span>
								</label>
								<span onclick="part_charge_chk(\'' . $part_idx . '\')" class="pointer" id="' . $part_span_id . '"><img src="../../common/images/icon/icon_p.png" alt="펼치기" /></span>
							</li>
						</ul>
					</div>
					<div class="charge_view_box left none" id="' . $part_ul_id . '">';

			// 그룹별
				$group_where = " and csg.part_idx = '" . $part_idx . "'";
				$group_list = company_staff_group_data('list', $group_where, '', '', '');
                
				foreach ($group_list as $group_k => $group_data)
				{
					if (is_array($group_data))
					{
						$group_idx   = $group_data['csg_idx'];
						$group_name  = $group_data['group_name'];
						$group_check = $part_check . '-' . $group_idx;

					// 직원
						$mem_where = " and mem.part_idx = '" . $part_idx . "' and mem.csg_idx = '" . $group_idx . "' and mem.auth_yn = 'Y' and mem.login_yn = 'Y'";
                        
                        if ($charge_type == 'project') {
                            $mem_where .= " and mem.mem_idx in (" . $charge_idx . ") ";
                        }
                        
						$mem_order = "csg.sort asc, cpd.sort asc, mem.mem_name asc";
						$mem_list = member_info_data('list', $mem_where, $mem_order, '', '');
                        
						if ($mem_list['total_num'] > 0)
						{
							$change_str .= '
						<ul>
							<li class="second">
								<label for="' . $group_check . '">
									<input type="checkbox" class="type_checkbox" title="' .$group_name . '" name="' . $group_check . '" id="' . $group_check . '" onclick="check_all2(\'' . $group_check . '\', this, \'0\');' . $script_view . '" />
									<span>' . $group_name . '</span>
								</label>
								<ul>';

							foreach ($mem_list as $mem_k => $mem_data)
							{
								if (is_array($mem_data))
								{
									$mem_idx   = $mem_data['mem_idx'];
									$mem_name  = $mem_data['mem_name'];
									$mem_check = $group_check . '_' . $mem_idx;

									$checked = ''; $disabled = '';
									if (is_array($charge_idx_arr))
									{
										foreach ($charge_idx_arr as $charge_k => $charge_v)
										{
											if ($mem_idx == $charge_v)
											{
												if ($charge_type != 'project') $checked  = ' checked="checked"';
												if ($charge_type == 'default') $disabled = ' disabled="disabled"';
												else $disabled = '';

												$part_charge_on[$part_idx] = '
													$("#' . $part_ul_id . '").css({"display": "block"});
													$("#' . $part_span_id . '").val(" - ");';
												break;
											}
										}
									}
									$total_member++;
									if ($charge_type == 'msg')
									{
										if ($chk_mem == $mem_idx)
										{
											$disabled = ' disabled="disabled"';
											$checked  = '';
										}
									}

									$change_str .= '
									<li class="mem_name">
										<label for="' . $mem_check . '">
											<input type="checkbox" name="' . $input_name . '" id="' . $mem_check . '" value="' . $mem_idx . '" class="type_checkbox"' . $checked . $disabled . ' title="' . $mem_name . '" onclick="' . $script_view . '" /> ' . $mem_name . '
										</label>
									</li>';
								}
							}
							$change_str .= '
								</ul>
							</li>
						</ul>';
						}
					}
				}
				$change_str .= '
					</div>';
			}
		}
		if (is_array($part_charge_on))
		{
			foreach ($part_charge_on as $on_k => $on_v)
			{
				$script_charge_on .= $on_v;
			}
		}

		$script = '
			$("#part_charge_btn_' . $chk_part . '").html(" <img src=\'../../common/images/icon/icon_m.png\' alt=\'접기\' /> ");
			$("#part_charge_view_' . $chk_part . '").css({"display": "block"});

			' . $script_charge_on . '

			function part_charge_chk(idx)
			{
				$("#part_charge_btn_" + idx).html(" <img src=\'../../common/images/icon/icon_m.png\' alt=\'접기\' /> ");
				$("#part_charge_view_" + idx).css({"display": "block"});
			}
		';

		$str['change_view']   = $change_str;
		$str['change_script'] = $script;
		$str['change_total']  = $total_member;

		Return $str;
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////
/////
///// 쪽지관련 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------- 받은쪽지
	function message_receive_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "mr.reg_date desc";
		if ($del_type == 1) $where = "mr.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(mr.mr_idx)
			from
				message_receive mr
				left join message_send ms on ms.del_yn = 'N' and ms.ms_idx = mr.ms_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				mr.*
				, ms.mem_name as send_mem_name, ms.mem_id as send_mem_id
				, ms.remark
			from
				message_receive mr
				left join message_send ms on ms.del_yn = 'N' and ms.ms_idx = mr.ms_idx
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

//-------------------------------------- 보낸쪽지
	function message_send_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "ms.reg_date desc";
		if ($del_type == 1) $where = "ms.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ms.ms_idx)
			from
				message_send ms
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ms.*
			from
				message_send ms
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

//-------------------------------------- 쪽지파일
	function message_file_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "msgf.reg_date desc";
		if ($del_type == 1) $where = "msgf.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(msgf.msgf_idx)
			from
				message_file msgf
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				msgf.*
			from
				message_file msgf
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
///// 직원 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------- 회원정보(직원)
	function member_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "mem.reg_date desc";
		if ($del_type == 1) $where = "mem.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(mem.mem_idx)
			from
				member_info mem
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = mem.comp_idx
				/*left join company_part part on part.del_yn = 'N' and part.comp_idx = mem.comp_idx and part.part_idx = mem.part_idx*/
				left join company_staff_duty csd on csd.del_yn = 'N' and csd.comp_idx = mem.comp_idx and csd.csd_idx = mem.csd_idx
				left join company_staff_group csg on csg.del_yn = 'N' and csg.comp_idx = mem.comp_idx and csg.csg_idx = mem.csg_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				mem.*
				, comp.comp_name
				/*, part.part_name, part.sort as part_sort*/
				, csd.duty_name
				, csg.group_name, csg.sort as group_sort
				, comp.api_yn, comp.api_key
			from
				member_info mem
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = mem.comp_idx
				/*left join company_part part on part.del_yn = 'N' and part.comp_idx = mem.comp_idx and part.part_idx = mem.part_idx*/
				left join company_staff_duty csd on csd.del_yn = 'N' and csd.comp_idx = mem.comp_idx and csd.csd_idx = mem.csd_idx
				left join company_staff_group csg on csg.del_yn = 'N' and csg.comp_idx = mem.comp_idx and csg.csg_idx = mem.csg_idx
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
	
//-------------------------------------- 직원레이어
    function staff_layer_data($mem_data, $mem_type, $add_mem, $set_part_work_yn, $set_color_list2, $layer_name = 'stafflayer', $idx = '0', $display = 'default')
    {
        $mem_name = $add_mem . $mem_data[$mem_type . '_mem_name'] . '&nbsp;<span style="font-size:11px">' . $mem_data[$mem_type . '_duty_name'] . '</span>';

        if ($mem_data[$mem_type . '_mem_name'] != '')
        {
            $part_ok = 'N';
            if ($display == 'default')
            {
                if ($set_part_work_yn == 'Y')
                {
                    if ($mem_data[$mem_type . '_part_name'] != '') $part_ok = 'Y';
                    unset($part_data);
                }
            }

            $name_idx = $layer_name . '_' . $mem_data[$mem_type . '_mem_idx'] . '_' . $idx;
            if ($part_ok == 'Y')
            {
                if ($mem_data[$mem_type . '_del_yn'] == 'Y')
                {
                    $charge_name = '[<span style="color:' . $set_color_list2[$mem_data[$mem_type . '_part_sort']] . '">' . $mem_data[$mem_type . '_part_name'] . '</span>:' . $mem_data[$mem_type . '_group_name'] . '] <strong style="color:#CCCCCC">' . $mem_name . '</strong>';
                }
                else
                {
                    $charge_name = '[<span style="color:' . $set_color_list2[$mem_data[$mem_type . '_part_sort']] . '">' . $mem_data[$mem_type . '_part_name'] . '</span>:' . $mem_data[$mem_type . '_group_name'] . '] <a href="javascript:void(0)" onclick="staff_layer_open(\'' . $mem_data[$mem_type . '_mem_idx'] . '\');"><strong style="color:#ff6c00" id="' . $name_idx . '">' . $mem_name . '</strong></a>';
                }
            }
            else
            {
                if ($mem_data[$mem_type . '_del_yn'] == 'Y')
                {
                    $charge_name = '[' . $mem_data[$mem_type . '_group_name'] . '] <span style="color:#CCCCCC">' . $mem_name . '</span>';
                }
                else
                {
                    $charge_name = '[' . $mem_data[$mem_type . '_group_name'] . '] <a href="javascript:void(0)" onclick="staff_layer_open(\'' . $mem_data[$mem_type . '_mem_idx'] . '\');"><span style="color:#ff6c00" id="' . $name_idx . '">' . $mem_name . '</span></a>';
                }
            }

        // 단순 지사-그룹-직원
            if ($display == 'memlist')
            {
                if ($mem_data[$mem_type . '_part_name'] != '') $part_ok = 'Y';
                else $part_ok = 'N';
                unset($part_data);

                if ($part_ok == 'Y')
                {
                    if ($mem_data[$mem_type . '_del_yn'] == 'Y')
                    {
                        $charge_name = '[<span style="color:' . $set_color_list2[$mem_data[$mem_type . '_part_sort']] . '">' . $mem_data[$mem_type . '_part_name'] . '</span>:' . $mem_data[$mem_type . '_group_name'] . '] <strong style="color:#CCCCCC">' . $mem_name . '</strong>';
                    }
                    else
                    {
                        $charge_name = '[<span style="color:' . $set_color_list2[$mem_data[$mem_type . '_part_sort']] . '">' . $mem_data[$mem_type . '_part_name'] . '</span>:' . $mem_data[$mem_type . '_group_name'] . '] <strong style="color:#ff6c00">' . $mem_name . '</strong>';
                    }
                }
                else
                {
                    if ($mem_data[$mem_type . '_del_yn'] == 'Y')
                    {
                        $charge_name = '[' . $mem_data[$mem_type . '_group_name'] . '] <span style="color:#CCCCCC">' . $mem_name . '</span>'; 
                    }
                    else
                    {
                        $charge_name = '[' . $mem_data[$mem_type . '_group_name'] . '] <span style="color:#ff6c00" id="' . $name_idx . '">' . $mem_name . '</span>';
                    }
                }
            }
        }
        else
        {
            $charge_name = '';
        }
        unset($mem_data);

        Return $charge_name;
    }

	
//-------------------------------------- 직원레이어
	function staff_layer_form($mem_idx, $add_mem, $set_part_work_yn, $set_color_list2, $layer_name = 'stafflayer', $idx = '0', $display = 'default')
	{
		$mem_where = " and mem.mem_idx = '" . $mem_idx . "'";
		$mem_data = member_info_data('view', $mem_where, '', '', '', 2);
		$mem_name = $add_mem . $mem_data['mem_name'] . '&nbsp;<span style="font-size:11px">' . $mem_data['duty_name'] . '</span>';

		if ($mem_data['total_num'] > 0)
		{
			$part_ok = 'N';
			if ($display == 'default')
			{
				if ($set_part_work_yn == 'Y')
				{
					$part_where = " and part.comp_idx = '" . $mem_data['comp_idx'] . "'";
					$part_data = company_part_data('page', $part_where);

					if ($part_data['total_num'] > 1) $part_ok = 'Y';
					unset($part_data);
				}
			}

			$name_idx = $layer_name . '_' . $mem_data['mem_idx'] . '_' . $idx;
			if ($part_ok == 'Y')
			{
				if ($mem_data['del_yn'] == 'Y')
				{
					$charge_name = '[<span style="color:' . $set_color_list2[$mem_data['part_sort']] . '">' . $mem_data['part_name'] . '</span>:' . $mem_data['group_name'] . '] <strong style="color:#CCCCCC">' . $mem_name . '</strong>';
				}
				else
				{
					$charge_name = '[<span style="color:' . $set_color_list2[$mem_data['part_sort']] . '">' . $mem_data['part_name'] . '</span>:' . $mem_data['group_name'] . '] <a href="javascript:void(0)" onclick="staff_layer_open(\'' . $mem_data['mem_idx'] . '\');"><strong style="color:#ff6c00" id="' . $name_idx . '">' . $mem_name . '</strong></a>';
				}
			}
			else
			{
				if ($mem_data['del_yn'] == 'Y')
				{
					$charge_name = '[' . $mem_data['group_name'] . '] <span style="color:#CCCCCC">' . $mem_name . '</span>';
				}
				else
				{
					$charge_name = '[' . $mem_data['group_name'] . '] <a href="javascript:void(0)" onclick="staff_layer_open(\'' . $mem_data['mem_idx'] . '\');"><span style="color:#ff6c00" id="' . $name_idx . '">' . $mem_name . '</span></a>';
				}
			}

		// 단순 지사-그룹-직원
			if ($display == 'memlist')
			{
				$part_where = " and part.comp_idx = '" . $mem_data['comp_idx'] . "'";
				$part_data = company_part_data('page', $part_where);

				if ($part_data['total_num'] > 1) $part_ok = 'Y';
				else $part_ok = 'N';
				unset($part_data);

				if ($part_ok == 'Y')
				{
					if ($mem_data['del_yn'] == 'Y')
					{
						$charge_name = '[<span style="color:' . $set_color_list2[$mem_data['part_sort']] . '">' . $mem_data['part_name'] . '</span>:' . $mem_data['group_name'] . '] <strong style="color:#CCCCCC">' . $mem_name . '</strong>';
					}
					else
					{
						$charge_name = '[<span style="color:' . $set_color_list2[$mem_data['part_sort']] . '">' . $mem_data['part_name'] . '</span>:' . $mem_data['group_name'] . '] <strong style="color:#ff6c00">' . $mem_name . '</strong>';
					}
				}
				else
				{
					if ($mem_data['del_yn'] == 'Y')
					{
						$charge_name = '[' . $mem_data['group_name'] . '] <span style="color:#CCCCCC">' . $mem_name . '</span>'; 
					}
					else
					{
						$charge_name = '[' . $mem_data['group_name'] . '] <span style="color:#ff6c00" id="' . $name_idx . '">' . $mem_name . '</span>';
					}
				}
			}
		}
		else
		{
			$charge_name = '';
		}
		unset($mem_data);

		Return $charge_name;
	}


	/** 모바일 용
	 * mem_idx : 사용자idx
	 * add_mem : 
	 * set_part_work_yn : 업무지사통합여부
	 * set_color_list2 : 
	 * layer_name : 레이어 이름
	 * idx : 업무 idx
	 * display : 
	 */
	function staff_layer_form2($mem_idx, $add_mem, $set_part_work_yn, $set_color_list2, $layer_name = 'stafflayer', $idx = '0', $display = 'default')
	{
		$mem_where = " and mem.mem_idx = '" . $mem_idx . "'";
		$mem_data = member_info_data('view', $mem_where, '', '', '', 2);
		$mem_name = $add_mem . $mem_data['mem_name'];

		if ($mem_data['total_num'] > 0)
		{
			$part_ok = 'N';
			if ($display == 'default')
			{
				//업무지시통합인 경우??
				if ($set_part_work_yn == 'Y')
				{
					$part_where = " and part.comp_idx = '" . $mem_data['comp_idx'] . "'";
					$part_data = company_part_data('page', $part_where);

					if ($part_data['total_num'] > 1) $part_ok = 'Y';
					unset($part_data);
				}
			}

			$name_idx = $layer_name . '_' . $mem_data['mem_idx'] . '_' . $idx;
			if ($display == 'worklist') {
				if ($part_ok == 'Y')
				{
					$charge_name = '[<span style="color:' . $set_color_list2[$mem_data['part_sort']] . '">' . $mem_data['part_name'] . '</span>:' . $mem_data['group_name'] . '] <strong style="color:#CCCCCC">' . $mem_name . '</strong>';					
				}
				else
				{
					$charge_name = '<span class="c_d ml4"><em class="c_orange">' . $mem_name . '</em>';
				}
			} else {
				if ($part_ok == 'Y')
				{
					if ($mem_data['del_yn'] == 'Y')
					{
						$charge_name = '[<span style="color:' . $set_color_list2[$mem_data['part_sort']] . '">' . $mem_data['part_name'] . '</span>:' . $mem_data['group_name'] . '] <strong style="color:#CCCCCC">' . $mem_name . '</strong>';
					}
					else
					{
						$charge_name = '[<span style="color:' . $set_color_list2[$mem_data['part_sort']] . '">' . $mem_data['part_name'] . '</span>:' . $mem_data['group_name'] . '] <a href="javascript:" onclick="viewMemInfo(' . $mem_idx . ')" class="md-trigger" data-modal="modal"><strong style="color:#ff6c00" id="' . $name_idx . '">' . $mem_name . '</strong></a>';
					}
				}
				else
				{
					if ($mem_data['del_yn'] == 'Y')
					{
						$charge_name = '<span class="c_d ml4"><em class="c_grey">' . $mem_name . '</em>';
					}
					else
					{
						$charge_name = '<span class="c_d ml4" id="' . $name_idx . '"><a href="javascript:" onclick="viewMemInfo(' . $mem_idx . ')" class="md-trigger" data-modal="modal"><em class="c_orange">' . $mem_name . '</em></a>';
					}
				}				
			}


		// 단순 지사-그룹-직원
			if ($display == 'memlist')
			{
				$part_where = " and part.comp_idx = '" . $mem_data['comp_idx'] . "'";
				$part_data = company_part_data('page', $part_where);

				if ($part_data['total_num'] > 1) $part_ok = 'Y';
				else $part_ok = 'N';
				unset($part_data);

				if ($part_ok == 'Y')
				{
					if ($mem_data['del_yn'] == 'Y')
					{
						$charge_name = '[<span style="color:' . $set_color_list2[$mem_data['part_sort']] . '">' . $mem_data['part_name'] . '</span>:' . $mem_data['group_name'] . '] <strong style="color:#CCCCCC">' . $mem_name . '</strong>';
					}
					else
					{
						$charge_name = '[<span style="color:' . $set_color_list2[$mem_data['part_sort']] . '">' . $mem_data['part_name'] . '</span>:' . $mem_data['group_name'] . '] <strong style="color:#ff6c00">' . $mem_name . '</strong>';
					}
				}
				else
				{
					if ($mem_data['del_yn'] == 'Y')
					{
						$charge_name = '<span style="color:#CCCCCC">' . $mem_name . '</span>';
					}
					else
					{
						$charge_name = '<span class="c_d ml4" id="' . $name_idx . '"><em class="c_orange">' . $mem_name . '</em></span>';
					}
				}
			}
		}
		else
		{
			$charge_name = '';
		}
		unset($mem_data);
		
		Return $charge_name;
	}



//-------------------------------------- SMS 내역
	function message_sms_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "sms.reg_date desc";
		if ($del_type == 1) $where = "sms.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(sms.sms_idx)
			from
				message_sms sms
				left join member_info mem on mem.del_yn = 'N' and mem.comp_idx = sms.comp_idx and mem.mem_idx = sms.send_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				sms.*
				, mem.mem_name
			from
				message_sms sms
				left join member_info mem on mem.del_yn = 'N' and mem.comp_idx = sms.comp_idx and mem.mem_idx = sms.send_idx
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

//-------------------------------------- 로그인하기 - 사이트 메인화면에서
	function member_login_action($mem_data, $sess_str, $auto_login = '', $en_key = '')
	{
		if ($mem_data['comp_idx'] == 0) $mem_data['comp_idx'] = '';
		if ($mem_data['part_idx'] == 0) $mem_data['part_idx'] = '';
		if ($mem_data['ubstory_level'] == '') $mem_data['ubstory_level'] = '91';
		if ($mem_data['ubstory_yn'] == '') $mem_data['ubstory_yn'] = 'N';

		$_SESSION[$sess_str . '_mem_idx']       = $mem_data['mem_idx'];
		$_SESSION[$sess_str . '_mem_id']        = $mem_data['mem_id'];
		$_SESSION[$sess_str . '_mem_name']      = $mem_data['mem_name'];
		$_SESSION[$sess_str . '_comp_idx']      = $mem_data['comp_idx'];
		$_SESSION[$sess_str . '_part_idx']      = $mem_data['part_idx'];
		$_SESSION[$sess_str . '_ubstory_level'] = $mem_data['ubstory_level'];
		$_SESSION[$sess_str . '_ubstory_yn']    = $mem_data['ubstory_yn'];
        $_SESSION[$sess_str . '_empowerment_yn'] = $mem_data['empowerment_yn'];

		// 자동로그인이 설정되어있으면 로그인값을 세션에 담는다.
		if ($auto_login == "Y") {
			unset($_SESSION[$sess_str . '_auto_login']);
			
			$cookie_value  = '!@#' . $mem_data['mem_id'] . '!@#' . $mem_data['mem_pwd'];
			$cookie_change = encrypt($cookie_value, $en_key);
			$_SESSION[$sess_str . '_auto_login'] = $cookie_change;			
		}

	// 30일 지난 받은쪽지 삭제
		global $ip_address;

		$query_str = "
			update message_receive set
				  del_yn   = 'Y'
				, del_ip   = '" . $ip_address . "'
				, del_id   = 'auto'
				, del_date = '" . date("Y-m-d H:i:s") . "'
			where
				del_yn = 'N'
				and mem_idx = '" . $mem_data["mem_idx"] . "'
				and recv_keep = 'N'
				and date_format(DATE_ADD(reg_date, INTERVAL 30 DAY),'%Y%m%d') < '" . date("Ymd") . "'
		";
		//db_query($query_str);
		//query_history($query_str, 'message_receive', 'update');
	}

//-------------------------------------- 회원파일(직원)
	function member_file_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "mf.sort asc";
		if ($del_type == 1) $where = "mf.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(mf.mf_idx)
			from
				member_file mf
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				mf.*
				, mem.mem_name
			from
				member_file mf
				left join member_info mem on mem.del_yn = 'N' and mem.mem_idx = mf.mem_idx
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

//-------------------------------------- 회원별 즐겨찾기
	function member_bookmark_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "mb.reg_date desc";
		if ($del_type == 1) $where = "mb.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(mb.mb_idx)
			from
				member_bookmark mb
				left join menu_info mi on mi.del_yn = 'N' and mi.mi_idx = mb.mi_idx
				left join menu_auth_company mac on mac.del_yn = 'N' and mac.comp_idx = mb.comp_idx and mac.mi_idx = mb.mi_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				mb.*
				, mi.menu_name, mi.mode_folder, mi.mode_file
			from
				member_bookmark mb
				left join menu_info mi on mi.del_yn = 'N' and mi.mi_idx = mb.mi_idx
				left join menu_auth_company mac on mac.del_yn = 'N' and mac.comp_idx = mb.comp_idx and mac.mi_idx = mb.mi_idx
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

//-------------------------------------- 회원관련 정보
	function member_chk_data($mem_idx)
	{
		global $sess_str;

		$code_comp = $_SESSION[$sess_str . '_comp_idx'];
		$code_part = $_SESSION[$sess_str . '_part_idx'];

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 회원정보
		$member_query = "
			select
				total_visit, last_date
			from
				member_info
			where
				mem_idx = '" . $mem_idx . "'
		";
		$mem_data = query_view($member_query);

		$str['total_login'] = $mem_data['total_visit'];
		$str['last_login']  = $mem_data['last_date'];
	
		

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 총업무
		$work_query = "
			select
				count(wi_idx)
			from
				work_info
			where
				del_yn = 'N'
				and comp_idx = '" . $code_comp . "'
				and (concat(',', charge_idx, ',') like '%," . $mem_idx . ",%' or apply_idx = '" . $mem_idx . "' or reg_id = '" . $mem_idx . "')
		";
        //echo $work_query . '<br>';
		$work_all = query_page($work_query);
		$str['work_all'] = $work_all['total_num'];

	// 보류(WS80), 완료(WS90), 종료(WS99), 취소(WS50)
		$work_ing_query = $work_query . "
				and work_status <> 'WS80' and work_status <> 'WS90' and work_status <> 'WS99' and work_status <> 'WS50'
		";
		//echo $work_ing_query . '<br>';
		$work_ing = query_page($work_ing_query);
		$str['work_ing'] = $work_ing['total_num'];

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 총접수
	
		$receipt_query = "
			select
				ri.ri_idx
			from
				receipt_info ri
				left join receipt_info_detail rid on rid.del_yn = 'N' and rid.comp_idx = ri.comp_idx and rid.ri_idx = ri.ri_idx
				left join client_info ci on ci.del_yn = 'N' and ci.comp_idx = ri.comp_idx and ci.ci_idx = ri.ci_idx
			where
				ri.del_yn = 'N'
				and ri.comp_idx = '" . $code_comp . "' and ri.part_idx = '" . $code_part . "'
				and (
					if (ifnull(rid.mem_idx, '') = ''
						, if (ifnull(ri.charge_mem_idx, '') = ''
							, ci.mem_idx
							, ri.charge_mem_idx)
						, rid.mem_idx) = '" . $mem_idx . "')
		";
        //echo $receipt_query . '<br>';
		// and ri.charge_mem_idx = '" . $mem_idx . "'
		$receipt_all = query_view($receipt_query);
		$str['receipt_all'] = $receipt_all['total_num'];
	
	// 미완료접수
		$receipt_ing_query = $receipt_query . "
				and ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60'
		";
        //echo $receipt_ing_query . '<br>';
		$receipt_ing = query_view($receipt_ing_query);
		$str['receipt_ing'] = $receipt_ing['total_num'];

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 미확인쪽지
		$msg_where = "
			del_yn = 'N'
			and comp_idx = '" . $code_comp . "'
			and mem_idx = '" . $mem_idx . "'
			and recv_keep = 'N'
		";

		$msg_all_query = "
			select
				count(mr_idx)
			from
				message_receive
			where
				" . $msg_where . "
		";
		$msg_all = query_page($msg_all_query);
		$str['msg_all'] = $msg_all['total_num'];

		$msg_ing_query = "
			select
				count(mr_idx)
			from
				message_receive
			where
				" . $msg_where . "
				and date_format(read_date, '%Y-%m-%d') = '0000-00-00'
		";
		$msg_ing = query_page($msg_ing_query);
		$str['msg_ing'] = $msg_ing['total_num'];
		
		return $str;
	}

//-------------------------------------- 회원사진
	function member_img_view($mem_idx, $mem_dir)
	{
		global $local_dir;

		$where = " and mem.mem_idx = '" . $mem_idx . "'";
		$mem_data  = member_info_data('view', $where);

		$mf_where = " and mf.mem_idx = '" . $mem_idx. "' and mf.sort = 1";
		$mf_data  = member_file_data('view', $mf_where);
		if ($mf_data['img_sname'] != '')
		{
			$staff_img_80 = '<img class="photo" src="' . $mem_dir . '/' . $mf_data['mem_idx'] . '/' . $mf_data['img_sname'] . '" alt="' . $mem_data['mem_name'] . '" width="80px" height="80px" />';
			$staff_img_53 = '<img class="photo" src="' . $mem_dir . '/' . $mf_data['mem_idx'] . '/' . $mf_data['img_sname'] . '" alt="' . $mem_data['mem_name'] . '" width="53px" height="70px" />';
			$staff_img_26 = '<img class="photo" src="' . $mem_dir . '/' . $mf_data['mem_idx'] . '/' . $mf_data['img_sname'] . '" alt="' . $mem_data['mem_name'] . '" width="26px" height="26px" />';
			$staff_img_22 = '<img class="photo" src="' . $mem_dir . '/' . $mf_data['mem_idx'] . '/' . $mf_data['img_sname'] . '" alt="' . $mem_data['mem_name'] . '" width="22px" height="22px" />';
			$staff_img_35 = '<img class="photo" src="' . $mem_dir . '/' . $mf_data['mem_idx'] . '/' . $mf_data['img_sname'] . '" alt="' . $mem_data['mem_name'] . '" width="35px" height="35px" />';
		}
		else
		{
			$staff_img_80 = '<img class="photo" src="' . $local_dir . '/bizstory/images/tfuse-top-panel/no_member.jpg" alt="' . $mem_data['mem_name'] . '" width="80px" height="80px" />';
			$staff_img_53 = '<img class="photo" src="' . $local_dir . '/bizstory/images/tfuse-top-panel/no_member.jpg" alt="' . $mem_data['mem_name'] . '" width="53px" height="70px" />';
			$staff_img_26 = '<img class="photo" src="' . $local_dir . '/bizstory/images/tfuse-top-panel/no_member.jpg" alt="' . $mem_data['mem_name'] . '" width="26px" height="26px" />';
			$staff_img_22 = '<img class="photo" src="' . $local_dir . '/bizstory/images/tfuse-top-panel/no_member.jpg" alt="' . $mem_data['mem_name'] . '" width="22px" height="22px" />';
			$staff_img_35 = '<img class="photo" src="' . $local_dir . '/bizstory/images/tfuse-top-panel/no_member.jpg" alt="' . $mem_data['mem_name'] . '" width="35px" height="35px" />';
		}

		$str['img_80'] = $staff_img_80;
		$str['img_53'] = $staff_img_53;
		$str['img_26'] = $staff_img_26;
		$str['img_22'] = $staff_img_22;
		$str['img_35'] = $staff_img_35;

		Return $str;
	}
	
	
//-------------------------------------- 회원정보(어플 자동 로그인시 active_auth비교)
    function member_active_auth($where = '') {
        $query_string = "
            select active_auth, applogin_state
            from push_member
            where " . $where . "
            order by mod_date desc
            limit 1
        ";
//      echo "<pre>" . $query_string . "</pre><br />";

        $data_info = query_view($query_string);

        Return $data_info;
    }



//--------------------학교 정보
	function school_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "sch.school_idx asc";
		if ($del_type == 1) $where = "sch.del_yn = ''" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(sch.school_idx)
			from
				school_info sch
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				sch.*
			from
			school_info sch
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
?>