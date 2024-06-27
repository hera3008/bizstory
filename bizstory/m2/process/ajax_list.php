<?
	include "../../common/set_info.php";
	include "../process/mobile_setting.php";
	include "../process/ajax_member_chk.php";
	include "../process/no_direct.php";
	
	$morenum1 = $morenum + 1;

// 접수목록
	
	switch($moretype) {
		case 'receipt':
			
		//$code_part = search_company_part($code_part);
		
		$where = " and ci.comp_idx = '" . $code_comp . "' and ci.part_idx = '" . $code_part . "'";
		
		if($set_part_yn == 'N') $where .= " and ri.part_idx = '" . $code_part ."'";
		
		if ($shclass != '' && $shclass != 'all') // 접수분류
		{
			$where .= " and (concat(code.up_code_idx, ',') like '%" . $shclass . ",%' or ri.receipt_class = '" . $shclass . "')";
		}
		if ($shstatus != '' && $shstatus != 'all') // 접수상태
		{
			if ($shstatus == 'end_no')
			{
				$where .= " and (ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60')";
			}
			else
			{
				$where .= " and ri.receipt_status = '" . $shstatus . "'";
			}
		}
		
		if ($list_type == 'all_no') // 전체 미처리
		{
			$where .= " and (ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60')";
		}
		if ($sdate1 != "") $where .= " and date_format(ri.reg_date, '%Y-%m-%d') >= '" . $sdate1 . "'";
		if ($sdate2 != "") $where .= " and date_format(ri.reg_date, '%Y-%m-%d') <= '" . $sdate2 . "'";
		if ($stext != '' && $swhere != '')
		{
			if ($swhere == 'ri.tel_num')
			{
				$stext = str_replace('-', '', $stext);
				$stext = str_replace('.', '', $stext);
				$where .= " and (
					replace(ri.tel_num, '-', '') like '%" . $stext . "%' or
					replace(ri.tel_num, '.', '') like '%" . $stext . "%'
				)";
			}
			else $where .= " and " . $swhere . " like '%" . $stext . "%'";
		}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 정렬
		if ($sorder1 == '') $sorder1 = 'ri.reg_date';
		if ($sorder2 == '') $sorder2 = 'desc';
		$orderby = $sorder1 . ' ' . $sorder2;
		
		if ($list_type == 'my_no') //나의 미처리
		{
			$where .= "
				and (ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60')
				and (
					if (ifnull(rid.mem_idx, '') = ''
						, if (ifnull(ri.charge_mem_idx, '') = ''
							, ci.mem_idx
							, ri.charge_mem_idx)
						, rid.mem_idx) = '" . $code_mem . "')
			";
			
			$query_page = "
				select
					count(ri.ri_idx)
				from
					receipt_info ri
					left join client_info ci on ci.comp_idx = ri.comp_idx and ci.ci_idx = ri.ci_idx
					left join (select ri_idx, mem_idx from receipt_info_detail where del_yn = 'N' group by ri_idx) rid on rid.ri_idx = ri.ri_idx
	
					left join member_info mem on mem.comp_idx = ci.comp_idx and mem.mem_idx = ci.mem_idx
					left join code_receipt_class code on code.comp_idx = ri.comp_idx and code.part_idx = ri.part_idx and code.code_idx = ri.receipt_class
					left join code_receipt_status code2 on code2.comp_idx = ri.comp_idx and code2.part_idx = ri.part_idx and code2.code_value = ri.receipt_status
				where
					ri.del_yn = 'N'
					" . $where . "
			";
			//echo "<pre>" . $query_page . "</pre><br />";
			$query_string = "
				select
					ri.*
					, ci.client_name, ci.del_yn as client_del_yn, ci.link_url
					, mem.mem_name, mem.del_yn as member_del_yn, mem.mem_idx
					, code.del_yn as class_del_yn
					, code2.code_name as receipt_status_str, code2.code_bold as receipt_status_bold, code2.code_color as receipt_status_color, code2.code_value as status_value
				from
					receipt_info ri
					left join client_info ci on ci.comp_idx = ri.comp_idx and ci.ci_idx = ri.ci_idx
					left join (select ri_idx, mem_idx from receipt_info_detail where del_yn = 'N' group by ri_idx) rid on rid.ri_idx = ri.ri_idx
	
					left join member_info mem on mem.comp_idx = ci.comp_idx and mem.mem_idx = ci.mem_idx
					left join code_receipt_class code on code.comp_idx = ri.comp_idx and code.part_idx = ri.part_idx and code.code_idx = ri.receipt_class
					left join code_receipt_status code2 on code2.comp_idx = ri.comp_idx and code2.part_idx = ri.part_idx and code2.code_value = ri.receipt_status
				where
					ri.del_yn = 'N'
					" . $where . "
				order by
					" . $orderby . "
			";
			
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $moresize;
			$data_sql['page_num']     = $morenum1;
			
			$list = query_list($data_sql);
			
		} else {
			$list = receipt_info_data_mobile('list', $where, '', $morenum1, $moresize);
		}
		
		$listData = array();

		if ($morenum1 <= $list['total_page'])
		{
			$idx = $morenum * $moresize;
			foreach ($list as $k => $data)
			{
				if (is_array($data))
				{
					$list_data = receipt_list_data2($data['ri_idx'], $data);			

					array_push($listData,
						array('ri_idx'=>$list_data['ri_idx'],
						'subject_txt'=>$list_data['subject'],
						'receipt_status_str'=>$list_data['receipt_status_str'],
						'reg_date'=>date_replace($list_data['reg_date'],Ymdw),
						'client_name'=>$list_data['client_name'],
						'file_str'=>$list_data['file_str'],
						'total_coment'=>$list_data['comment_str']
						));
						
					$idx++;
				}
			}
		}


		break;
		
		case 'client':
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 검색
			$search_company = $_POST["search_company"]; 
	
			if ($search_company != ''){
				$code_part = $search_company;
			}
			
			$where = " and ci.comp_idx = '" . $code_comp . "' and ci.part_idx = '" . $code_part . "'";
			if ($shgroup != '' && $shgroup != 'all') // 거래처분류
			{
				$where .= " and (concat(ccg.up_ccg_idx, ',') like '%" . $shgroup . ",%' or ci.ccg_idx = '" . $shgroup . "')";
			}
			if ($stext != '' && $swhere != '')
			{
				if ($swhere == 'ci.tel_num')
				{
					$stext = str_replace('-', '', $stext);
					$stext = str_replace('.', '', $stext);
					$where .= " and (
						replace(ci.tel_num, '-', '') like '%" . $stext . "%' or
						replace(ci.tel_num, '.', '') like '%" . $stext . "%'or
						replace(ci.fax_num, '-', '') like '%" . $stext . "%'or
						replace(ci.fax_num, '.', '') like '%" . $stext . "%'
					)";
				}
				else $where .= " and " . $swhere . " like '%" . $stext . "%'";
			}
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 정렬
			if ($sorder1 == '') $sorder1 = 'ci.reg_date';
			if ($sorder2 == '') $sorder2 = 'desc';
			$orderby = $sorder1 . ' ' . $sorder2;
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 목록, 페이지관련
			$list = client_info_data('list', $where, $orderby, $morenum1, $page_size);
			$page_num = $list['page_num'];
				
	
			$listData = array();
			
			if ($morenum1 <= $list['total_page'])
			{
				$idx = $morenum * $moresize;
				$num = $list["total_num"] - ($page_num - 1) * $page_size;
				foreach ($list as $k => $data)
				{
					if (is_array($data))
					{

						$charge_info = $data['charge_info'];
						$charge_info_arr = explode('||', $charge_info);
						$info_str = explode('/', $charge_info_arr[0]);
		
						if ($auth_menu['view'] == "Y") $btn_view = "view_open('" . $data["ci_idx"] . "')";
						else $btn_view = "check_auth_popup('view')";
		
						$link_url = $data['link_url'];
						$link_url_arr = explode(',', $link_url);
		
						$tel_num = $info_str[1];
						if ($tel_num != '--' && $tel_num != '-' && $tel_num != '') $tel_num_str = '<a href="tel:' . $tel_num . '" class="tel call_me">' . $tel_num . '</a>';
						else $tel_num_str = '';
		
						$client_email = $info_str[2];
						if ($client_email != '@' && $client_email != '') $client_email_str = '<a href="mailto:' . $client_email . '" class="email">' . $client_email . '</a>';
						else $client_email_str = '';
		
					// 거래처분류 2단계까지만
						$group_view = client_group_view($data['ccg_idx']);
						$group_name = $group_view['group_level1'];
						if ($group_view['group_level2'] != '') $group_name .= '<br />' . $group_view['group_level2'];
		
					// 메모수
						$sub_where = " and cim.ci_idx='" . $data['ci_idx'] . "'";
						$sub_data = client_memo_data('page', $sub_where);
						$data['total_memo'] = $sub_data['total_num'];
		
						//$charge_str = staff_layer_form($data['mem_idx'], '', $set_part_work_yn, $set_color_list2, 'clientliststaff', $data['ci_idx'], '');
						$mem_where = " and mem.mem_idx = '" . $data['mem_idx'] . "'";
						$mem_data = member_info_data('view', $mem_where, '', '', '', 2);
						$mem_name = $mem_data['mem_name'];
						
						array_push($listData, 
							array('ci_idx'=>$data['ci_idx'],
							'num'=>$data['num'],
							'client_name'=>$data['client_name'],
							'total_memo'=>$data['total_memo'],
							'mem_name'=>$mem_name,
							'group_name'=>$group_name,
							'info_str'=>$info_str[0],
							'tel_num_str'=>$tel_num_str,
							'client_email_str'=>$client_email_str,
							'view_yn'=>$data['view_yn'],
							'ip_yn'=>$data['ip_yn'],
							'mem_idx'=>$data['mem_idx'],
							'link_url_arr'=>$link_url_arr
							));
						$num--;	
						$idx++;
	
					}
				}
	
			}

		break;	
		
		case 'work_list':
		// 나의 업무

		$where  = " and wi.comp_idx = '" . $code_comp . "'";
		
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 보고, 코멘트 있는 경우
		if ($sview == 'today')
		{
			$work_check = work_read_check('');
			$add_where = $work_check['work_where'];
		}

		//smember == "all" 전체
		//smember == "멤버idx" 특정 사용자
		//smember == "my" 나의업무
		$where = $add_where . " and wi.comp_idx = '" . $code_comp . "'";
		//모바일버전에서는 프로젝트는 제외함(2013-08-24)
		//$where .= " and wi.pro_idx is null ";
		
		//업무지사통합여부
		if ($set_part_work_yn == 'Y')
		{ }
		else
		{
			if ($set_part_yn == 'N') $where .= " and wi.part_idx = '" . $code_part . "'";
		}
		if ($swtype != '' && $swtype != 'all') $where .= " and wi.work_type = '" . $swtype . "'";
		if ($shwstatus != '' && $shwstatus != 'all') $where .= " and wi.work_status = '" . $shwstatus . "'";
		if ($send_smember == 'all') // 전체업무일 경우
		{
			$where .= " and (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "' or wi.part_idx = '" . $code_part . "')";
		}
		else if ($send_smember != '')
		{
			$where .= " and (concat(',', wi.charge_idx, ',') like '%," . $smember . ",%' or wi.apply_idx = '" . $smember . "' or wi.reg_id = '" . $smember . "')";
		}
		else
		{
			$where .= " and (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "')";
		}
		if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";
		
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 정렬
		if ($sorder1 == '' || $sorder2 == '')
		{
			$today_date = date('Y-m-d');
			$orderby = "
				 if (ws.code_value = 'WS90'
					, if (wi.end_date = '0000-00-00', '9999-12-31', wi.end_date)
					, if (ws.code_value = 'WS80'
						, '9000-12-31'
						, if (ws.code_value = 'WS01'
							, '9001-12-31'
							, if (ws.code_value = 'WS20'
								, '9002-12-31'
								, '9003-12-31'
							)
						)
					)
				) desc
				, if (datediff('" . $today_date . "', if (wi.deadline_date = '0000-00-00', '9999-12-31', wi.deadline_date)) < 0
					, 0
					, datediff('" . $today_date . "', if (wi.deadline_date = '0000-00-00', '9999-12-31', wi.deadline_date))
				) desc
				, wi.reg_date desc
			";
		}
		else
		{
			$orderby = $sorder1 . ' ' . $sorder2;
		}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 목록, 페이지관련
		$list = work_info_data_mobile('list', $where, $orderby, $morenum1, $moresize);
		
		$listData = array();
		
		if ($morenum1 <= $list['total_page'])
		{
			$idx = $morenum * $moresize;
			foreach ($list as $k => $data)
			{
				if (is_array($data))
				{
					$list_data = work_list_data2($data, $data['wi_idx']);
					
					
					array_push($listData, 
						array('wi_idx'=>$list_data['wi_idx'],
						'subject_txt'=>$list_data['subject_txt'],
						'read_work_str'=>$list_data['read_work_str'],
						'work_img'=>$list_data['work_img'],
						'reg_date'=>date_replace($list_data['reg_date'],Ymdw),
						'charge_str'=>$list_data['charge_str'],
						'important_img'=>$list_data['important_img'],
						'file_str'=>$list_data['file_str'],
						'report_str'=>$list_data['report_str'],
						'comment_str'=>$list_data['comment_str'],
						'end_date_str'=>$list_data['end_date_str']
						));
						
					$idx++;

				}
			}

		}

		break;
		
		case 'msg':
		// 받은쪽지
	
		$where = "and mr.comp_idx = '" . $code_comp . "' and mr.mem_idx = '" . $code_mem . "'";
		$list = message_receive_data('list', $where, '', $morenum1, $moresize);

		if ($morenum1 <= $list['total_page'])
		{
			foreach ($list as $k => $data)
			{
				if (is_array($data))
				{
					$remark = strip_tags($data["remark"]);
					$remark = string_cut($remark, 30);
					if ($data['read_date'] == "" || $data['read_date'] == "0000-00-00 00:00:00")
					{
						$remark = $remark;
					}
					else
					{
						$remark = '<span">' . $remark . '</span>';
					}
?>
		<li class="barmenu2 loop">
			<strong class="subject"><?=$remark;?></strong>
			<strong class="date"><span><?=$data['reg_date'];?></span></strong>
			<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/message_view.php?mr_idx=<?=$data['mr_idx'];?>'" class="arrow">
				<em class="push"></em>
			</a>
		</li>
<?
				}
			}
		}
		
		break;
		
		case 'consult':
		// 상담
	
		$where  = " and cons.comp_idx = '" . $code_comp . "' and concat(',', cons.charge_idx, ',') like '%," . $code_mem . ",%'";
		$list = consult_info_data('list', $where, '', $morenum1, $moresize);

		$listData = array();
		
		if ($morenum1 <= $list['total_page'])
		{
			foreach ($list as $k => $data)
			{
				if (is_array($data))
				{
					$list_data = consult_list_data($data['cons_idx'], $data);
					
					array_push($listData, 
						array('cons_idx'=>$list_data['cons_idx'],
						'client_name'=>$list_data['client_name'],
						'subject'=>$list_data['subject'],
						'important_img'=>$list_data['important_img'],
						'reg_date'=>date_replace($list_data['reg_date'], 'm-d'),
						'total_file_str'=>$list_data['total_file_str'],
						'total_comment_str'=>$list_data['total_comment_str'],
						'read_consult_str'=>$list_data['read_consult_str']
						));
						
					$idx++;

				}
			}
		}

			
		break;
	}

	echo json_encode(array('result_code'=>'0',
		'list_data'=>$listData,
		'last_idx'=>$idx,
		'list'=>$list,
		'more_num'=>$morenum1,
		'total_num'=>$list['total_num'],
		'total_page'=>$list['total_page'],
		'swhere'=>$send_swhere,
		'stext'=>$send_stext,
		'swtype'=>$send_swtype,
		'shwstatus'=>$send_shwstatus,
		'list_type'=>$send_list_type,
		'smember'=>$send_smember,
		'sorder1'=>$send_sorder1,
		'sorder2'=>$send_sorder2			
		));
	db_close();
?>