<?
/*
	수정 : 2013.05.22
	위치 : 설정폴더(총관리자용) > 설정관리 > 메뉴관리 - 등록, 수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$mi_idx = $idx;

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
	if ($auth_menu['int'] == 'Y' && $mi_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $mi_idx != '') // 수정권한
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
		$where = " and mi.mi_idx = '" . $mi_idx . "'";
		$data = menu_info_data("view", $where);

		if ($menu_depth == "") $menu_depth = $data["menu_depth"];
		if ($data["tab_yn"]  == "") $data["tab_yn"] = "N";
		if ($data["view_yn"] == "") $data["view_yn"] = "Y";

		$depth_data = query_view("select max(menu_depth) as max_depth from menu_info where del_yn = 'N' limit 1");
		if($depth_data["max_depth"] == "") $max_depth = 1;
		else $max_depth = $depth_data["max_depth"] + 1;

		if ($menu_depth == "") $menu_depth = 1;
?>

<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_default;?>

			<fieldset>
				<legend class="blind">메뉴관리 폼</legend>
				<table class="tinytable write" summary="메뉴를 등록/수정합니다.">
				<caption>메뉴관리</caption>
					<colgroup>
						<col width="120px" />
						<col />
					</colgroup>
					<tbody>
						<tr>
							<th><label for="post_menu_depth">메뉴 단계</label></th>
							<td>
								<div class="left">
									<select name="param[menu_depth]" id="post_menu_depth" onchange="up_menu_change(this.value, '<?=$mi_idx;?>'); check_menu_depth('post_menu_depth')">
										<option value="">메뉴 단계</option>
								<?
									for($i = 1; $i <= $max_depth; $i++) {
								?>
										<option value="<?=$i;?>" <?=selected($menu_depth, $i);?>><?=$i;?>차 메뉴</option>
								<?
									}
								?>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<th>상위메뉴</th>
							<td>
								<div class="left" id="up_menu_list">
									선택한 상위메뉴가 없습니다.
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_menu_name">메뉴명</label></th>
							<td colspan="3">
								<div class="left">
									<input type="text" name="param[menu_name]" id="post_menu_name" class="type_text" title="메뉴명을 입력하세요." size="30" value="<?=$data["menu_name"];?>" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_mode_folder">폴더명/파일명</label></th>
							<td colspan="3">
								<div class="left">
									<input type="text" name="param[mode_folder]" id="post_mode_folder" class="type_text" title="폴더명을 입력하세요." size="15" value="<?=$data["mode_folder"];?>" />
									/
									<input type="text" name="param[mode_file]" id="post_mode_file" class="type_text" title="파일명을 입력하세요." size="15" value="<?=$data["mode_file"];?>" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_icon_img">아이콘이미지</label></th>
							<td colspan="3">
								<div class="left">
									<input type="text" name="param[icon_img]" id="post_icon_img" class="type_text" title="아이콘이미지 입력하세요." size="5" value="<?=$data["icon_img"];?>" />
								</div>
							</td>
						</tr>
						<tr>
							<th>탭 메뉴사용</th>
							<td>
								<div class="left">
									<?=code_radio($set_use, "param[tab_yn]", "post_tab_yn", $data["tab_yn"]);?>
								</div>
							</td>
						</tr>
						<tr>
							<th>출력여부</th>
							<td>
								<div class="left">
									<?=code_radio($set_view, "param[view_yn]", "post_view_yn", $data["view_yn"]);?>
								</div>
							</td>
						</tr>
					</tbody>
				</table>

				<div class="section">
					<div class="fr">
				<?
					if ($mi_idx == '') {
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
						<input type="hidden" name="mi_idx"   value="<?=$mi_idx;?>" />
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
	up_menu_change('<?=$menu_depth;?>', '<?=$mi_idx;?>');
//]]>
</script>
<?
	}
?>