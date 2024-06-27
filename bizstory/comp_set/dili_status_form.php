<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_idx  = $idx;

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
			check_auth('');
		//]]>
		</script>
<?
	}

	if ($form_chk == 'Y')
	{
		$where = " and code.code_idx = '" . $code_idx . "'";
		$data = code_dili_status_data("view", $where);

		if ($data["request_yn"] == '') $data["request_yn"] = 'N';
		if ($data["view_yn"] == '') $data["view_yn"] = 'Y';
		if ($data["default_yn"] == '') $data["default_yn"] = 'N';
		if ($data["part_idx"] == '' || $data["part_idx"] == '0') $data["part_idx"] = $code_part;

		$name_style = ' style="';
		if ($data['code_color'] != '') $name_style .= 'color:' . $data['code_color'] . ';';
		$name_style .= '"';
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>
		<fieldset>
			<legend class="blind">근태상태 폼</legend>
			<table class="tinytable write" summary="근태상태를 등록/수정합니다.">
			<caption>근태상태</caption>
			<colgroup>
				<col width="120px" />
				<col />
				<col width="120px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_part_idx">지사</label></th>
					<td colspan="3">
						<div class="left">
							<?=company_part_form($data['part_idx'], $data['part_name'], '');?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_code_name">근태상태명</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[code_name]" id="post_code_name" value="<?=$data['code_name'];?>" size="25" title="근태상태명을 입력하세요." class="type_text"<?=$name_style;?> />
						</div>
					</td>
					<th><label for="post_code_color">표시색상</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[code_color]" id="post_code_color" value="<?=$data['code_color'];?>" size="7" title="표시색상을 입력하세요." class="type_text" onclick="color_open('post_code_name')" readonly="readonly" />
						</div>
						<div id="fontcolorview"></div>
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