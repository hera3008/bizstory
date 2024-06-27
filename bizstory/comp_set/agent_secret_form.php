<?
/*
	생성 : 2012.07.03
	위치 : 설정관리 > 접수관리 > 에이전트관리 > 알림관리 - 등록/수정폼
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$abn_idx   = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
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
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if (($auth_menu['int'] == 'Y' && $sub_type == 'postform') || ($auth_menu['mod'] == 'Y' && $sub_type == 'modifyform')) // 등록, 수정권한
	{
		$form_chk = 'Y';
	}
	else
	{
?>
		<script type="text/javascript">
		//<![CDATA[
			check_auth('');
		//]]>
		</script>
<?
	}

	if ($form_chk == 'Y')
	{
		$where = " and abn.abn_idx = '" . $abn_idx . "'";
		$data = agent_bnotice_data('view', $where);

		if ($sub_type == "postform")
		{
			$data['import_type'] = '0';
			$data['client_all'] = 'Y';
		}

		$client_add_where = '';
		$client_idx_arr = explode(',', $data['ci_idx']);
		foreach($client_idx_arr as $client_k => $client_v)
		{
			if ($client_k > 0)
			{
				$client_add_where .= " and ci.ci_idx != '" . $client_v . "'";
			}
		}

		$client_where = " and ci.comp_idx = '" . $code_comp . "' and ci.part_idx = '" . $code_part . "' and ci.view_yn = 'Y'" . $client_add_where;
		$client_order = "ci.client_name asc";
		$client_list = client_info_data('list', $client_where, $client_order, '', '');
?>
<div class="info_text">
	<ul>
		<li>거래처그룹을 선택할 경우 '거래처전체'를 해제하세요.</li>
		<li>거래처를 다중선택시 Ctrl 누른상태에서 선택하세요.</li>
	</ul>
</div>

<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<input type="hidden" name="param[ci_idx]" id="post_ci_idx" value="" />

		<fieldset>
			<legend class="blind">알림 폼</legend>
			<table class="tinytable write" summary="알림 등록/수정합니다.">
			<caption>알림</caption>
			<colgroup>
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_subject">제목</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[subject]" id="post_subject" class="type_text" title="제목을 입력하세요." size="50" value="<?=$data['subject'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th>거래처그룹</th>
					<td>
						<div class="left">
							<select name="param[ccg_idx]" id="post_ccg_idx">
								<option value="">거래처그룹 선택</option>
					<?
						$sub_where = " and ccg.comp_idx = '" . $code_comp . "' and ccg.part_idx = '" . $code_part . "' and ccg.view_yn = 'Y'";
						$sub_list = company_client_group_data('list', $sub_where, '', '', '');

						foreach ($sub_list as $sub_k => $sub_data)
						{
							if (is_array($sub_data))
							{
								$emp_str = str_repeat('&nbsp;', ($sub_data['menu_depth']-1)*4);
					?>
								<option value="<?=$sub_data['ccg_idx'];?>"<?=selected($sub_data['ccg_idx'], $data['ccg_idx']);?>><?=$emp_str;?><?=$sub_data['group_name'];?></option>
					<?
							}
						}
					?>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<th rowspan="2">거래처설정</th>
					<td>
						<div class="left">
							<label for="post_client_all"><input type="checkbox" name="param[client_all]" id="post_client_all" value="Y" <?=checked($data['client_all'], 'Y');?> /> 거래처전체</label>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="left">
							<select name="left_ci_idx" id="post_left_ci_idx" style="width:250px;" size="15" multiple="multiple" title="업체를 선택하세요.">
				<?
					foreach($client_list as $client_k => $client_data)
					{
						if (is_array($client_data))
						{
				?>
								<option value="<?=$client_data['ci_idx'];?>"><?=$client_data['client_name'];?></option>
				<?
						}
					}
				?>
							</select>

							<a href="javascript:void(0);" onclick="right_move()" class="btn_con"><span> + </span></a>
							<a href="javascript:void(0);" onclick="left_move()" class="btn_con"><span> - </span></a>

							<select name="right_ci_idx" id="post_right_ci_idx" style="width:250px;" size="15" multiple="multiple" title="업체를 선택하세요.">
				<?
					foreach($client_idx_arr as $client_k => $client_v)
					{
						if ($client_k > 0)
						{
							$sub_where = " and ci.ci_idx = '" . $client_v . "'";
							$sub_data = client_info_data('view', $sub_where);
				?>
								<option value="<?=$sub_data['ci_idx'];?>"><?=$sub_data['client_name'];?></option>
				<?
						}
					}
				?>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<th>중요도</th>
					<td>
						<div class="left">
							<?=code_radio($set_agent_important, 'param[import_type]', 'post_import_type', $data['import_type']);?>
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
					<th><label for="f_fname1">파일1</label></th>
					<td>
			<?
				$file_where = " and abnf.abn_idx = '" . $abn_idx . "' and abnf.sort = '1'";
				$file_data = agent_bnotice_file_data('view', $file_where);
				if ($file_data['img_sname'] != '')
				{
					$fsize = $file_data['img_size'];
					$fsize = byte_replace($fsize);
			?>
						<div class="filewrap">
							<div class="file" id="file_fname1_view">
								<a href="<?=$local_diir;?>/bizstory/comp_set/agent_bnotice_download.php?abnf_idx=<?=$file_data['abnf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
								<a href="javascript:void(0);" class="btn_con" onclick="file_form_delete('<?=$file_data['abnf_idx'];?>', '1')"><span>삭제</span></a>
							</div>
						</div>
			<?
				}
			?>
						<div class="filewrap">
							<div class="file" id="f_fname1_view">
								<input type="file" name="f_fname1" id="f_fname1" class="type_text type_file type_multi" title="파일 선택하기" />
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="f_fname2">파일2</label></th>
					<td>
			<?
				$file_where = " and abnf.abn_idx = '" . $abn_idx . "' and abnf.sort = '2'";
				$file_data = agent_bnotice_file_data('view', $file_where);
				if ($file_data['img_sname'] != '')
				{
					$fsize = $file_data['img_size'];
					$fsize = byte_replace($fsize);
			?>
						<div class="filewrap">
							<div class="file" id="file_fname2_view">
								<a href="<?=$local_diir;?>/bizstory/comp_set/agent_bnotice_download.php?abnf_idx=<?=$file_data['abnf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
								<a href="javascript:void(0);" class="btn_con" onclick="file_form_delete('<?=$file_data['abnf_idx'];?>', '2')"><span>삭제</span></a>
							</div>
						</div>
			<?
				}
			?>
						<div class="filewrap">
							<div class="file" id="f_fname2_view">
								<input type="file" name="f_fname2" id="f_fname2" class="type_text type_file type_multi" title="파일 선택하기" />
							</div>
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
			<?
				if ($abn_idx == '') {
			?>
					<span class="btn_big_grreen"><input type="submit" value="등록" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

					<input type="hidden" name="sub_type" value="post" />
			<?
				} else {
			?>
					<span class="btn_big_blue"><input type="submit" value="수정" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

					<input type="hidden" name="sub_type" value="modify" />
					<input type="hidden" name="abn_idx"  value="<?=$abn_idx;?>" />
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

<script type="text/javascript" src="<?=$local_dir;?>/bizstory/editor/smarteditor/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/script_file.js" charset="utf-8"></script>
<script type="text/javascript">
//<![CDATA[
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

	file_setting('f_fname1', 'agent_bnotice', '1', '<?=$file_multi_size;?>', '');
	file_setting('f_fname2', 'agent_bnotice', '2', '<?=$file_multi_size;?>', '');

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_subject').val(); // 제목
		chk_title = $('#post_subject').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

	// 거래처선택
		var client_all = $("input:checkbox[name='param[client_all]']:checked").length; // 승인요청
		if (client_all == 0) // 업체전체가 아닐 경우
		{
			var total_num = 0;
			var total_comp = '';
			var comp_len = $('#post_right_ci_idx option').size();
			var comp_val;
			chk_title = $('#post_right_ci_idx').attr('title');

			for (var i = 0; i < comp_len; i++)
			{
				comp_val = $("#post_right_ci_idx option:eq(" + i + ")").val();

				total_comp = total_comp + ',' + comp_val;
				total_num = total_num + 1;
			}
			if (total_num == 0)
			{
				chk_total = chk_total + chk_title + '<br />';
				action_num++;
			}
			else
			{
				$('#post_ci_idx').val(total_comp);
			}
		}

		oEditors.getById["post_remark"].exec("UPDATE_CONTENTS_FIELD", []);
		chk_value = $('#post_remark').val(); // 내용
		chk_title = $('#post_remark').attr('title');
		if (chk_value == '' || chk_value == '<br>')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				beforeSubmit: function(){ $("#loading").fadeIn('slow').fadeOut('slow'); },
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$("#loading").fadeIn('slow').fadeOut('slow');
					<?
						$f_default1 = str_replace('&amp;', '&', $f_default);
					?>
						location.href = '?<?=$f_default1;?>';
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

//------------------------------------ 왼쪽 -> 오른쪽
	function right_move()
	{
		var total_num = 0;
		var comp_len = $('#post_left_ci_idx option').size();
		var comp_text, comp_val;

		for (var i = 0; i < comp_len; i++)
		{
			if($("#post_left_ci_idx option:eq(" + i + ")").attr("selected") == 'selected')
			{
				comp_text = $("#post_left_ci_idx option:eq(" + i + ")").text();
				comp_val  = $("#post_left_ci_idx option:eq(" + i + ")").val();

				$('#post_right_ci_idx').append('<option value="' + comp_val + '">' + comp_text + '</option>');
				$("#post_left_ci_idx option:eq(" + i + ")").remove();
				total_num = total_num + 1;
			}
		}
		if (total_num == 0)
		{
			check_auth_popup($('#post_left_ci_idx').attr('title'));
		}
	}

//------------------------------------ 오른쪽 -> 왼쪽
	function left_move()
	{
		var total_num = 0;
		var comp_len = $('#post_right_ci_idx option').size();
		var comp_text, comp_val;

		for (var i = 0; i < comp_len; i++)
		{
			if($("#post_right_ci_idx option:eq(" + i + ")").attr("selected") == 'selected')
			{
				comp_text = $("#post_right_ci_idx option:eq(" + i + ")").text();
				comp_val  = $("#post_right_ci_idx option:eq(" + i + ")").val();

				$('#post_leftt_ci_idx').append('<option value="' + comp_val + '">' + comp_text + '</option>');
				$("#post_right_ci_idx option:eq(" + i + ")").remove();
				total_num = total_num + 1;
			}
		}
		if (total_num == 0)
		{
			check_auth_popup($('#post_right_ci_idx').attr('title'));
		}
	}
//]]>
</script>
<?
	}
?>
