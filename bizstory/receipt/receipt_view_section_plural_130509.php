<?
	if ($sub_type == 'plural_form' || $sub_type == 'plural_list')
	{
?>
	<div class="singular_top">
		<p class="count">
			<span class="txt"><img src="<?=$local_dir;?>/bizstory/images/icon/icon_03.png" alt="" /> 다수 접수업무가 등록되었습니다.</span>
		</p>
	</div>
<?
	}

	if ($sub_type == 'plural_form') // 다수접수 등록/수정
	{
		if ($rid_idx != '')
		{
			$plural_where = " and rid.rid_idx = '" . $rid_idx . "'";
			$detail_data = receipt_info_detail_data('view', $plural_where);
		}
?>
	<div class="plural_view">
		<div class="info_text">
			<ul>
				<li>접수된 업무진행시 담당자를 여러명 지정할 때 사용합니다.</li>
			</ul>
		</div>

		<table class="tinytable write" summary="다수접수 등록/수정합니다.">
		<caption>접수</caption>
		<colgroup>
			<col width="80px" />
			<col width="300px" />
			<col width="80px" />
			<col width="300px" />
		</colgroup>
		<tbody>
			<tr>
				<th><label for="detail_receipt_class">접수분류</label></th>
				<td>
					<div class="left">
						<select id="detail_receipt_class" name="detail_receipt_class" title="접수분류 선택">
							<option value="">접수분류 선택</option>
						</select>
					</div>
				</td>
				<th><label for="detail_mem_idx">담당자</label></th>
				<td>
					<div class="left">
						<select id="detail_mem_idx" name="detail_mem_idx" title="담당자 선택">
							<option value="">담당자 선택</option>
						</select>
					</div>
				</td>
			</tr>
			<tr>
				<th><label for="detail_end_pre_date">완료예정일</label></th>
				<td colspan="3">
					<div class="left">
						<input type="text" id="detail_end_pre_date" name="detail_end_pre_date" class="type_text datepicker" title="완료예정일 입력하세요." size="10" value="<?=date_replace($detail_data['end_pre_date'], 'Y-m-d');?>" />
					</div>
				</td>
			</tr>
			<tr>
				<th><label for="detail_remark">내용</label></th>
				<td colspan="3">
					<textarea name="detail_remark" id="detail_remark" title="내용을 입력하세요."><?=$detail_data['remark'];?></textarea>
				</td>
			</tr>
		</tbody>
		</table>

		<div class="section">
			<div class="fr">
			<?
				if ($rid_idx == '') {
			?>
				<span class="btn_big fl"><input type="button" value="등록하기" onclick="check_plural()" /></span>
				<span class="btn_big fl"><input type="button" value="등록취소" onclick="plural_list()" /></span>
			<?
				} else {
			?>
				<span class="btn_big fl"><input type="button" value="수정하기" onclick="check_plural()" /></span>
				<span class="btn_big fl"><input type="button" value="수정취소" onclick="plural_list()" /></span>
				<input type="hidden" name="detail_rid_idx" id="detail_rid_idx" value="<?=$rid_idx;?>" />
			<?
				}
			?>
			</div>
		</div>
	</div>
<?
	}
	else
	{
		$plural_where = " and rid.ri_idx = '" . $ri_idx . "' and rid.receipt_type = '2'";
		$plural_order = "rid.reg_date asc";
		$plural_list = receipt_info_detail_data('list', $plural_where, $plural_order, '', '');
?>
	<div class="plural_view">
		<div class="info_text">
			<ul>
				<li>접수된 업무진행시 담당자를 여러명 지정할 때 사용합니다.</li>
			</ul>
		</div>
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

			// 담당자이미지
				$mf_where = " and mf.mem_idx = '" . $detail_data["mem_idx"] . "' and mf.sort = 1";
				$mf_data  = member_file_data('view', $mf_where);

				if ($mf_data['img_sname'] != '')
				{
					$mem_image = '<img class="photo" src="' . $comp_member_dir . '/' . $mf_data['mem_idx'] . '/' . $mf_data['img_sname'] . '" alt="' . $mf_data['mem_name'] . '" width="80px" height="80px" />';
				}
				else
				{
					$mem_image = '<img class="photo" src="' . $local_dir . '/bizstory/images/tfuse-top-panel/no_member.jpg" alt="' . $mem_data['mem_name'] . '" width="80px" height="80px" />';
				}
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
		<?
			if ($detail_data['receipt_status'] != 'RS90' && $detail_data['receipt_status'] != 'RS60') // 완료, 취소
			{
		?>
					<div class="right">
						<a href="javascript:void(0);" onclick="plural_form('<?=$detail_data['rid_idx'];?>')" class="btn_con_blue"><span>수정</span></a>
						<a href="javascript:void(0);" onclick="plural_delete('<?=$detail_data['rid_idx'];?>')" class="btn_con_red"><span>삭제</span></a>

						<select id="detail_receipt_status_<?=$detail_data['rid_idx'];?>" name="detail_receipt_status_<?=$detail_data['rid_idx'];?>" title="접수상태 선택" onchange="receipt_status_end(this.value, '<?=$detail_data['rid_idx'];?>')">
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
						<a href="javascript:void(0)" onclick="receipt_status_change('<?=$detail_data['rid_idx'];?>');"><img src="<?=$local_dir;?>/bizstory/images/btn/btn_apply.gif" alt="적용" /></a></span>
					</div>

					<div class="left" id="end_view_<?=$detail_data['rid_idx'];?>" style="display:none">
						<div class="info_text">
							<ul>
								<li>담당자의 [완료처리] 내역은 [보고서] 완료내역에 출력됩니다.
								<span id="status_end_text_<?=$detail_data['rid_idx'];?>" style="display:none" class="status_end_text">
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
									<textarea cols="30" rows="5" name="detail_remark_end_<?=$detail_data['rid_idx'];?>" id="detail_remark_end_<?=$detail_data['rid_idx'];?>" title="완료문구를 입력하세요."></textarea>
								</div>
							</div>
						</div>
					</div>
		<?
			}
		?>
				</td>
			</tr>
<?
			}
		}
	}
?>
		</tbody>
		</table>

		<div class="section">
			<div class="fr">
				<a href="javascript:void(0)" onclick="plural_form('');"><img src="<?=$local_dir;?>/bizstory/images/btn/btn_detail.gif" alt="상세업무등록" /></a>
			</div>
		</div>
	</div>
<?
	}
?>