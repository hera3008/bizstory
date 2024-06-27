<?
/*
	생성 : 2012.09.10
	위치 : 접수상태
*/
	require_once "../common/setting.php";
	require_once $local_path . "/bizstory/mobile/process/mobile_setting.php";
	require_once $mobile_path . "/process/member_chk.php";
	require_once $mobile_path . "/process/no_direct.php";

	$receipt_info = new receipt_info();
	$receipt_info->ri_idx = $ri_idx;
	$receipt_info->data_path = $comp_receipt_path;
	$receipt_info->data_dir = $comp_receipt_dir;

	$receipt_data = $receipt_info->receipt_info_view();

	$detail_data['end_pre_date']  = date('Y-m-d');
	$detail_data['receipt_class'] = $receipt_data['receipt_class'];
	$detail_data['mem_idx']       = $receipt_data['charge_mem_idx'];
	$receipt_status = $receipt_data['receipt_status'];

	if ($receipt_status == 'RS01')
	{
		$sub_type = 'postform';
	}
	else
	{
		if ($sub_type == '')
		{
			$singular_where = " and rid.ri_idx = '" . $ri_idx . "' and rid.receipt_type = '1'";
			$singular_data = receipt_info_detail_data('view', $singular_where);

			$rid_idx  = $singular_data['rid_idx'];
			$sub_type = 'singular_view';
		}
	}
	if ($rid_idx != '')
	{
		$detail_where = " and rid.rid_idx = '" . $rid_idx . "'";
		$detail_data = receipt_info_detail_data('view', $detail_where);
	}

	$receipt_class = receipt_class_view($detail_data['receipt_class']);
	$receipt_class = $receipt_class['code_name'];
	$class_len = count($receipt_class) - 1;

	$mem_img = member_img_view($detail_data['mem_idx'], $comp_member_dir);

	if ($sub_type == 'singular_view') // 단일보기
	{
?>
	<div class="singular_top">
		<p class="count">
			<span><img src="<?=$local_dir;?>/bizstory/images/icon/icon_03.png" alt="" /><span>접수분류</span></span>
			<?=$receipt_class[$class_len];?>
		</p>

		<p class="count">
			<span><img src="<?=$local_dir;?>/bizstory/images/icon/icon_03.png" alt="" /><span>담당자</span></span>
			<?=$detail_data['mem_name'];?>
	<?
		if ($detail_data['receipt_status'] != 'RS90' && $detail_data['receipt_status'] != 'RS60') // 완료, 취소
		{
	?>
			<span><img src="<?=$local_dir;?>/bizstory/images/icon/icon_03.png" alt="" /><span>완료예정일</span></span>
			<?=date_replace($detail_data['end_pre_date'], 'Y-m-d');?>
	<?
		}
		else
		{
	?>
			<span><img src="<?=$local_dir;?>/bizstory/images/icon/icon_03.png" alt="" /><span>완료일</span></span>
			<?=date_replace($detail_data['end_date'], 'Y-m-d');?>
	<?
		}
	?>
	<?
		if ($detail_data['receipt_status'] != 'RS90' && $detail_data['receipt_status'] != 'RS60') // 완료, 취소
		{
			if ($code_level <= '11' || $code_mem == $detail_data['mem_idx']) // 관리자, 담당자
			{
	?>
			<span class="btn_sml2"><input type="button" value="수정" onclick="receipt_change_modify('<?=$detail_data['rid_idx'];?>')" /></span>
	<?
			}
		}
	?>
		</p>
	<?
		if ($detail_data['receipt_status'] != 'RS90' && $detail_data['receipt_status'] != 'RS60') // 완료, 취소
		{
			if ($code_level <= '11' || $code_mem == $detail_data['mem_idx']) // 관리자, 담당자
			{
	?>
		<p class="count">
			<span><img src="<?=$local_dir;?>/bizstory/images/icon/icon_03.png" alt="" /><span>접수상태변경</span></span>
			<select id="detail_receipt_status" name="detail_receipt_status" title="접수상태 선택" onchange="receipt_change_end(this.value, '<?=$rid_idx;?>')" class="type_text">
				<option value="">접수상태 선택</option>
		<?
			foreach ($set_receipt_status as $k => $v)
			{
				$view_ok = 'Y';
				if ($k == 'RS01' || $k == 'RS02')
				{
					$view_ok = 'N';
				}
				if ($detail_data['receipt_status'] == 'RS03')
				{
					if ($k == 'RS03')
					{
						$view_ok = 'N';
					}
				}
				if ($view_ok == 'Y')
				{
		?>
				<option value="<?=$k;?>" <?=selected($k, $detail_data['receipt_status']);?>><?=$v;?></option>
		<?
				}
			}
		?>
			</select>
			<a href="javascript:void(0)" onclick="receipt_change_endok('<?=$rid_idx;?>');"><img src="<?=$local_dir;?>/bizstory/images/btn/btn_apply.gif" alt="적용" /></a></span>
		</p>
	<?
			}
		}
	?>
	</div>
	<div class="plural_view" id="end_view_<?=$rid_idx;?>" style="display:none">
		<div class="info_text">
			<ul>
				<li>
					담당자의 [완료처리] 내역은 [보고서] 완료내역에 출력됩니다.
					<span class="status_end_text">
						<span><img src="<?=$local_dir;?>/bizstory/images/icon/icon_04.png" alt="금지" /></span> 완료, 취소처리시 수정, 삭제 불가
					</span>
				</li>
			</ul>
		</div>
		<div class="info_status">
			<div class="mem_img">
				<?=$mem_img['img_80'];?>
			</div>
			<div class="info_status_remark">
				<textarea cols="30" rows="5" name="detail_remark_end" id="detail_remark_end" title="완료문구를 입력하세요." class="type_text"></textarea>
			</div>
		</div>
	</div>
<?
	}
	else // 단일등록, 단일수정
	{
		if ($detail_data['receipt_status'] == 'RS01' || $detail_data['receipt_status'] == '')
		{
			$btn_str = '접수승인';
		}
		else
		{
			$btn_str = '저장';
		}

		$class_where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "' and code.view_yn = 'Y'";
		$class_order = "code.sort asc";
		$class_list = code_receipt_class_data('list', $class_where, $class_order, '', '');

		$staff_where = " and mem.comp_idx = '" . $code_comp . "' and mem.part_idx = '" . $code_part . "' and mem.login_yn = 'Y'";
		$staff_order = "mem.mem_name asc";
		$staff_list = member_info_data('list', $staff_where, $staff_order, '', '');
?>
	<div class="singular_top">
		<p class="count">
			<span><img src="<?=$local_dir;?>/bizstory/images/icon/icon_03.png" alt="" /><span>접수분류</span></span>
			<select id="detail_receipt_class" name="detail_receipt_class" title="접수분류 선택" class="type_text">
				<option value="">접수분류 선택</option>
	<?
		foreach ($class_list as $k => $class_data)
		{
			if (is_array($class_data))
			{
				$empty_num = 3 * ($class_data['menu_depth']-1);
				$empty_str = str_repeat('&nbsp;', $empty_num);
				echo '
				<option value="' . $class_data['code_idx'] . '"' . selected($detail_data['receipt_class'], $class_data['code_idx']) . '>' . $empty_str . $class_data['code_name'] . '</option>
				';
			}
		}
	?>
			</select>
		</p>

		<p class="count">
			<span><img src="<?=$local_dir;?>/bizstory/images/icon/icon_03.png" alt="" /><span>담당자</span></span>
			<select id="detail_mem_idx" name="detail_mem_idx" title="담당자 선택" class="type_text">
				<option value="">담당자 선택</option>
	<?
		foreach ($staff_list as $k => $staff_data)
		{
			if (is_array($staff_data))
			{
				echo '
				<option value="' . $staff_data['mem_idx'] . '"' . selected($detail_data['mem_idx'], $staff_data['mem_idx']) . '>' . $staff_data['mem_name'] . '</option>
				';
			}
		}
	?>
			</select>
		</p>

		<p class="count">
			<span><img src="<?=$local_dir;?>/bizstory/images/icon/icon_03.png" alt="" /><span>완료예정일</span></span>
			<input type="text" id="detail_end_pre_date" name="detail_end_pre_date" class="type_text" title="완료예정일 입력하세요." size="10" value="<?=date_replace($detail_data['end_pre_date'], 'Y-m-d');?>" />
<?
		if ($code_level <= '11' || $code_mem == $detail_data['mem_idx']) // 관리자, 담당자
		{
?>
			<span class="btn_sml2"><input type="button" value="<?=$btn_str;?>" onclick="receipt_change_check('<?=$rid_idx;?>')" /></span>
<?
		}
?>
		</p>
	</div>
<?
	}
?>
