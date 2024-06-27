<?
/*
	생성 : 2012.11.05
	위치 : 메인화면 > 업무이력
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp    = $_SESSION[$sess_str . '_comp_idx'];
	$code_part    = $_SESSION[$sess_str . '_part_idx'];
	$code_mem     = $_SESSION[$sess_str . '_mem_idx'];
	$code_ubstory = $_SESSION[$sess_str . '_ubstory_yn'];
	$code_level   = $_SESSION[$sess_str . '_ubstory_level'];

	$set_part_yn      = $comp_set_data['part_yn'];
	$set_part_work_yn = $comp_set_data['part_work_yn'];
?>
	<ul>
<?
	$work_where = " and wsh.comp_idx = '" . $code_comp . "'";
	if ($set_part_work_yn == 'Y') // 업무통합일 경우
	{ }
	else
	{
		if ($set_part_yn == 'N') // 지사통합 아닐경우
		{
			$work_where .= " and wsh.part_idx = '" . $code_part . "'";
		}
	}

// 대표일 경우
	if ($code_ubstory == 'Y' && $code_level == '11')
	{ }
// 지사장일 경우
	else if ($code_ubstory == 'Y' && $code_level == '21')
	{
		$work_where .= "
			and wi.open_yn = 'Y'
			or (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "')";
	}
// 그외
	else
	{
		$work_where .= " and (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "')";
	}

	$work_where .= " and wi.del_yn = 'N' and wsh.mem_idx > 0";
	$work_order = "wsh.reg_date desc";
	$work_list = work_status_history_data('list', $work_where, $work_order, 1, 20);

	if ($work_list['total_num'] > 0)
	{
		foreach ($work_list as $work_k => $work_data)
		{
			if (is_array($work_data))
			{
			// 등록자 이미지
				$mem_img = member_img_view($work_data['mem_idx'], $comp_member_dir);

				$work_data['display_type'] = 'display_main';

				$list_data = work_list_data($work_data, $work_data['wi_idx']);

				if ($list_data['view_link_main'] == '')
				{
					$status_memo = $work_data['status_memo'];
				}
				else
				{
					$status_memo = '<a href="javascript:void(0)" onclick="' . $list_data['view_link_main'] . '">' . $work_data['status_memo'] . '</a>';
				}
?>
		<li class="line">
			<ul class="li_l">
				<li class="li_img"><?=$list_data['work_img'];?></li>
				<li class="li_subject">
					<?=$list_data['part_img'];?>
					<?=$list_data['subject_main'];?>
					<?=$list_data['important_img'];?>
					<?=$list_data['open_img'];?>
				</li>
				<li class="li_memo"><?=$status_memo;?></li>
			</ul>
			<ul class="li_r">
				<li class="li_date">[<?=date_replace($work_data['reg_date'], 'Y-m-d H:i');?>]</li>
				<li class="li_mem"><span><?=$mem_img['img_26'];?></span></li>
			</ul>
		</li>
<?
			}
		}
	}
	else
	{
?>
		<li style="height:200px; text-align:center; padding-top:120px;">등록된 내용이 없습니다.</li>
<?
	}
    
    db_close();
?>
	</ul>