<?
/*
	생성 : 2012.07.03
	위치 : 설정관리 > 접수관리 > 에이전트관리 > 공지관리 - 등록/수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$an_idx    = $idx;

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
	if ($auth_menu['int'] == 'Y' && $an_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $an_idx != '') // 수정권한
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
		$where = " and an.an_idx = '" . $an_idx . "'";
		$data = agent_notice_data("view", $where);

		if ($data["view_yn"] == '') $data["view_yn"] = 'Y';
		if ($data['import_type'] == '') $data['import_type'] = '0';
		if ($data["part_idx"] == '' || $data["part_idx"] == '0') $data["part_idx"] = $code_part;
?>
<div class="ajax_write">
	<div class="upload_title">
		<strong><?=$page_menu_name;?></strong> <?=$form_title;?>
		<img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_close.png" onclick="popupform_close();" alt="닫기" />
	</div>
	<div class="info_text">
		<ul>
			<li>링크주소 입력시 "http://" 를 입력하지 마세요.</li>
		</ul>
	</div>

	<div class="ajax_frame">
		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<input type="hidden" id="post_code_part"  name="code_part"  value="<?=$code_part;?>" />
			<?=$form_all;?>
			<fieldset>
				<legend class="blind">에이전트 공지 폼</legend>
				<table class="tinytable write" summary="에이전트 공지를 등록/수정합니다.">
				<caption>에이전트 공지</caption>
				<colgroup>
					<col width="120px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th><label for="post_part_idx">지사</label></th>
						<td>
							<div class="left">
								<?=company_part_form($data['part_idx'], $data['part_name'], '');?>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_content">공지</label></th>
						<td>
							<div class="left">
								<input type="text" name="param[content]" id="post_content" value="<?=$data['content'];?>" size="50" title="공지명 입력하세요." class="type_text"<?=$name_style;?> />
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_link_url">링크주소</label></th>
						<td>
							<div class="left">
								<input type="text" name="param[link_url]" id="post_link_url" value="<?=$data['link_url'];?>" size="30" title="공지 링크주소 입력하세요." class="type_text" />
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
					</tr>
					<tr>
						<th>중요도</th>
						<td>
							<div class="left">
								<?=code_radio($set_agent_important, 'param[import_type]', 'post_import_type', $data['import_type']);?>
							</div>
						</td>
					</tr>
				</tbody>
				</table>

				<div class="section">
					<div class="fr">
				<?
					if ($an_idx == "") {
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
						<input type="hidden" name="an_idx"   value="<?=$an_idx;?>" />
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
