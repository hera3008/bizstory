<?
/*
	생성 : 2012.08.06
	위치 : 고객관리 > 접수목록 - 보기 - 접수상태
*/
	require_once "../common/setting.php";
	require_once $local_path . "/bizstory/mobile/process/mobile_setting.php";
	require_once $mobile_path . "/process/member_chk.php";
	require_once $mobile_path . "/process/no_direct.php";

	$receipt_info = new receipt_info();
	$receipt_info->ri_idx = $ri_idx;

	//$status_list = $receipt_info->receipt_status_only();

// 상세값 총수
	$chk_where = " and rid.ri_idx = '" . $ri_idx . "'";
	$chk_data = receipt_info_detail_data('view', $chk_where);

	$comp_member_dir = $comp_dir . '/' . $chk_data['comp_idx'] . '/member';

	$sub_where = " and rsh.ri_idx = '" . $ri_idx . "' and rsh.status != ''";
	$sub_order = "rsh.ri_idx asc, rsh.rid_idx asc, rsh.reg_date asc";
	$sub_list = receipt_status_history_data('list', $sub_where, $sub_order, '', '');
	foreach ($sub_list as $sub_k => $sub_data)
	{
		if (is_array($sub_data))
		{
		// 상세접수건
			$detail_where = " and rid.rid_idx = '" . $sub_data['rid_idx'] . "'";
			$detail_data = receipt_info_detail_data('view', $detail_where);

			$rid_idx  = $sub_data['rid_idx'];
			$status   = $sub_data['status'];
			$mem_idx  = $detail_data['mem_idx'];
			$mem_name = $detail_data['mem_name'];

		// 담당자 이미지
			$photo_where = " and mf.mem_idx = '" . $mem_idx . "' and mf.sort = '1'";
			$photo_data = member_file_data('view', $photo_where);
			if ($photo_data['total_num'] > 0)
			{
				$photo_img = '<img src="' . $comp_member_dir . '/' . $photo_data['mem_idx'] . '/' . $photo_data['img_sname'] . '" width="26px" height="26px" alt="' . $photo_data['mem_name'] . '" />';
			}
			else
			{
				$photo_img = '<img src="' . $local_dir . '/bizstory/images/common/no_photo.gif" width="26px" height="26px" alt="' . $photo_data['mem_name'] . '" />';
			}

		// 접수상태
			$status_where = " and code.comp_idx = '" . $sub_data['comp_idx'] . "' and code.part_idx = '" . $sub_data['part_idx'] . "' and code.code_value = '" . $status . "'";
			$status_data = code_receipt_status_data('view', $status_where);

			$status_str = '<span style="';
			if ($status_data['code_bold'] == 'Y') $status_str .= 'font-weight:900;';
			if ($status_data['code_color'] != '') $status_str .= 'color:' . $status_data['code_color'] . ';';
			$status_str .= '">' . $status_data['code_name'] . '</span>';

		// 접수자/변경자
			if ($sub_data['mem_name'] == '') $reg_name = $sub_data['reg_id'];
			else $reg_name = $sub_data['mem_name'];

		// 상태별로 표현
			if ($status == 'RS01') // 접수등록
			{
				$status_list[$rid_idx][$status] .= '<div><span class="icon01"></span> ' . $status_str . ' [' . $reg_name . ' : ' . $sub_data['reg_date'] . ']</div>';
			}
			else
			{
				$status_list[$rid_idx][$status] .= '<div>';
				if ($chk_data['total_num'] > 0)
				{
					if ($rid_idx != $old_rid_idx)
					{
						$status_list[$rid_idx][$status] .= '
							<div class="mem_user">
								<span class="mem">' . $photo_img . '</span>
								<span class="user"><a class="name_ui">' . $mem_name . '</a></span>
							</div>
						';
					}
					$status_list[$rid_idx][$status] .= '<div><span class="icon03"></span> ' . $status_str . ' [' . $reg_name . ' : ' . $sub_data['reg_date'] . ']</div>';
				}
				else
				{
					$status_list[$rid_idx][$status] .= '<div><span class="icon01"></span> ' . $status_str . ' [' . $reg_name . ' : ' . $sub_data['reg_date'] . ']</div>';
				}
				$status_list[$rid_idx][$status] .= '</div>';
			}
			if ($status == 'RS90') // 완료일 경우
			{
				$status_list[$rid_idx][$status] .= '<div class="status_str"><span class="icon02"></span> ' . nl2br($detail_data['remark_end']) . '</div>';
			}
			$old_rid_idx = $rid_idx;
		}
	}

	ksort($status_list);



	foreach ($status_list as $status_k => $status_v)
	{
		foreach ($status_v as $status_k1 => $status_data)
		{
			echo $status_data;
		}
	}
?>
