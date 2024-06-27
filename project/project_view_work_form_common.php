<?
	$data['work_type'] = 'WT01';
	$data['important'] = 'WI01';

// 업무종류가 '본인'일 경우
	if ($data['work_type'] == 'WT01')
	{
		$charge_view_class = 'class="none"';
		$charge_idx_wt     = $code_mem;
	}
	else $charge_view_class = '';

	$file_upload_num = 0;
	$file_chk_num    = $file_upload_num + 1;

	$project_where = " and pro.pro_idx = '" . $pro_idx . "'";
	$project_data = project_info_data('view', $project_where);
	$project_start_date = $project_data['start_date'];
	$project_end_date   = $project_data['deadline_date'];

	$project_class_where = " and proc.proc_idx = '" . $proc_idx . "'";
	$project_class_data = project_class_data('view', $project_class_where);
	$project_class_date = $project_class_data['deadline_date'];

	$data_open_yn       = $project_data['open_yn'];
	$charge_idx         = $project_class_data['charge_idx'];
	$project_charge_arr = explode(',', $charge_idx);

// 지사
	$part_ok = 'N';
	if ($set_part_work_yn == 'Y')
	{
		$part_where = " and part.comp_idx = '" . $code_comp . "'";
		$part_data = company_part_data('page', $part_where);

		if ($part_data['total_num'] > 1) $part_ok = 'Y';
		unset($part_data);
	}

	$chk_start_date = str_replace('-', '', $project_start_date);
	$chk_today_date = date('Ymd');

	if ($chk_start_date >= $chk_today_date)
	{
		$data_deadline_date = $project_start_date;
	}
	else
	{
		$data_deadline_date = date('Y-m-d');
	}
?>
				<tr>
					<th><label for="workpost_work_type">업무종류</label></th>
					<td>
						<div class="left">
							<input type="hidden" name="param[charge_idx]" id="workpost_charge_idx" value="<?=$charge_idx_wt;?>" title="담당자를 선택하세요." />
							<input type="hidden" name="param[apply_idx]"  id="workpost_apply_idx" value="" title="승인자를 선택하세요." />
							<ul>
								<li>
									<select name="param[work_type]" id="workpost_work_type" title="업무종류를 선택하세요." onchange="work_type_select();">
										<option value="">업무종류선택</option>
									<?
										foreach ($set_work_type as $set_k => $set_v)
										{
									?>
										<option value="<?=$set_k;?>"<?=selected($data['work_type'], $set_k);?>><?=$set_v;?></option>
									<?
										}
									?>
									</select>
								</li>
								<li>
									<span id="workpost_apply_view" class="none"></span>
								</li>
							</ul>
							<div id="workpost_charge_view" <?=$charge_view_class;?>></div>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="workpost_subject">업무제목</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[subject]" id="workpost_subject" class="type_text" title="업무제목을 입력하세요." size="50" value="<?=$data['subject'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="workpost_deadline_date">기한</label></th>
					<td>
						<div class="left">
							<!--<input type="text" name="param[deadline_date]" id="workpost_deadline_date" class="type_text datepicker" title="기한을 입력하세요." size="10" value="<?=date_replace($data_deadline_date, 'Y-m-d');?>" />-->
							<ul>
    							<li>
    							    <select name="deadline_date1" id="post_deadline_date1" onchange="deadline_date_view(this.value, 'deadline_date_view')">
                    <?
                    	$deadline_list = deadline_date();
                        foreach ($deadline_list['date'] as $date_k => $date_v)
                        {
                            echo '
                                <option value="' . $date_v . '">' . $date_v . ' ' . $deadline_list['week'][$date_k] . '</option>';
                        }
                    ?>
                                    <option value="-">---------------</option>
                                    <option value="select">직접선택하기</option>
                                    </select>
                                </li>
                                <li>
                                    <span id="deadline_date_view" class="none">
                                        <input type="text" name="deadline_date2" id="post_deadline_date2" class="type_text datepicker" title="기한을 입력하세요." size="10" value="<?=date_replace($data_deadline_date, 'Y-m-d');?>" />
                                    </span>
                                </li>
                                <li>
                                    <?=code_select($set_work_deadline_txt, 'deadline_str1', 'post_deadline_str1', '', '덧붙이기(선택사항)', '덧붙이기(선택사항)', '', '', 'onchange="deadline_str_view(this.value, \'deadline_str_view\')"');?>
                                </li>
                                <li>
                                    <span id="deadline_str_view" class="none">
                                        <input type="text" name="deadline_str2" id="post_deadline_str2" class="type_text" title="직접입력하세요." size="20" />
                                    </span>
                                </li>
                            </ul>
							<input type="hidden" name="project_class_start_date" id="project_class_start_date" value="<?=$project_start_date;?>" />
							<input type="hidden" name="project_class_end_date"   id="project_class_end_date"   value="<?=$project_class_date;?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th>중요도</th>
					<td>
						<div class="left">
							<?=code_radio($set_work_important, 'param[important]', 'workpost_important', $data['important']);?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="workpost_remark">내용</label></th>
					<td>
						<div class="left textarea_span">
							<textarea name="param[remark]" id="workpost_remark" title="내용을 입력하세요." cols="50" rows="10"><?=$data['remark'];?></textarea>
						</div>
					</td>
				</tr>