<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
	include $mobile_path . "/process/member_chk.php";
	include $mobile_path . "/header.php";

	$wi_idx = $idx;

	$work_info = new work_info();
	$work_info->wi_idx = $wi_idx;
	$work_info->data_path = $comp_work_path;
	$work_info->data_dir = $comp_work_dir;

	$data      = $work_info->work_info_view();
	$file_list = $work_info->work_file_list();
	$deadline_list = deadline_date();

	if ($sub_type == "postform")
	{
		$data['work_type'] = 'WT01';
		$data['important'] = 'WI01';
		$data['open_yn']   = 'Y';

		$class_where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "' and code.default_yn = 'Y'";
		$class_data = code_work_class_data('view', $class_where);

		$data['work_class']     = $class_data['code_value'];
		$data['work_class_str'] = $class_data['code_name'];
	}

// 기한 - 덧붙이기
	$data['deadline_str'] = $set_work_deadline_txt[$data['deadline_str']];

// 업무종류가 '본인'일 경우
	if ($data['work_type'] == 'WT01') $charge_view_class = 'class="none"';
	else $charge_view_class = '';

// 업무종류가 '승인'일 경우
	if ($data['work_type'] == 'WT03') $apply_view_class = '';
	else $apply_view_class = 'class="none"';

// 업무파일
	$file_query = "select max(sort) as sort from work_file where wi_idx = '" . $wi_idx . "'";
	$file_chk = query_view($file_query);
	$file_sort = ($file_chk['sort'] == '') ? '0' : $file_chk['sort'];

	$file_upload_num = $file_chk['sort'];
	$file_chk_num    = $file_upload_num + 1;
?>
<div id="bbs_view" class="homebox full sub view">

	<div class="toolbar han">
		<?=$btn_back;?>
		<h1>
			<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/work_my_view.php?wi_idx=<?=$wi_idx;?>'"><?=$bbs_title;?></a>
		</h1>
		<?=$btn_menu;?>
	</div>




<div class="info_text">
	<ul>
		<li>업무종류가 [요청], [알림]일 경우에는 본인선택이 불가능합니다.</li>
		<li>업무종류가 [승인]일 경우 승인자로 지정된 사람은 업무 담당에서 제외됩니다.</li>
		<li>업무종류가 [요청], [승인]일 경우 업무종류변경이 안됩니다.</li>
		<li>업무등록시 승인자를 지정하게 되면 수정이 안됩니다.</li>
		<li>파일 업로드시 파일선택을 "쉬프트키"와 함께 다중선택하시면 한번에 여러개 파일을 올릴수 있습니다.</li>
	</ul>
</div>

<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<input type="hidden" name="file_upload_num" id="file_upload_num" value="<?=$file_upload_num;?>" />
			<input type="hidden" name="old_work_type"     id="old_work_type"     value="<?=$data['work_type'];?>" />
			<input type="hidden" name="old_deadline_date" id="old_deadline_date" value="<?=$data['deadline_date'];?>" />
			<input type="hidden" name="old_deadline_str"  id="old_deadline_str"  value="<?=$data['deadline_str'];?>" />
			<input type="hidden" name="old_charge_idx"    id="old_charge_idx"    value="<?=$data['charge_idx'];?>" />

		<fieldset>
			<legend class="blind">업무정보 폼</legend>
			<table class="tinytable write" summary="업무정보를 등록/수정합니다.">
			<caption>업무정보</caption>
			<colgroup>
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_work_type">업무종류</label></th>
					<td>
						<div class="left">
							<input type="hidden" name="param[charge_idx]" id="post_charge_idx" value="<?=$data['charge_idx'];?>" title="담당자를 선택하세요." />
							<input type="hidden" name="param[apply_idx]" id="post_apply_idx" value="<?=$data['apply_idx'];?>" title="승인자를 선택하세요." />
							<ul>
								<li>
						<?
							if ($sub_type == 'postform')
							{
								$disabled = '';
							}
							else
							{
								if ($data['work_type'] == 'WT02' || $data['work_type'] == 'WT03')
								{
									$disabled = ' disabled="disabled"';
								}
								else
								{
									$disabled = '';
								}
							}
						?>
									<select name="param[work_type]" id="post_work_type" title="업무종류를 선택하세요." onchange="work_type_select();"<?=$disabled;?>>
										<option value="">:: 업무종류선택 ::</option>
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
									<span id="apply_view" <?=$apply_view_class;?>></span>
								</li>
							</ul>
							<div id="charge_view" <?=$charge_view_class;?>></div>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_subject">업무제목</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[subject]" id="post_subject" class="type_text" title="업무제목을 입력하세요." size="50" value="<?=$data['subject'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th>공개여부</th>
					<td>
						<div class="left">
							<?=code_radio($set_work_open, 'param[open_yn]', 'post_open_yn', $data['open_yn']);?>
							<div class="field_help">* 업무뿐만 아니라 관련 첨부자료와 코멘트의 상태도 공개여부에 따라 전환됩니다.</div>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_deadline_date1">기한</label></th>
					<td>
						<div class="left">
							<ul>
				<?
					if ($data['deadline_date'] == '')
					{
				?>
								<li>
									<select name="deadline_date1" id="post_deadline_date1" onchange="deadline_date_view(this.value, 'deadline_date_view')">
					<?
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
										<input type="text" name="deadline_date2" id="post_deadline_date2" class="type_text datepicker" title="기한을 입력하세요." size="10" value="<?=date('Y-m-d');?>" />
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
				<?
					}
					else
					{
				?>
								<li>
									<input type="text" name="deadline_date1" id="post_deadline_date1" class="type_text datepicker" title="기한을 입력하세요." size="10" value="<?=date_replace($data['deadline_date'], 'Y-m-d');?>" />
								</li>
								<li>
									<input type="text" name="deadline_str1" id="post_deadline_str1" class="type_text" title="직접입력하세요." size="20" value="<?=$data['deadline_str'];?>" />
								</li>
				<?
					}
				?>
							</ul>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_work_class">분류</label></th>
					<td class="field">
						<div class="left">
							<input type="hidden" name="param[work_class]" id="post_work_class" value="<?=$data['work_class'];?>" />
							<div id="work_class_view_btn">
				<?
					if ($data['work_class'] == '')
					{
						$class_str = ' class="none"';
				?>
								<span>미지정</span>
								<a href="javascript:void(0);" onclick="popup_work_class('post_work_class', 'work_class_view')">선택하기</a>
				<?
					}
					else
					{
				?>
								<a href="javascript:void(0);" onclick="popup_work_class('post_work_class', 'work_class_view')">수정하기</a>
				<?
					}
				?>
							</div>
							<div class="field_help" id="work_class_view_select">
								<?=$data['work_class_str'];?>
							</div>
							<div id="work_class_view"></div>
						</div>
					</td>
				</tr>
				<tr>
					<th>중요도</th>
					<td>
						<div class="left">
							<?=code_radio($set_work_important, 'param[important]', 'post_important', $data['important']);?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_remark">내용</label></th>
					<td>
						<div class="left textarea_span">
							<textarea name="param[remark]" id="post_remark" title="내용을 입력하세요." rows="5" cols="50" class="none"><?=$data['remark'];?></textarea>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="file_fname">파일</label></th>
					<td colspan="3">
						<div class="filewrap">
							<input type="file" name="file_fname" id="file_fname" class="type_text type_file type_multi" title="파일 선택하기" />
							<div class="file">
								<ul id="file_fname_view">
			<?
				foreach ($file_list as $file_k => $file_data)
				{
					if (is_array($file_data))
					{
						$file_chk = $file_data['sort'];
						$fsize = $file_data['img_size'];
						$fsize = byte_replace($fsize);
			?>
									<li id="file_fname_<?=$file_chk;?>_liview" class="org_file">
										<a href="<?=$local_diir;?>/bizstory/work/work_download.php?wf_idx=<?=$file_data['wf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
										<a href="javascript:void(0);" class="btn_con" onclick="file_multi_form_delete('<?=$file_data['wf_idx'];?>', '<?=$file_chk;?>')"><span>삭제</span></a>
									</li>
			<?
					}
				}
			?>
								</ul>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
			<?
				if ($wi_idx == '') {
			?>
					<span class="btn_big fl"><input type="submit" value="등록하기" /></span>
					<span class="btn_big fl"><input type="button" value="등록취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

					<input type="hidden" name="sub_type" value="post" />
			<?
				} else {
			?>
					<span class="btn_big fl"><input type="submit" value="수정하기" /></span>
					<span class="btn_big fl"><input type="button" value="수정취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

					<input type="hidden" name="sub_type"   value="modify" />
					<input type="hidden" name="wi_idx"     value="<?=$wi_idx;?>" />
					<input type="hidden" name="old_status" value="<?=$data['work_status'];?>" />
			<?
				}
			?>
				</div>
			</div>

		</fieldset>
			<?=$form_all;?>
		</form>
	</div>
</div>
	<!-- //Contents -->
	<?
		$bottom_btn = '
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/index.php\'" class="icon4"><span>홈</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/work_my_list.php\'" class="icon2"><span>나의업무</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/work_my_form.php?list_type=list_no\'" class="icon2"><span>업무등록</span></a>
			<a href="javascript:void(0)" onclick="login_out();" class="icon1"><span class="leave_type">로그아웃</span></a>
		';
	?>
	<? include $mobile_path . "/footer.php"; ?>
</div>

<script type="text/javascript">
//<![CDATA[
	$(".datepicker").datepicker();

// 에디터관련
	var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "post_remark",
		sSkinURI: "<?=$local_dir;?>/bizstory/editor/smarteditor/SmartEditor2Skin.html",
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){ }
		},
		fCreator: "createSEditor2"
	});

	var file_chk_num = <?=$file_chk_num;?>;
	file_multi_setting('file_fname', '<?=$file_multi_size;?>', 'work', '');

// 업무종류선택
	function work_type_select()
	{
		var chk_value = $('#post_work_type').val();

		$("#apply_view").css({"display": "none"});
		$("#charge_view").css({"display": "none"});

		if (chk_value == 'WT03') // 승인일 경우
		{
			$("#apply_view").css({"display": "block"});
		}

		if (chk_value != 'WT01') // 본인제외
		{
			$("#charge_view").css({"display": "block"});
		}
<?
	if ($sub_type == 'postform') // 등록일 경우
	{
?>
		if (chk_value == 'WT01')
		{
			$("#post_charge_idx").val('<?=$data['charge_idx'];?>');
		}
		else // 요청, 알림, 승인일 경우
		{
			$("#post_charge_idx").val('');
		}
<?
	}
?>
		charge_member_list(chk_value, '<?=$data['wi_idx'];?>');
	}

// 담당자목록, 승인자목록
	function charge_member_list(work_type, wi_idx)
	{
		var apply_idx     = $("#post_apply_idx").val();
		var charge_idx    = $("#post_charge_idx").val();
		var old_work_type = $("#old_work_type").val();

		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/include/select_charge_member.php',
			data: {'old_work_type':old_work_type, 'work_type':work_type, 'charge_idx':charge_idx, 'apply_idx':apply_idx, 'wi_idx':wi_idx},
			beforeSubmit: function(){ $("#loading").fadeIn('slow').fadeOut('slow'); },
			success: function(msg) {
				$("#charge_view").html(msg);
			}
		});

		$.ajax({
			type: 'post', dataType: 'html', url: '<?=$local_dir;?>/bizstory/include/select_apply_member.php',
			data: {'old_work_type':old_work_type, 'work_type':work_type, 'charge_idx':charge_idx, 'apply_idx':apply_idx, 'wi_idx':wi_idx},
			beforeSubmit: function(){ $("#loading").fadeIn('slow').fadeOut('slow'); },
			success: function(msg) {
				$("#apply_view").html(msg);
			}
		});
	}

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_work_type').val(); // 업무종류
		chk_title = $('#post_work_type').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_subject').val(); // 제목
		chk_title = $('#post_subject').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		oEditors.getById["post_remark"].exec("UPDATE_CONTENTS_FIELD", []);
		chk_value = $('#post_remark').val(); // 내용
		chk_title = $('#post_remark').attr('title');
		if (chk_value == '' || chk_value == '<br>')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		var work_type = $("#post_work_type").val();
		if (work_type == 'WT03') // 승인일 경우
		{
			chk_value = $('#post_apply_idx').val(); // 승인자
			chk_title = $('#post_apply_idx').attr('title');
			chk_msg = check_input_value(chk_value);
			if (chk_msg == 'No')
			{
				chk_total = chk_total + chk_title + '<br />';
				action_num++;
			}
		}
		else if (work_type == 'WT02') // 요청일 경우
		{
			chk_value = $('#post_charge_idx').val(); // 담당자
			chk_title = '본인만 선택이 되었습니다. 업무요청일 경우는 가능하지 않습니다.';
<?
	if ($sub_type == 'postform')
	{
		$mem_str = $_SESSION[$sess_str . '_mem_idx'];
	}
	else
	{
		$mem_str = $data['reg_id'];
	}
?>
			if (chk_value == '<?=$mem_str;?>')
			{
				chk_total = chk_total + chk_title + '<br />';
				action_num++;
			}
		}

	// 오늘날짜 이전은 안됨
<?
	if ($sub_type == 'postform')
	{
?>
		chk_value = $('#post_deadline_date2').val();
		chk_value = chk_value.replace('-', '');
		chk_value = chk_value.replace('-', '');
		if (chk_value < <?=date('Ymd');?>)
		{
			chk_total = chk_total + '이전 날짜는 선택하실 수 없습니다.<br />';
			action_num++;
		}
<?
	}
?>
		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				async : false,
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						file_preview(msg.f_class, msg.f_idx); // 미리보기를 위해서 파일변환
						$("#work_form_view").html('');
						list_data();
					<?
						$f_default1 = str_replace('&amp;', '&', $f_default);
					?>
						//location.href = '?<?=$f_default1;?>';
					}
					else check_auth_popup(msg.error_string);
				},
				complete: function(){
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

	charge_member_list('<?=$data['work_type'];?>', '<?=$data['wi_idx'];?>');
//]]>
</script>
</body>
</html>
