<?
	// 지사별
		$part_where = " and part.comp_idx = '" . $code_comp . "' and part.view_yn = 'Y'";
		if ($set_part_work_yn == 'Y') { }
		else if ($set_part_yn == 'N') $part_where .= " and part.part_idx = '" . $code_part . "'";
		$part_list = company_part_data('list', $part_where, '', '', '');

	// 담당자
		$charge_idx_arr = explode(',', $data['charge_idx']);
		$charge_view = form_charge_view('project_member_idx[]', $data['charge_idx'], $part_list, 'select_member();');
?>
						<tr>
							<th><label for="post_subject">* 제목</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[subject]" id="post_subject" class="type_text" title="제목을 입력하세요." size="50" value="<?=$data['subject'];?>" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_project_code">프로젝트코드</label></th>
							<td>							    
								<div class="left">
								    <span class="left" id="up_menu_list"></span>
									<input type="text" name="param[project_code]" id="post_project_code" class="type_text" title="프로젝트코드를 입력하세요." size="20" value="<?=$data['project_code'];?>" onblur="project_code_check()" />
									<span class="field_help">* 프로젝트코드가 없을 경우 자동으로 생성이 됩니다. 중복등록 안됩니다.</span>
								</div>
							</td>
						</tr>
						<tr>
							<th>공개여부</th>
							<td>
								<div class="left">
									<?=code_radio($set_project_open, 'param[open_yn]', 'post_open_yn', $data['open_yn']);?>
									<span class="field_help">* 관련 첨부자료, 프로젝트분류와 업무, 업무보고, 업무코멘트의 상태도 공개여부에 따라 전환됩니다.</span>
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_start_date">* 기한</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[start_date]" id="post_start_date" class="type_text datepicker" title="시작일을 입력하세요." size="10" value="<?=date_replace($data['start_date'], 'Y-m-d');?>" readonly="readonly" />
									~
									<input type="text" name="param[deadline_date]" id="post_deadline_date" class="type_text datepicker" title="종료일을 입력하세요." size="10" value="<?=date_replace($data['deadline_date'], 'Y-m-d');?>" readonly="readonly" />
									<span class="field_help">예) 2013-01-01 ~ 2013-01-31, 달력아이콘을 클릭하여 사용하세요.</span>
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_apply_idx">* 프로젝트 책임자</label></th>
							<td>
								<div class="left">
									<select name="param[apply_idx]" id="post_apply_idx" title="책임자를 지정하세요.">
										<option value="">책임자를 지정하세요.</option>
								<?
									foreach ($part_list as $part_k => $part_data)
									{
										if (is_array($part_data))
										{
								?>
										<option value=""><?=$part_data['part_name'];?></option>
								<?
										// 지사별 직원
											$sub_where2 = " and mem.comp_idx = '" . $code_comp . "' and mem.part_idx = '" . $part_data['part_idx'] . "' and mem.auth_yn = 'Y' and mem.login_yn = 'Y'";
											$sub_order2 = "cpd.sort asc, mem.mem_name asc";
											$mem_list = member_info_data('list', $sub_where2, $sub_order2, '', '');
											foreach ($mem_list as $mem_k => $mem_data)
											{
												if (is_array($mem_data))
												{
								?>
										<option value="<?=$mem_data['mem_idx'];?>" <?=selected($mem_data['mem_idx'], $data['apply_idx']);?>>&nbsp;&nbsp;&nbsp;&nbsp;<?=$mem_data['mem_name'];?></option>
								<?
												}
											}
										}
									}
								?>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_charge_idx">* 담당자</label></th>
							<td>
								<input type="hidden" name="param[charge_idx]" id="post_charge_idx" value="<?=$data['charge_idx'];?>" title="담당자를 선택하세요." />
								<input type="hidden" name="post_old_charge_idx" id="post_old_charge_idx" value="<?=$data['charge_idx'];?>" />
							<?
								echo $charge_view['change_view'];
							?>
							</td>
						</tr>
						<tr>
							<th><label for="post_remark">* 내용</label></th>
							<td>
								<div class="left textarea_span">
									<textarea name="param[remark]" id="post_remark" title="내용을 입력하세요." rows="5" cols="50" class="none"><?=$data['remark'];?></textarea>
								</div>
							</td>
						</tr>