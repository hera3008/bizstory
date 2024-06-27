<?
/*
	생성 : 2012.11.02
	생성 : 2013.05.22
	위치 : 설정폴더 > 업체관리 > 업체분류 - 등록, 수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

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

	if ($form_chk == 'Y')
	{
		$where = " and code.code_idx = '" . $code_idx . "'";
		$data = company_class_data("view", $where);

		if ($data["view_yn"] == '') $data["view_yn"] = 'Y';
		if ($data["default_yn"] == '') $data["default_yn"] = 'N';
		if ($data["viewer_yn"] == '') $data["viewer_yn"] = 'N';
		if ($menu_depth == "") $menu_depth = $data["menu_depth"];

		$depth_data = query_view("select max(menu_depth) as max_depth from company_class where del_yn = 'N' limit 1");
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
				<legend class="blind">분류 폼</legend>

				<div class="sub_frame"><h4>분류설정</h4></div>
				<table class="tinytable write" summary="분류를 등록/수정합니다.">
					<caption>분류</caption>
					<colgroup>
						<col width="100px" />
						<col />
						<col width="100px" />
						<col />
					</colgroup>
					<tbody>
						<tr>
							<th><label for="post_menu_depth">분류단계</label></th>
							<td colspan="3">
								<div class="left">
									<select name="param[menu_depth]" id="post_menu_depth" onchange="up_menu_change(this.value, '<?=$code_idx;?>');" title="분류단계를 입력하세요.">
										<option value="">분류단계</option>
								<?
									for($i = 1; $i <= $max_depth; $i++) {
								?>
										<option value="<?=$i;?>" <?=selected($menu_depth, $i);?>><?=$i;?>차 분류</option>
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
							<th><label for="post_code_name">분류명</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[code_name]" id="post_code_name" value="<?=$data['code_name'];?>" size="25" title="분류명을 입력하세요." class="type_text" />
								</div>
							</td>
							<th><label for="post_main_type">메인화면</label></th>
							<td>
								<div class="left">
									<?=code_select($set_main_type, "param[main_type]", "post_main_type", $data["main_type"], '메인화면을 선택하세요.');?>
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

				<div class="sub_frame"><h4>기본서비스설정</h4></div>
				<table class="tinytable write" summary="기본서비스설정을 등록/수정합니다.">
					<caption>기본서비스설정</caption>
					<colgroup>
						<col width="100px" />
						<col />
						<col width="100px" />
						<col />
					</colgroup>
					<tbody>
						<tr>
							<th><label for="post_part_num">지사수</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[part_num]" id="post_part_num" value="<?=$data['part_num'];?>" size="10" title="지사수 입력하세요." class="type_text" />
								</div>
							</td>
							<th><label for="post_client_num">거래처수</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[client_num]" id="post_client_num" value="<?=$data['client_num'];?>" size="10" title="거래처수 입력하세요." class="type_text" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_staff_num">직원수</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[staff_num]" id="post_staff_num" value="<?=$data['staff_num'];?>" size="10" title="직원수 입력하세요." class="type_text" />
								</div>
							</td>
							<th><label for="post_banner_num">배너수</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[banner_num]" id="post_banner_num" value="<?=$data['banner_num'];?>" size="10" title="배너수 입력하세요." class="type_text" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_volume_num">저장공간</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[volume_num]" id="post_volume_num" value="<?=$data['volume_num'];?>" size="10" title="저장공간 입력하세요." class="type_text" /> GByte
								</div>
							</td>
							<th><label for="post_viewer_yn">뷰어기능</label></th>
							<td>
								<div class="left">
									<?=code_radio($set_use, "param[viewer_yn]", "post_viewer_yn", $data["viewer_yn"]);?>
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_default_price">기본가격</label></th>
							<td colspan="3">
								<div class="left">
									<input type="text" name="param[default_price]" id="post_default_price" value="<?=number_format($data['default_price']);?>" size="15" title="기본가격 입력하세요." class="type_text" /> 원
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