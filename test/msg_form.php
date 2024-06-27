<?
/*
	수정 : 2013.05.10
	위치 : 업무관리 > 나의 업무 > 쪽지 - 쪽지작성
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$mem_idx   = $idx2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $send_page_num;
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
		<input type="hidden" name="page_num"  value="' . $send_page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
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
		$file_upload_num = 0;
		$file_chk_num    = 1;

	// 지사별
		$part_where = " and part.comp_idx = '" . $code_comp . "' and part.view_yn = 'Y'";
		$part_list = company_part_data('list', $part_where, '', '', '');
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<input type="hidden" name="file_upload_num" id="file_upload_num" value="<?=$file_upload_num;?>" />
			<?=$form_all;?>

			<fieldset>
				<legend class="blind">쪽지작성 폼</legend>

				<table class="tinytable write" summary="쪽지작성을 등록합니다.">
					<caption>쪽지작성</caption>
					<colgroup>
						<col width="100px" />
						<col />
					</colgroup>
					<tbody>
						<tr>
							<th><label for="post_charge_idx">받는자</label></th>
							<td>
							<?
								$charge_view = form_charge_view('receive_idx[]', $mem_idx, $part_list, '', 'msg');
								echo $charge_view['change_view'];
							?>
							</td>
						</tr>
						<tr>
							<th><label for="post_remark">내용</label></th>
							<td>
								<textarea name="param[remark]" id="post_remark" title="내용을 입력하세요." rows="5" cols="50" class="none"><?=$data['remark'];?></textarea>
								<label for="post_send_save">
									<input type="checkbox" class="type_checkbox" title="보낸쪽지함에 저장(해제시 수신확인 불가)" value='Y' name="param[send_save]" id="post_send_save" checked="checked" />
									<span>보낸쪽지함에 저장(해제시 수신확인 불가)</span>
								</label>
							</td>
						</tr>
						<tr>
							<th><label for="file_fname">파일</label></th>
							<td colspan="3">
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
						<span class="btn_big_green"><input type="submit" value="등록" /></span>
						<span class="btn_big_green"><input type="button" value="취소" onclick="close_data_form(); msg_list_data('<?=$mem_idx;?>')" /></span>

						<input type="hidden" name="sub_type" value="post" />
					</div>
				</div>

			</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

	// 받는사람확인
		var mem_idx  = document.getElementsByName('receive_idx[]');
		var i = 0, j = 0;

		while(mem_idx[i])
		{
			if (mem_idx[i].type == 'checkbox' && mem_idx[i].disabled == false && mem_idx[i].checked == true)
			{
				j++;
			}
			i++;
		}
		if (j == 0)
		{
			chk_total = chk_total + '받는자를 선택하세요.<br />';
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

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: '<?=$local_dir;?>/bizstory/msg/msg_ok.php',
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						file_preview(msg.f_class, msg.f_idx); // 미리보기를 위해서 파일변환
						close_data_form();
						msg_list_data('<?=$mem_idx;?>');
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

	var file_chk_num = <?=$file_chk_num;?>;
	file_multi_setting('file_fname', '<?=$file_multi_size;?>', 'message', '');

<?
	echo $charge_view['change_script']
?>
//]]>
</script>
<?
	}
?>
