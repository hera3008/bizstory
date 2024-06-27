<?
/*
	생성 : 2012.11.21
	수정 : 2013.03.22
	위치 : 설정관리 > 코드관리 > 회계설정 > 통장관리 - 등록, 수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
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
	if ($auth_menu['int'] == 'Y' && $code_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $code_idx != '') // 수정권한
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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if ($form_chk == 'Y')
	{
		$where = " and code.code_idx = '" . $code_idx . "'";
		$data = code_account_bank_data("view", $where);

		if ($data["code_bold"]  == '') $data["code_bold"]  = 'N';
		if ($data["view_yn"]    == '') $data["view_yn"]    = 'Y';
		if ($data["default_yn"] == '') $data["default_yn"] = 'N';
		if ($data["part_idx"]   == '' || $data["part_idx"] == '0') $data["part_idx"] = $code_part;

		$name_style = ' style="';
		if ($data['code_bold'] == 'Y') $name_style .= 'font-weight:900;';
		if ($data['code_color'] != '') $name_style .= 'color:' . $data['code_color'] . ';';
		$name_style .= '"';
?>
<div class="ajax_write">
	<div class="upload_title">
		<strong><?=$page_menu_name;?></strong> <?=$form_title;?>
		<img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_close.png" onclick="popupform_close();" alt="닫기" />
	</div>

	<div class="ajax_frame">
		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>

			<fieldset>
				<legend class="blind">통장관리 폼</legend>
				<table class="tinytable write" summary="통장관리를 <?=$form_title;?>합니다.">
				<caption>통장관리</caption>
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
						<th><label for="post_code_name">계좌명</label></th>
						<td>
							<div class="left">
								<input type="text" name="param[code_name]" id="post_code_name" value="<?=$data['code_name'];?>" size="25" title="계좌명을 입력하세요." class="type_text"<?=$name_style;?> />
							</div>
						</td>
						<th><label for="post_bank_num">계좌번호</label></th>
						<td>
							<div class="left">
								<input type="text" name="param[bank_num]" id="post_bank_num" value="<?=$data['bank_num'];?>" size="25" title="계좌번호를 입력하세요." class="type_text" />
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_class_code">계정과목</label></th>
						<td colspan="3">
							<div class="left">
								<select name="param[class_code]" id="post_class_code" title="계정과목을 선택하세요">
									<option value="">계정과목을 선택하세요</option>
								</select>
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
<script type="text/javascript">
//<![CDATA[
	part_information('<?=$data['part_idx'];?>', 'account_class', 'post_class_code', '<?=$data['class_code'];?>', '');
//]]>
</script>
<?
	}
?>