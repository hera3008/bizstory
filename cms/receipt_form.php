<?
	$ri_idx = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = '';
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shclass=' . $send_shclass . '&amp;shstatus=' . $send_shstatus;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"    value="' . $send_swhere . '" />
		<input type="hidden" name="stext"     value="' . $send_stext . '" />
		<input type="hidden" name="shclass"   value="' . $send_shclass . '" />
		<input type="hidden" name="shstatus"  value="' . $send_shstatus . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1"   value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2"   value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$receipt_info = new receipt_info();
	$receipt_info->ri_idx = $ri_idx;
	$receipt_info->data_path = $comp_receipt_path;
	$receipt_info->data_dir = $comp_receipt_dir;

	$data      = $receipt_info->receipt_info_view();
	$file_list = $receipt_info->receipt_file();

	if ($data['writer'] == '') $data['writer'] = $client_user_name;
	if ($data['tel_num'] == '') $data['tel_num'] = $client_tel_num;
?>

<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>
			<input type="hidden" name="file_upload_num" id="file_upload_num" value="<?=$file_upload_num;?>" />

			<input type="hidden" name="comp_idx"    id="post_comp_idx"    value="<?=$client_comp;?>" />
			<input type="hidden" name="part_idx"    id="post_part_idx"    value="<?=$client_part;?>" />
			<input type="hidden" name="ci_idx"      id="post_ci_idx"      value="<?=$client_idx;?>" />
			<input type="hidden" name="client_code" id="post_client_code" value="<?=$client_code;?>" />

		<fieldset>
			<legend class="blind">접수정보 폼</legend>
			<table class="tinytable write" summary="접수정보를 등록/수정합니다.">
			<caption>접수정보</caption>
			<colgroup>
				<col width="80px" />
				<col width="300px" />
				<col width="80px" />
				<col />
			</colgroup>
			<tbody>
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
						<textarea name="param[remark]" id="post_remark" title="내용을 입력하세요." rows="20"><?=$data['remark'];?></textarea>
					</td>
				</tr>
				<tr>
					<th><label for="file_fname">파일</label></th>
					<td colspan="3">
						<div class="filewrap">
							<div style="padding-bottom:10px;">파일 업로드시 파일을 다중선택하시면 한번에 여러개 파일을 올릴수 있습니다.</div>
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
					<span class="btn_big fl"><input type="button" value="등록하기" onclick="check_form()" /></span>
					<span class="btn_big fl"><input type="button" value="등록취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

					<input type="hidden" name="sub_type" value="post" />
				</div>
			</div>

		</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
	part_information('<?=$client_part;?>', 'receipt_class', 'post_receipt_class', '', '');

// 에디터관련
	var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "post_remark",
		sSkinURI: "<?=$local_dir;?>/bizstory/editor/smarteditor/SmartEditor2Skin.html",
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){
			}
		},
		fCreator: "createSEditor2"
	});

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
		if (chk_msg == 'No' || chk_value == '-' || chk_value == '--')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_receipt_class').val(); // 접수분류
		chk_title = $('#post_receipt_class').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No' || chk_value == '-' || chk_value == '--')
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
						//location.href = '<?=$local_dir;?>/cms/receipt.php?<?=$f_default1;?>';
					}
					else check_auth_popup(msg.error_string);
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

	var file_chk_num = 1;
	file_multi_setting('file_fname', '<?=$file_multi_size;?>', 'receipt_comment', '');
//]]>
</script>