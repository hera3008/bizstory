<?
/*
	수정 : 2014.08.25
	위치 : 설정관리 > 코드관리 > 프로젝트분류 - 등록, 수정
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
		$data = code_project_class_data("view", $where);

		if ($menu_depth == "") $menu_depth = $data["menu_depth"];
		if ($data["view_yn"] == '') $data["view_yn"] = 'Y';		
		if ($data["part_idx"] == '' || $data["part_idx"] == '0') $data["part_idx"] = $code_part;
    
        if ($menu_depth == "") $menu_depth = 1;
        $max_depth = 2;
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
				<legend class="blind">프로젝트분류 폼</legend>
				<table class="tinytable write" summary="프로젝트분류를 <?=$form_title;?>합니다.">
				<caption>프로젝트분류</caption>
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
                        <th><label for="post_menu_depth">메뉴 단계</label></th>
                        <td>
                            <div class="left">
                                <select name="param[menu_depth]" id="post_menu_depth" onchange="up_menu_change(this.value, '<?=$code_idx;?>'); check_menu_depth('post_menu_depth')">
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
                        <th><label for="post_menu_name">분류코드</label></th>
                        <td colspan="3">
                            <div class="left">
                                <input type="text" name="param[code_name]" id="code_name" class="type_text" title="메뉴명을 입력하세요." size="30" value="<?=$data["code_name"];?>" />
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
    up_menu_change('<?=$menu_depth;?>', '<?=$code_idx;?>');
//]]>
</script>
<?
	}
?>