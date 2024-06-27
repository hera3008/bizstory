<?
/*
	생성 : 2013.01.29
	수정 : 2013.02.05
	위치 : 파일센터 > 타입설정 - 등록
*/
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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		$data = filecenter_code_type_data("view", $where);

		if ($data["view_yn"] == '') $data["view_yn"] = '1';
		if ($data["part_idx"] == '' || $data["part_idx"] == '0') $data["part_idx"] = $code_part;
		if ($menu_depth == "") $menu_depth = $data["menu_depth"];

		$depth_data = query_view("select max(menu_depth) as max_depth from filecenter_code_type where del_yn = 'N' and comp_idx = '" . $code_comp . "' and part_idx = '" . $code_part . "' limit 1");
		if($depth_data["max_depth"] == "") $max_depth = 1;
		else $max_depth = $depth_data["max_depth"] + 1;

		if ($menu_depth == "") $menu_depth = 1;
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
				<legend class="blind">타입설정 폼</legend>

				<table class="tinytable write" summary="타입설정 등록/수정합니다.">
				<caption>타입설정</caption>
				<colgroup>
					<col width="120px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th><label for="post_part_idx">지사</label></th>
						<td>
							<div class="left">
								<?=company_part_select($data['part_idx'], '');?>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_menu_depth">단계</label></th>
						<td>
							<div class="left">
								<select name="param[menu_depth]" id="post_menu_depth" onchange="up_change(this.value, '<?=$code_idx;?>');">
									<option value="">단계</option>
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
							<div class="left" id="up_list">
								선택한 상위메뉴가 없습니다.
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_code_name">설정명</label></th>
						<td>
							<div class="left">
								<input type="text" name="param[code_name]" id="post_code_name" value="<?=$data['code_name'];?>" size="80" title="설정명을 입력하세요." class="type_text" />
							</div>
						</td>
					</tr>
					<tr>
						<th>보기여부</th>
						<td>
							<div class="left">
								<?=code_radio($set_use_num, "param[view_yn]", "post_view_yn", $data["view_yn"]);?>
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
	up_change('<?=$menu_depth;?>', '<?=$code_idx;?>');
//]]>
</script>
<?
	}
?>