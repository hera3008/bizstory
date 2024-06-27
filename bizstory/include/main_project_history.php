<?
/*
	생성 : 2013.03.04
	위치 : 메인화면 > 프로젝트이력
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
	$project_where = " and prosh.comp_idx = '" . $code_comp . "'";
	if ($set_part_work_yn == 'Y') // 업무통합일 경우
	{ }
	else
	{
		if ($set_part_yn == 'N') // 지사통합 아닐경우
		{
			$project_where .= " and prosh.part_idx = '" . $code_part . "'";
		}
	}

// 총관리자 경우
	if ($code_ubstory == 'Y' && $code_level == '11')
	{ }
// 일반관리자 경우
	else if ($code_ubstory == 'Y' && $code_level == '21')
	{
		$project_where .= "
			and pro.open_yn = 'Y'
			or (concat(',', pro.charge_idx, ',') like '%," . $code_mem . ",%' or pro.apply_idx = '" . $code_mem . "' or pro.reg_id = '" . $code_mem . "')";
	}
// 그외
	else
	{
		$project_where .= " and (concat(',', pro.charge_idx, ',') like '%," . $code_mem . ",%' or pro.apply_idx = '" . $code_mem . "' or pro.reg_id = '" . $code_mem . "')";
	}

	$project_where .= " and pro.del_yn = 'N' and prosh.mem_idx > 0";
	$project_order = "prosh.reg_date desc";
	$project_list = project_status_history_data('list', $project_where, $project_order, 1, 20);

	if ($project_list['total_num'] > 0)
	{
		foreach ($project_list as $project_k => $project_data)
		{
			if (is_array($project_data))
			{
			// 등록자 이미지
				$mem_img = member_img_view($project_data['mem_idx'], $comp_member_dir);

				$project_data['display_type'] = 'display_main';

				$list_data = project_list_data($project_data, $project_data['pro_idx']);

				if ($list_data['subject_url_main'] == 'Y')
				{
					$status_memo = '<a href="javascript:void(0)" onclick="location.href=\'' . $local_dir . '/index.php?fmode=project&smode=project&pro_idx=' . $data['pro_idx'] . '\'">' . $project_data['status_memo'] . '</a>';
				}
				else
				{
					$status_memo = $project_data['status_memo'];
				}
?>
		<li class="line">
			<ul class="li_l">
				<li class="li_img"><?=$list_data['project_img'];?></li>
				<li class="li_subject">
					[<strong>
                    <?if ($list_data['menu1'] == '') {?><?=$list_data['subject_txt'];?><?}else{
                        
                       echo $list_data['menu1'] . '-';
                       
                       if ($list_data['menu2'] != '') echo $list_data['menu2'] . '-';
                       
                       echo $list_data['project_code'];
                     
                    }?>     
                    </strong>]
					<?=$list_data['open_img'];?>
				</li>
				<li class="li_memo"><?=$status_memo;?></li>
			</ul>
			<ul class="li_r">
				<li class="li_date">[<?=date_replace($project_data['reg_date'], 'Y-m-d H:i');?>]</li>
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