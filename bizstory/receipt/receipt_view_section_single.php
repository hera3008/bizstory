<?
	if ($rid_idx != '')
	{
		$singular_where = " and rid.rid_idx = '" . $rid_idx . "'";
		$detail_data = receipt_info_detail_data('view', $singular_where);
	}

	$receipt_class = receipt_class_view($detail_data['receipt_class']);
	$receipt_class = $receipt_class['code_name'];

	$mf_where = " and mf.mem_idx = '" . $detail_data['mem_idx'] . "' and mf.sort = 1";
	$mf_data  = member_file_data('view', $mf_where);

	if ($mf_data['img_sname'] != '')
	{
		$mem_image = '<img class="photo" src="' . $comp_member_dir . '/' . $mf_data['mem_idx'] . '/' . $mf_data['img_sname'] . '" alt="' . $mf_data['mem_name'] . '" width="80px" height="80px" />';
	}
	else
	{
		$mem_image = '<img class="photo" src="' . $local_dir . '/bizstory/images/tfuse-top-panel/no_member.jpg" alt="' . $mem_data['mem_name'] . '" width="80px" height="80px" />';
	}

	if ($sub_type == 'singular_view') // 단일보기
	{
?>
	<div class="singular_top">
		<p class="count">
	<?
		if ($detail_data['receipt_status'] == 'RS90' || $detail_data['receipt_status'] == 'RS60' || ($_SESSION[$sess_str . '_ubstory_level'] > '11' && $code_mem != $detail_data['mem_idx'])) // 완료, 취소, 관리자, 담당자
		{
	?>
			<span class="txt">
	<?
		}
	?>
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


	<?
		if ($detail_data['receipt_status'] != 'RS90' && $detail_data['receipt_status'] != 'RS60') // 완료, 취소
		{
			if ($_SESSION[$sess_str . '_ubstory_level'] <= '11' || $code_mem == $detail_data['mem_idx']) // 관리자, 담당자
			{
	?>
			<span class="btn_big"><input type="button" value="수정" onclick="singular_modify('<?=$detail_data['rid_idx'];?>')" /></span>

			<span><select id="detail_receipt_status_<?=$rid_idx;?>" name="detail_receipt_status_<?=$rid_idx;?>" title="접수상태 선택" onchange="receipt_status_end(this.value, '<?=$rid_idx;?>')">
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
			<a href="javascript:void(0)" onclick="receipt_status_change('<?=$rid_idx;?>');"><img src="<?=$local_dir;?>/bizstory/images/btn/btn_apply.gif" alt="적용" /></a></span>
	<?
			}
			else
			{
				echo '</span>';
			}
		}
		else
		{
			echo '</span>';
		}
	?>
		</p>
	</div>
	<div class="plural_view" id="end_view_<?=$rid_idx;?>" style="display:none">
		<div class="info_text">
			<ul>
				<li>담당자의 [완료처리] 내역은 [보고서] 완료내역에 출력됩니다.
				<span id="status_end_text_<?=$rid_idx;?>" style="display:none" class="status_end_text">
					<span><img src="<?=$local_dir;?>/bizstory/images/icon/icon_04.png" alt="금지" /></span> 완료, 취소처리시 수정, 삭제 불가
				</span>
				</li>
			</ul>
		</div>
		<div class="info_status">
			<div class="mem_img">
				<?=$mem_image;?>
			</div>
			<div class="info_status_remark">
				<div class="info_status_remark_area">
					<textarea cols="30" rows="5" name="detail_remark_end_<?=$rid_idx;?>" id="detail_remark_end_<?=$rid_idx;?>" title="완료문구를 입력하세요."></textarea>
				</div>
				<!--//
				<div class="filewrap">
					<input type="file" name="view_file_fname" id="view_file_fname" class="type_text type_file type_multi" title="파일 선택하기" />
					<div class="file">
						<ul id="view_file_fname_view">
						</ul>
					</div>
				</div>
				//-->
			</div>
			<!--//<input type="hidden" name="file_upload_num" id="file_upload_num" value="<?=$file_upload_num;?>" /> //-->
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
?>
	<div class="singular_top">
		<p class="count">
			<span><img src="<?=$local_dir;?>/bizstory/images/icon/icon_03.png" alt="" /><span>접수분류</span></span>
			<select id="detail_receipt_class" name="detail_receipt_class" title="접수분류 선택">
				<option value="">접수분류 선택</option>
			</select>

			<span><img src="<?=$local_dir;?>/bizstory/images/icon/icon_03.png" alt="" /><span>담당자</span></span>
			<select id="detail_mem_idx" name="detail_mem_idx" title="담당자 선택">
				<option value="">담당자 선택</option>
			</select>

			<span><img src="<?=$local_dir;?>/bizstory/images/icon/icon_03.png" alt="" /><span>완료예정일</span></span>
			<input type="text" id="detail_end_pre_date" name="detail_end_pre_date" class="type_text datepicker" title="완료예정일 입력하세요." size="10" value="<?=date_replace($detail_data['end_pre_date'], 'Y-m-d');?>" />
<?
		if ($_SESSION[$sess_str . '_ubstory_level'] <= '11' || $code_mem == $detail_data['mem_idx']) // 관리자, 담당자
		{
?>
			<span class="btn_big"><input type="button" value="<?=$btn_str;?>" onclick="check_singular('<?=$rid_idx;?>')" /></span>
<?
		}
?>
		</p>
	</div>
<?
	}
?>