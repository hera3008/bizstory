<?
/*
	생성 : 2012.12.10
	수정 : 2012.12.10
	위치 : 설정폴더 > 설정관리 > 총판관리 - 등록, 수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$sole_idx = $idx;

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
	if (($auth_menu['int'] == 'Y' && $sole_idx == '') || ($auth_menu['mod'] == 'Y' && $sole_idx != '')) // 등록, 수정권한
	{
		$form_chk = 'Y';
	}
	else
	{
?>
		<script type="text/javascript">
		//<![CDATA[
			check_auth_popup('');
		//]]>
		</script>
<?
	}

	if ($form_chk == 'Y')
	{
		$where = " and sole.sole_idx = '" . $sole_idx . "'";
		$data = sole_info_data("view", $where);

		if ($data['view_yn'] == '') $data['view_yn'] = 'Y';

		$tel_num = $data['tel_num'];
		$tel_num_arr = explode('-', $tel_num);
		$data['tel_num1'] = $tel_num_arr[0];
		$data['tel_num2'] = $tel_num_arr[1];
		$data['tel_num3'] = $tel_num_arr[2];

		$comp_num = $data['comp_num'];
		$comp_num_arr = explode('-', $comp_num);
		$data['comp_num1'] = $comp_num_arr[0];
		$data['comp_num2'] = $comp_num_arr[1];
		$data['comp_num3'] = $comp_num_arr[2];

		$zip_code = $data['zip_code'];
		$zip_code_arr = explode('-', $zip_code);
		$data['zip_code1'] = $zip_code_arr[0];
		$data['zip_code2'] = $zip_code_arr[1];

		$address = $data['address'];
		$address_arr = explode('||', $address);
		$data['address1'] = $address_arr[0];
		$data['address2'] = $address_arr[1];

		$comp_email = $data['comp_email'];
		$comp_email_arr = explode('@', $comp_email);
		$data['comp_email1'] = $comp_email_arr[0];
		$data['comp_email2'] = $comp_email_arr[1];
?>

<div class="ajax_write">
	<div class="ajax_frame">
		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_default;?>

			<fieldset>
				<legend class="blind">총판관리 폼</legend>
				<table class="tinytable write" summary="총판명, 담당자, 전화번호, 아이디, 비밀번호를 등록/수정합니다.">
					<caption>총판관리</caption>
					<colgroup>
						<col width="100px" />
						<col />
						<col width="100px" />
						<col />
					</colgroup>
					<tbody>
		<?
			if ($sole_idx == '')
			{
		?>
						<tr>
							<th><label for="post_sole_id">아이디</label></th>
							<td colspan="3">
								<div class="left">
									<input type="text" name="param[sole_id]" id="post_sole_id" class="type_text" value="<?=$data['sole_id'];?>" title="아이디를 입력하세요." size="25" maxlength="30" />
									<input type="hidden" name="post_sole_id_chk" id="post_sole_id_chk" value="N" />
									<strong class="btn_sml" onclick="double_id_chk();"><span>중복확인</span></strong>
								</div>
							</td>
						</tr>
		<?
			}
			else
			{
		?>
						<tr>
							<th>아이디</th>
							<td colspan="3">
								<div class="left"><?=$data['sole_id'];?></div>
							</td>
						</tr>
		<?
			}
		?>
						<tr>
							<th><label for="post_sole_pwd">비밀번호</label></th>
							<td>
								<div class="left">
									<input type="password" name="param[sole_pwd]" id="post_sole_pwd" value="" size="25" title="비밀번호를 입력하세요." class="type_text" />
							<?
								if ($sole_idx != '')
								{
									echo '* 수정시 입력하세요.';
								}
							?>
								</div>
							</td>
							<th><label for="post_sole_pwd2">비밀번호확인</label></th>
							<td>
								<div class="left">
									<input type="password" name="param[sole_pwd2]" id="post_sole_pwd2" value="" size="25" title="비밀번호확인을 입력하세요." class="type_text" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_comp_name">상호명</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[comp_name]" id="post_comp_name" class="type_text" title="상호명을 입력하세요." size="30" value="<?=$data['comp_name'];?>" />
								</div>
							</td>
							<th><label for="post_comp_num1">사업자등록번호</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[comp_num1]" id="post_comp_num1" class="type_text" title="사업자등록번호를 모두 입력하세요." size="4" maxlength="3" value="<?=$data['comp_num1'];?>" />
									-
									<input type="text" name="param[comp_num2]" id="post_comp_num2" class="type_text" title="사업자등록번호를 모두 입력하세요." size="4" maxlength="2" value="<?=$data['comp_num2'];?>" />
									-
									<input type="text" name="param[comp_num3]" id="post_comp_num3" class="type_text" title="사업자등록번호를 모두 입력하세요." size="4" maxlength="5" value="<?=$data['comp_num3'];?>" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_boss_name">대표자명</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[boss_name]" id="post_boss_name" class="type_text" title="대표자명을 입력하세요." size="20" value="<?=$data['boss_name'];?>" />
								</div>
							</td>
							<th><label for="post_tel_num1">전화번호</label></th>
							<td>
								<div class="left">
									<?=code_select($set_telephone, 'param[tel_num1]', 'post_tel_num1', $data['tel_num1'], '전화번호 앞자리를 선택하세요.', '없음', '', '');?>
									-
									<input type="text" name="param[tel_num2]" id="post_tel_num2" class="type_text" title="전화번호를 모두 입력하세요." size="4" value="<?=$data['tel_num2'];?>" />
									-
									<input type="text" name="param[tel_num3]" id="post_tel_num3" class="type_text" title="전화번호를 모두 입력하세요." size="4" value="<?=$data['tel_num3'];?>" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_upjong">업종</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[upjong]" id="post_upjong" class="type_text" title="업종을 입력하세요." size="24" value="<?=$data['upjong'];?>" />
								</div>
							</td>
							<th><label for="post_uptae">업태</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[uptae]" id="post_uptae" class="type_text" title="업태를 입력하세요." size="24" value="<?=$data['uptae'];?>" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_zip_code1">사업장주소</label></th>
							<td colspan="3">
								<div class="left">
									<input type="text" name="param[zip_code1]" id="post_zip_code1" class="type_text" title="사업장주소 우편번호 앞자리를 입력하세요." size="4" maxlength="3" value="<?=$data['zip_code1'];?>" />
									-
									<input type="text" name="param[zip_code2]" id="post_zip_code2" class="type_text" title="사업장주소 우편번호 뒷자리를 입력하세요." size="4" maxlength="3" value="<?=$data['zip_code2'];?>" />
									<strong class="btn_sml" onclick="check_address_find();"><span>우편번호찾기</span></strong>
								</div>
								<div class="left mt">
									<input type="text" name="param[address1]" id="post_address1" class="type_text" title="사업장 주소 입력하세요." size="40" value="<?=$data['address1'];?>" />
									<input type="text" name="param[address2]" id="post_address2" class="type_text" title="사업장 상세주소 입력하세요." size="35" value="<?=$data['address2'];?>" />
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_comp_email1">이메일</label></th>
							<td colspan="3">
								<div class="left">
									<input type="text" name="param[comp_email1]" id="post_comp_email1" class="type_text" title="이메일 아이디를 입력하세요." size="12" value="<?=$data['comp_email1'];?>" />
									@
									<input type="text" name="param[comp_email2]" id="post_comp_email2" class="type_text" title="이메일 주소를 입력하세요." size="20" value="<?=$data['comp_email2'];?>" />
									<?=code_select($set_email_domain, 'post_comp_email3', 'post_comp_email3', $data['comp_email2'], '이메일 선택하세요', '이메일 선택하세요', '', '', 'onchange="email_input(\'post_comp_email2\', \'post_comp_email3\');"');?>
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_charge_name">담당자</label></th>
							<td>
								<div class="left">
									<input type="text" name="param[charge_name]" id="post_charge_name" value="<?=$data['charge_name'];?>" size="25" title="담당자를 입력하세요." class="type_text" />
								</div>
							</td>
							<th><label for="post_view_yn">보기여부</label></th>
							<td>
								<div class="left">
									<?=code_radio($set_use, "param[view_yn]", "post_view_yn", $data["view_yn"]);?>
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="file_fname1">로고이미지</label></th>
							<td colspan="3">
								<div class="left">
									<input type="file" name="file_fname1" id="file_fname1" class="type_text type_file type_multi" title="파일 선택하기" />
									<span>* 198px*39px (.jpg, .gif, .png 만 가능) </span>
								</div>
								<div class="filewrap">
									<div class="file" id="file_fname1_view">
					<?
						$file_where = " and solef.sole_idx = '" . $sole_idx . "' and solef.sort = '1'";
						$file_data = sole_file_data('view', $file_where);

						if ($file_data["img_sname"] != '')
						{
							$img_str = '<img src="' . $sole_dir . '/' . $file_data["img_sname"] . '" alt="' . $data["comp_name"] . ' LOGO" width="198px" height="39px" />';

							$fsize = $file_data['img_size'];
							$fsize = byte_replace($fsize);
					?>
										<?=$img_str;?>
										<a href="<?=$local_diir;?>/bizstory/maintain/sole_download.php?solef_idx=<?=$file_data['solef_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
										<a href="javascript:void(0);" class="btn_con_red" onclick="file_form_delete('<?=$file_data['solef_idx'];?>', '1')"><span>삭제</span></a>
					<?
						}
					?>
									</div>
								</div>
								<input type="hidden" id="upload_fnum" name="upload_fnum" value="1" />
							</td>
						</tr>
					</tbody>
				</table>

				<div class="section">
					<div class="fr">
				<?
					if ($sole_idx == '') {
				?>
						<span class="btn_big_green"><input type="submit" value="등록" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="close_data_form()" /></span>

						<input type="hidden" name="sub_type" value="post" />
				<?
					} else {
				?>
						<span class="btn_big_blue"><input type="submit" value="수정" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="close_data_form()" /></span>

						<input type="hidden" name="sub_type" value="modify" />
						<input type="hidden" name="sole_idx" value="<?=$sole_idx;?>" />
				<?
					}
				?>
					</div>
				</div>

			</fieldset>
		</form>
	</div>
</div>
<? include "../include/find_address.php"; ?>

<script type="text/javascript">
//<![CDATA[
	file_setting('file_fname1', 'sole', '1', '<?=$file_multi_size;?>', '');

//------------------------------------ 등록
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '', chk_value = '', chk_title = '';

		chk_value = $('#post_comp_name').val();
		chk_title = $('#post_comp_name').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}
<?
	if ($sole_idx == '') {
?>

		chk_msg = check_sole_id(); // 아이디
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		chk_msg = check_sole_pwd(); // 비밀번호
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}
		chk_msg = check_sole_pwd2(); // 비밀번호 재설정
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}
<?
	}
?>
		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						popupform_close();
						list_data();

						close_data_form();
					}
					else
					{
						$("#loading").fadeOut('slow');
						$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
						check_auth_popup(msg.error_string);
					}
				},
				complete: function(){
					$("#loading").fadeOut('slow');
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

//------------------------------------ 아이디확인
	function check_sole_id()
	{
		var chk_msg = ''; var chk_total = '';
		var chk_value = $('#post_sole_id').val();
		var chk_title = $('#post_sole_id').attr('title');

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_msg = check_num_no(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '아이디는 숫자만 사용할 수 없습니다. <br />';

		chk_msg = check_first_eng(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '아이디 첫자는 숫자를 사용할 수 없습니다. <br />';

		chk_msg = check_empty_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '아이디는 공백없이 입력하세요. <br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 아이디 중복체크
	function double_id_chk()
	{
		$('#post_sole_id_chk').val('N');
		var chk_value = $('#post_sole_id').val();

		var chk_msg = check_sole_id();
		if (chk_msg == 'No') return false;
		else
		{
			$.ajax({
				type : "get", dataType: 'json', url: link_ok,
				data : {"sub_type" : "double_id", "sole_id" : chk_value},
				success : function(msg) {
					if (msg.success_chk == "Y")
					{
						if (msg.double_chk == "N")
						{
							$('#post_sole_id_chk').val('Y');
							check_auth_popup('사용 가능한 아이디입니다.');
						}
						else check_auth_popup('이미 사용중인 아이디입니다.');
					}
					else check_auth_popup(msg.error_string);
				}
			});
			return false;
		}
	}

//------------------------------------ 비밀번호
	function check_sole_pwd()
	{
		var chk_msg = '', chk_total = '';
		var chk_value = $('#post_sole_pwd').val();
		var chk_title = $('#post_sole_pwd').attr('title');

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_msg = check_length_value(chk_value, 4, 20);
		if (chk_msg == 'No') chk_total = chk_total + '비밀번호는 4~20자까지 입력하세요. <br />';

		chk_msg = check_empty_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '비밀번호는 공백없이 입력하세요. <br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}

//------------------------------------ 비밀번호확인
	function check_sole_pwd2()
	{
		var chk_msg = '', chk_total = '';
		var chk_value = $('#post_sole_pwd2').val();
		var chk_title = $('#post_sole_pwd2').attr('title');
		var chk_value1 = $('#post_sole_pwd').val();

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + chk_title + '<br />';

		chk_msg = check_length_value(chk_value, 4, 20);
		if (chk_msg == 'No') chk_total = chk_total + '비밀번호는 4~20자까지 입력하세요. <br />';

		chk_msg = check_empty_value(chk_value);
		if (chk_msg == 'No') chk_total = chk_total + '비밀번호는 공백없이 입력하세요. <br />';

		chk_msg = check_value_same(chk_value, chk_value1);
		if (chk_msg == 'No') chk_total = chk_total + '비밀번호가 일치하지 않습니다. <br />';

		if (chk_total == '') return 'Yes';
		else
		{
			check_auth_popup(chk_total);
			return 'No';
		}
	}
//]]>
</script>
<?
	}
?>