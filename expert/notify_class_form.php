<?
/*
	생성 : 2013.01.17
	수정 : 2013.01.17
	위치 : 전문가코너 > 코드설정 > 알림분류 - 등록/수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_idx = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default;
	$f_page    = $f_search;
	$f_all     = $f_page;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if (($auth_menu['int'] == 'Y' && $code_idx == '') || ($auth_menu['mod'] == 'Y' && $code_idx != '')) // 등록권한
	{
		$form_chk = 'Y';
	}
	else
	{
?>
		<script type="text/javascript">
		//<![CDATA[
			check_auth_popup('');
		//]]>
		</script>
<?
	}

	if ($form_chk == 'Y')
	{
		$where = " and code.code_idx = '" . $code_idx . "'";
		$data = expert_code_notify_class_data("view", $where);

		if ($data["code_bold"] == '') $data["code_bold"] = 'N';
		if ($data["view_yn"] == '') $data["view_yn"] = 'Y';
		if ($data["default_yn"] == '') $data["default_yn"] = 'N';

		$name_style = ' style="';
		if ($data['code_bold'] == 'Y') $name_style .= 'font-weight:900;';
		if ($data['code_color'] != '') $name_style .= 'color:' . $data['code_color'] . ';';
		$name_style .= '"';
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>
		<fieldset>
			<legend class="blind">알림분류 폼</legend>
			<table class="tinytable write" summary="알림분류를 등록/수정합니다.">
			<caption>알림분류</caption>
			<colgroup>
				<col width="120px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_code_name">분류명</label></th>
					<td colspan="3">
						<div class="left">
							<input type="text" name="param[code_name]" id="post_code_name" value="<?=$data['code_name'];?>" size="25" title="분류명을 입력하세요." class="type_text"<?=$name_style;?> />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_code_color">글자색상</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[code_color]" id="post_code_color" value="<?=$data['code_color'];?>" size="7" title="글자색상을 입력하세요." class="type_text" onclick="color_open('post_code_name')" readonly="readonly" />
						</div>
						<div id="fontcolorview"></div>
					</td>
					<th>글자굵기</th>
					<td>
						<div class="left">
							<?=code_radio($set_use, "param[code_bold]", "post_code_bold", $data["code_bold"], '', '', ' onclick="check_strong(this.value, \'post_code_name\')"');?>
						</div>
					</td>
				</tr>
				<tr>
					<th>보기여부</th>
					<td>
						<div class="left">
							<?=code_radio($set_use, "param[view_yn]", "post_view_yn", $data["view_yn"]);?>
						</div>
					</td>
					<th>기본여부</th>
					<td>
						<div class="left">
							<?=code_radio($set_use, "param[default_yn]", "post_default_yn", $data["default_yn"]);?>
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
			<?
				if ($code_idx == "") {
			?>
					<span class="btn_big_green"><input type="submit" value="등록" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="popupform_close()" /></span>

					<input type="hidden" name="sub_type" value="post" />
			<?
				} else {
			?>
					<span class="btn_big_blue"><input type="submit" value="수정" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="popupform_close()" /></span>

					<input type="hidden" name="sub_type" value="modify" />
					<input type="hidden" name="code_idx" value="<?=$code_idx;?>" />
			<?
				}
			?>
				</div>
			</div>

		</fieldset>
		</form>
	</div>
</div>
<?
	}
?>