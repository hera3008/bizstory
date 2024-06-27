<?
/*
	생성 : 2012.10.12
	수정 : 2012.10.12
	위치 : 상담게시판 - 등록/수정
*/
	$cons_idx  = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = '';
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="comp_idx"    value="' . $client_comp . '" />
		<input type="hidden" name="part_idx"    value="' . $client_part . '" />
		<input type="hidden" name="client_idx"  value="' . $client_idx . '" />
		<input type="hidden" name="client_code" value="' . $client_code . '" />
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

	$where = " and cons.cons_idx = '" . $cons_idx . "'";
	$data = consult_info_data('view', $where);

	if ($data['important'] == '') $data['important'] = 'CI01';
	if ($data['part_idx'] == '' || $data['part_idx'] == '0') $data['part_idx'] = $client_part;

// 파일
	$file_query = "select max(sort) as sort from consult_file where cons_idx = '" . $cons_idx . "'";
	$file_chk = query_view($file_query);
	$file_sort = ($file_chk['sort'] == '') ? '0' : $file_chk['sort'];

	$file_upload_num = $file_chk['sort'];
	$file_chk_num    = $file_upload_num + 1;
?>
<div class="info_text">
	<ul>
		<li>파일 업로드시 파일선택을 "쉬프트키"와 함께 다중선택하시면 한번에 여러개 파일을 올릴수 있습니다.</li>
	</ul>
</div>

<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>
			<input type="hidden" name="file_upload_num"   id="file_upload_num" value="<?=$file_upload_num;?>" />
			<input type="hidden" name="param[macaddress]" id="post_macaddress" value="<?=$macaddress;?>" />

		<fieldset>
			<legend class="blind">상담정보 폼</legend>
			<table class="tinytable write" summary="상담정보를 등록/수정합니다.">
			<caption>상담정보</caption>
			<colgroup>
				<col width="80px" />
				<col width="300px" />
				<col width="80px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_client_code">거래처명</label></th>
					<td colspan="3">
						<div class="left">
							<strong><?=$client_data['client_name'];?></strong>
							<input type="hidden" name="param[ci_idx]" id="post_ci_idx" value="<?=$client_idx;?>" />
							<input type="hidden" name="param[client_code]" id="post_client_code" value="<?=$client_code;?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_consult_class">분류</label></th>
					<td>
						<div class="left">
							<select name="param[consult_class]" id="post_consult_class" title="분류를 선택하세요">
								<option value="">분류를 선택하세요</option>
							</select>
						</div>
					</td>
					<th>중요도</th>
					<td>
						<div class="left">
							<?=code_radio($set_consult_important, 'param[important]', 'post_important', $data['important']);?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_writer">작성자</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[writer]" id="post_writer" class="type_text" title="작성자를 입력하세요." size="30" value="<?=$data['writer'];?>" />
						</div>
					</td>
					<th><label for="post_tel_num">연락처</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[tel_num]" id="post_tel_num" class="type_text" title="연락처 입력하세요." size="15" value="<?=$data['tel_num'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th>담당자</th>
					<td colspan="3">
						<div class="left">
							<input type="hidden" name="param[charge_idx]" id="post_charge_idx" value="<?=$data['charge_idx'];?>" title="담당자를 선택하세요." />
				<?
				// 지사별 직원
					$sub_where = " and mem.comp_idx = '" . $client_comp . "' and mem.part_idx = '" . $client_part . "' and mem.auth_yn = 'Y' and mem.login_yn = 'Y'";
					$sub_order = "mem.mem_name asc";
					$mem_list = member_info_data('list', $sub_where, $sub_order, '', '');
					if ($mem_list['total_num'] > 0)
					{
				?>
							<ul>
				<?
						foreach ($mem_list as $mem_k => $mem_data)
						{
							if (is_array($mem_data))
							{
							// 해당메뉴를 사용하는지 확인
								$menu_where = " and mam.mem_idx = '" . $mem_data['mem_idx'] . "' and mam.yn_list = 'Y' and mi.mode_folder = 'consult' and mi.mode_file = 'my_consult'";
								$menu_page = menu_auth_member_data('page', $menu_where);
								if ($menu_page['total_num'] > 0)
								{
				?>
								<li class="mem_name">
									<label for="<?=$checkbox_str;?>">
										<input type="checkbox" name="check_member_idx[]" id="<?=$checkbox_str;?>" value="<?=$mem_data['mem_idx'];?>" class="type_checkbox" title="<?=$mem_data['mem_name'];?>" />
										<?=$mem_data['mem_name'];?>
									</label>
								</li>
				<?
								}
								unset($menu_page);
							}
						}
				?>
							</ul>
				<?
					}
					unset($mem_data);
					unset($mem_list);
				?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_subject">제목</label></th>
					<td colspan="3">
						<div class="left">
							<input type="text" name="param[subject]" id="post_subject" class="type_text" title="제목을 입력하세요." size="50" value="<?=$data['subject'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_remark">내용</label></th>
					<td colspan="3">
						<textarea name="param[remark]" id="post_remark" title="내용을 입력하세요."><?=$data['remark'];?></textarea>
					</td>
				</tr>
				<tr>
					<th><label for="file_fname">파일</label></th>
					<td colspan="3">
						<div class="filewrap">
							<input type="file" name="file_fname" id="file_fname" class="type_text type_file type_multi" title="파일 선택하기" />
							<div class="file">
								<ul id="file_fname_view">
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
				if ($cons_idx == '') {
			?>
					<span class="btn_big_green"><input type="button" value="등록" onclick="check_form()" /></span>
					<span class="btn_big_green"><input type="button" value="취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

					<input type="hidden" name="sub_type" value="post" />
			<?
				} else {
			?>
					<span class="btn_big_green"><input type="submit" value="수정" /></span>
					<span class="btn_big_green"><input type="button" value="취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

					<input type="hidden" name="sub_type" value="modify" />
					<input type="hidden" name="cons_idx" value="<?=$cons_idx;?>" />
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
	file_multi_setting('file_fname', '<?=$file_multi_size;?>', 'consult', '');

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

	part_information('<?=$data['part_idx'];?>', 'consult_class', 'post_consult_class', '<?=$data['consult_class'];?>', '');

//------------------------------------ Save Value
	$('#post_writer').val($.cookie('consult_writer_save'));
	$('#post_tel_num').val($.cookie('consult_tel_num_save'));

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		var file_chk = $('#upload_select_num').val();
		if (file_chk > 0)
		{
			chk_total = chk_total + '먼저 선택한 파일을 올리세요.<br />';
			action_num++;
		}

		chk_value = $('#post_part_idx').val(); // 지사
		chk_title = $('#post_part_idx').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_ci_idx').val(); // 거래처
		chk_title = $('#post_ci_idx').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_consult_class').val(); // 분류
		chk_title = $('#post_consult_class').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

	// 담당자
		var mem_idx  = document.getElementsByName('check_member_idx[]');
		var i = 0, j = 0;
		var total_member_idx = ''

		while(mem_idx[i])
		{
			if (mem_idx[i].type == 'checkbox' && mem_idx[i].disabled == false && mem_idx[i].checked == true)
			{
				if (j == 0)
				{
					total_member_idx  = mem_idx[i].value;
				}
				else
				{
					total_member_idx  += ',' + mem_idx[i].value;
				}
				j++;
			}
			i++;
		}
		if (total_member_idx == '')
		{
			chk_total = chk_total + '담당자를 선택하세요.<br />';
			action_num++;
		}
		$('#post_charge_idx').val(total_member_idx);

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
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			chk_value = $('#post_writer').val(); // 작성자
			$.cookie('consult_writer_save', chk_value, { expires: 7 });

			chk_value = $('#post_tel_num').val(); // 전화번호
			$.cookie('consult_tel_num_save', chk_value, { expires: 7 });

			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
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
//]]>
</script>