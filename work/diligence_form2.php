<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$di_idx    = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2;
	$f_all     = $f_page;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if (($auth_menu['int'] == 'Y' && $di_idx == '') || ($auth_menu['mod'] == 'Y' && $di_idx != '')) // 등록, 수정권한
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
		$where = " and ds.comp_idx = '" . $code_comp . "'and ds.part_idx = '" . $code_part . "'";
		$set_dili = diligence_set_data('view', $where);

		$where = " and di.di_idx = '" . $di_idx . "'";
		$data = diligence_info_data('view', $where);
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form2()">
			<?=$form_all;?>
	<?
		$chk_date = substr($sdate, 0, 4) . '-' . substr($sdate, 4, 2) . '-' . substr($sdate, 6, 2);

		if ($data['dili_sdate'] == '') $data['dili_sdate'] = $chk_date;
		if ($data['dili_edate'] == '') $data['dili_edate'] = $chk_date;
	?>
			<h3>상태</h3>
			<fieldset>
				<legend class="blind">상태설정</legend>
				<table class="tinytable write" summary="상태설정을 수정합니다.">
				<caption>상태설정</caption>
				<colgroup>
					<col width="100px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th>상태설정</th>
						<td>
							<div class="left">
								<select name="param[dili_status]" id="post_dili_status" title="상태를 선택하세요">
									<option value="">상태를 선택하세요</option>
								</select>
								<input type="text" name="param[start_sdate]" id="post_start_sdate" class="type_text datepicker" title="시작일을 입력하세요." size="10" value="<?=$data['start_sdate'];?>" />
								~
								<input type="text" name="param[end_edate]" id="post_end_edate" class="type_text datepicker" title="종료일을 입력하세요." size="10" value="<?=$data['end_edate'];?>" />
								<!--//
								<input type="text" name="param[dili_hour]" id="post_dili_hour" class="type_text" title="상태의 시를 입력하세요." size="5" value="<?=$data['dili_hour'];?>" /> 시
								<input type="text" name="param[dili_minute]" id="post_dili_minute" class="type_text" title="상태의 분을 입력하세요." size="5" value="<?=$data['dili_minute'];?>" /> 분
								<label for="post_night_yn"><input type="checkbox" name="param[night_yn]" id="post_night_yn" value="Y" <?=checked($data['night_yn'], 'Y');?> class="type_checkbox" onclick="night_check()" /> 철야</label>
								//-->
							</div>
						</td>
					</tr>
					<tr>
						<th>코멘트</th>
						<td>
							<div class="left">
								<textarea name="param[remark]" id="post_remark" title="코멘트를 입력하세요."></textarea>
							</div>
						</td>
					</tr>
				</tbody>
				</table>
			</fieldset>

			<div class="section">
				<div class="fr">
			<?
				if ($di_idx == '') {
			?>
					<span class="btn_big_violet"><input type="submit" value="확인" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="popupform_close()" /></span>

					<input type="hidden" name="sub_type" value="post" />
					<input type="hidden" name="param[mem_idx]" id="post_mem_idx" value="<?=$mem_idx;?>" />
			<?
				} else {
			?>
					<span class="btn_big_blue"><input type="submit" value="수정" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="popupform_close()" /></span>

					<input type="hidden" name="sub_type" value="modify" />
					<input type="hidden" name="di_idx"   value="<?=$di_idx;?>" />
			<?
				}
			?>
				</div>
			</div>

		</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript" src="<?=$local_dir;?>/bizstory/editor/tinymce/jquery.tinymce.js"></script>
<script type="text/javascript">
//<![CDATA[
	$( ".datepicker" ).datepicker( {
		showOn: "button",
		buttonImage: "<?=$local_dir;?>/bizstory/images/btn/calendar.jpg",
		dateFormat:"yy-mm-dd",
		buttonImageOnly: true,
	});

	part_information('<?=$code_part;?>', 'dili_status2', 'post_dili_status', '<?=$data['dili_status'];?>', '');

	$('#post_remark').tinymce({
		script_url : '<?=$local_dir;?>/bizstory/editor/tinymce/tiny_mce.js',
		theme : "advanced", skin : "default",
		width : "100%", height : "60",
		plugins : "autolink,emotions,inlinepopups",

		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,sub,sup,|,forecolor,backcolor,|,charmap,emotions",
		theme_advanced_buttons2 : "", theme_advanced_buttons3 : "", theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top", theme_advanced_toolbar_align : "left",
		theme_advanced_resizing : true
	});

//------------------------------------ 출근, 퇴근확인
	function check_form2()
	{
		$("#popup_notice_view").hide();

		$.ajax({
			type    : "post", dataType : "json", url : link_ok,
			data    : $('#postform').serialize(),
			success : function(msg) {
				if (msg.success_chk == "Y")
				{
					popupform_close();
					list_data();
				}
				else check_auth_popup(msg.error_string);
			}
		});

		return false;
	}
//]]>
</script>
<?
	}
?>
