<?
/*
	생성 : 2012.07.03
	수정 : 2012.10.24
	위치 : 설정관리 > 접수관리 > 에이전트관리 > 알림관리 > 알림게시판 - 등록/수정폼
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$abn_idx   = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;sbclass=' . $send_sbclass;
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
		<input type="hidden" name="sbclass" value="' . $send_sbclass . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y' && $abn_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $abn_idx != '') // 수정권한
	{
		$form_chk   = 'Y';
		$form_title = '수정';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
			//]]>
			</script>';
		exit;
	}

	if ($form_chk == 'Y')
	{
		
		$where = " and abn.abn_idx = '" . $abn_idx . "'";
		$data = agent_bnotice_data('view', $where);

		if ($abn_idx == "")
		{
			$data['important']   = 'BNI01';
			$data['client_type'] = '2';
		}

		if ($data['part_idx'] == '' || $data['part_idx'] == '0') $data['part_idx'] = $code_part;
		if ($data['part_name'] == '')
		{
			$sub_where = " and part.part_idx = '" . $code_part . "'";
			$sub_data = company_part_data('view', $sub_where);

			$data['part_name'] = $sub_data['part_name'];
		}

	// 구분 - 거래처그룹
		if ($data['client_type'] == '1') $client_group_class = '';
		else $client_group_class = ' none';

	// 구분 - 거래처개별
		if ($data['client_type'] == '3') $client_info_class = '';
		else $client_info_class = ' none';

	// 거래처개별일 경우
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

	// 파일목록
		$file_where = " and abnf.abn_idx = '" . $abn_idx . "'";
		$file_list = agent_bnotice_file_data('list', $file_where, '', '', '');

	// 파일
		$file_query = "select max(sort) as sort from agent_bnotice_file where abn_idx = '" . $abn_idx . "'";
		$file_chk = query_view($file_query);
		$file_sort = ($file_chk['sort'] == '') ? '0' : $file_chk['sort'];

		$file_upload_num = $file_chk['sort'] ? $file_chk['sort'] : 0;
		$file_chk_num    = $file_upload_num;
		
?>

<div class="ajax_write">
	<div class="info_text">
		<ul>
			<li>거래처 다중선택시 Ctrl 누른상태에서 선택하세요.</li>
		</ul>
	</div>

	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<input type="hidden" name="file_upload_num" id="file_upload_num" value="<?=$file_upload_num;?>" />
			<input type="hidden" name="param[ci_idx]"   id="post_ci_idx"     value="" />
			<input type="hidden" name="param[part_idx]" id="post_part_idx"   value="<?=$code_part;?>" />
			<?=$form_all;?>

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
						<th><label for="post_bnotice_class">분류</label></th>
						<td>
							<div class="left">
								<select name="param[bnotice_class]" id="post_bnotice_class" title="분류를 선택하세요">
									<option value="">분류를 선택하세요</option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<th>중요도</th>
						<td>
							<div class="left">
								<?=code_radio($set_bnotice_important, 'param[important]', 'post_important', $data['important']);?>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_subject">제목</label></th>
						<td>
							<div class="left">
								<input type="text" name="param[subject]" id="post_subject" class="type_text" title="제목을 입력하세요." size="50" value="<?=$data['subject'];?>" />
							</div>
						</td>
					</tr>
					<tr>
						<th>구분</th>
						<td>
							<div class="left">
								<?=code_radio($set_client_type, 'param[client_type]', 'post_client_type', $data['client_type'], '', ' onclick="check_client_type(this.value)"');?>
							</div>
						</td>
					</tr>
					<tr>
						<th>거래처설정</th>
						<td>
							<div class="left<?=$client_group_class;?>" id="client_group_list">
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

							<div class="left<?=$client_info_class;?>" id="client_info_list">
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
										<li id="file_fname<?=$file_chk;?>_view" class="org_file">
											<a href="<?=$local_diir;?>/bizstory/comp_set/agent_bnotice_download.php?abnf_idx=<?=$file_data['abnf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
											<a href="javascript:void(0);" class="btn_con" onclick="file_form_delete('<?=$file_data['abnf_idx'];?>', '<?=$file_chk;?>')"><span>삭제</span></a>
										</li>
				<?
						}
					}
					unset($file_list);
					unset($file_data);
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
					if ($abn_idx == '') {
				?>
						<span class="btn_big_green"><input type="submit" value="등록" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="close_data_form()" /></span>

						<input type="hidden" name="sub_type" value="post" />
				<?
					} else {
				?>
						<span class="btn_big_blue"><input type="submit" value="수정" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="close_data_form()" /></span>

						<input type="hidden" name="sub_type" value="modify" />
						<input type="hidden" name="abn_idx"  value="<?=$abn_idx;?>" />
				<?
					}
				?>
					</div>
				</div>

			</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
	var file_chk_num = <?=$file_chk_num;?>;
	//file_multi_setting('file_fname', '<?=$file_multi_size;?>', 'agent_bnotice', '');
	file_setting('file_fname', 'agent_bnotice', '', '<?=$file_multi_size;?>', '');

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

	part_information('<?=$data['part_idx'];?>', 'bnotice_class', 'post_bnotice_class', '<?=$data['bnotice_class'];?>', '');

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		chk_value = $('#post_subject').val(); // 제목
		chk_title = $('#post_subject').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

	// 거래처선택
		var client_type = $("input:radio[name='param[client_type]']:checked").val(); // 거래처개별일 경우
		if (client_type == 3)
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
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						file_preview(msg.f_class, msg.f_idx); // 미리보기를 위해서 파일변환
						$("#data_view").html('');
						list_data();
					}
					else
					{
						$("#loading").fadeOut('slow');
						$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
						check_auth_popup(msg.error_string);
					}
				},
				complete: function(){
					$("#loading").fadeOut('slow');
					$("#backgroundPopup").fadeOut("slow");
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

//------------------------------------ 구분에 의한 선택
	function check_client_type(str)
	{
		$("#client_group_list").css({"display": "none"});
		$("#client_info_list").css({"display": "none"});

		if (str == '1') // 거래처그룹
		{
			$("#client_group_list").css({"display": "block"});
		}
		if (str == '3') // 거래처개별
		{
			$("#client_info_list").css({"display": "block"});
		}
	}
//]]>
</script>
<?
	}
?>
