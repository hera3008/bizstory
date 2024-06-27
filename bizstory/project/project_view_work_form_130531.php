<?
/*
	생성 : 2012.12.27
	수정 : 2013.02.05
	위치 : 업무폴더 > 프로젝트관리 - 보기 - 업무 - 등록/수정폼
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'Y';
	if ($form_chk == 'Y')
	{
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
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<input type="hidden" name="file_upload_num"  id="file_upload_num"     value="<?=$file_upload_num;?>" />
			<input type="hidden" name="param[pro_idx]"   id="workpost_pro_idx"    value="<?=$pro_idx;?>" />
			<input type="hidden" name="param[proc_idx]"  id="workpost_proc_idx"   value="<?=$proc_idx;?>" />
			<input type="hidden" name="param[open_yn]"   id="workpost_open_yn"    value="<?=$data_open_yn;?>" />
			<input type="hidden" name="project_class_start_date" id="project_class_start_date" value="<?=$project_start_date;?>" />
			<input type="hidden" name="project_class_end_date"   id="project_class_end_date"   value="<?=$project_class_date;?>" />

		<fieldset>
			<legend class="blind">업무등록 작성</legend>
			<table class="tinytable write" summary="해당 프로젝트 작업의 업무를 등록합니다.">
			<caption>업무등록</caption>
			<colgroup>
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
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
							<input type="text" name="param[deadline_date]" id="workpost_deadline_date" class="type_text datepicker" title="기한을 입력하세요." size="10" value="<?=date_replace($data_deadline_date, 'Y-m-d');?>" />
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
							<textarea name="param[remark]" id="workpost_remark" title="내용을 입력하세요." rows="5" cols="50" class="none"><?=$data['remark'];?></textarea>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="file_fname">파일</label></th>
					<td>
						<div class="filewrap">
							<input type="file" name="file_fname" id="file_fname" class="type_text type_file type_multi" title="파일 선택하기" />
							<div class="file">
								<ul id="file_fname_view"></ul>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
					<span class="btn_big fl"><input type="submit" value="등록하기" /></span>
					<span class="btn_big fl"><input type="button" value="등록취소" onclick="popupform_close()" /></span>

					<input type="hidden" name="sub_type" value="post" />
				</div>
			</div>

		</fieldset>
			<?=$form_all;?>
		</form>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
	$(".datepicker").datepicker();

// 에디터관련
	var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "workpost_remark",
		sSkinURI: "<?=$local_dir;?>/bizstory/editor/smarteditor/SmartEditor2Skin.html",
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){ }
		},
		fCreator: "createSEditor2"
	});

	var file_chk_num = <?=$file_chk_num;?>;
	file_multi_setting('file_fname', '<?=$file_multi_size;?>', 'work', '');

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#workpost_subject').val(); // 제목
		chk_title = $('#workpost_subject').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#workpost_charge_idx').val(); // 담당자
		chk_title = $('#workpost_charge_idx').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

	// 오늘날짜 이전은 안됨
		chk_value = $('#workpost_deadline_date').val();
		chk_value = chk_value.replace('-', '');
		chk_value = chk_value.replace('-', '');
		if (chk_value < <?=date('Ymd');?>)
		{
			chk_total = chk_total + '이전 날짜는 선택하실 수 없습니다.<br />';
			action_num++;
		}

		var start_date1 = $('#project_class_start_date').val();
		var start_date  = $('#project_class_start_date').val().replace(/-/g, '');

		var end_date1   = $('#project_class_end_date').val();
		var end_date    = $('#project_class_end_date').val().replace(/-/g, '');

		var chk_date    = $('#workpost_deadline_date').val().replace(/-/g, '');

		if (chk_date < start_date)
		{
			chk_total = chk_total + '기한은 시작일 ' + start_date1 + ' 보다 커야합니다.<br />';
			action_num++;
		}
		if (chk_date > end_date)
		{
			chk_total = chk_total + '기한은 종료일 ' + end_date1 + ' 보다 작아야합니다.<br />';
			action_num++;
		}

		oEditors.getById["workpost_remark"].exec("UPDATE_CONTENTS_FIELD", []);
		chk_value = $('#workpost_remark').val(); // 내용
		chk_title = $('#workpost_remark').attr('title');
		if (chk_value == '' || chk_value == '<br>')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				async : false,
				type: 'post', dataType: 'json', url: '<?=$local_dir;?>/bizstory/project/project_view_work_ok.php',
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						file_preview(msg.f_class, msg.f_idx); // 미리보기를 위해서 파일변환
						list_data();
						class_list_data('');
						popupform_close();
					}
					else
					{
						$("#loading").fadeOut('slow');
						check_auth_popup(msg.error_string);
					}
				},
				complete: function(){
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

// 업무종류선택
	function work_type_select()
	{
		var chk_value = $('#workpost_work_type').val();

		$("#workpost_apply_view").css({"display": "none"});
		$("#workpost_charge_view").css({"display": "none"});

		if (chk_value == 'WT03') // 승인일 경우
		{
			$("#workpost_apply_view").css({"display": "block"});
		}

		if (chk_value != 'WT01') // 본인제외
		{
			$("#workpost_charge_view").css({"display": "block"});
		}

		if (chk_value == 'WT01')
		{
			$("#workpost_charge_idx").val('<?=$code_mem;?>');
		}
		else // 요청, 알림, 승인일 경우
		{
			$("#workpost_charge_idx").val('');
		}

		charge_member_list(chk_value);
	}

// 담당자목록, 승인자목록
	function charge_member_list(work_type)
	{
		var apply_idx  = $("#workpost_apply_idx").val();
		var charge_idx = $("#workpost_charge_idx").val();
		var proc_idx   = $("#workpost_proc_idx").val();

		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/project/select_charge_member.php',
			data: {'work_type':work_type, 'apply_idx':apply_idx, 'charge_idx':charge_idx, 'proc_idx':proc_idx},
			success: function(msg) {
				$("#workpost_charge_view").html(msg);
			}
		});

		$.ajax({
			type: 'post', dataType: 'html', url: '<?=$local_dir;?>/bizstory/project/select_apply_member.php',
			data: {'work_type':work_type, 'apply_idx':apply_idx, 'charge_idx':charge_idx, 'proc_idx':proc_idx},
			success: function(msg) {
				$("#workpost_apply_view").html(msg);
			}
		});
	}

	charge_member_list('');
//]]>
</script>
<?
	}
?>
