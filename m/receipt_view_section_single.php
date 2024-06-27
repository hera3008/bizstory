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
	<div class="status_section" id="receipt_section">
		<ul class="title">
	<?
		if ($detail_data['receipt_status'] == 'RS90' || $detail_data['receipt_status'] == 'RS60' || ($_SESSION[$sess_str . '_ubstory_level'] > '11' && $code_mem != $detail_data['mem_idx'])) // 완료, 취소, 관리자, 담당자
		{
	?>
			
	<?
		}
	?>
				<li class="sort"><span class="fw700">접수분류</span>: 
				<?
					foreach ($receipt_class as $k => $v)
					{
						if ($k == 1) echo $v;
						//else echo ' &gt; ', $v;
					}
				?>
				</li>
				<li class="person"><span class="fw700">담당자</span>: <?=$detail_data['mem_name'];?></li>
		<?
			if ($detail_data['receipt_status'] != 'RS90' && $detail_data['receipt_status'] != 'RS60') // 완료, 취소
			{
		?>
				<li class="date"><span class="fw700 ">완료예정일</span>: <?=date_replace($detail_data['end_pre_date'], 'Y-m-d');?>
				
		<?
			}
			else
			{
		?>
				<li class="date"><span class="fw700">완료일</span> : <?=date_replace($detail_data['end_date'], 'Y-m-d');?>
				
		<?
			}
		?>
	<?
		if ($detail_data['receipt_status'] != 'RS90' && $detail_data['receipt_status'] != 'RS60') // 완료, 취소
		{
			if ($_SESSION[$sess_str . '_ubstory_level'] <= '11' || $code_mem == $detail_data['mem_idx']) // 관리자, 담당자
			{
	?>
			<span class="btn11"><input value="수정" onclick="singular_modify('<?=$detail_data['rid_idx'];?>')" type="button" /></span>
	<?
			}
		}
	?></li>
		</ul>

	<?
		if ($detail_data['receipt_status'] != 'RS90' && $detail_data['receipt_status'] != 'RS60') // 완료, 취소
		{
	?>
		<ul class="title">
			<li class="state">
			<span><span class="fw700">접수상태</span></span>
	<?
			if ($_SESSION[$sess_str . '_ubstory_level'] <= '11' || $code_mem == $detail_data['mem_idx']) // 관리자, 담당자
			{
	?>
			<span>
				<select id="detail_receipt_status_<?=$rid_idx;?>" name="detail_receipt_status_<?=$rid_idx;?>" title="접수상태 선택" onchange="receipt_status_end(this.value, '<?=$rid_idx;?>')">
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
			<a href="javascript:void(0)" onclick="receipt_status_change('<?=$rid_idx;?>');" class="btn05 c_white">적용</a>
			</span>
	<?
			}
	?></li>
		</ul>
	<?
		}
	?>
		
		
		<div class="plural_view" id="end_view_<?=$rid_idx;?>" style="display:none">
			<div class="info_text">
				<ul>
					<li>담당자의 [완료처리] 내역은 [보고서] 완료내역에 출력됩니다.	</li>
					<li class="ico01"><span id="status_end_text_<?=$rid_idx;?>" style="display:none" class="status_end_text">완료, 취소처리시 수정, 삭제 불가</span></li>
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
	$status_list = $receipt_info->receipt_status_only_mobile();

	foreach ($status_list as $status_k => $status_v)
	{
		foreach ($status_v as $status_k1 => $status_data)
		{
			echo $status_data;
		}
	}
?>
		
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
	<div class="status_section" id="receipt_section">
		<ul class="title">
			<li class="sort pb5"><span class="fw700">접수분류</span>: 
				<select id="detail_receipt_class" name="detail_receipt_class" title="접수분류 선택">
					<option value="">접수분류 선택</option>
				</select>
			</li>
			<li class="person pb5"><span class="fw700">담당자</span>:  
				<select id="detail_mem_idx" name="detail_mem_idx" title="담당자 선택">
					<option value="">담당자 선택</option>
				</select>
			</li>
			<li class="date"><span class="fw700">완료예정일</span>: 
				<input type="text" id="detail_end_pre_date" name="detail_end_pre_date" class="type_text date_input bc_w" title="완료예정일 입력하세요." size="10" value="<?=date_replace($detail_data['end_pre_date'], 'Y-m-d');?>" />
<?
		if ($_SESSION[$sess_str . '_ubstory_level'] <= '11' || $code_mem == $detail_data['mem_idx']) // 관리자, 담당자
		{
?>
			<span class="btn11"><input type="button" value="<?=$btn_str;?>" onclick="check_singular('<?=$rid_idx;?>')" /></span>
<?
		}
?>
			</li>
		</ul>
	</div>
<?
	}
?>