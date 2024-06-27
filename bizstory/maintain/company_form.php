<?
/*
	수정 : 2013.05.22
	위치 : 설정폴더(총관리자용) > 업체관리 > 업체목록 - 등록, 수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$comp_idx = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;sclass=' . $send_sclass;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
		<input type="hidden" name="sclass" value="' . $send_sclass . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y' && $comp_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $comp_idx != '') // 수정권한
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
			</script>
		';
	}

	if ($form_chk == 'Y')
	{
		$where = " and comp.comp_idx = '" . $comp_idx . "'";
		$data = company_info_data("view", $where);

		if ($data["start_date"] == "") $data["start_date"] = $data["auth_date"];
		if ($data["start_date"] == "") $data["start_date"] = date('Y-m-d');
		$data["start_date"] = date_replace($data["start_date"], 'Y-m-d');
		$data["end_date"]   = date_replace($data["end_date"], 'Y-m-d');

		if ($data["view_yn"] == "") $data["view_yn"] = "Y";
		if ($data["auth_yn"] == "") $data["auth_yn"] = "N";
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
				<legend class="blind">업체정보 폼</legend>

				<div class="sub_frame"><h4>업체정보</h4></div>
				<table class="tinytable write" summary="업체정보를 등록/수정합니다.">
					<caption>업체정보</caption>
					<colgroup>
						<col width="100px" />
						<col />
						<col width="110px" />
						<col />
					</colgroup>
					<tbody>
						<tr>
							<th><label for="post_sole_idx">총판</label></th>
							<td>
								<div class="left">
									<select name="param[sole_idx]" id="post_sole_idx" title="총판을 선택하세요">
										<option value="">총판을 선택하세요</option>
						<?
							$sole_where = " and sole.view_yn = 'Y'";
							$sole_list = sole_info_data('list', $sole_where, '', '', '');
							foreach ($sole_list as $sole_k => $sole_data)
							{
								if (is_array($sole_data))
								{
						?>
										<option value="<?=$sole_data['sole_idx'];?>" <?=selected($sole_data['sole_idx'], $data['sole_idx']);?>><?=$sole_data['comp_name'];?></option>
						<?
								}
							}
						?>
									</select>
								</div>
							</td>
							<th><label for="post_comp_class">업체분류</label></th>
							<td>
								<div class="left">
									<select name="param[comp_class]" id="post_comp_class" title="업체분류를 선택하세요">
										<option value="">업체분류를 선택하세요</option>
						<?
							$class_where = " and code.view_yn = 'Y'";
							$class_list = company_class_data('list', $class_where, '', '', '');
							foreach ($class_list as $class_k => $class_data)
							{
								if (is_array($class_data))
								{
									$emp_str = str_repeat('&nbsp;', 4 * ($class_data['menu_depth'] - 1));
						?>
										<option value="<?=$class_data['code_idx'];?>" <?=selected($class_data['code_idx'], $data['comp_class']);?>><?=$emp_str;?><?=$class_data['code_name'];?></option>
						<?
								}
							}
						?>
									</select>
								</div>
							</td>
						</tr>
					<?
						include $local_path . "/bizstory/maintain/company_form_inc.php";
					?>
						<tr>
							<th><label for="post_start_date">시작일</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[start_date]" id="post_start_date" class="type_text datepicker" title="시작일을 입력하세요." size="10" value="<?=date_replace($data['start_date'], 'Y-m-d');?>" readonly="readonly" />
								</div>
							</td>
							<th><label for="post_end_date">종료일</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[end_date]" id="post_end_date" class="type_text datepicker" title="종료일을 입력하세요." size="10" value="<?=date_replace($data['end_date'], 'Y-m-d');?>" readonly="readonly" />
								</div>
							</td>
						</tr>
						<tr>
							<th>보기여부</th>
							<td colspan="3">
								<div class="left">
									<?=code_radio($set_use, "param[view_yn]", "post_view_yn", $data["view_yn"]);?>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
	<?
		if ($comp_idx != '')
		{
			$where = " and cs.comp_idx = '" . $comp_idx . "'";
			$data = company_setting_data("view", $where);
	?>
				<div class="sub_frame"><h4>업체설정</h4></div>
				<table class="tinytable write" summary="업체설정을 등록/수정합니다.">
					<caption>업체설정</caption>
					<colgroup>
						<col width="100px" />
						<col />
						<col width="100px" />
						<col />
					</colgroup>
					<tbody>
						<tr>
							<th><label for="post_use_price">서비스 가격</label></th>
							<td>
								<div class="left">
									<input type="text" name="param_set[use_price]" id="post_use_price" class="type_text" title="서비스 가격을 입력하세요." size="10" value="<?=$data['use_price'];?>" /> 원
								</div>
							</td>
							<th>메인화면</th>
							<td>
								<div class="left">
									<?=code_select($set_main_type, "param_set[main_type]", "post_main_type", $data["main_type"], '메인화면을 선택하세요.');?>
								</div>
							</td>
						</tr>
						<tr>
							<th>세금계산서</th>
							<td colspan="3">
								<div class="left">
									<?=code_radio($set_use, "param_set[tax_yn]", "post_tax_yn", $data["tax_yn"]);?>
								</div>
							</td>
						</tr>
						<tr>
							<th>지사통합</th>
							<td colspan="3">
								<div class="left">
									<?=code_radio($set_use, "param_set[part_yn]", "post_part_yn", $data["part_yn"]);?>
									* 'N'일 경우 지사끼리 데이타를 볼 수 없습니다.
								</div>
							</td>
						</tr>
						<tr>
							<th>업무지사통합</th>
							<td colspan="3">
								<div class="left">
									<?=code_radio($set_use, "param_set[part_work_yn]", "post_part_work_yn", $data["part_work_yn"]);?>
									* 'N'일 경우 지사끼리 업무를 볼 수 없습니다.
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_part_cnt">지사수</label></th>
							<td>
								<div class="left">
									<input type="text" name="param_set[part_cnt]" id="post_part_cnt" class="type_text" title="지사수를 입력하세요." size="10" value="<?=number_format($data['part_cnt']);?>" /> 개
								</div>
							</td>
							<th><label for="post_client_cnt">거래처수</label></th>
							<td>
								<div class="left">
									<input type="text" name="param_set[client_cnt]" id="post_client_cnt" class="type_text" title="거래처수를 입력하세요." size="10" value="<?=number_format($data['client_cnt']);?>" /> 개
									<input type="hidden" name="old_client_cnt" id="old_client_cnt" value="<?=$data['client_cnt'];?>" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_staff_cnt">직원수</label></th>
							<td>
								<div class="left">
									<input type="text" name="param_set[staff_cnt]" id="post_staff_cnt" class="type_text" title="직원수를 입력하세요." size="10" value="<?=number_format($data['staff_cnt']);?>" /> 개
								</div>
							</td>
							<th><label for="post_banner_cnt">배너수</label></th>
							<td>
								<div class="left">
									<input type="text" name="param_set[banner_cnt]" id="post_banner_cnt" class="type_text" title="배너수를 입력하세요." size="10" value="<?=number_format($data['banner_cnt']);?>" /> 개
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_volume_num">저장공간</label></th>
							<td>
								<div class="left">
									<input type="text" name="param_set[volume_num]" id="post_volume_num" value="<?=$data['volume_num'];?>" size="10" title="저장공간 입력하세요." class="type_text" /> GByte
								</div>
							</td>
							<th><label for="post_viewer_yn">뷰어기능</label></th>
							<td>
								<div class="left">
									<?=code_radio($set_use, "param_set[viewer_yn]", "post_viewer_yn", $data["viewer_yn"], '뷰어기능을 선택하세요.');?>
								</div>
							</td>
						</tr>
						<tr>
							<th>에이전트사용</th>
							<td colspan="3">
								<div class="left">
									<?=code_radio($set_use, "param_set[agent_yn]", "post_agent_yn", $data["agent_yn"], '에이전트사용을 선택하세요.');?>
								</div>
							</td>
						</tr>
						<tr>
							<th>에이전트타입</th>
							<td colspan="3">
								<div class="left">
									<?=code_checkbox($set_agent_type, "agent_type[]", "post_agent_type", $data["agent_type"], '에어전트타입을 선택하세요.');?>
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_file_class">파일공간</label></th>
							<td>
								<div class="left">
									<?=code_radio($set_filecenter_class, "param_set[file_class]", "post_file_class", $data["file_class"], '파일공간을 선택하세요.');?>
								</div>
							</td>
							<th><label for="post_file_out_url">외부파일주소</label></th>
							<td>
								<div class="left">
									<input type="text" name="param_set[file_out_url]" id="post_file_out_url" value="<?=$data['file_out_url'];?>" size="30" title="외부파일주소 입력하세요." class="type_text" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_filecenter_yn">파일센터</label></th>
							<td colspan="3">
								<div class="left">
									<?=code_radio($set_use_num, "param_set[filecenter_yn]", "post_filecenter_yn", $data["filecenter_yn"], '파일센터를 선택하세요.');?>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
	<?
		}
	?>
				<div class="section">
					<div class="fr">
				<?
					if ($comp_idx == '') {
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
						<input type="hidden" name="comp_idx" value="<?=$comp_idx;?>" />
				<?
					}
				?>
					</div>
				</div>

			</fieldset>
		</form>
	</div>
</div>
<? include "../include/find_address_daum.php"; ?>
<script type="text/javascript">
//<![CDATA[
	$(".datepicker").datepicker();
//]]>
</script>
<?
	}
?>