<?
/*
	수정 : 2013.03.22
	위치 : 설정관리 > 코드관리 > 지사관리 - 등록, 수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp    = $_SESSION[$sess_str . '_comp_idx'];
	$set_part_num = $comp_set_data['part_cnt'];
	$part_idx     = $idx;

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
	$page_where = " and part.comp_idx = '" . $code_comp . "'";
	$page_data = company_part_data('page', $page_where);

	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y' && $part_idx == '') // 등록권한
	{
		if ($page_data['total_num'] >= $set_part_num) // 지사수확인
		{
			echo '
				<script type="text/javascript">
				//<![CDATA[
					check_auth_popup("' . $set_part_num . '개까지 등록이 가능합니다.<br />더이상 등록할 수 없습니다.");
				//]]>
				</script>';
			exit;
		}
		else $form_chk = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $part_idx != '') // 수정권한
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
		$where = " and part.part_idx = '" . $part_idx . "'";
		$data = company_part_data("view", $where);

		if ($data["view_yn"] == '') $data["view_yn"] = 'Y';
		if ($data["default_yn"] == '') $data["default_yn"] = 'N';

		$part_agent_type = $comp_set_data['agent_type'];
		$part_agent_type = explode(',', $part_agent_type);

		$tel_num = $data['tel_num'];
		$tel_num_arr = explode('-', $tel_num);
		$data['tel_num1'] = $tel_num_arr[0];
		$data['tel_num2'] = $tel_num_arr[1];
		$data['tel_num3'] = $tel_num_arr[2];
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
				<legend class="blind">지사정보 폼</legend>
				<table class="tinytable write" summary="지사정보를 <?=$form_title;?>합니다.">
				<caption>지사정보</caption>
				<colgroup>
					<col width="120px" />
					<col />
					<col width="120px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th><label for="post_part_name">지사명</label></th>
						<td colspan="3">
							<div class="left">
								<input type="text" name="param[part_name]" id="post_part_name" value="<?=$data['part_name'];?>" size="25" title="지사명을 입력하세요." class="type_text" />
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
					<tr>
						<th>에이전트타입</th>
						<td colspan="3">
							<div class="left">
								<?=code_checkbox($part_agent_type, "agent_type[]", "post_agent_type", $data["agent_type"], 'value');?>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_tel_num1">전화번호</label></th>
						<td colspan="3">
							<div class="left">
								<?=code_select($set_telephone, 'param[tel_num1]', 'post_tel_num1', $data['tel_num1'], '전화번호 앞자리를 선택하세요.', '없음', '', '');?>
								-
								<input type="text" name="param[tel_num2]" id="post_tel_num2" class="type_text" title="전화번호를 모두 입력하세요." size="4" value="<?=$data['tel_num2'];?>" />
								-
								<input type="text" name="param[tel_num3]" id="post_tel_num3" class="type_text" title="전화번호를 모두 입력하세요." size="4" value="<?=$data['tel_num3'];?>" />
							</div>
						</td>
					</tr>
				</tbody>
				</table>

				<div class="section">
					<div class="fr">
				<?
					if ($part_idx == "") {
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
						<input type="hidden" name="part_idx" value="<?=$part_idx;?>" />
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