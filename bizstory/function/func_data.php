<?

//-------------------------------------- 지사삭제시
	function delete_part_data($part_idx, $del_id)
	{
		global $delete_table, $ip_address;

		$del_date = date("Y-m-d H:i:s");

		foreach ($delete_table as $del_k => $del_v)
		{
			$table_name = $del_v[0];

			if ($del_k == '14') // client_code_info
			{
				$delete_query = "update " . $table_name . " set part_idx = '0', ci_idx = '0' where part_idx = '" . $part_idx . "'";
				db_query($delete_query);
				query_history($delete_query, $table_name, 'update');
			}
			else if ($del_k == '15') // client_info
			{
				$table_name1 = $delete_table[18][0];
				$table_name2 = $delete_table[20][0];
				$table_name3 = $delete_table[21][0];

				$client_where = " and ci.part_idx = '" . $part_idx . "'";
				$client_list = client_info_data('list', $client_where, '', '', '');
				foreach ($client_list as $client_k => $client_data)
				{
					if (is_array($client_data))
					{
						$ci_idx = $client_data['ci_idx'];

						$delete_query1 = "update " . $table_name1 . " set del_yn = 'Y', del_ip = '" . $ip_address . "', del_id = '" . $del_id . "', del_date = '" . $del_date . "' where ci_idx = '" . $ci_idx . "'";
						db_query($delete_query1);
						query_history($delete_query1, $table_name1, 'update');

						$delete_query2 = "update " . $table_name2 . " set del_yn = 'Y', del_ip = '" . $ip_address . "', del_id = '" . $del_id . "', del_date = '" . $del_date . "' where ci_idx = '" . $ci_idx . "'";
						db_query($delete_query2);
						query_history($delete_query2, $table_name2, 'update');

						$delete_query3 = "update " . $table_name3 . " set del_yn = 'Y', del_ip = '" . $ip_address . "', del_id = '" . $del_id . "', del_date = '" . $del_date . "' where ci_idx = '" . $ci_idx . "'";
						db_query($delete_query3);
						query_history($delete_query3, $table_name3, 'update');
					}
				}

				$delete_query = "update " . $table_name . " set del_yn = 'Y', del_ip = '" . $ip_address . "', del_id = '" . $del_id . "', del_date = '" . $del_date . "' where part_idx = '" . $part_idx . "'";
				db_query($delete_query);
				query_history($delete_query, $table_name, 'update');
			}
			else if ($del_k == '18' || $del_k == '20' || $del_k == '21') // client_user, expert_client_search, agenct_data
			{ }
			else if ($del_k == '62') // bbs_setting
			{
				$bbs_where = " and bs.part_idx = '" . $part_idx . "'";
				$bbs_list = bbs_setting_data('list', $bbs_where, '', '', '');
				foreach ($bbs_list as $bbs_k => $bbs_data)
				{
					if (is_array($bbs_data))
					{
						$bs_idx  = $bbs_data['bs_idx'];
						$add_idx = $bbs_data['part_add_idx'];
						if ($add_idx == '')
						{
							$bbs_query = "update " . $table_name . " set del_yn = 'Y', del_ip = '" . $ip_address . "', del_id = '" . $del_id . "', del_date = '" . $del_date . "' where bs_idx = '" . $bs_idx . "'";
							db_query($bbs_query);
							query_history($bbs_query, $table_name, 'update');
						}
					}
				}
			}
			else if ($del_k == '76' || $del_k == '77' || $del_k == '78' || $del_k == '79' || $del_k == '80') // filecenter_auth, filecenter_code_type, filecenter_code_type_auth, filecenter_info, filecenter_history
			{
				$delete_query = "update " . $table_name . " set del_yn = '1', del_ip = '" . $ip_address . "', del_id = '" . $del_id . "', del_date = '" . time() . "' where part_idx = '" . $part_idx . "'";
				db_query($delete_query);
				query_history($delete_query, $table_name, 'update');
			}
			else
			{
				$delete_query = "update " . $table_name . " set del_yn = 'Y', del_ip = '" . $ip_address . "', del_id = '" . $del_id . "', del_date = '" . $del_date . "' where part_idx = '" . $part_idx . "'";
				db_query($delete_query);
				query_history($delete_query, $table_name, 'update');
			}
		}
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////
/////
///// 코드 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------- 지사정보
	function company_part_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "part.sort asc";
		if ($del_type == 1) $where = "part.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(part.part_idx)
			from
				company_part part
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				part.*
			from
				company_part part
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

//-------------------------------------- 업체별 지사보기
	function company_part_form($idx, $idx_name = '', $script = '')
	{
		global $sess_str;

		$code_comp = $_SESSION[$sess_str . '_comp_idx'];
		$ub_level  = $_SESSION[$sess_str . '_ubstory_level'];

		$str = '';
		if ($ub_level <= '11') // 직원일 경우
		{
			$str .= '
				<select id="post_part_idx" name="param[part_idx]" title="지사를 선택해 주세요" ' . $script . '>
					<option value="">지사를 선택해 주세요.</option>';

			$part_where = "and part.comp_idx = '" . $code_comp . "'";
			$part_list = company_part_data('list', $part_where, '', '', '');
			foreach ($part_list as $k => $part_data)
			{
				if (is_array($part_data))
				{
					$str .= '
					<option value="' . $part_data['part_idx'] . '"' . selected($idx, $part_data['part_idx']) . '>' . $part_data['part_name'] . '</option>';
				}
			}
			$str .= '
				</select>';
		}
		else // 관리자일 경우
		{
			$part_where = "and part.part_idx = '" . $idx . "'";
			$part_data = company_part_data('view', $part_where);

			$str .= '
				' . $part_data['part_name'] . '
				<input type="hidden" id="post_part_idx" name="param[part_idx]" value="' . $idx . '" title="지사를 선택해 주세요" />';
		}

		Return $str;
	}

//-------------------------------------- 지사별 일정종류
	function code_sche_class_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "code.sort asc";
		if ($del_type == 1) $where = "code.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(code.code_idx)
			from
				code_sche_class code
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
				code_sche_class code
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

//-------------------------------------- 지사별 직책
	function company_part_duty_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "cpd.sort asc";
		if ($del_type == 1) $where = "cpd.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(cpd.cpd_idx)
			from
				company_part_duty cpd
				left join company_part part on part.del_yn = 'N' and part.part_idx = cpd.part_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				cpd.*
				, part.part_name
			from
				company_part_duty cpd
				left join company_part part on part.del_yn = 'N' and part.part_idx = cpd.part_idx
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

//-------------------------------------- 지사별 직원그룹
	function company_staff_group_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "csg.sort asc";
		if ($del_type == 1) $where = "csg.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(csg.csg_idx)
			from
				company_staff_group csg
				left join company_part part on part.del_yn = 'N' and part.part_idx = csg.part_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				csg.*
				, part.part_name
			from
				company_staff_group csg
				left join company_part part on part.del_yn = 'N' and part.part_idx = csg.part_idx
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

//-------------------------------------- 지사별 거래처분류
	function company_client_group_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "ccg.sort asc";
		if ($del_type == 1) $where = "ccg.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ccg.ccg_idx)
			from
				company_client_group ccg
				left join company_part part on part.del_yn = 'N' and part.part_idx = ccg.part_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ccg.*
				, part.part_name
			from
				company_client_group ccg
				left join company_part part on part.del_yn = 'N' and part.part_idx = ccg.part_idx
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



//-------------------------------------- 알림보내기
	function charge_push_send($mem_idx, $charge_idx, $apply_idx, $push_type, $subject, $old_charge_idx, $old_apply_idx, $reg_id)
	{
		//$push = new PUSH("bizstory_push");

	// 메세지
		$add_push = 'N';
		if ($push_type == 'project') // 프로젝트 - 등록, 담당자변경, 책임자변경
		{
			$msg_txt  = '[프로젝트] ';
			$msg_type = 'project';
		}
		else if ($push_type == 'project_work') // 프로젝트 - 업무등록
		{
			$msg_txt  = '[프로젝트업무] ';
			$msg_type = 'project';
		}
		else if ($push_type == 'work') // 업무 - 등록, 담당자변경, 책임자변경
		{
			$msg_txt  = '[업무] ';
			$msg_type = 'work';
		}
		else if ($push_type == 'work_report') // 업무 - 업무보고
		{
			$msg_txt  = '[업무보고] ';
			$msg_type = 'work';
			$add_push = 'Y';
		}
		else if ($push_type == 'work_comment') // 업무 - 코멘트
		{
			$msg_txt  = '[업무코멘트] ';
			$msg_type = 'work';
			$add_push = 'Y';
		}
		else if ($push_type == 'receipt') // 접수 - 등록
		{
			$msg_txt  = '[접수] ';
			$msg_type = 'receipt';
		}
		else if ($push_type == 'receipt_apply') // 접수 - 승인
		{
			$msg_txt  = '[접수승인] ';
			$msg_type = 'receipt';
		}
		else if ($push_type == 'receipt_charge') // 접수 - 담당자변경
		{
			$msg_txt  = '[접수담당자변경] ';
			$msg_type = 'receipt';
		}
		else if ($push_type == 'receipt_comment') // 접수 - 코멘트
		{
			$msg_txt  = '[접수코멘트] ';
			$msg_type = 'receipt';
		}
		else if ($push_type == 'message') // 쪽지 - 등록
		{
			$msg_txt  = '[쪽지] ';
			$msg_type = 'message';
		}
		else if ($push_type == 'sms') // 단문자 - 등록
		{
			$msg_txt  = '[SMS] ';
			$msg_type = 'sms';
		}
		else if ($push_type == 'consult') // 상담 - 등록, 코멘트
		{
			$msg_txt  = '[상담] ';
			$msg_type = 'consult';
		}
		else if ($push_type == 'reg_ok') // 업체신청시 - 대표
		{
			$msg_txt  = '[업체신청] ';
			$msg_type = 'reg_ok';
		}
		$message = strip_tags($subject);
		$message = $msg_txt . string_cut($message, 20);

	// 담당자
		$new_charge     = ',' . $charge_idx . ',';
		$new_charge_arr = explode(',', $new_charge);
		sort($new_charge_arr);

	// 예전담당자
		$old_charge     = ',' . $old_charge_idx . ',';
		$old_charge_arr = explode(',', $old_charge);
		sort($old_charge_arr);

	// 승인자
		$apply_idx = str_replace($old_apply_idx, '', $apply_idx);

	// 담당자재정렬
		foreach ($new_charge_arr as $new_k => $new_v)
		{
			foreach ($old_charge_arr as $old_k => $old_v)
			{
				if ($new_v == $old_v)
				{
					$chk_v = ',' . $old_v . ',';
					$new_charge = str_replace($chk_v, ',', $new_charge);
				}
			}
		}

	// 등록자에게
		if ($add_push == 'Y' && $reg_id != '')
		{
			if ($reg_id != $mem_idx) // 본인제외
			{
				$mem_where = " and mem.mem_idx = '" . $reg_id . "'";
				$mem_data = member_info_data('view', $mem_where);

				$comp_idx = $mem_data['comp_idx'];
				$part_idx = $mem_data['part_idx'];
				$receiver = $mem_data['mem_id'];

				//$result = @$push->push_send($sender, $comp_idx, $part_idx, $reg_id, $receiver, $msg_type, $message);
				push_send($sender, $comp_idx, $part_idx, $reg_id, $receiver, $msg_type, $message);
				unset($mem_data);
			}
			$chk_v = ',' . $reg_id . ',';
			$new_charge = str_replace($chk_v, ',', $new_charge);
		}

	// 담당자에게(1명이상)
		$apply_push = 'Y';
		$charge_arr = explode(',', $new_charge);
		foreach ($charge_arr as $charge_k => $charge_v)
		{
			if ($charge_v != '' && $charge_v != $mem_idx) // 본인제외
			{
				$mem_where = " and mem.mem_idx = '" . $charge_v . "'";
				$mem_data = member_info_data('view', $mem_where);

				$comp_idx = $mem_data['comp_idx'];
				$part_idx = $mem_data['part_idx'];
				$receiver = $mem_data['mem_id'];

				//$result = @$push->push_send($sender, $comp_idx, $part_idx, $charge_v, $receiver, $msg_type, $message);
				push_send($sender, $comp_idx, $part_idx, $charge_v, $receiver, $msg_type, $message);
				unset($mem_data);

				if ($apply_idx == $charge_v) // 승인자 = 담당자
				{
					$apply_push = 'N';
				}
			}
		}

	// 승인, 책임자(1명)
		if ($apply_push == 'Y' && $apply_idx != '')
		{
			if ($apply_idx != $mem_idx) // 본인제외
			{
				$mem_where = " and mem.mem_idx = '" . $apply_idx . "'";
				$mem_data = member_info_data('view', $mem_where);

				if($mem_data['total_num'] > 0){
					$comp_idx = $mem_data['comp_idx'];
					$part_idx = $mem_data['part_idx'];
					$receiver = $mem_data['mem_id'];

					//$result = @$push->push_send($sender, $comp_idx, $part_idx, $apply_idx, $receiver, $msg_type, $message);
					push_send($sender, $comp_idx, $part_idx, $apply_idx, $receiver, $msg_type, $message);
				}
				unset($mem_data);
				
			}
		}
	}

//-------------------------------------- 푸쉬 등록ID
	function push_member_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "push.reg_date desc";
		if ($del_type == 1) $where = "push.del_yn = 'N' and comp.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(push.push_idx)
			from
				push_member push
				left join member_info mem on mem.del_yn = 'N' and mem.mem_idx = push.mem_idx
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = mem.comp_idx
				left join company_part part on part.del_yn = 'N' and part.part_idx = mem.part_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				push.*
				, comp.comp_name
				, mem.mem_name, mem.mem_id
			from
				push_member push
				left join member_info mem on mem.del_yn = 'N' and mem.mem_idx = push.mem_idx
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = mem.comp_idx
				left join company_part part on part.del_yn = 'N' and part.part_idx = mem.part_idx
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

//-------------------------------------- 반복설정
	function repeat_date_setting($repeat_class, $start_date, $end_date, $repeat_num)
	{
		$str = '';
		if ($repeat_class == 'day') // 매일
		{
			$data_date = query_view("select date_format(date_add('" . $start_date . "',interval " . $repeat_num . " day),'%Y-%m-%d') as next_date");
		}
		else if ($repeat_class == 'week') // 매주
		{
			$data_date = query_view("select date_format(date_add('" . $start_date . "',interval " . $repeat_num . " week),'%Y-%m-%d') as next_date");
		}
		else if ($repeat_class == 'month') // 매월
		{
			$data_date = query_view("select date_format(date_add('" . $start_date . "',interval " . $repeat_num . " month),'%Y-%m-%d') as next_date");
		}
		else if ($repeat_class == 'year') // 매년
		{
			$data_date = query_view("select date_format(date_add('" . $start_date . "',interval 1 year),'%Y-%m-%d') as next_date");
		}
		$chk_date = $data_date['next_date'];
		if ($chk_date != '')
		{
			if ($chk_date < $end_date)
			{
				$str .= ',' . $chk_date;
				$str .= repeat_date_setting($repeat_class, $chk_date, $end_date, $repeat_num);
			}
		}

		Return $str;
	}

//-------------------------------------- SMS 내역 - 전체공지용
	function sms_notice_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "sn.reg_date desc";
		if ($del_type == 1) $where = "sn.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(sn.sn_idx)
			from
				sms_notice sn
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				sn.*
			from
				sms_notice sn
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

//-------------------------------------- 왼쪽메뉴목록
	function left_menu_list($comp_idx, $part_idx, $mem_idx, $part_yn)
	{
		$data_sql['query_string'] = "
			select
				mc.menu_name as part_menu_name, mc.sort as part_sort
				, mi.mi_idx, mi.up_mi_idx, mi.menu_depth, mi.menu_num, mi.mode_folder, mi.mode_file, mi.menu_name, mi.icon_img, mi.mode_type
			from
				menu_auth_member mam
				left join member_info mem on mem.del_yn = 'N' and mem.mem_idx = mam.mem_idx
				left join menu_company mc on mc.del_yn = 'N' and mc.comp_idx = mem.comp_idx and mc.part_idx = mem.part_idx and mc.mi_idx = mam.mi_idx
				left join menu_auth_company mac on mac.del_yn = 'N' and mac.comp_idx = mem.comp_idx and mac.mi_idx = mam.mi_idx
				left join menu_info mi on mi.del_yn = 'N' and mi.mi_idx = mam.mi_idx
			where
				mam.del_yn = 'N' and mam.comp_idx = '" . $comp_idx . "' and mam.mem_idx = '" . $mem_idx . "' and mam.yn_list = 'Y'
				and mi.del_yn = 'N' and mi.view_yn = 'Y' and mi.tab_yn = 'N'
				and mac.del_yn = 'N' and mac.view_yn = 'Y'
			order by
				mi.sort asc, mc.sort asc
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

				if ($part_menu_name != '') $menu_name = $part_menu_name;

				if ($mode_type == 'board')
				{
					$link_url = '#';
				}
				else
				{
					if ($mode_folder == '' || $mode_file == '') $link_url = '#';
					else $link_url = $local_dir . '/index.php?fmode=' . $mode_folder . '&amp;smode=' . $mode_file;
				}

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
					$menu_chk[$menu_depth][0][$sort]['link_url']    = $link_url;
					$menu_chk[$menu_depth][0][$sort]['em_str']      = $em_str;
					$menu_chk[$menu_depth][0][$sort]['a_class']     = $a_class;
					$menu_chk[$menu_depth][0][$sort]['li_class']    = $li_class;
					$menu_chk[$menu_depth][0][$sort]['li_id_str']   = $li_id_str;
					$menu_chk[$menu_depth][0][$sort]['ul_id_str']   = 'submenu_' . $chk_up;

				// 게시판
					if ($mode_type == 'board')
					{
						$board_menu_depth = '2';
						$board_menu_up    = $menu_up . ',' . $menu_idx;

						$board_where = " and bs.comp_idx = '" . $comp_idx . "' and bs.view_yn = 'Y'";
						if ($part_yn == 'N')
						{
							$board_where .= " and (bs.part_idx = '" . $part_idx . "' or concat(',', bs.part_add_idx, ',') like '%," . $part_idx . ",%')";
						}
						$board_list = bbs_setting_data('list', $board_where, '', '', '');

						$board_sort = 1;
						foreach ($board_list as $board_k => $board_data)
						{
							if (is_array($board_data))
							{
								$chk_sort[$board_menu_depth][$board_menu_up]++;
								$link_url = $local_dir . '/index.php?fmode=' . $mode_folder . '&amp;smode=' . $mode_file . '&amp;bs_idx=' . $board_data['bs_idx'];

								$li_class  = '';
								if ($board_sort == 1) $li_class = ' class="frist"';
								else if ($board_sort == $board_list['total_num']) $li_class = ' class="end"';
								if ($board_sort == 1 && $board_sort == $board_list['total_num']) $li_class = ' class="frist end"';

								$menu_chk[$board_menu_depth][$board_menu_up][$board_sort]['menu_up']     = $board_menu_up;
								$menu_chk[$board_menu_depth][$board_menu_up][$board_sort]['chk_menu_up'] = $board_menu_up;
								$menu_chk[$board_menu_depth][$board_menu_up][$board_sort]['menu_idx']    = $menu_idx;
								$menu_chk[$board_menu_depth][$board_menu_up][$board_sort]['menu_depth']  = $board_menu_depth;
								$menu_chk[$board_menu_depth][$board_menu_up][$board_sort]['chk_depth']   = $board_menu_depth+1;
								$menu_chk[$board_menu_depth][$board_menu_up][$board_sort]['menu_name']   = $board_data['subject'];
								$menu_chk[$board_menu_depth][$board_menu_up][$board_sort]['menu_num']    = 0;
								$menu_chk[$board_menu_depth][$board_menu_up][$board_sort]['menu_sort']   = $chk_sort[$board_menu_depth][$board_menu_up];
								$menu_chk[$board_menu_depth][$board_menu_up][$board_sort]['link_url']    = $link_url;
								$menu_chk[$board_menu_depth][$board_menu_up][$board_sort]['em_str']      = '';
								$menu_chk[$board_menu_depth][$board_menu_up][$board_sort]['a_class']     = '';
								$menu_chk[$board_menu_depth][$board_menu_up][$board_sort]['li_class']    = $li_class;
								$menu_chk[$board_menu_depth][$board_menu_up][$board_sort]['li_id_str']   = $li_id_str . '_' . $board_sort;
								$menu_chk[$board_menu_depth][$board_menu_up][$board_sort]['ul_id_str']   = 'submenu_' . $chk_up;

								$board_sort++;
							}
						}
					}
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
					$menu_chk[$menu_depth][$menu_up][$sort]['link_url']    = $link_url;
					$menu_chk[$menu_depth][$menu_up][$sort]['em_str']      = $em_str;
					$menu_chk[$menu_depth][$menu_up][$sort]['a_class']     = $a_class;
					$menu_chk[$menu_depth][$menu_up][$sort]['li_class']    = $li_class;
					$menu_chk[$menu_depth][$menu_up][$sort]['li_id_str']   = $li_id_str;
					$menu_chk[$menu_depth][$menu_up][$sort]['ul_id_str']   = 'submenu_' . $chk_up;
				}

				$sort++;
			}
		}

	// 총관리자모드메뉴
		if ($comp_idx == '1' && ($mem_idx == '2' || $mem_idx == '8' || $mem_idx == '195' || $mem_idx == '15' || $mem_idx == '14'))
		{
			$data_sql['query_string'] = "
				select
					mi.*
				from
					menu_info mi
				where
					mi.del_yn = 'N' and mi.view_yn = 'Y' and mi.tab_yn = 'N' and mi.mode_type = 'maintain'
				order by
					mi.sort asc
			";
			$data_sql['page_size'] = '';
			$data_sql['page_num'] = '';
			$auth_list = query_list($data_sql);

			$menu_total = $auth_list['total_num'];
			$left_str = '';

			foreach ($auth_list as $auth_k => $auth_data)
			{
				if (is_array($auth_data))
				{
					$menu_idx       = $auth_data['mi_idx'];
					$menu_up        = $auth_data['up_mi_idx'];
					$menu_depth     = $auth_data['menu_depth'];
					$menu_name      = $auth_data['menu_name'];
					$menu_num       = $auth_data['menu_num'];
					$mode_type      = $auth_data['mode_type'];
					$mode_folder    = $auth_data['mode_folder'];
					$mode_file      = $auth_data['mode_file'];
					$icon_img       = $auth_data['icon_img'];
					$chk_menu_up    = $menu_up . ',' . $menu_idx;
					$chk_depth      = $menu_depth + 1;

					if ($mode_folder == '' || $mode_file == '') $link_url = '#';
					else $link_url = $local_dir . '/index.php?fmode=' . $mode_folder . '&amp;smode=' . $mode_file;

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
						$menu_chk[$menu_depth][0][$sort]['link_url']    = $link_url;
						$menu_chk[$menu_depth][0][$sort]['em_str']      = $em_str;
						$menu_chk[$menu_depth][0][$sort]['a_class']     = $a_class;
						$menu_chk[$menu_depth][0][$sort]['li_class']    = $li_class;
						$menu_chk[$menu_depth][0][$sort]['li_id_str']   = $li_id_str;
						$menu_chk[$menu_depth][0][$sort]['ul_id_str']   = 'submenu_' . $chk_up;
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
						$menu_chk[$menu_depth][$menu_up][$sort]['link_url']    = $link_url;
						$menu_chk[$menu_depth][$menu_up][$sort]['em_str']      = $em_str;
						$menu_chk[$menu_depth][$menu_up][$sort]['a_class']     = $a_class;
						$menu_chk[$menu_depth][$menu_up][$sort]['li_class']    = $li_class;
						$menu_chk[$menu_depth][$menu_up][$sort]['li_id_str']   = $li_id_str;
						$menu_chk[$menu_depth][$menu_up][$sort]['ul_id_str']   = 'submenu_' . $chk_up;
					}

					$sort++;
				}
			}
		}

		$str['menu'] = $menu_chk;
		$str['sort'] = $chk_sort;

		Return $str;
	}

//-------------------------------------- 왼쪽메뉴보기
	function down_menu_view($menu_val, $depth_val, $up_val, $id_val, $sort_val)
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
				$link_url    = $menu_data['link_url'];
				$a_class     = $menu_data['a_class'];
				$li_class    = $menu_data['li_class'];
				$menu_num    = $menu_data['menu_num'];

				if ($menu_depth == 2)
				{
					if ($sort_val[$menu_depth][$menu_up] == $sort_num) $li_class = ' class="end"';

					if ($sort_val[$menu_depth][$menu_up] == 1 && $sort_val[$menu_depth][$menu_up] == $sort_num) $li_class = ' class="frist end"';
				}

				$left_str .= '
				<li' . $li_class . ' id="' . $li_id_str . '">
					<a href="javascript:void(0);" onclick="location.href=\'' . $link_url . '\'"' . $a_class . '>' . $em_str . $menu_name . '</a>';

				$left_str .= down_menu_view($menu_val, $chk_depth, $chk_menu_up, $ul_id_str, $sort_val);

				$left_str .= '
				</li>';

				$sort_num++;
			}
			$left_str .= '
			</ul>';
		}
		Return $left_str;
	}

//-------------------------------------- 데모신청
	function demo_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "demo.reg_date desc";
		if ($del_type == 1) $where = "demo.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(demo.demo_idx)
			from
				demo_info demo
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				demo.*
			from
				demo_info demo
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

//-------------------------------------- 총판관리
	function sole_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "sole.reg_date desc";
		if ($del_type == 1) $where = "sole.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(sole.sole_idx)
			from
				sole_info sole
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				sole.*
			from
				sole_info sole
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

//-------------------------------------- 총판파일관리
	function sole_file_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "solef.sort asc";
		if ($del_type == 1) $where = "solef.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(solef.solef_idx)
			from
				sole_file solef
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				solef.*
			from
				sole_file solef
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

//-------------------------------------- 운영비관리
	function account_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "ai.reg_date desc";
		if ($del_type == 1) $where = "ai.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ai.ai_idx)
			from
				account_info ai
				left join code_account_class code1 on code1.del_yn = 'N' and code1.code_idx = ai.class_code
				left join code_account_gubun code2 on code2.del_yn = 'N' and code2.comp_idx = ai.comp_idx and code2.part_idx = ai.part_idx and code2.code_value = ai.gubun_code
				left join code_account_bank  code3 on code3.del_yn = 'N' and code3.code_idx = ai.bank_code
				left join code_account_card  code4 on code4.del_yn = 'N' and code4.code_idx = ai.card_code
				left join member_info mem on mem.del_yn = 'N' and mem.mem_idx = code4.mem_idx
				left join client_info ci on ci.del_yn = 'N' and ci.ci_idx = ai.ci_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ai.*
				, code1.code_name as class_code_name, code1.code_value as class_code_value, code1.code_bold as class_code_bold, code1.code_color as class_code_color
				, code2.code_name as gubun_code_name, code2.code_value as gubun_code_value, code2.code_bold as gubun_code_bold, code2.code_color as gubun_code_color
				, code3.code_name as bank_code_name, code3.bank_num, code3.code_bold as bank_code_bold, code3.code_color as bank_code_color
				, code4.code_name as card_code_name, code4.card_num, code4.code_bold as card_code_bold, code4.code_color as card_code_color
				, mem.mem_name as card_mem_name
				, ci.client_name
			from
				account_info ai
				left join code_account_class code1 on code1.del_yn = 'N' and code1.code_idx = ai.class_code
				left join code_account_gubun code2 on code2.del_yn = 'N' and code2.comp_idx = ai.comp_idx and code2.part_idx = ai.part_idx and code2.code_value = ai.gubun_code
				left join code_account_bank  code3 on code3.del_yn = 'N' and code3.code_idx = ai.bank_code
				left join code_account_card  code4 on code4.del_yn = 'N' and code4.code_idx = ai.card_code
				left join member_info mem on mem.del_yn = 'N' and mem.mem_idx = code4.mem_idx
				left join client_info ci on ci.del_yn = 'N' and ci.ci_idx = ai.ci_idx
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

//-------------------------------------- 지사별 카드관리
	function code_account_card_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "code.sort asc";
		if ($del_type == 1) $where = "code.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(code.code_idx)
			from
				code_account_card code
				left join company_part part on part.del_yn = 'N' and part.part_idx = code.part_idx
				left join member_info mem on mem.del_yn = 'N' and mem.mem_idx = code.mem_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				code.*
				, part.part_name
				, mem.mem_name
			from
				code_account_card code
				left join company_part part on part.del_yn = 'N' and part.part_idx = code.part_idx
				left join member_info mem on mem.del_yn = 'N' and mem.mem_idx = code.mem_idx
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

//-------------------------------------- 지사별 통장관리
	function code_account_bank_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "code.sort asc";
		if ($del_type == 1) $where = "code.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(code.code_idx)
			from
				code_account_bank code
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
				code_account_bank code
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

//-------------------------------------- 지사별 계정과목
	function code_account_class_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "code.sort asc";
		if ($del_type == 1) $where = "code.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(code.code_idx)
			from
				code_account_class code
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
				code_account_class code
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

//-------------------------------------- 지사별 회계구분
	function code_account_gubun_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "code.sort asc";
		if ($del_type == 1) $where = "code.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(code.code_idx)
			from
				code_account_gubun code
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
				code_account_gubun code
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

//-------------------------------------- 수출신고
	function export_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "ei.reg_date desc";
		if ($del_type == 1) $where = "ei.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ei.ei_idx)
			from
				export_info ei
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ei.*
			from
				export_info ei
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

//-------------------------------------- 도메인관리
	function domain_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "di.reg_date desc";
		if ($del_type == 1) $where = "di.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(di.di_idx)
			from
				domain_info di
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				di.*
			from
				domain_info di
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

//-------------------------------------- 업체분류별 메뉴설정
	function company_class_menu_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "ccm.reg_date desc";
		if ($del_type == 1) $where = "ccm.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ccm.ccm_idx)
			from
				company_class_menu ccm
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ccm.*
			from
				company_class_menu ccm
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

//-------------------------------------- 업체분류
	function company_class_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "code.sort asc";
		if ($del_type == 1) $where = "code.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(code.code_idx)
			from
				company_class code
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				code.*
			from
				company_class code
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
///// 업체, 지사, 거래처 관련 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------- 업체정보
	function company_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "comp.reg_date desc";
		if ($del_type == 1) $where = "comp.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(comp.comp_idx)
			from
				company_info comp
				left join company_setting cs on cs.del_yn = 'N' and cs.comp_idx = comp.comp_idx
				left join company_class code on code.del_yn = 'N' and code.code_idx = comp.comp_class
				left join sole_info sole on sole.del_yn = 'N' and sole.sole_idx = comp.sole_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				comp.*
				, cs.client_cnt, cs.part_cnt
				, code.code_name as comp_class_str
				, sole.comp_name as sole_name
			from
				company_info comp
				left join company_setting cs on cs.del_yn = 'N' and cs.comp_idx = comp.comp_idx
				left join company_class code on code.del_yn = 'N' and code.code_idx = comp.comp_class
				left join sole_info sole on sole.del_yn = 'N' and sole.sole_idx = comp.sole_idx
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		echo "<pre>" . $query_string . "</pre><br />";

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

//-------------------------------------- 업체설정 - 삭제할것
	function company_set_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "cs.comp_idx desc";
		if ($del_type == 1) $where = "cs.del_yn = 'N' and comp.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(cs.cs_idx)
			from
				company_setting cs
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = cs.comp_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				cs.*
				, comp.comp_name, comp.auth_yn
			from
				company_setting cs
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = cs.comp_idx
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

//-------------------------------------- 업체설정
	function company_setting_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "cs.comp_idx desc";
		if ($del_type == 1) $where = "cs.del_yn = 'N' and comp.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(cs.cs_idx)
			from
				company_setting cs
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = cs.comp_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				cs.*
				, comp.comp_name, comp.auth_yn
			from
				company_setting cs
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = cs.comp_idx
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

//-------------------------------------- 업체파일
	function company_file_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "cf.sort asc";
		if ($del_type == 1) $where = "cf.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(cf.cf_idx)
			from
				company_file cf
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = cf.comp_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				cf.*
				, comp.comp_name
			from
				company_file cf
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = cf.comp_idx
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

//-------------------------------------- 업체연혁
	function company_history_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "ch.sort asc";
		if ($del_type == 1) $where = "ch.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ch.ch_idx)
			from
				company_history ch
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ch.*
			from
				company_history ch
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

//-------------------------------------- 거래처분류 위치
	function client_group_view($ccg_idx)
	{
		$group_where = " and ccg.ccg_idx = '" . $ccg_idx . "'";
		$group_data = company_client_group_data("view", $group_where);

		$group_name = '<span style="';
		if ($group_data['code_bold'] == 'Y') $group_name .= 'font-weight:900;';
		if ($group_data['code_color'] != '') $group_name .= 'color:' . $group_data['code_color'] . ';';
		$group_name .= '">' . $group_data["group_name"] . '</span>';
		$group_name_mobile = $group_data["group_name"];

		if ($group_data['menu_depth'] == 1)
		{
			$str['group_level1']        = $group_name;
			$str['group_level1_mobile'] = $group_name_mobile;
		}
		else if ($group_data['menu_depth'] == 2)
		{
			$str['group_level2']        = $group_name;
			$str['group_level2_mobile'] = $group_name_mobile;
		}

		$str['code_bold'][$group_data['menu_depth']]  = $group_data['code_bold'];
		$str['code_color'][$group_data['menu_depth']] = $group_data['code_color'];
		$str['group_name'][$group_data['menu_depth']] = $group_data["group_name"];

		$arr_up = explode(",", $group_data["up_ccg_idx"]);
		foreach ($arr_up as $arr_k => $arr_v)
		{
			$up_menu_where = " and ccg.ccg_idx = '" . $arr_v . "'";
			$up_menu_data = company_client_group_data("view", $up_menu_where);

			$group_name = '<span style="';
			if ($up_menu_data['code_bold'] == 'Y') $group_name .= 'font-weight:900;';
			if ($up_menu_data['code_color'] != '') $group_name .= 'color:' . $up_menu_data['code_color'] . ';';
			$group_name .= '">' . $up_menu_data["group_name"] . '</span>';

			$group_name_mobile = $up_menu_data["group_name"];

			if ($arr_k == 1)
			{
				$str['group_level1']        = $group_name;
				$str['group_level1_mobile'] = $group_name_mobile;
			}
			else if ($arr_k == 2)
			{
				$str['group_level2']        = $group_name;
				$str['group_level2_mobile'] = $group_name_mobile;
			}

			$str['code_bold'][$arr_k]  = $up_menu_data['code_bold'];
			$str['code_color'][$arr_k] = $up_menu_data['code_color'];
			$str['group_name'][$arr_k] = $up_menu_data["group_name"];
		}

		sort($str['code_bold']);
		sort($str['code_color']);
		sort($str['group_name']);
		Return $str;
	}

//-------------------------------------- 지사값구하기
	function search_company_part($code_part)
	{
		global $sess_str;
		
		if ($code_part == '')
		{
			$code_comp = $_SESSION[$sess_str . '_comp_idx'];
			$code_part = $_SESSION[$sess_str . '_part_idx'];
			if ($code_part == '')
			{
				$part_where = "and part.comp_idx = '" . $code_comp . "'";
				$part_data = company_part_data('view', $part_where);
				$code_part = $part_data['part_idx'];
			}
		}

		return $code_part;
	}

//-------------------------------------- 에이전트타입구하기
	function search_agent_type($code_part, $code_agent)
	{
		global $sess_str;

		if ($code_agent == '')
		{
			$code_comp = $_SESSION[$sess_str . '_comp_idx'];
			$code_part = search_company_part($code_part);

			$where = " and part.part_idx = '" . $code_part . "'";
			$data = company_part_data("view", $where);

			$part_agent_type = $data['agent_type'];
			$agent_type_arr  = explode(',', $part_agent_type);

			foreach ($agent_type_arr as $k => $v)
			{
				if ($k == 0)
				{
					$code_agent = $v;
				}
			}
		}
		if ($code_agent == '') $code_agent = 'A';

		return $code_agent;
	}

//-------------------------------------- 업체별 지사보기
	function company_part_select($idx, $script = '')
	{
		global $sess_str;

		$code_comp = $_SESSION[$sess_str . '_comp_idx'];
		$ub_level  = $_SESSION[$sess_str . '_ubstory_level'];

		$str = '';
		if ($ub_level <= '11')
		{
			$str .= '
				<select id="post_part_idx" name="param[part_idx]" title="지사를 선택해 주세요" ' . $script . '>
					<option value="">지사를 선택해 주세요.</option>';

			$part_where = "and part.comp_idx = '" . $code_comp . "'";
			$part_list = company_part_data('list', $part_where, '', '', '');
			foreach ($part_list as $k => $part_data)
			{
				if (is_array($part_data))
				{
					$str .= '
					<option value="' . $part_data['part_idx'] . '"' . selected($idx, $part_data['part_idx']) . '>' . $part_data['part_name'] . '</option>';
				}
			}
			$str .= '
				</select>';
		}
		else
		{
			$part_where = "and part.part_idx = '" . $idx . "'";
			$part_data = company_part_data('view', $part_where);

			$str .= '
				' . $part_data['part_name'] . '
				<input type="hidden" id="post_part_idx" name="param[part_idx]" value="' . $idx . '" title="지사를 선택해 주세요" />';
		}

		Return $str;
	}

//-------------------------------------- 거래처정보
	function client_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "ci.reg_date desc";
		if ($del_type == 1) $where = "ci.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ci.ci_idx)
			from
				client_info ci
				left join company_client_group ccg on ccg.del_yn = 'N' and ccg.comp_idx = ci.comp_idx and ccg.part_idx = ci.part_idx and ccg.ccg_idx = ci.ccg_idx
				left join company_part part on part.del_yn = 'N' and part.comp_idx = ci.comp_idx and part.part_idx = ci.part_idx
				left join member_info mem on mem.del_yn = 'N' and mem.comp_idx = ci.comp_idx and mem.mem_idx = ci.mem_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ci.*
				, ccg.group_name, ccg.code_bold, ccg.code_color
				, part.part_name
				, mem.mem_name, mem.hp_num as charge_hp_num, mem.mem_email as charge_email, mem.tel_num as charge_tel_num
			from
				client_info ci
				left join company_client_group ccg on ccg.del_yn = 'N' and ccg.comp_idx = ci.comp_idx and ccg.part_idx = ci.part_idx and ccg.ccg_idx = ci.ccg_idx
				left join company_part part on part.del_yn = 'N' and part.comp_idx = ci.comp_idx and part.part_idx = ci.part_idx
				left join member_info mem on mem.del_yn = 'N' and mem.comp_idx = ci.comp_idx and mem.mem_idx = ci.mem_idx
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

//-------------------------------------- 거래처코드정보
	function client_code_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "cci.client_code asc";
		if ($del_type == 1) $where = "cci.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(cci.cci_idx)
			from
				client_code_info cci
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				cci.*
			from
				client_code_info cci
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

//-------------------------------------- 거래처사용자 함수
	function client_user_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "cu.reg_date desc";
		if ($del_type == 1) $where = "cu.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(cu.cu_idx)
			from
				client_user cu
				left join client_info ci on ci.del_yn = 'N' and ci.ci_idx = cu.ci_idx
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = cu.comp_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				cu.*
				, ci.client_name
			from
				client_user cu
				left join client_info ci on ci.del_yn = 'N' and ci.ci_idx = cu.ci_idx
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = cu.comp_idx
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

//-------------------------------------- 거래처사용자 로그인하기
	function client_login_action($cu_idx, $sess_str)
	{
		$mem_where = " and cu.cu_idx = '" . $cu_idx . "'";
		$mem_data = client_user_data("view", $mem_where);

		if ($mem_data['comp_idx'] == 0) $mem_data['comp_idx'] = '';
		if ($mem_data['mem_name'] == '') $mem_data['mem_name'] = $mem_data['client_name'];

		$_SESSION[$sess_str . '_comp_idx']    = $mem_data['comp_idx'];
		$_SESSION[$sess_str . '_client_idx']  = $mem_data['ci_idx'];
		$_SESSION[$sess_str . '_client_name'] = $mem_data['client_name'];
		$_SESSION[$sess_str . '_cu_idx']      = $mem_data['cu_idx'];
		$_SESSION[$sess_str . '_mem_id']      = $mem_data['mem_id'];
		$_SESSION[$sess_str . '_mem_name']    = $mem_data['mem_name'];
	}

//-------------------------------------- 거래처 메모
	function client_memo_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "cim.order_idx desc";
		if ($del_type == 1) $where = "cim.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(cim.cim_idx)
			from
				client_memo cim
				left join member_info mem on mem.del_yn = 'N' and mem.comp_idx = cim.comp_idx and mem.mem_idx = cim.mem_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				cim.*
				, mem.mem_name
			from
				client_memo cim
				left join member_info mem on mem.del_yn = 'N' and mem.comp_idx = cim.comp_idx and mem.mem_idx = cim.mem_idx
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

//-------------------------------------- 거래처 메모 파일
	function client_memo_file_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "cimf.sort asc";
		if ($del_type == 1) $where = "cimf.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(cimf.cimf_idx)
			from
				client_memo_file cimf
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				cimf.*
			from
				client_memo_file cimf
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
///// 회원관련 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------- 쿠키값변환
	function cookie_value_change($str)
	{
		global $sess_str;

		$sess_chk   = $sess_str . $sess_str . $sess_str . $sess_str . $sess_str;
		$str_length = strlen($str);

		$total_str = "";
		for ($i = 0; $i < $str_length; $i++)
		{
			$sess_char = substr($sess_chk, $i, 1);
			$str_char  = substr($str, $i, 1);
			$total_str .= $sess_char . $str_char;
		}

		$total_str = $sess_str . $total_str . $sess_str;
		$total_str = base64_encode($total_str);
		$str = $total_str;

		Return $str;
	}
	
	### PHP암호화 함수
	function encrypt($data,$k) { 
		 $encrypt_these_chars = "1234567890ABCDEFGHIJKLMNOPQRTSUVWXYZabcdefghijklmnopqrstuvwxyz.,/?!$@^*()_+-=:;~{}";
		 $t = $data;
		 $result2;
		 $ki;
		 $ti;
		 $keylength = strlen($k);
		 $textlength = strlen($t);
		 $modulo = strlen($encrypt_these_chars);
		 $dbg_key;
		 $dbg_inp;
		 $dbg_sum;
		 for ($result2 = "", $ki = $ti = 0; $ti < $textlength; $ti++, $ki++) {
		  if ($ki >= $keylength) {
		   $ki = 0;
		  }
		  $dbg_inp += "["+$ti+"]="+strpos($encrypt_these_chars, substr($t, $ti,1))+" ";   
		  $dbg_key += "["+$ki+"]="+strpos($encrypt_these_chars, substr($k, $ki,1))+" ";   
		  $dbg_sum += "["+$ti+"]="+strpos($encrypt_these_chars, substr($k, $ki,1))+ strpos($encrypt_these_chars, substr($t, $ti,1)) % $modulo +" ";
		  $c = strpos($encrypt_these_chars, substr($t, $ti,1));
		  $d;
		  $e;
		  if ($c >= 0) {
		   $c = ($c + strpos($encrypt_these_chars, substr($k, $ki,1))) % $modulo;
		   $d = substr($encrypt_these_chars, $c,1);
		   $e .= $d;
		  } else {
		   $e += $t.substr($ti,1);
		  }
		 }
		 $key2 = $result2;
		 $debug = "Key  : "+$k+"\n"+"Input: "+$t+"\n"+"Key  : "+$dbg_key+"\n"+"Input: "+$dbg_inp+"\n"+"Enc  : "+$dbg_sum;
		 return $e . "";
	}

	function decrypt($key2,$secret) {
		 $encrypt_these_chars = "1234567890ABCDEFGHIJKLMNOPQRTSUVWXYZabcdefghijklmnopqrstuvwxyz.,/?!$@^*()_+-=:;~{}";
		 $input = $key2;
		 $output = "";
		 $debug = "";
		 $k = $secret;
		 $t = $input;
		 $result;
		 $ki;
		 $ti;
		 $keylength = strlen($k);
		 $textlength = strlen($t);
		 $modulo = strlen($encrypt_these_chars);
		 $dbg_key;
		 $dbg_inp;
		 $dbg_sum;
		 for ($result = "", $ki = $ti = 0; $ti < $textlength; $ti++, $ki++) {
		  if ($ki >= $keylength){
		   $ki = 0;
		  }
		  $c = strpos($encrypt_these_chars, substr($t, $ti,1));
		  if ($c >= 0) {
		   $c = ($c - strpos($encrypt_these_chars , substr($k, $ki,1)) + $modulo) % $modulo;
		   $result .= substr($encrypt_these_chars , $c, 1);
		  } else {
		   $result += substr($t, $ti,1);
		  }
		 }
		 return $result;
	}
//-------------------------------------- 회원 메모
	function member_memo_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "mm.reg_date desc";
		if ($del_type == 1) $where = "mm.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(mm.mm_idx)
			from
				member_memo mm
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				mm.*
			from
				member_memo mm
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
///// 메뉴관련 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------- 메뉴정보
	function menu_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "mi.sort asc";
		if ($del_type == 1) $where = "mi.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(mi.mi_idx)
			from
				menu_info mi
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				mi.*
			from
				menu_info mi
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

//-------------------------------------- 사이트별메뉴
    function menu_auth_company_default($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
    {
        if ($orderby == '') $orderby = "mac.reg_date desc";
        if ($del_type == 1) $where = "mc.del_yn = 'N' and mi.del_yn = 'N' and mac.view_yn='Y' and mi.view_yn = 'Y' and ifnull(mc.default_yn, mac.default_yn) = 'Y'" . $where;
        else $where = "1" . $where;

        $query_page = "
            select
                count(mac.mac_idx)
            from
                menu_company mc
                left join menu_info mi on mi.del_yn = 'N' and mi.mi_idx = mc.mi_idx
                left join menu_auth_company mac on mac.del_yn='N' and mac.mi_idx=mi.mi_idx
            where
                " . $where . "
        ";
        //echo "<pre>" . $query_page . "</pre><br />";
        $query_string = "
            select
                mc.mc_idx, mc.comp_idx, mc.part_idx, mc.mi_idx, mc.menu_name, mc.default_yn, mc.sort
                , mi.up_mi_idx, mi.menu_depth, mi.menu_num, mac.default_yn mac_default_yn
            from
                menu_company mc
                left join menu_info mi on mi.del_yn = 'N' and mi.mi_idx = mc.mi_idx
                left join menu_auth_company mac on mac.del_yn='N' and mac.mi_idx=mi.mi_idx
            where
                " . $where . "
            order by
                " . $orderby . "
        ";
        //echo "<!--" . $query_string . "-->";

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

//-------------------------------------- 사이트별메뉴
	function menu_auth_company_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "mac.reg_date desc";
		if ($del_type == 1) $where = "mac.del_yn = 'N' and mi.del_yn = 'N' and mi.view_yn = 'Y'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(mac.mac_idx)
			from
				menu_auth_company mac
				left join menu_info mi on mi.del_yn = 'N' and mi.mi_idx = mac.mi_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				mac.*
				, mi.up_mi_idx, mi.menu_depth, mi.menu_num, mi.menu_name, mi.sort
			from
				menu_auth_company mac
				left join menu_info mi on mi.del_yn = 'N' and mi.mi_idx = mac.mi_idx
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<!--" . $query_string . "-->";

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

//-------------------------------------- 사용자별메뉴
	function menu_auth_member_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "mam.reg_date desc";
		//if ($del_type == 1) $where = "mam.del_yn = 'N' and mac.del_yn = 'N' and mc.del_yn = 'N' and mi.del_yn = 'N'" . $where;
		if ($del_type == 1) $where = "mam.del_yn = 'N' and mac.del_yn = 'N' and mi.del_yn = 'N' and mi.view_yn = 'Y'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(mam.mam_idx)
			from
				menu_auth_member mam
				left join member_info mem on mem.del_yn = 'N' and mem.mem_idx = mam.mem_idx
				left join menu_auth_company mac on mac.del_yn = 'N' and mac.comp_idx = mem.comp_idx and mac.mi_idx = mam.mi_idx
				left join menu_company mc on mc.del_yn = 'N' and mc.comp_idx = mem.comp_idx and mc.part_idx = mem.part_idx and mc.mi_idx = mam.mi_idx
				left join menu_info mi on mi.del_yn = 'N' and mi.mi_idx = mam.mi_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				mam.*
				, mi.up_mi_idx, mi.menu_depth, mi.menu_num, mi.mode_type, mi.mode_folder, mi.mode_file, mi.menu_name, mi.icon_img, mi.menu_code, mi.menu_num
				, mc.menu_name as part_menu_name
			from
				menu_auth_member mam
				left join member_info mem on mem.del_yn = 'N' and mem.mem_idx = mam.mem_idx
				left join menu_auth_company mac on mac.del_yn = 'N' and mac.comp_idx = mem.comp_idx and mac.mi_idx = mam.mi_idx
				left join menu_company mc on mc.del_yn = 'N' and mc.comp_idx = mem.comp_idx and mc.part_idx = mem.part_idx and mc.mi_idx = mam.mi_idx
				left join menu_info mi on mi.del_yn = 'N' and mi.mi_idx = mam.mi_idx
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

//-------------------------------------- 업체별로 메뉴명설정
	function menu_company_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "mc.reg_date desc";
		if ($del_type == 1) $where = "mc.del_yn = 'N' and mi.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(mc.mc_idx)
			from
				menu_company mc
				left join menu_info mi on mi.del_yn = 'N' and mi.mi_idx = mc.mi_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				mc.*
				, mi.up_mi_idx, mi.menu_depth, mi.menu_num
			from
				menu_company mc
				left join menu_info mi on mi.del_yn = 'N' and mi.mi_idx = mc.mi_idx
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

//-------------------------------------- 지사별 정렬을 위한 함수
	function menu_level_sort($comp_idx, $part_idx, $menu_depth = 1, $up_idx = '', $sort_num = 1)
	{
		if ($menu_depth > 1)
		{
			$depth_where = " and concat(mi.up_mi_idx, ',') like '%" . $up_idx . ",%'";
		}

		$data_sql["query_page"] = "
			select
				count(mc.mc_idx)
			from
				menu_company mc
				left join menu_info mi on mi.del_yn = 'N' and mi.mi_idx = mc.mi_idx
			where
				mc.del_yn = 'N' and mc.comp_idx = '" . $comp_idx . "' and mc.part_idx = '" . $part_idx . "'
				and mi.del_yn = 'N' and mi.menu_depth = '" . $menu_depth . "'
				" . $depth_where . "
		";
		$data_sql["query_string"] = "
			select
				mc.mc_idx, mc.sort
				, mi.mi_idx, mi.up_mi_idx, mi.menu_depth, mi.menu_num, mi.sort as mi_sort
			from
				menu_company mc
				left join menu_info mi on mi.del_yn = 'N' and mi.mi_idx = mc.mi_idx
			where
				mc.del_yn = 'N' and mc.comp_idx = '" . $comp_idx . "' and mc.part_idx = '" . $part_idx . "'
				and mi.del_yn = 'N' and mi.menu_depth = '" . $menu_depth . "'
				" . $depth_where . "
			order by
				mc.sort asc, mi.sort asc
		";
		$data_sql["page_size"] = "";
		$data_sql["page_num"]  = "";
		$list = query_list($data_sql);
		foreach ($list as $k => $data)
		{
			if (is_array($data))
			{
				$sql = "
					update menu_company set
						sort = '" . $sort_num . "'
					where
						mc_idx = '" . $data['mc_idx'] . "'
				";
				db_query($sql);
				$sort_num++;

				if ($data["menu_num"] > 0)
				{
					$next_depth = $data["menu_depth"] + 1;
					$next_up_mi = $data['up_mi_idx'] . "," . $data['mi_idx'];
					menu_level_sort($comp_idx, $part_idx, $next_depth, $next_up_mi, $sort_num);
					$sort_num = $sort_num + $data["menu_num"];
				}
			}
		}
	}

//-------------------------------------- 현메뉴위치
	function menu_navigation_view($menu_idx)
	{
		global $sess_str, $bs_idx;

		$code_comp = $_SESSION[$sess_str . '_comp_idx'];
		$code_part = $_SESSION[$sess_str . '_part_idx'];

		$menu_where = " and mi.mi_idx = '" . $menu_idx . "'";
		$menu_data = menu_info_data("view", $menu_where);

	// 업체별로 메뉴명 가지고 오기
		$sub_where = " and mc.comp_idx = '" . $code_comp . "' and mc.part_idx = '" . $code_part . "' and mc.mi_idx = '" . $menu_data['mi_idx'] . "'";
		$sub_data = menu_company_data('view', $sub_where);

		$menu_name = $sub_data['menu_name'];
		if ($menu_name == '') $menu_name = $menu_data['menu_name'];

		$menu_depth = $menu_data['menu_depth'];
		$str['menu_name'][$menu_depth] = $menu_name;
		$str['menu_idx'][$menu_depth]  = $menu_data['mi_idx'];

		if ($menu_depth == 1)
		{
			$str['first_menu'] = $menu_name;
			$str['first_memo'] = $menu_data["contents"];

		// 게시판일 경우
			if ($bs_idx != '')
			{
				$board_where = " and bs.bs_idx = '" . $bs_idx . "'";
				$board_data = bbs_setting_data('view', $board_where);

				$str['menu_name'][2] = $board_data["subject"];
			}
		}
		else
		{
			$chk_depth = $menu_depth - 1;
			for ($i = 1; $i <= $chk_depth ; $i++)
			{
				$arr_up = explode(",", $menu_data["up_mi_idx"]);
				$up_idx  = $arr_up[$i];

				$up_menu_where = " and mi.mi_idx = '" . $up_idx . "'";
				$up_menu_data = menu_info_data("view", $up_menu_where);

			// 업체별로 메뉴명 가지고 오기
				$sub_where = " and mc.comp_idx = '" . $code_comp . "' and mc.part_idx = '" . $code_part . "' and mc.mi_idx = '" . $up_menu_data['mi_idx'] . "'";
				$sub_data = menu_company_data('view', $sub_where);

				$menu_name = $sub_data['menu_name'];
				if ($menu_name == '') $menu_name = $up_menu_data['menu_name'];

				$up_menu_depth = $up_menu_data['menu_depth'];
				$str['menu_name'][$up_menu_depth] = $menu_name;
				$str['menu_idx'][$up_menu_depth]  = $up_menu_data['mi_idx'];

				if ($up_menu_depth == 1)
				{
					$str['first_menu'] = $menu_name;
					$str['first_memo'] = $up_menu_data["contents"];
				}
			}
		}
		ksort($str['menu_name']);
		Return $str;
	}

//-------------------------------------- 탭메뉴
// 권한이 있는 사람만 가능하도록 수정을 한다.
	function tab_menu_view($mi_idx)
	{
		global $sess_str;

		$chk_comp = $_SESSION[$sess_str . '_comp_idx'];
		$chk_part = $_SESSION[$sess_str . '_part_idx'];

		$menu_where = " and mi.mi_idx = '" . $mi_idx . "'";
		$menu_data = menu_info_data("view", $menu_where);

		$up_mi_idx = $menu_data['up_mi_idx'];
		$tab_where = " and mi.up_mi_idx = '" . $up_mi_idx . "' and mi.tab_yn = 'Y'";
		$tab_list = menu_info_data("list", $tab_where, '', '', '');

		if ($tab_list['total_num'] > 0)
		{
			$tab_str = '
		<div class="tab_menu [tab_css]">
			<ul>';
			foreach ($tab_list as $tab_k => $tab_data)
			{
				if (is_array($tab_data))
				{
				// 업체별로 메뉴명 가지고 오기
					$sub_where = " and mc.comp_idx = '" . $chk_comp . "' and mc.part_idx = '" . $chk_part . "' and mc.mi_idx = '" . $tab_data['mi_idx'] . "'";
					$sub_data = menu_company_data('view', $sub_where);

					$menu_name = $sub_data['menu_name'];
					if ($menu_name == '') $menu_name = $tab_data['menu_name'];

					$tab_kk = $tab_k + 1;
					if ($tab_data['mode_folder'] == $menu_data['mode_folder'] && $tab_data['mode_file'] == $menu_data['mode_file']) $tab_css = "m" . $tab_kk;
					$tab_str .= '
				<li class="m' . $tab_kk . '"><a href="' . $local_dir . '/index.php?fmode=' . $tab_data['mode_folder'] . '&amp;smode=' .  $tab_data['mode_file'] . '"><span>' . $menu_name . '</span></a></li>
					';
				}
			}
			$tab_str .= '
			</ul>
		</div>';
		}
		$tab_str = str_replace("[tab_css]", $tab_css, $tab_str);

		Return $tab_str;
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////
/////
///// 공통 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------- 각종데이타 정렬
	function data_sort_action($table_name, $idx_name, $where = '', $orderby = '')
	{
		if ($orderby == "") $orderby = "sort asc, " . $idx_name . " asc";

		$data_sql["query_page"] = "select count(" . $idx_name . ") from " . $table_name . " where del_yn = 'N' " . $where;
		$data_sql["query_string"] = "
			select
				*
			from
				" . $table_name . "
			where
				del_yn = 'N'
				" . $where . "
			order by
				" . $orderby . "
		";
		$data_sql["page_size"] = "";
		$data_sql["page_num"]  = "";
		$list = query_list($data_sql);

		$sort_num = 1;
		foreach ($list as $k => $data)
		{
			if (is_array($data))
			{
				$sql = "
					update " . $table_name . " set
						sort = '" . $sort_num . "'
					where
						" . $idx_name . " = '" . $data[$idx_name] . "'
				";
				db_query($sql);
				$sort_num++;
			}
		}
	}

//-------------------------------------- 각종레벨데이타 정렬
	function data_level_sort_action($table_name, $idx_name, $up_idx_name, $where_add = '', $orderby = '', $menu_depth = 1, $up_idx = '', $sort_num = 1)
	{
		if ($orderby == "") $orderby = "sort asc, " . $idx_name . " asc";

		$where = $where_add . " and menu_depth = '" . $menu_depth . "'";
		if ($menu_depth > 1)
		{
			$where .= " and concat(" . $up_idx_name . ", ',') like '%" . $up_idx . ",%'";
		}

		$data_sql["query_page"] = "select count(" . $idx_name . ") from " . $table_name . " where del_yn = 'N' " . $where;
		$data_sql["query_string"] = "
			select
				*
			from
				" . $table_name . "
			where
				del_yn = 'N'
				" . $where . "
			order by
				" . $orderby . "
		";
		$data_sql["page_size"] = "";
		$data_sql["page_num"]  = "";
		$list = query_list($data_sql);

		foreach ($list as $k => $data)
		{
			if (is_array($data))
			{
				$sql = "
					update " . $table_name . " set
						sort = '" . $sort_num . "'
					where
						" . $idx_name . " = '" . $data[$idx_name] . "'
				";
				db_query($sql);
				$sort_num++;

				if ($data["menu_num"] > 0)
				{
					$next_depth  = $data["menu_depth"] + 1;
					$next_up_smi = $data[$up_idx_name] . "," . $data[$idx_name];
					data_level_sort_action($table_name, $idx_name, $up_idx_name, $where_add, $orderby, $next_depth, $next_up_smi, $sort_num);
					$sort_num = $sort_num + $data["menu_num"];
				}
			}
		}
	}

//-------------------------------------- 메뉴하위메뉴수업데이트
	function data_level_depth_action($table_name, $idx_name, $up_idx_name, $where_add = "", $orderby = "", $menu_depth = 1, $up_idx = "", $sort_num = 1)
	{
		$depth_data = query_view("select max(menu_depth) as max_depth from " . $table_name . " where del_yn = 'N' limit 1");

		if ($orderby == "") $orderby = "sort asc, " . $idx_name . " asc";

		$where = $where_add . " and menu_depth = '" . $menu_depth . "'";
		if ($menu_depth > 1)
		{
			$where .= " and concat(" . $up_idx_name . ", ',') like '%" . $up_idx . ",%'";
		}

		$data_sql["query_page"] = "select count(" . $idx_name . ") from " . $table_name . " where del_yn = 'N' " . $where;
		$data_sql["query_string"] = "
			select
				*
			from
				" . $table_name . "
			where
				del_yn = 'N'
				" . $where . "
			order by
				" . $orderby . "
		";
		$data_sql["page_size"] = "";
		$data_sql["page_num"]  = "";
		$list = query_list($data_sql);

		foreach ($list as $k => $data)
		{
			if (is_array($data))
			{
				$next_depth = $data["menu_depth"] + 1;
				$up_smi_idx = $data[$up_idx_name] . "," . $data[$idx_name];
				$sub_where = $where_add . " and concat(" . $up_idx_name . ", ',') like '%" . $up_smi_idx . ",%'";

				$sub_sql = "
					select
						*
					from
						" . $table_name . "
					where
						del_yn = 'N'
						" . $sub_where . "
					order by
						" . $orderby . "
				";
				$sub_data = query_view($sub_sql);

				$sql = "
					update " . $table_name . " set
						menu_num = '" . $sub_data["total_num"] . "'
					where
						" . $idx_name . " = '" . $data[$idx_name] . "'
				";
				db_query($sql);

				if ($data["menu_depth"] <= $depth_data["max_depth"])
				{
					data_level_depth_action($table_name, $idx_name, $up_idx_name, $where_add, $orderby, $next_depth, $up_smi_idx, $sort_num);
				}
			}
		}
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////
/////
///// 서비스관련 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------- 기본서비스정보
	function service_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "si.sort asc";
		if ($del_type == 1) $where = "si.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(si.si_idx)
			from
				service_info si
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";

		$query_string = "
			select
				si.*
			from
				service_info si
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
///// 코드성관련 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------- 지사별 근태상태
	function code_dili_status_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "code.sort asc";
		if ($del_type == 1) $where = "code.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(code.code_idx)
			from
				code_dili_status code
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				code.*
			from
				code_dili_status code
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
///// 작업내역 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------- 작업내역 설정
	function query_history_data($query_type, $table_name, $where = '', $orderby = '', $page_num = '', $page_size = '')
	{
		if ($orderby == '') $orderby = "qh.reg_date desc";
		$where = "1" . $where;

		$query_page = "
			select
				count(qh.qh_idx)
			from
				" . $table_name . " qh
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				qh.*
			from
				" . $table_name . " qh
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

//-------------------------------------- 계약 정보
	function contract_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "con.reg_date desc";
		if ($del_type == 1) $where = "con.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(con.con_idx)
			from
				contract_info con
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				con.*
				, part.part_name
			from
				contract_info con
				left join company_part part on part.del_yn = 'N' and part.part_idx = con.part_idx
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

//-------------------------------------- 페이지 정보
	function page_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "pi.sort asc";
		if ($del_type == 1) $where = "pi.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(pi.pi_idx)
			from
				page_info pi
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				pi.*
			from
				page_info pi
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
///// 보내기관련 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------- SMS 보내기
	function send_sms($receive_hp, $send_hp, $msg, $comp_idx, $part_idx, $table_name, $table_idx)
	{
		global $sess_str, $set_cellular;

		if ($receive_hp != '')
		{
			$chk_hp  = substr($receive_hp, 0, 3);
			$chk_val = array_key_exists($chk_hp, $set_cellular);

			if ($chk_val === true)
			{
				$comp_set_where = " and cs.comp_idx = '" . $comp_idx . "'";
				$comp_set_data = company_setting_data('view', $comp_set_where);

				if ($comp_set_data['receipt_inform_yn'] == "Y" && $comp_set_data['sms_cnt'] > 0)
				{
					$send_date = date("Y-m-d H:i:s");
					$msg       = string_cut($msg, 80);

				// SMS저장
					$sms_query = "
						insert into em_tran set
							  tran_phone    = '" . $receive_hp . "'
							, tran_callback = '" . $send_hp . "'
							, tran_status   = '1'
							, tran_date     = '" . $send_date . "'
							, tran_msg      = '" . $msg . "'
							, tran_type     = '4'
							, tran_etc1     = '" . $comp_idx . "'
							, tran_etc2     = '" . $part_idx . "'
					";
					db_query($sms_query);
					query_history($sms_query, 'em_tran', 'insert');

				// 이력
					$history_query = "
						insert into sms_history set
							  comp_idx   = '" . $comp_idx . "'
							, part_idx   = '" . $part_idx . "'
							, table_name = '" . $table_name . "'
							, table_idx  = '" . $table_idx . "'
							, callback   = '" . $send_hp . "'
							, hp         = '" . $receive_hp . "'
							, msg        = '" . $msg . "'
							, reg_id     = '" . $_SESSION[$sess_str . '_mem_idx'] . "'
							, reg_date   = '" . $send_date . "'
					";
					db_query($history_query);
					query_history($history_query, 'sms_history', 'insert');

				// 개수 차감하기
					$update = "update company_setting set sms_cnt = sms_cnt - 1 where comp_idx = '" . $comp_idx . "'";
					db_query($update);
					query_history($update, 'company_setting', 'update');
				}
			}
		}
	}

//-------------------------------------- email 보내기
	function send_email($receive_email, $send_email, $subject, $comp_idx, $part_idx, $table_name, $table_idx, $form_page)
	{
		global $sess_str;

		$comp_set_where = " and cs.comp_idx = '" . $comp_idx . "'";
		$comp_set_data = company_setting_data('view', $comp_set_where);

		if ($receive_email != '' && $receive_email != '@')
		{
			if ($comp_set_data['receipt_inform_yn'] == "Y")
			{
				if ($form_page != '')
				{
					ob_start();
					include '../include/' . $form_page;
					$message = ob_get_contents();
					ob_end_clean();

					$addtion_header["from"] = $send_email;

					$mail_ok = mail_fsend($receive_email, $subject, $message, $addtion_header);

					if ($mail_ok === true)
					{
					// 이력
						$send_date = date("Y-m-d H:i:s");
						$history_query = "
							insert into email_history set
								  comp_idx      = '" . $comp_idx . "'
								, part_idx      = '" . $part_idx . "'
								, table_name    = '" . $table_name . "'
								, table_idx     = '" . $table_idx . "'
								, send_email    = '" . $send_email . "'
								, receive_email = '" . $receive_email . "'
								, remark        = '" . $message . "'
								, reg_id        = '" . $_SESSION[$sess_str . '_mem_idx'] . "'
								, reg_date      = '" . $send_date . "'
						";
						db_query($history_query);
						query_history($history_query, 'email_history', 'insert');
					}
				}
			}
		}
	}

//-------------------------------------- Push 보내기
	function send_push($receive_email, $push_data, $comp_idx, $part_idx, $table_name, $table_idx)
	{
		global $sess_str;

		$comp_set_where = " and cs.comp_idx = '" . $comp_idx . "'";
		$comp_set_data = company_setting_data('view', $comp_set_where);

		if ($comp_set_data['receipt_inform_yn'] == "Y")
		{
			$comp_where = " and comp.comp_idx = '" . $comp_idx . "'";
			$comp_data = company_info_data('view', $comp_where);

		// 이력
			$send_date = date("Y-m-d H:i:s");
			$history_query = "
				insert into push_history set
					  comp_idx   = '" . $comp_idx . "'
					, part_idx   = '" . $part_idx . "'
					, table_name = '" . $table_name . "'
					, table_idx  = '" . $table_idx . "'
					, push_data  = '" . $push_data . "'
					, reg_id     = '" . $_SESSION[$sess_str . '_mem_idx'] . "'
					, reg_date   = '" . $send_date . "'
			";
			//db_query($history_query);
			//query_history($history_query, 'push_history', 'insert');
		}
		/*
		include_once $_SERVER["DOCUMENT_ROOT"]."/classes/PassnetUtil.php";
		$ip = "112.216.20.59";
		$port = 4227;
		$id = $client_data['user_email'];
		$msg = $push_data;

		$obj = new PassnetUtil($ip,$port);
		$obj->push($id,$msg);
		*/
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////
/////
///// 일정관련 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------- 날짜관련
	function calenda_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '')
	{
		if ($orderby == '') $orderby = "cal.cd_sy asc, cal.cd_sm asc, cal.cd_sd asc";
		if ($del_type == 1) $where = "cal.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(cal.cal_idx)
			from
				calenda_info cal
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				cal.*
			from
				calenda_info cal
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

//-------------------------------------- 일정내용
	function schedule_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "sche.reg_date desc";
		if ($del_type == 1) $where = "sche.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(sche.sche_idx)
			from
				schedule_info sche
				left join code_sche_class code on code.code_idx = sche.sche_class
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				sche.*
				, code.code_name as sche_class_str
			from
				schedule_info sche
				left join code_sche_class code on code.code_idx = sche.sche_class
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
///// 출근관련 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------- 출근부설정
	function diligence_set_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "ds.reg_date desc";
		if ($del_type == 1) $where = "ds.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ds.ds_idx)
			from
				diligence_set ds
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ds.*
			from
				diligence_set ds
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

//-------------------------------------- 출근정보
	function diligence_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "di.reg_date desc";
		if ($del_type == 1) $where = "di.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(di.di_idx)
			from
				diligence_info di
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				di.*
			from
				diligence_info di
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

//-------------------------------------- 해당기본값 셋팅
	function default_code_setting($comp_idx, $part_idx)
	{
		$reg_date = date("Y-m-d H:i:s");

	// 지사별 메뉴기본값 설정
		$menu_where = " and mac.comp_idx = '" . $comp_idx . "' and mac.view_yn = 'Y' and mac.default_yn = 'Y'";
		$menu_order = "mi.sort asc";
		$menu_list = menu_auth_company_data('list', $menu_where, $menu_order, '', '');

		$chk_menu_k = 1;
		$query14 = '';
		foreach ($menu_list as $menu_k => $menu_data)
		{
			if (is_array($menu_data))
			{
			// 메뉴설정값 확인
				$menu_auth_chk_where = " and mc.comp_idx = '" . $comp_idx . "' and mc.part_idx = '" . $part_idx . "' and mc.mi_idx = '" . $menu_data['mi_idx'] . "'";
				$menu_auth_chk_data = menu_company_data('page', $menu_auth_chk_where);
				if ($menu_auth_chk_data['total_num'] == 0) // 값이 없을 경우 등록
				{
					if ($chk_menu_k == 1)
					{
						$query14 = "INSERT INTO menu_company (comp_idx, part_idx, mi_idx, menu_name, default_yn, sort, reg_id, reg_date) VALUES ";
					}
					else $query14 .= ",";
					$query14 .= "('" . $comp_idx . "', '" . $part_idx . "', '" . $menu_data['mi_idx'] . "', '" . $menu_data['menu_name'] . "', 'Y', '" . $menu_data['sort'] . "', 'system', '" . $reg_date . "')";

					$chk_menu_k++;
				}
			}
		}
		if ($query14 != '')
		{
			db_query($query14);
			query_history($query14, 'menu_company', 'insert');
		}

	// 직원그룹
		$query13  = "insert into company_staff_group (comp_idx, part_idx, group_name, view_yn, default_yn, sort, reg_id, reg_date) values";
		$query13 .= "('" . $comp_idx . "', '" . $part_idx . "', '일반', 'Y', 'Y', '1', 'system', '" . $reg_date . "')";
		db_query($query13);
		query_history($query13, 'company_staff_group', 'insert');

	// 접수상태 - 접수, 접수승인, 작업중, 완료, 보류, 취소
		$query4  = "insert into code_receipt_status (comp_idx, part_idx, code_name, code_value, code_color, code_bold, view_yn, default_yn, sort, set_yn, reg_id, reg_date) values";
		$query4 .= " ('" . $comp_idx . "', '" . $part_idx . "', '접수등록', 'RS01', '#ff0000', 'Y', 'Y', 'Y', '1', 'Y', 'system', '" . $reg_date . "')";
		$query4 .= ",('" . $comp_idx . "', '" . $part_idx . "', '접수승인', 'RS02', '#ff6c00', 'Y', 'Y', 'N', '2', 'Y', 'system', '" . $reg_date . "')";
		$query4 .= ",('" . $comp_idx . "', '" . $part_idx . "', '작업진행', 'RS03', '#009e25', 'Y', 'Y', 'N', '3', 'N', 'system', '" . $reg_date . "')";
		$query4 .= ",('" . $comp_idx . "', '" . $part_idx . "', '완료처리', 'RS90', '#518fbb', 'Y', 'Y', 'N', '4', 'Y', 'system', '" . $reg_date . "')";
		$query4 .= ",('" . $comp_idx . "', '" . $part_idx . "', '보류처리', 'RS80', '#6a65bb', 'Y', 'Y', 'N', '5', 'Y', 'system', '" . $reg_date . "')";
		$query4 .= ",('" . $comp_idx . "', '" . $part_idx . "', '취소처리', 'RS60', '#636363', 'Y', 'Y', 'N', '6', 'Y', 'system', '" . $reg_date . "')";
		db_query($query4);
		query_history($query4, 'code_receipt_status', 'insert');

	// 업무분류
		$query5  = "insert into code_work_class (comp_idx, part_idx, code_name, code_value, view_yn, default_yn, sort, set_yn, reg_id, reg_date) values";
		$query5 .= "('" . $comp_idx . "', '" . $part_idx . "', '일반', '1', 'Y', 'Y', '1', 'Y', 'system', '" . $reg_date . "')";
		db_query($query5);
		query_history($query5, 'code_work_class', 'insert');

	// 업무상태 - 대기 - WS01, 진행 - WS02, 승인대기 - WS20, 반려 - WS70, 보류 - WS80, 취소 - WS60, 완료 - WS90, 종료 - WS99
		$query6  = "insert into code_work_status (comp_idx, part_idx, code_name, code_value, code_color, code_bold, view_yn, default_yn, sort, set_yn, reg_id, reg_date) values";
		$query6 .= " ('" . $comp_idx . "', '" . $part_idx . "', '대기',     'WS01', '#ffaa00', 'Y', 'Y', 'Y', '1', 'Y', 'system', '" . $reg_date . "')";
		$query6 .= ",('" . $comp_idx . "', '" . $part_idx . "', '진행',     'WS02', '#009e25', 'Y', 'Y', 'N', '2', 'Y', 'system', '" . $reg_date . "')";
		$query6 .= ",('" . $comp_idx . "', '" . $part_idx . "', '승인대기', 'WS20', '#7820b9', 'Y', 'Y', 'N', '3', 'Y', 'system', '" . $reg_date . "')";
		$query6 .= ",('" . $comp_idx . "', '" . $part_idx . "', '요청대기', 'WS30', '#00b0a2', 'Y', 'Y', 'N', '4', 'Y', 'system', '" . $reg_date . "')";
		$query6 .= ",('" . $comp_idx . "', '" . $part_idx . "', '반려',     'WS70', '#a6cf00', 'Y', 'Y', 'N', '5', 'Y', 'system', '" . $reg_date . "')";
		$query6 .= ",('" . $comp_idx . "', '" . $part_idx . "', '보류',     'WS80', '#00b0a2', 'Y', 'Y', 'N', '6', 'Y', 'system', '" . $reg_date . "')";
		$query6 .= ",('" . $comp_idx . "', '" . $part_idx . "', '취소',     'WS60', '',        'Y', 'Y', 'N', '7', 'Y', 'system', '" . $reg_date . "')";
		$query6 .= ",('" . $comp_idx . "', '" . $part_idx . "', '완료',     'WS90', '#0075c8', 'Y', 'Y', 'N', '8', 'Y', 'system', '" . $reg_date . "')";
		$query6 .= ",('" . $comp_idx . "', '" . $part_idx . "', '종료',     'WS99', '#3a32c3', 'Y', 'Y', 'N', '9', 'Y', 'system', '" . $reg_date . "')";
		db_query($query6);
		query_history($query6, 'code_work_status', 'insert');

	// 프로젝트상태 - 대기, 진행, 취소, 보류, 완료
		$query10  = "insert into code_project_status (comp_idx, part_idx, code_name, code_value, code_color, code_bold, view_yn, default_yn, sort, set_yn, reg_id, reg_date) values";
		$query10 .= " ('" . $comp_idx . "', '" . $part_idx . "', '대기', 'PS01', '#ffaa00', 'Y', 'Y', 'Y', '1', 'Y', 'system', '" . $reg_date . "')";
		$query10 .= ",('" . $comp_idx . "', '" . $part_idx . "', '진행', 'PS02', '#009e25', 'Y', 'Y', 'N', '2', 'Y', 'system', '" . $reg_date . "')";
		$query10 .= ",('" . $comp_idx . "', '" . $part_idx . "', '취소', 'PS60', '',        '',  'Y', 'N', '3', 'Y', 'system', '" . $reg_date . "')";
        $query10 .= ",('" . $comp_idx . "', '" . $part_idx . "', '반려', 'PS70', '#a6cf00', 'Y', 'Y', 'N', '4', 'Y', 'system', '" . $reg_date . "')";
		$query10 .= ",('" . $comp_idx . "', '" . $part_idx . "', '보류', 'PS80', '#00b0a2', 'Y', 'Y', 'N', '5', 'Y', 'system', '" . $reg_date . "')";
		$query10 .= ",('" . $comp_idx . "', '" . $part_idx . "', '완료', 'PS90', '#0075c8', 'Y', 'Y', 'N', '6', 'Y', 'system', '" . $reg_date . "')";
		db_query($query10);
		query_history($query10, 'code_project_status', 'insert');

	// 회계구분 - 현금, 카드, 충전, 계좌이체
		$query9  = "insert into code_account_gubun (comp_idx, part_idx, code_name, view_yn, default_yn, sort, reg_id, reg_date) values";
		$query9 .= " ('" . $comp_idx . "', '" . $part_idx . "', '현금',     'Y', 'N', '1', 'system', '" . $reg_date . "')";
		$query9 .= ",('" . $comp_idx . "', '" . $part_idx . "', '카드',     'Y', 'N', '2', 'system', '" . $reg_date . "')";
		$query9 .= ",('" . $comp_idx . "', '" . $part_idx . "', '계좌이체', 'Y', 'N', '3', 'system', '" . $reg_date . "')";
		db_query($query9);
		query_history($query9, 'code_account_gubun', 'insert');

	// 회계계정
		$query8  = "insert into code_account_class (comp_idx, part_idx, menu_depth, menu_num, code_value, code_name, view_yn, default_yn, sort, reg_id, reg_date) values";
		$query8 .= " ('" . $comp_idx . "', '" . $part_idx . "', '1', '57', '',    '손익계산서',         'Y', 'Y', '1',   'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '2', '4',  '',    '매출',               'Y', 'N', '2',   'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '401', '상품매출',           'Y', 'N', '3',   'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '402', '매출환입 및 에누리', 'Y', 'N', '4',   'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '403', '매출할인',           'Y', 'N', '5',   'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '412', '용역수입',           'Y', 'N', '6',   'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '2', '1',  '',    '매출원가',           'Y', 'N', '7',   'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '451', '상품매출원가',       'Y', 'N', '8',   'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '2', '23', '',    '판관비',             'Y', 'N', '9',   'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '801', '급여',               'Y', 'N', '10',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '808', '퇴직급여',           'Y', 'N', '11',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '811', '복리후생비',         'Y', 'N', '12',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '812', '여비교통비',         'Y', 'N', '13',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '813', '접대비',             'Y', 'N', '14',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '814', '통신비',             'Y', 'N', '15',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '815', '수도광열비',         'Y', 'N', '16',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '817', '세금과공과금',       'Y', 'N', '17',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '818', '감가상각비',         'Y', 'N', '18',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '819', '지급임차료',         'Y', 'N', '19',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '820', '수선비',             'Y', 'N', '20',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '821', '보험료',             'Y', 'N', '21',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '822', '차량유지비',         'Y', 'N', '22',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '824', '운반비',             'Y', 'N', '23',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '825', '교육훈련비',         'Y', 'N', '24',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '826', '도서인쇄비',         'Y', 'N', '25',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '829', '사무용품비',         'Y', 'N', '26',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '830', '소모품비',           'Y', 'N', '27',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '831', '지급수수료',         'Y', 'N', '28',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '833', '광고선전비',         'Y', 'N', '29',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '835', '대손상각비',         'Y', 'N', '30',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '837', '건물관리비',         'Y', 'N', '31',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '2', '7',  '',    '영업외수익',         'Y', 'N', '32',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '901', '이자수익',           'Y', 'N', '33',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '903', '배당금수익',         'Y', 'N', '34',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '904', '수입임대료',         'Y', 'N', '35',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '914', '유형자산처분이익',   'Y', 'N', '36',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '917', '자산수증이익',       'Y', 'N', '37',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '918', '채무면제이익',       'Y', 'N', '38',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '930', '잡이익',             'Y', 'N', '39',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '2', '6',  '',    '영업외비용',         'Y', 'N', '40',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '931', '이자비용',           'Y', 'N', '41',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '933', '기부금',             'Y', 'N', '42',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '936', '매출채권처분손실',   'Y', 'N', '43',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '941', '재해손실',           'Y', 'N', '44',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '950', '유형자산처분손실',   'Y', 'N', '45',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '960', '잡손실',             'Y', 'N', '46',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '2', '1',  '',    '소득세등',           'Y', 'N', '47',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '999', '소득세등',           'Y', 'N', '48',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '1', '57', '',    '재무상태표',         'Y', 'N', '49',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '2', '15', '',    '당좌자산',           'Y', 'N', '50',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '101', '현금',               'Y', 'N', '51',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '102', '당좌예금',           'Y', 'N', '52',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '103', '보통예금',           'Y', 'N', '53',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '105', '정기예적금',         'Y', 'N', '54',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '107', '단기매매증권',       'Y', 'N', '55',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '108', '외상매출금',         'Y', 'N', '56',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '110', '받을어음',           'Y', 'N', '57',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '114', '단기대여금',         'Y', 'N', '58',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '116', '미수수익',           'Y', 'N', '59',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '120', '미수금',             'Y', 'N', '60',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '121', '대손충당금',         'Y', 'N', '61',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '122', '소모품',             'Y', 'N', '62',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '131', '선급금',             'Y', 'N', '63',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '133', '선급비용',           'Y', 'N', '64',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '134', '가지급금',           'Y', 'N', '65',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '2', '3',  '',    '재고자산',           'Y', 'N', '66',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '146', '상품',               'Y', 'N', '67',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '147', '매입환출및에누리',   'Y', 'N', '68',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '148', '매입할인',           'Y', 'N', '69',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '2', '3',  '',    '투자자산',           'Y', 'N', '70',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '176', '장기성예금',         'Y', 'N', '71',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '179', '장기대여금',         'Y', 'N', '72',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '187', '투자부동산',         'Y', 'N', '73',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '2', '7',  '',    '유형자산',           'Y', 'N', '74',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '201', '토지',               'Y', 'N', '75',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '202', '건물',               'Y', 'N', '76',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '204', '구축물',             'Y', 'N', '77',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '206', '기계장치',           'Y', 'N', '78',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '208', '차량운반구',         'Y', 'N', '79',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '212', '비품',               'Y', 'N', '80',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '213', '감가상각누계액',     'Y', 'N', '81',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '2', '4',  '',    '무형자산',           'Y', 'N', '82',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '231', '영업권',             'Y', 'N', '83',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '232', '특허권',             'Y', 'N', '84',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '239', '개발비',             'Y', 'N', '85',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '240', '소프트웨어',         'Y', 'N', '86',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '2', '1',  '',    '기타비유동자산',     'Y', 'N', '87',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '962', '임차보증금',         'Y', 'N', '88',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '2', '10', '',    '유동부채',           'Y', 'N', '89',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '251', '외상매입금',         'Y', 'N', '90',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '252', '지급어음',           'Y', 'N', '91',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '253', '미지급금',           'Y', 'N', '92',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '254', '예수금',             'Y', 'N', '93',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '257', '가수금',             'Y', 'N', '94',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '259', '선수금',             'Y', 'N', '95',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '260', '단기차입금',         'Y', 'N', '96',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '262', '미지급비용',         'Y', 'N', '97',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '263', '선수수익',           'Y', 'N', '98',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '264', '유동성장기부채',     'Y', 'N', '99',  'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '2', '4',  '',    '비유동부채',         'Y', 'N', '100', 'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '291', '사채',               'Y', 'N', '101', 'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '293', '장기차입금',         'Y', 'N', '102', 'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '294', '임대보증금',         'Y', 'N', '103', 'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '295', '퇴직급여충당부채',   'Y', 'N', '104', 'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '2', '1',  '',    '자본금',             'Y', 'N', '105', 'system', '" . $reg_date . "')";
		$query8 .= ",('" . $comp_idx . "', '" . $part_idx . "', '3', '0',  '331', '자본금',             'Y', 'N', '106', 'system', '" . $reg_date . "')";
		db_query($query8);
		query_history($query8, 'code_account_class', 'insert');

	// 회계계정 - 상위값
		$code_where = " and code.part_idx = '" . $part_idx . "'";
		$code_order = "code.sort asc";
		$code_list = code_account_class_data('list', $code_where, $code_order, '', '');
		foreach ($code_list as $k => $code_data)
		{
			if (is_array($code_data))
			{
				$part_idx   = $code_data['part_idx'];
				$menu_depth = $code_data['menu_depth'];

				if ($code_data['menu_depth'] == '1')
				{
					$up_code_idx = '';
					$chk_idx[$part_idx][2] = $code_data['code_idx'];
				}
				else if ($code_data['menu_depth'] == '2')
				{
					$up_code_idx = ',' . $chk_idx[$part_idx][2];
					$chk_idx[$part_idx][3] = $code_data['code_idx'];
				}
				else if ($code_data['menu_depth'] == '3')
				{
					$up_code_idx = ',' . $chk_idx[$part_idx][2] . ',' . $chk_idx[$part_idx][3];
				}
				$code_query = "update code_account_class set up_code_idx = '" . $up_code_idx . "' where code_idx = '" . $code_data['code_idx'] . "';";
				db_query($code_query);
				query_history($code_query, 'code_account_class', 'update');
			}
		}

	// 일정종류 - 개인, 업무, 미팅
		$query7  = "insert into code_sche_class (comp_idx, part_idx, code_name, view_yn, default_yn, sort, reg_id, reg_date) values";
		$query7 .= " ('" . $comp_idx . "', '" . $part_idx . "', '개인', 'Y', 'Y', '1', 'system', '" . $reg_date . "')";
		$query7 .= ",('" . $comp_idx . "', '" . $part_idx . "', '업무', 'Y', 'N', '2', 'system', '" . $reg_date . "')";
		$query7 .= ",('" . $comp_idx . "', '" . $part_idx . "', '미팅', 'Y', 'N', '3', 'system', '" . $reg_date . "')";
		db_query($query7);
		query_history($query7, 'code_sche_class', 'insert');
	}

//-------------------------------------- 파일저장
	function upload_file_save($fnum, $tmp_path, $new_path, $_FILE, $idx, $new_name = 'receipt', $file_form_name = 'file_fname')
	{
		$chk_file_save = $_FILE[$file_form_name . $fnum . '_save_name'];

		if ($chk_file_save != '')
		{
			$chk_file_name = $_FILE[$file_form_name . $fnum . '_file_name'];
			$chk_file_size = $_FILE[$file_form_name . $fnum . '_file_size'];
			$chk_file_type = $_FILE[$file_form_name . $fnum . '_file_type'];
			$chk_file_ext  = $_FILE[$file_form_name . $fnum . '_file_ext'];

			$chk_file_size = str_replace(',', '', $chk_file_size);
			$new_file_name = $new_name . '_' . $idx . '_' . $fnum . '.' . $chk_file_ext;

			$old_file = $tmp_path . '/' . $chk_file_save;
			$new_file = $new_path . '/' . $new_file_name;

			if(!file_exists($new_path))
			{
				mkdir($new_path, 0777, true);
			}

			if (file_exists($old_file))
			{
				if(!copy($old_file, $new_file))
				{
					$upfile_data[$fnum]['error']  = '{"success_chk" : "N", "error_string" : "저장시 오류가 생겼습니다. <br />다시 확인하고 파일을 올리세요."}';
					$upfile_data[$fnum]['f_name'] = '';
					$upfile_data[$fnum]['s_name'] = '';
					$upfile_data[$fnum]['f_size'] = '';
					$upfile_data[$fnum]['f_type'] = '';
					$upfile_data[$fnum]['f_ext']  = '';
				}
				else
				{
					$upfile_data[$fnum]['error']  = '';
					$upfile_data[$fnum]['f_name'] = $chk_file_name;
					$upfile_data[$fnum]['s_name'] = $new_file_name;
					$upfile_data[$fnum]['f_size'] = $chk_file_size;
					$upfile_data[$fnum]['f_type'] = $chk_file_type;
					$upfile_data[$fnum]['f_ext']  = $chk_file_ext;

					unlink($old_file);
				}
				Return $upfile_data;
			}
		}
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////
/////
///// 에이전트관련
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////
//-------------------------------------- 접속 CPU
	function agent_cpu_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "ac.reg_date desc";
		if ($del_type == 1) $where = "1" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ac.ac_idx)
			from
				agent_cpu ac
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ac.*
			from
				agent_cpu ac
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

//-------------------------------------- 접속데이타
	function agent_data_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "ad.reg_date desc";
		if ($del_type == 1) $where = "1" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ad.ad_idx)
			from
				agent_data ad
				left join client_info ci on ci.del_yn = 'N' and ci.ci_idx = ad.ci_idx
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = ad.comp_idx
				left join company_part part on part.del_yn = 'N' and part.part_idx = ci.part_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ad.*
				, comp.comp_name
				, ci.client_name, ci.agent_type
				, datediff('" . date("Y-m-d") . "', if(ifnull(ad.mod_date, 0) = 0, ad.reg_date, ad.mod_date)) as remain_days
			from
				agent_data ad
				left join client_info ci on ci.del_yn = 'N' and ci.ci_idx = ad.ci_idx
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = ad.comp_idx
				left join company_part part on part.del_yn = 'N' and part.part_idx = ci.part_idx
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
///// 총관리자용 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////
//-------------------------------------- 배너관리
	function banner_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "bi.sort asc";
		if ($del_type == 1) $where = "bi.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(bi.bi_idx)
			from
				banner_info bi
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				bi.*
			from
				banner_info bi
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

//-------------------------------------- 공지관리
	function notice_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "ni.sort asc";
		if ($del_type == 1) $where = "ni.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ni.ni_idx)
			from
				notice_info ni
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ni.*
			from
				notice_info ni
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

//-------------------------------------- 푸시이력
	function push_history_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "ph.reg_date desc";
		if ($del_type == 1) $where = "1 " . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ph.idx)
			from
				push_history ph
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = ph.comp_idx
				left join member_info mem on mem.del_yn = 'N' and mem.comp_idx = ph.comp_idx and mem.mem_idx = ph.mem_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ph.*
				, comp.comp_name
				, mem.mem_name
			from
				push_history ph
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = ph.comp_idx
				left join member_info mem on mem.del_yn = 'N' and mem.comp_idx = ph.comp_idx and mem.mem_idx = ph.mem_idx
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

//-------------------------------------- 디비사용량 - 업체별
	function company_db_volume($comp_idx)
	{
		$result = db_query("SHOW TABLE STATUS");
		$str = 0;
		while($dbData = query_fetch_array($result))
		{
			$str += $dbData["Data_length"] + $dbData["Index_length"];
		}
		Return $str;
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////
/////
///// 에이전트 관련 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------- 배너 함수
	function agent_banner_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "ab.sort asc";
		if ($del_type == 1) $where = "ab.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ab.ab_idx)
			from
				agent_banner ab
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ab.*
			from
				agent_banner ab
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

//-------------------------------------- 버튼 함수
	function agent_button_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "abu.sort asc";
		if ($del_type == 1) $where = "abu.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(abu.abu_idx)
			from
				agent_button abu
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				abu.*
			from
				agent_button abu
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

//-------------------------------------- 공지 함수
	function agent_notice_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "an.sort asc";
		if ($del_type == 1) $where = "an.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(an.an_idx)
			from
				agent_notice an
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				an.*
			from
				agent_notice an
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

//-------------------------------------- 일반게시판
	function agent_board_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "abo.order_idx desc";
		if ($del_type == 1) $where = "abo.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(abo.abo_idx)
			from
				agent_board abo
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				abo.*
			from
				agent_board abo
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

//-------------------------------------- 일반게시판 파일
	function agent_board_file_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "abof.sort asc";
		if ($del_type == 1) $where = "abof.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(abof.abof_idx)
			from
				agent_board_file abof
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				abof.*
			from
				agent_board_file abof
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

//-------------------------------------- 상담, 일반 카테고리
	function agent_board_category_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "acate.sort asc";
		if ($del_type == 1) $where = "acate.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(acate.acate_idx)
			from
				agent_board_category acate
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				acate.*
			from
				agent_board_category acate
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

//-------------------------------------- 알림게시판 함수
	function agent_bnotice_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "abn.reg_date desc";
		if ($del_type == 1) $where = "abn.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(abn.abn_idx)
			from
				agent_bnotice abn
				left join company_client_group ccg on ccg.del_yn = 'N' and ccg.comp_idx = abn.comp_idx and ccg.ccg_idx = abn.ccg_idx
				left join code_bnotice_class code on code.del_yn = 'N' and code.comp_idx = abn.comp_idx and code.part_idx = abn.part_idx and code.code_idx = abn.bnotice_class
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				abn.*
				, ccg.group_name
			from
				agent_bnotice abn
				left join company_client_group ccg on ccg.del_yn = 'N' and ccg.comp_idx = abn.comp_idx and ccg.ccg_idx = abn.ccg_idx
				left join code_bnotice_class code on code.del_yn = 'N' and code.comp_idx = abn.comp_idx and code.part_idx = abn.part_idx and code.code_idx = abn.bnotice_class
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

//-------------------------------------- 알림게시판 파일 함수
	function agent_bnotice_file_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "abnf.sort asc";
		if ($del_type == 1) $where = "abnf.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(abnf.abnf_idx)
			from
				agent_bnotice_file abnf
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				abnf.*
			from
				agent_bnotice_file abnf
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

//-------------------------------------- 지사별 알림분류
	function code_bnotice_class_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "code.sort asc";
		if ($del_type == 1) $where = "code.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(code.code_idx)
			from
				code_bnotice_class code
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
				code_bnotice_class code
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

//-------------------------------------- 알림게시판 읽기
	function agent_bnotice_check_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "abc.reg_date desc";
		if ($del_type == 1) $where = "abc.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(abc.abc_idx)
			from
				agent_bnotice_check abc
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				abc.*
			from
				agent_bnotice_check abc
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

//-------------------------------------- 지사별 알림분류 현위치
	function bnotice_class_view($code_idx)
	{
		$where = " and code.code_idx = '" . $code_idx . "'";
		$data = code_bnotice_class_data('view', $where);
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
			$up_data = code_bnotice_class_data("view", $up_where);


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
				$up_data = code_bnotice_class_data("view", $up_where);

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

//-------------------------------------- 알림게시판 읽은 확인 - 맥으로 확인
	function agent_bnotice_data_read($chk_data)
	{
		$chk_ci  = $chk_data['chk_ci'];
		$abn_idx = $chk_data['abn_idx'];
		$chk_mac = $chk_data['chk_mac'];

		if ($abn_idx != '')
		{
			$sub_where = " and abn.abn_idx = '" . $abn_idx . "'";
			$sub_data = agent_bnotice_data('view', $sub_where);

			$check_where = " and abc.ci_idx = '" . $chk_ci . "' and abc.abn_idx = '" . $abn_idx . "' and abc.macaddress = '" . $chk_mac . "'";
			$check_data = agent_bnotice_check_data('view', $check_where);
		}

		if ($check_data['total_num'] == 0)
		{
			$insert_query = "
				insert into agent_bnotice_check set
					  comp_idx   = '" . $sub_data['comp_idx'] . "'
					, part_idx   = '" . $sub_data['part_idx'] . "'
					, ci_idx     = '" . $chk_ci . "'
					, abn_idx    = '" . $abn_idx . "'
					, macaddress = '" . $chk_mac . "'
					, read_date  = '" . date('Y-m-d H:i:s') . "'
					, reg_id     = '" . $code_mem . "'
					, reg_date   = '" . date('Y-m-d H:i:s') . "'
			";
			db_query($insert_query);
			query_history($insert_query, 'agent_bnotice_check', 'insert');
		}
	}

//-------------------------------------- 상담댓글 읽을 값
	function agent_bnotice_read_check($chk_data)
	{
		$chk_comp = $chk_data['chk_comp'];
		$chk_part = $chk_data['chk_part'];
		$chk_ci   = $chk_data['chk_ci'];
		$chk_ccg  = $chk_data['chk_ccg'];
		$chk_mac  = $chk_data['chk_mac'];
		$abn_idx  = $chk_data['abn_idx'];

		$bnotice_check = 0;

		$chk_where = "
			and abn.comp_idx = '" . $chk_comp . "'
			and abn.part_idx = '" . $chk_part . "'
			and (
				if (abn.client_type = '1'
					, if (abn.ccg_idx = '" . $chk_ccg . "', 'Y', 'N')
					, if (abn.client_type = '3'
						, if (concat(abn.ci_idx, ',') like '%," . $chk_ci . ",%', 'Y', 'N')
						, 'Y')
				) = 'Y'
			)";
		if ($abn_idx != '')
		{
			$chk_where .= " and abn.abn_idx = '" . $abn_idx . "'";
		}
		$chk_list = agent_bnotice_data('list', $chk_where, '', '', '');
		foreach ($chk_list as $chk_k => $data)
		{
			if (is_array($data))
			{
				$check_where = " and abc.ci_idx = '" . $chk_ci . "' and abc.abn_idx = '" . $data['abn_idx'] . "' and abc.macaddress = '" . $chk_mac . "'";
				$check_data = agent_bnotice_check_data('view', $check_where);
				if ($check_data['total_num'] == 0)
				{
					$abn_idx = $data['abn_idx'];
					$ri_chk[$abn_idx] = $abn_idx;
					$chk_no++;
				}
				unset($check_data);
			}
		}
		unset($chk_list);

		$bnotice_check = $chk_no;

		if ($bnotice_check > 0)
		{
			$chk_num = 1;
			$add_where = " and (";
			foreach ($ri_chk as $k => $v)
			{
				if ($chk_num == 1)
				{
					$add_where .= " abn.abn_idx = '" . $v . "'";
				}
				else
				{
					$add_where .= " or abn.abn_idx = '" . $v . "'";
				}
				$chk_num++;
			}
			$add_where .= ')';
		}
		else
		{
			$add_where = " and 1 != 1";
		}

		$str['bnotice_check'] = $bnotice_check;
		$str['bnotice_where'] = $add_where;

		Return $str;
	}

//-------------------------------------- 알림게시판관련정보
	function agent_bnotice_list_data($data, $abn_idx)
	{
		global $local_dir, $sess_str, $btn_view;

		$chk_comp = $_SESSION[$sess_str . '_comp_idx'];
		$chk_part = $_SESSION[$sess_str . '_part_idx'];
		$chk_mem  = $_SESSION[$sess_str . '_mem_idx'];

		$subject     = $data['subject'];
		$client_type = $data['client_type'];
		$ccg_idx     = $data['ccg_idx'];
		$ci_idx      = $data['ci_idx'];
		$client_arr  = explode(',', $ci_idx);

	// 분류
		$data['class_str'] = bnotice_class_view($data['bnotice_class']);

		$group_str = ''; $client_str = ''; $total_client_str = '';

		if ($client_type == '2') // 거래처전체
		{
			$client_str       = '거래처전체';
			$total_client_str = '거래처전체';
		}
		else if ($client_type == '1') // 거래처그룹
		{
		// 거래처분류 2단계까지만
			$group_view = client_group_view($ccg_idx);
			$group_name = $group_view['group_level1'];
			if ($group_view['group_level2'] != '') $group_name .= '<br />' . $group_view['group_level2'];
			if ($group_name == '') $group_name = '';

			$group_str = $group_name;
		}
		else // 거래처개별
		{
			$client_len = count($client_arr);
			$client_exp = $client_len - 1;

			$client_where = " and ci.ci_idx = '" . $client_arr[0] . "'";
			$client_data = client_info_data('view', $client_where);
			if ($client_data['del_yn'] == 'Y')
			{
				$client_str = '<span style="color:#CCCCCC">' . $client_data['client_name'] . '</span>';
			}
			else
			{
				$client_str = $client_data['client_name'];
			}
			if ($client_len > 1)
			{
				$client_str .= '외 ' . $client_exp;
			}
			unset($client_data);

			$client_view = '';
			$client_num = 1;
			foreach ($client_arr as $client_k => $client_v)
			{
				if ($client_v != '')
				{
					$client_where = " and ci.ci_idx = '" . $client_v . "'";
					$client_data = client_info_data('view', $client_where);
					if ($client_data['total_num'] > 0)
					{
						if ($client_num == 1)
						{
							$client_view = $client_data['client_name'];
							$client_str = $client_data['client_name'];
						}
						else
						{
							$client_view .= ', ' . $client_data['client_name'];
						}
						$client_num++;
					}
					unset($client_data);
				}
			}
			$total_client_str = $client_view;
			if ($client_num > 2)
			{
				$client_str .= ' 외 ' . ($client_num - 1) . '개';
			}
		}
		unset($client_arr);
		$data['group_str'] = $group_str;

		$data['client_str']       = $client_str;
		$data['total_client_str'] = $total_client_str;

	// 중요도
		if ($data['important'] == 'BNI02')
		{
			$important_span = '<span class="btn_level_1"><span>상</span></span>';
			$important_txt  = '상';
		}
		else if ($data['important'] == 'BNI03')
		{
			$important_span = '<span class="btn_level_2"><span>중</span></span>';
			$important_txt  = '중';
		}
		else if ($data['important'] == 'BNI04')
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
		$file_where = " and abnf.abn_idx = '" . $abn_idx . "'";
		$file_page = agent_bnotice_file_data('page', $file_where);
		$data['total_file'] = $file_page['total_num'];
		if ($data['total_file'] > 0) $data['file_str'] = '<span class="attach" title="첨부파일">' . number_format($data['total_file']) . '</span>';
		else $data['file_str'] = '';
		unset($file_page);

	// 읽을 알림
		global $macaddress, $client_ccg_idx, $client_idx, $client_comp, $client_part;

		$cc_data['chk_comp'] = $client_comp;
		$cc_data['chk_part'] = $client_part;
		$cc_data['chk_ci']   = $client_idx;
		$cc_data['chk_ccg']  = $client_ccg_idx;
		$cc_data['chk_mac']  = $macaddress;
		$cc_data['abn_idx']  = $data['abn_idx'];
		$check_num = agent_bnotice_read_check($cc_data);
		$data['bnotice_check'] = $check_num['bnotice_check'];
		if ($data['bnotice_check'] > 0) $data['bnotice_check_str'] = '<span class="today_num" title="읽을 알림"><em>' . number_format($data['bnotice_check']) . '</em></span>';
		else $data['bnotice_check_str'] = '';

	// 새로등록된 알림 - new 이미지표현
		$new_day = date('YmdHis', time() - (60 * 60 * 24 * 1));
		if (date_replace($data['reg_date'], 'YmdHis') >= $new_day)
		{
			$data['new_img'] = '<img src="' . $local_dir . '/bizstory/images/icon/ico_new2.png" alt="새알림" />';
			$data['new_txt'] = '새알림';
		}
		else
		{
			$data['new_img'] = '';
			$data['new_txt'] = '';
		}

		Return $data;
	}

    function push_send($sender, $comp_idx, $part_idx, $mem_idx, $receiver, $msg_type, $message)
    {
        
        $service_type = "ios";              // 서비스 구분 (android, ios)
        $send = 0;                          // 전송여부 상태
        $state = "9";                       // 전송결과 (1:성공, 0:실패, 9:발송전)
        $state_comment = "";
        $debug = "";
        $result = 0;

		if(!isset($query_type) || empty($query_type)) $query_type = '';

        if ($sender == "") $sender = "bizstorycokr@gmail.com";
        if ($comp_idx == "") $comp_idx = 0;
        if ($part_idx == "") $part_idx = 0;
        if ($mem_idx == "") $mem_idx = 0;

        $where = " pm.push_id = '".$receiver."' and pm.del_yn = 'N'";

        //$log->log("sender=$sender, comp_idx=$comp_idx, part_idx=$part_idx, mem_idx=$mem_idx, receiver=$receiver, msg_type=$msg_type, message=$message");

        $query_page = "
                select
                    count(*)
                from push_member pm 
                where 
                " . $where . " ";
        
        $query_string = "
                select pm.*
                from 
                push_member pm
                where "
                . $where;
        
        // 수신자 정보를 확인한다.
        //$sql = "select * from push_member where push_id = '".$receiver."' and del_yn = 'N'";
        //$log->log("sql = ".$sql);
        //$_list = $db->__list($sql);
		
        if ($query_type == 'view') $data_info = query_view($query_string);
        else if ($query_type == 'page') $data_info = query_page($query_page);
        else
        {
            $data_sql['query_page']   = $query_page;
            $data_sql['query_string'] = $query_string;
            $data_sql['page_size']    = $page_size;
            $data_sql['page_num']     = $page_num;

            $data_info = query_list($data_sql);          
            if ($data_info['total_num'] > 0) {
                foreach($data_info as $info_k => $row) {
					
                    if (is_array($row)) {
                    
                        $debug .= "수신자 확인 OK";
                        
                        $comp_idx = $row['comp_idx'];
                        $part_idx = $row['part_idx'];
                        $mem_idx  = $row['mem_idx'];
            
                        $receiver_name   = $row['push_name'];
                        $device_type     = $row['push_device_type'];
                        $registration_id = $row['push_registration_id'];
            
                        //$log->log("[".$row['push_id']."] push_device_type = ".$device_type.", message = ".$row['push_message'].", receipt = ".$row['push_receipt'].", work = ".$row['push_work'].", notice = ".$row['push_notice'].", consult = ".$row['push_consult']);
            
                        // push 전송기록 등록
                        $send_key = date('YmdHis').rand(100000, 999999);
                        $sql = "insert into push_history (send_key, comp_idx, part_idx, mem_idx, receiver_id, push_device_type, push_registration_id, receiver_name, service_type, msg_type, message, state, state_comment, request_time) "
                             . "values ( '$send_key', $comp_idx, $part_idx, $mem_idx, '$receiver', '$device_type', '$registration_id', '$receiver_name', '$service_type', '$msg_type', '$message', '$state', '$state_comment', now())";						
                         db_query($sql);
                        //$log->log("sql = ".$sql);
                        //$db->__execute($sql);
                        /*
                        if ($device_type == "A")
                        {
                            $result = $c2dm->push_send($sender, $comp_idx, $part_idx, $mem_idx, $receiver, $msg_type, $message);
                        }
                        else if ($device_type == "I")
                        {
                            $log->log("APNS PUSH");
                            $result = $apns->push_send($sender, $registration_id, $comp_idx, $part_idx, $mem_idx, $receiver, $msg_type, $message);
                        }
                         */
                    }
                }
            } else {
                $state = "0";
                $state_comment = "등록되지 않은 사용자";
    
                // push 전송기록 등록
                $send_key = date('YmdHis').rand(100000, 999999);
    
                $query_str = "insert into push_history (send_key, comp_idx, part_idx, mem_idx, receiver_id, receiver_name, service_type, msg_type, message, state, state_comment, request_time) "
                     . "values ( '$send_key', $comp_idx, $part_idx, $mem_idx, '$receiver', '$receiver_name', '$service_type', '$msg_type', '$message', '$state', '$state_comment', now())";
                //$log->log("sql = ".$sql);
                db_query($query_str);
            }
        }
    }
?>