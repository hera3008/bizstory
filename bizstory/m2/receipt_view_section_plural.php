<?
	if ($sub_type == 'plural_form' || $sub_type == 'plural_list')
	{
?>
	<div class="status_section" id="receipt_section">
		<div class="title">
			<span class="txt"><img src="<?=$local_dir;?>/bizstory/images/icon/icon_03.png" alt="" /> 다수 접수업무가 등록되었습니다.</span>
		</div>
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
				<span class="btn_big_green"><input type="button" value="등록" onclick="check_plural()" /></span>
				<span class="btn_big_gray"><input type="button" value="취소" onclick="plural_list()" /></span>
			<?
				} else {
			?>
				<span class="btn_big_blue"><input type="button" value="수정" onclick="check_plural()" /></span>
				<span class="btn_big_gray"><input type="button" value="취소" onclick="plural_list()" /></span>
				<input type="hidden" name="detail_rid_idx" id="detail_rid_idx" value="<?=$rid_idx;?>" />
			<?
				}
			?>
			</div>
		</div>
	</div>
<?
	}
?>