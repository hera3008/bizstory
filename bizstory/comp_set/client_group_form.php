<?
/*
	수정 : 2013.03.25
	위치 : 설정폴더 > 거래처관리 > 거래처분류 - 등록, 수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$ccg_idx   = $idx;

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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y' && $ccg_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $ccg_idx != '') // 수정권한
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
		$where = " and ccg.ccg_idx = '" . $ccg_idx . "'";
		$data = company_client_group_data("view", $where);

		if ($data["code_bold"] == '') $data["code_bold"] = 'N';
		if ($data["view_yn"] == '') $data["view_yn"] = 'Y';
		if ($data["default_yn"] == '') $data["default_yn"] = 'N';
		if ($data["part_idx"] == '' || $data["part_idx"] == '0') $data["part_idx"] = $code_part;
		if ($menu_depth == "") $menu_depth = $data["menu_depth"];

		$depth_data = query_view("select max(menu_depth) as max_depth from company_client_group where del_yn = 'N' and comp_idx = '" . $code_comp . "' and part_idx = '" . $code_part . "' limit 1");
		if($depth_data["max_depth"] == "") $max_depth = 1;
		else $max_depth = $depth_data["max_depth"] + 1;

		if ($menu_depth == "") $menu_depth = 1;

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
				<legend class="blind">거래처분류 폼</legend>
				<table class="tinytable write" summary="거래처분류 <?=$form_title;?>합니다.">
				<caption>거래처분류</caption>
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
					<?
					// 거래처, 접수분류
						$str_script = "up_menu_change('" . $menu_depth . "', '" . $ccg_idx . "'); $('#post_menu_depth').val(1)";
					?>
								<?=company_part_form($data['part_idx'], $data['part_name'], ' onchange="' . $str_script . '"');?>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_menu_depth">분류 단계</label></th>
						<td colspan="3">
							<div class="left">
								<select name="param[menu_depth]" id="post_menu_depth" onchange="up_menu_change(this.value, '<?=$ccg_idx;?>');">
									<option value="">분류 단계</option>
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
						<th>상위분류</th>
						<td colspan="3">
							<div class="left" id="up_menu_list">
								선택한 상위분류가 없습니다.
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_group_name">거래처분류명</label></th>
						<td colspan="3">
							<div class="left">
								<input type="text" name="param[group_name]" id="post_group_name" value="<?=$data['group_name'];?>" size="25" title="거래처분류명을 입력하세요." class="type_text"<?=$name_style;?> />
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_code_color">글자색상</label></th>
						<td>
							<div class="left">
								<input type="text" name="param[code_color]" id="post_code_color" value="<?=$data['code_color'];?>" size="7" title="글자색상을 입력하세요." class="type_text" onclick="color_open('post_group_name')" readonly="readonly" />
							</div>
							<div id="fontcolorview"></div>
						</td>
						<th>글자굵기</th>
						<td>
							<div class="left">
								<?=code_radio($set_use, "param[code_bold]", "post_code_bold", $data["code_bold"], '', '', ' onclick="check_strong(this.value, \'post_group_name\')"');?>
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
					if ($ccg_idx == "") {
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
						<input type="hidden" name="ccg_idx"  value="<?=$ccg_idx;?>" />
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
	up_menu_change('<?=$menu_depth;?>', '<?=$ccg_idx;?>');
//]]>
</script>
<?
	}
?>