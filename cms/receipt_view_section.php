<?
/*
	생성 : 2012.08.07
	위치 : 접수보기 - 단일, 다수 접수
*/
// 다수값이 없으면 단일로 인식
	$plural_where = " and rid.ri_idx = '" . $ri_idx . "' and rid.receipt_type = '2'";
	$plural_list = receipt_info_detail_data('page', $plural_where);

	if ($plural_list['total_num'] == 0)
	{
		$sub_type = 'singular_view';

		$singular_where = " and rid.ri_idx = '" . $ri_idx . "' and rid.receipt_type = '1'";
		$detail_data = receipt_info_detail_data('view', $singular_where);

		$receipt_class = receipt_class_view($detail_data['receipt_class']);
		$receipt_class = $receipt_class['code_name'];
?>
	<div class="singular_top">
		<p class="count">
			<span class="txt">
				<span><img src="<?=$local_dir;?>/bizstory/images/icon/icon_03.png" alt="" /><span>접수분류</span> : </span>
				<?
					foreach ($receipt_class as $k => $v)
					{
						if ($k == 1) echo $v;
						//else echo ' &gt; ', $v;
					}
				?>
				<span><img src="<?=$local_dir;?>/bizstory/images/icon/icon_03.png" alt="" /><span>담당자</span> : </span>
				<?=$detail_data['mem_name'];?>
		<?
			if ($detail_data['receipt_status'] != 'RS90' && $detail_data['receipt_status'] != 'RS60') // 완료, 취소
			{
		?>
				<span><img src="<?=$local_dir;?>/bizstory/images/icon/icon_03.png" alt="" /><span>완료예정일</span> : </span>
				<?=date_replace($detail_data['end_pre_date'], 'Y-m-d');?>
		<?
			}
			else
			{
		?>
				<span><img src="<?=$local_dir;?>/bizstory/images/icon/icon_03.png" alt="" /><span>완료일</span> : </span>
				<?=date_replace($detail_data['end_date'], 'Y-m-d');?>
		<?
			}
		?>
			</span>
		</p>
	</div>
<?
	}
	else
	{
?>
	<div class="singular_top">
		<p class="count">
			<span class="txt"><img src="<?=$local_dir;?>/bizstory/images/icon/icon_03.png" alt="" /> 다수 접수업무가 등록되었습니다.</span>
		</p>
	</div>
<?
		$plural_where = " and rid.ri_idx = '" . $ri_idx . "' and rid.receipt_type = '2'";
		$plural_order = "rid.reg_date asc";
		$plural_list = receipt_info_detail_data('list', $plural_where, $plural_order, '', '');
?>
	<div class="plural_view">
		<table class="tinytable">
		<colgroup>
			<col width="100px" />
			<col />
			<col width="80px" />
			<col width="80px" />
			<col width="80px" />
			<col width="80px" />
		</colgroup>
		<thead>
			<tr>
				<th class="nosort">분류</th>
				<th class="nosort">내용</th>
				<th class="nosort">담당자</th>
				<th class="nosort">상태</th>
				<th class="nosort">완료예정일</th>
				<th class="nosort">완료일</th>
			</tr>
		</thead>
		<tbody>
<?
	if ($plural_list['total_num'] == 0)
	{
?>
			<tr>
				<td colspan="6">등록된 데이타가 없습니다.</td>
			</tr>
<?
	}
	else
	{
		foreach ($plural_list as $plural_k => $detail_data)
		{
			if (is_array($detail_data))
			{
				$receipt_class = receipt_class_view($detail_data['receipt_class']); // 분류

			// 접수상태
				$receipt_status_str = '<span style="';
				if ($detail_data['receipt_status_bold'] == 'Y') $receipt_status_str .= 'font-weight:900;';
				if ($detail_data['receipt_status_color'] != '') $receipt_status_str .= 'color:' . $detail_data['receipt_status_color'] . ';';
				$receipt_status_str .= '">' . $detail_data['receipt_status_str'] . '</span>';

				if ($detail_data['status_del_yn'] == 'Y') $status_str = '<span style="color:#CCCCCC">' . $detail_data['receipt_status_str'] . '</span>';
				else $status_str = $receipt_status_str;

				$status_str = $set_receipt_status[$detail_data['receipt_status']];

			// 담당직원
				if ($detail_data['member_del_yn'] == 'Y') $member_str = '<span style="color:#CCCCCC">' . $detail_data['mem_name'] . '</span>';
				else $member_str = $detail_data['mem_name'];
				if ($member_str == '') $member_str = '<span style="color:#AAAAAA">해당없음</span>';

			// 내용
				$remark = strip_tags($detail_data["remark"]);
				$remark = string_cut($remark, 50);
	?>
			<tr>
				<td><?=$receipt_class['first_class'];?></td>
				<td class="receipt_subject"><a href="javascript:void(0)" onclick="plural_remark('<?=$detail_data['rid_idx'];?>')"><?=$remark;?></a></td>
				<td><?=$member_str;?></td>
				<td><?=$status_str;?></td>
				<td><span class="num"><?=date_replace($detail_data['end_pre_date'], 'Y-m-d');?></span></td>
				<td><span class="num"><?=date_replace($detail_data['end_date'], 'Y-m-d');?></span></td>
			</tr>
			<tr id="plural_remark_<?=$detail_data['rid_idx'];?>" style="display:none">
				<td colspan="6" height="50px">
					<div class="left">
						<?=$detail_data['remark'];?>
					</div>
				</td>
			</tr>
<?
			}
		}
	}
?>
		</tbody>
		</table>
<?
	}
?>
