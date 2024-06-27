<?
/*
	수정 : 2013.03.25
	위치 : 설정폴더 > 직원관리 > 부서관리 - 등록, 수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$csg_idx   = $idx;

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
	if ($auth_menu['int'] == 'Y' && $csg_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $csg_idx != '') // 수정권한
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
		$where = " and csg.csg_idx = '" . $csg_idx . "'";
		$data = company_staff_group_data("view", $where);

		if ($data["view_yn"] == '') $data["view_yn"] = 'Y';
		if ($data["default_yn"] == '') $data["default_yn"] = 'N';
		if ($data["part_idx"] == '' || $data["part_idx"] == '0') $data["part_idx"] = $code_part;
?>
<div class="ajax_write" id="form_view">
	<div class="upload_title">
		<strong><?=$page_menu_name;?></strong> <?=$form_title;?>
		<img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_close.png" onclick="popupform_close();" alt="닫기" />
	</div>

	<div class="ajax_frame">
		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>

			<fieldset>
				<legend class="blind">부서관리 폼</legend>
				<table class="tinytable write" summary="부서관리를 <?=$form_title;?>합니다.">
				<caption>부서관리</caption>
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
						<th><label for="post_group_name">부서명</label></th>
						<td colspan="3">
							<div class="left">
								<input type="text" name="param[group_name]" id="post_group_name" value="<?=$data['group_name'];?>" size="25" title="부서명을 입력하세요." class="type_text" />
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
					if ($csg_idx == "") {
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
						<input type="hidden" name="csg_idx"  value="<?=$csg_idx;?>" />
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