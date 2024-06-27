<?
	require_once "../../common/setting.php";
	require_once $local_path . "/bizstory/mobile/process/mobile_setting.php";
	require_once $mobile_path . "/process/member_chk.php";
	require_once $mobile_path . "/process/no_direct.php";

	$morenum1 = $morenum + 1;

// 접수목록
	if ($moretype == 'receipt')
	{
		$where = " and ci.comp_idx = '" . $code_comp . "' and ci.part_idx = '" . $code_part . "'";
		if ($list_type == 'list_no')
		{
			$where .= " and (ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60')";
		}
		$list = receipt_info_data('list', $where, '', $morenum1, $moresize);
		if ($morenum1 <= $list['total_page'])
		{
			foreach ($list as $k => $data)
			{
				if (is_array($data))
				{
					$list_data = receipt_list_data($data['ri_idx'], $data);
?>
		<li class="barmenu2 loop">
				<strong class="date"><span><?=date_replace($data['reg_date'], 'm-d');?></span></strong>
				<strong class="date"><?=$list_data['receipt_status_str'];?></strong>
				<strong class="gubun">[<?=$data['client_name'];?>]</strong>
				<?=$list_data['subject'];?>
			<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/receipt_view.php?ri_idx=<?=$data['ri_idx'];?>'" class="arrow"><em class="push"></em>
<?
	if ($list_data['total_file'] > 0)
	{
		echo '
				<span class="attach" title="첨부파일">', number_format($list_data['total_file']), '</span>';
	}
	if ($list_data['total_comment'] > 0)
	{
		echo '
				<span class="cmt" title="코멘트">', number_format($list_data['total_comment']), '</span>';
	}

	if ($read_work > 0)
	{
		echo '
				<span class="today_num" title="읽을 코멘트"><em>', number_format($read_work), '</em></span>';
	}
?>
			</a>
		</li>
<?
				}
			}
		}
	}
// 나의 업무
	else if ($moretype == 'work_my')
	{
		$today_date = date('Y-m-d');

		$where  = " and wi.comp_idx = '" . $code_comp . "'";
		$where .= " and (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "')";
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
		$list = work_info_data('list', $where, $orderby, $morenum1, $moresize);
		if ($morenum1 <= $list['total_page'])
		{
			foreach ($list as $k => $data)
			{
				if (is_array($data))
				{
					$list_data = work_list_data($data, $data['wi_idx']);
?>
		<li class="barmenu2 loop">
			<strong class="date"><span><?=$list_data['deadline_date_mobile'];?></span></strong>
			<?=$list_data['work_img'];?>
			<?=$list_data['part_img'];?>
			<?=$list_data['subject'];?>
			<?=$list_data['important_img'];?>
			<?=$list_data['open_img'];?>
			<?=$list_data['file_str'];?>
			<?=$list_data['report_str'];?>
			<?=$list_data['comment_str'];?>
			<?=$list_data['new_img'];?>
			<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/work_my_view.php?wi_idx=<?=$data['wi_idx'];?>'" class="arrow">
				<em class="push"></em>
			</a>
		</li>
<?
				}
			}
		}
	}

// 받은쪽지
	else if ($moretype == 'msg')
	{
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
	}

// 상담
	else if ($moretype == 'consult')
	{
		$where  = " and cons.comp_idx = '" . $code_comp . "' and concat(',', cons.charge_idx, ',') like '%," . $code_mem . ",%'";
		$list = consult_info_data('list', $where, '', $morenum1, $moresize);

		if ($morenum1 <= $list['total_page'])
		{
			foreach ($list as $k => $data)
			{
				if (is_array($data))
				{
					$list_data = consult_list_data($data['cons_idx'], $data);
?>
		<li class="barmenu2 loop">
			<strong class="date"><span><?=date_replace($list_data['reg_date'], 'm-d');?></span></strong>
			<strong class="gubun">[<?=$list_data['client_name'];?>]</strong>
			<?=$list_data['subject'];?>
			<?=$list_data['important_str'];?>
			<?=$list_data['total_file_str'];?>
			<?=$list_data['total_comment_str'];?>
			<?=$list_data['read_consult_str'];?>
			<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/consult_view.php?cons_idx=<?=$list_data['cons_idx'];?>'" class="arrow">
				<em class="push"></em>
			</a>
		</li>
<?
				}
			}
		}
	}
?>