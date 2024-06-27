<?
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 거래처정보
	$client_where = " and ci.ci_idx = '" . $client_idx . "'";
	$client_data = client_info_data('view', $client_where);

	$client_code = $client_data['client_code'];
	$code_comp   = $client_data['comp_idx'];
	$code_part   = $client_data['part_idx'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'client_idx=' . $client_idx;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shclass=' . $send_shclass . '&amp;shstatus=' . $send_shstatus;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="client_idx"  value="' . $client_idx . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"    value="' . $send_swhere . '" />
		<input type="hidden" name="stext"     value="' . $send_stext . '" />
		<input type="hidden" name="shclass"   value="' . $send_shclass . '" />
		<input type="hidden" name="shstatus"  value="' . $send_shstatus . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$upload_file_num_max   = '2';
	$upload_file_size_max1 = 10 * 1024 * 1024;
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>
			<input type="hidden" name="param[macaddress]" id="post_macaddress" value="<?=$macaddress;?>" />

		<fieldset>
			<legend class="blind">접수정보 폼</legend>
			<table class="tinytable write" summary="접수정보를 등록/수정합니다.">
			<caption>접수정보</caption>
			<colgroup>
				<col width="80px" />
				<col />
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
					<th><label for="post_writer">작성자</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[writer]" id="post_writer" class="type_text" title="작성자를 입력하세요." size="20" value="<?=$data['writer'];?>" />
						</div>
					</td>
					<th><label for="post_tel_num">연락처</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[tel_num]" id="post_tel_num" class="type_text" title="전화번호를 입력하세요." size="20" value="<?=$data['tel_num'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_receipt_class">접수분류</label></th>
					<td colspan="3">
						<div class="left">
							<select name="param[receipt_class]" id="post_receipt_class" title="접수분류를 선택하세요">
								<option value="">접수분류를 선택하세요</option>
							</select>
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
						<div class="left textarea_span">
							<textarea name="param[remark]" id="post_remark" title="내용을 입력하세요.">
								<?=$data['remark'];?>
							</textarea>
						</div>
					</td>
				</tr>
		<?
			for ($i = 1; $i <= $upload_file_num_max; $i++)
			{
		?>
				<tr>
					<th><label for="file_fname<?=$i;?>">파일<?=$i;?></label></th>
					<td colspan="3">
		<?
				$file_where = " and rf.ri_idx = '" . $ri_idx . "' and rf.sort = '" . $i . "'";
				$file_data = receipt_file_data('view', $file_where);

				if ($file_data["img_sname"] != '')
				{
		?>
						<div class="left">
							<a href="javascript:void(0);" class="btn_sml fl" onclick="check_file_delete('<?=$file_data['rf_idx'];?>')"><span>삭제</span></a>
							<a href="<?=$local_dir;?>/agent/receipt_download.php?rf_idx=<?=$file_data['rf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?></a>
						</div>
		<?
				}
				else
				{
		?>
						<div class="left file" id="file_fname<?=$i;?>_view">
							<input type="file" name="file_fname<?=$i;?>" id="file_fname<?=$i;?>" class="type_text type_file type_multi" title="파일 선택하기" />
						</div>
						<div class="left file"><ul id="upload_fname<?=$i;?>"></ul></div>
		<?
				}
		?>
					</td>
				</tr>
		<?
			}
		?>
			</tbody>
			</table>
			<input type="hidden" id="upload_fnum" name="upload_fnum" value="<?=$upload_file_num_max;?>" />

			<div class="section">
				<div class="fr">
					<span class="btn_big fl"><input type="submit" value="등록하기" /></span>
					<span class="btn_big fl"><input type="button" value="등록취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

					<input type="hidden" name="sub_type" value="post" />
				</div>
			</div>

		</fieldset>
		</form>
		<form id="fileform" name="fileform" method="post" action="<?=$this_page;?>">
			<input type="hidden" id="file_sub_type" name="sub_type"    value="receipt_file_ok" />
			<input type="hidden" id="file_comp_idx" name="comp_idx"    value="<?=$code_comp;?>" />
			<input type="hidden" id="file_part_idx" name="part_idx"    value="<?=$code_part;?>" />
			<input type="hidden" id="file_idx"      name="idx"         value="" />
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
	part_information('<?=$code_part;?>', 'receipt_class', 'post_receipt_class', '', '');

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_writer').val(); // 작성자
		chk_title = $('#post_writer').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_tel_num').val(); // 연락처
		chk_title = $('#post_tel_num').attr('title');
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
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			chk_value = $('#post_writer').val(); // 작성자
			$.cookie('login_writer_save', chk_value, { expires: 7 });

			chk_value = $('#post_tel_num').val(); // 전화번호
			$.cookie('login_tel_num_save', chk_value, { expires: 7 });

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

//------------------------------------ Save Value
	$('#post_writer').val($.cookie('login_writer_save'));
	$('#post_tel_num').val($.cookie('login_tel_num_save'));
<?
	for ($i = 1; $i <= $upload_file_num_max; $i++) {
?>
	file_setting_id('file_fname<?=$i;?>', '<?=$tmp_dir;?>', '<?=$upload_file_size_max1;?>', 'upload_fname<?=$i;?>', 'receipt', '');
<?
	}
?>
//]]>
</script>