<?
/*
	수정 : 2013.03.25
	위치 : 설정폴더 > 직원관리 > 직원등록/수정 - 등록, 수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp     = $_SESSION[$sess_str . '_comp_idx'];
	$code_part     = search_company_part($code_part);
	$set_staff_num = $comp_set_data['staff_cnt'];
	$mem_idx       = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shgroup=' . $send_shgroup;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"  value="' . $send_swhere . '" />
		<input type="hidden" name="stext"   value="' . $send_stext . '" />
		<input type="hidden" name="shgroup" value="' . $send_shgroup . '" />
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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$page_where = " and mem.comp_idx = '" . $code_comp . "'";
	$page_data = member_info_data('page', $page_where);

	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y' && $mem_idx == '') // 등록권한
	{
		if ($page_data['total_num'] >= $set_staff_num) // 직원수확인
		{
			echo '
				<script type="text/javascript">
				//<![CDATA[
					check_auth_popup("' . $set_staff_num . '개까지 등록이 가능합니다.<br />더이상 등록할 수 없습니다.");
				//]]>
				</script>';
			exit;
		}
		else $form_chk = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $mem_idx != '') // 수정권한
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
		$where = " and mem.mem_idx = '" . $mem_idx . "'";
		$data = member_info_data("view", $where);

		if ($data['part_idx'] == '' || $data['part_idx'] == '0') $data['part_idx'] = $code_part;
		if ($data['login_yn'] == '') $data['login_yn'] = 'Y';
		if ($data['ubstory_yn'] == '') $data['ubstory_yn'] = 'N';

		$mem_email = $data['mem_email'];
		$mem_email_arr = explode('@', $mem_email);
		$data['mem_email1'] = $mem_email_arr[0];
		$data['mem_email2'] = $mem_email_arr[1];

		$zip_code = $data['zip_code'];
		$zip_code_arr = explode('-', $zip_code);
		$data['zip_code1'] = $zip_code_arr[0];
		$data['zip_code2'] = $zip_code_arr[1];

		$address = $data['address'];
		$address_arr = explode('||', $address);
		$data['address1'] = $address_arr[0];
		$data['address2'] = $address_arr[1];

		$tel_num = $data['tel_num'];
		$tel_num_arr = explode('-', $tel_num);
		$data['tel_num1'] = $tel_num_arr[0];
		$data['tel_num2'] = $tel_num_arr[1];
		$data['tel_num3'] = $tel_num_arr[2];

		$hp_num = $data['hp_num'];
		$hp_num_arr = explode('-', $hp_num);
		$data['hp_num1'] = $hp_num_arr[0];
		$data['hp_num2'] = $hp_num_arr[1];
		$data['hp_num3'] = $hp_num_arr[2];

		$code_part = $data['part_idx'];
?>
<div class="info_text">
	<ul>
		<li>직책, 부서는 '설정관리 > 직원관리'에서 등록해주세요.</li>
		<li>비밀번호는 변경할 경우만 입력하세요.</li>
		<li>사진은 80*80 크기로 해주세요.</li>
	</ul>
</div>

<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>
			<input type="hidden" id="ubstory_level" name="ubstory_level" value="<?=$data['ubstory_level'];?>" />

			<div class="sub_frame"><h4>직원정보</h4></div>
			<fieldset>
				<legend class="blind">직원정보 폼</legend>
				<table class="tinytable write" summary="직원정보를 등록/수정합니다.">
				<caption>직원정보</caption>
				<colgroup>
					<col width="100px" />
					<col />
					<col width="100px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th><label for="post_part_idx">지사</label></th>
						<td colspan="3">
							<div class="left">
					<?
					// 거래처, 접수분류
						$str_script = "part_information(this.value, 'part_duty', 'post_cpd_idx', '" . $data['cpd_idx'] . "', ''); part_information(this.value, 'staff_group', 'post_csg_idx', '" . $data['csg_idx'] . "', '');";
					?>
								<?=company_part_form($data['part_idx'], $data['part_name'], ' onchange="' . $str_script . '"');?>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_mem_name">이름</label></th>
						<td colspan="3">
							<div class="left">
								<input type="text" name="param[mem_name]" id="post_mem_name" class="type_text" title="이름을 입력하세요." size="20" value="<?=$data['mem_name'];?>" />
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_mem_email1">이메일</label></th>
						<td colspan="3">
							<div class="left">
								<input type="text" name="param[mem_email1]" id="post_mem_email1" class="type_text" title="이메일 아이디를 입력하세요." size="15" value="<?=$data['mem_email1'];?>" />
								@
								<input type="text" name="param[mem_email2]" id="post_mem_email2" class="type_text" title="이메일 주소를 입력하세요." size="20" value="<?=$data['mem_email2'];?>" />
								<?=code_select($set_email_domain, 'post_mem_email3', 'post_mem_email3', $data['mem_email2'], '이메일 주소를 선택하세요', '이메일 주소를 선택하세요', '', '', 'onchange="email_input(\'post_mem_email2\', \'post_mem_email3\');"');?>
				<?
					if ($mem_idx == '')
					{
				?>
								<input type="hidden" name="post_mem_email_chk" id="post_mem_email_chk" value="N" />
								<strong class="btn_sml" onclick="double_email_chk();"><span>중복확인</span></strong>
				<?
					} else {
				?>
								<input type="hidden" name="post_mem_email_chk" id="post_mem_email_chk" value="Y" />
				<?
					}
				?>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_mem_pwd">비밀번호</label></th>
						<td colspan="3">
							<div class="left">
								<input type="password" name="param[mem_pwd]" id="post_mem_pwd" class="type_text" title="비밀번호를 입력하세요." size="20" value="" />
						<?
							if ($mem_idx == '') { echo '* 입력하지 않으면 핸드폰번호 뒷자리가 됩니다.'; }
							else { echo '* 수정시만 입력하세요.'; }
						?>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_cpd_idx">직책</label></th>
						<td>
							<div class="left">
								<select name="param[cpd_idx]" id="post_cpd_idx" title="직책명을 선택하세요">
									<option value="">직책명을 선택하세요</option>
								</select>
							</div>
						</td>
						<th><label for="post_csg_idx">부서</label></th>
						<td>
							<div class="left">
								<select name="param[csg_idx]" id="post_csg_idx" title="부서를 선택하세요">
									<option value="">부서를 선택하세요</option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_zip_code1">주소</label></th>
						<td colspan="3">
							<div class="left">
								<input type="text" name="param[zip_code1]" id="post_zip_code1" class="type_text" title="우편번호 앞자리를 입력하세요." size="4" maxlength="3" value="<?=$data['zip_code1'];?>" />
								-
								<input type="text" name="param[zip_code2]" id="post_zip_code2" class="type_text" title="우편번호 뒷자리를 입력하세요." size="4" maxlength="3" value="<?=$data['zip_code2'];?>" />
								<a href="javascript:void(0);" onclick="check_address_find();" class="btn_sml_violet"><span>우편번호찾기</span></a>
							</div>
							<div class="left mt">
								<input type="text" name="param[address1]" id="post_address1" class="type_text" title="주소 입력하세요." size="40" value="<?=$data['address1'];?>" />
								<input type="text" name="param[address2]" id="post_address2" class="type_text" title="상세주소 입력하세요." size="35" value="<?=$data['address2'];?>" />
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_tel_num1">전화번호</label></th>
						<td>
							<div class="left">
								<?=code_select($set_telephone, 'param[tel_num1]', 'post_tel_num1', $data['tel_num1'], '전화번호 앞자리를 선택하세요.', '없음', '', '{validate:{required:true}}');?>
								-
								<input type="text" name="param[tel_num2]" id="post_tel_num2" class="type_text" title="전화번호를 모두 입력하세요." size="4" value="<?=$data['tel_num2'];?>" />
								-
								<input type="text" name="param[tel_num3]" id="post_tel_num3" class="type_text" title="전화번호를 모두 입력하세요." size="4" value="<?=$data['tel_num3'];?>" />
							</div>
						</td>
						<th><label for="post_hp_num1">핸드폰 번호</label></th>
						<td>
							<div class="left">
								<?=code_select($set_cellular, 'param[hp_num1]', 'post_hp_num1', $data['hp_num1'], '핸드폰 번호 앞자리를 선택하세요.', '없음', '', '{validate:{required:true}}');?>
								-
								<input type="text" name="param[hp_num2]" id="post_hp_num2" class="type_text" title="핸드폰 번호를 모두 입력하세요." size="4" value="<?=$data['hp_num2'];?>" />
								-
								<input type="text" name="param[hp_num3]" id="post_hp_num3" class="type_text" title="핸드폰 번호를 모두 입력하세요." size="4" value="<?=$data['hp_num3'];?>" />
							</div>
						</td>
					</tr>
					<tr>
						<th>재직여부</th>
						<td>
							<div class="left">
								<?=code_radio($set_use, "param[login_yn]", "post_login_yn", $data["login_yn"]);?>
							</div>
						</td>
						<th>관리자여부</th>
						<td>
							<div class="left">
								<?=code_radio($set_use, "param[ubstory_yn]", "post_ubstory_yn", $data["ubstory_yn"]);?>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_enter_date">입사일</label></th>
						<td>
							<div class="left">
								<input type="text" name="param[enter_date]" id="post_enter_date" class="type_text datepicker" title="입사일을 입력하세요." size="15" value="<?=date_replace($data['enter_date'], 'Y-m-d');?>" />
							</div>
						</td>
						<th><label for="post_end_date">퇴사일</label></th>
						<td>
							<div class="left">
								<input type="text" name="param[end_date]" id="post_end_date" class="type_text datepicker" title="퇴사일을 입력하세요." size="15" value="<?=date_replace($data['end_date'], 'Y-m-d');?>" />
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_remark">메모</label></th>
						<td colspan="3">
							<div class="left">
								<textarea name="param[remark]" id="post_remark" class="type_text" title="메모을 입력하세요." cols="50" rows="7"><?=$data['remark'];?></textarea>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_remark2">이력사항</label></th>
						<td colspan="3">
							<div class="left">
								<textarea name="param[remark2]" id="post_remark2" class="type_text" title="이력사항 입력하세요." cols="50" rows="7"><?=$data['remark2'];?></textarea>
							</div>
						</td>
					</tr>
				</tbody>
				</table>
			</fieldset>
		<?
		// 사진
			$photo_where = " and mf.comp_idx = '" . $code_comp . "' and mf.mem_idx = '" . $mem_idx . "' and mf.sort = '1'";
			$photo_data = member_file_data('view', $photo_where);

		// 추가파일
			$file_where = " and mf.comp_idx = '" . $code_comp . "' and mf.mem_idx = '" . $mem_idx . "' and mf.sort != '1'";
			$file_list = member_file_data('list', $file_where, '', '', '');

			$total_file_num = 2 + $file_list['total_num'];
		?>
			<div class="sub_frame"><h4>파일관리</h4></div>
			<input type="hidden" id="upload_fnum" name="upload_fnum" value="<?=$total_file_num;?>" />

			<fieldset>
				<legend class="blind">직원파일 폼</legend>
				<table class="tinytable write" summary="직원파일을 관리합니다." id="file_table">
				<caption>직원파일</caption>
				<colgroup>
					<col width="100px" />
					<col />
					<col width="50px" />
				</colgroup>
				<tbody>
					<tr>
						<th><label for="file_fname1">사진</label></th>
						<td colspan="2">
							<div class="filewrap">
				<?
					if ($photo_data['total_num'] > 0)
					{
						$img_size = $photo_data['img_size'];
						if ($img_size > 0) $img_size = number_format($img_size/1024);
						else $img_size = 0;

						$photo_img = '
							<img src="' . $comp_member_dir . '/' . $photo_data['mem_idx'] . '/' . $photo_data['img_sname'] . '" width="80px" alt="' . $data['mem_name'] . '" />
							<a href="javascript:void(0);" class="btn_con_red" onclick="file_form_delete(\'' . $photo_data['mf_idx'] . '\', \'1\')"><span>삭제</span></a>';
					}
				?>
								<div class="file">
									<input type="file" name="file_fname1" id="file_fname1" class="type_text type_file type_multi" title="직원사진 선택하기" />
									<span>* 사진크기는 80*80 </span>
								</div>
								<div class="file" id="file_fname1_view">
									<?=$photo_img;?>
								</div>
							</div>
						</td>
					</tr>
				<?
					$file_chk = 2;
					if ($file_list['total_num'] > 0)
					{
						foreach ($file_list as $file_k => $file_data)
						{
							if (is_array($file_data))
							{
								$img_size = $file_data['img_size'];
								if ($img_size > 0) $img_size = number_format($img_size/1024);
								else $img_size = 0;
				?>
					<tr>
						<th><label for="file_subject<?=$file_chk;?>">추가파일</label></th>
						<td>
							<div class="left file">
								<input type="text" name="file_subject<?=$file_chk;?>" id="file_subject<?=$file_chk;?>" value="<?=$file_data['subject'];?>" class="type_text" title="추가파일제목을 입력하세요." />
								&nbsp;<a href="<?=$local_dir;?>/bizstory/comp_set/staff_download.php?mf_idx=<?=$file_data['mf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?></a>(<?=$img_size;?>KByte)
								<a href="javascript:void(0);" class="btn_con_red" onclick="file_form_delete('<?=$file_data['mf_idx'];?>', '<?=$file_chk;?>')"><span>삭제</span></a>
							</div>
							<div class="left file" id="file_fname<?=$file_chk;?>_view"></div>
						</td>
						<td>&nbsp;</td>
					</tr>
				<?
								$file_chk++;
							}
						}
					}
				?>
					<tr>
						<th><label for="file_subject<?=$file_chk;?>">추가파일</label></th>
						<td>
							<div class="left file">
								<input type="text" name="file_subject<?=$file_chk;?>" id="file_subject<?=$file_chk;?>" class="type_text" title="추가파일제목을 입력하세요." />
								<input type="file" name="file_fname<?=$file_chk;?>" id="file_fname<?=$file_chk;?>" class="type_text type_file type_multi" title="추가파일 선택하기" />
							</div>
							<div class="left file" id="file_fname<?=$file_chk;?>_view"></div>
						</td>
						<td>
							<div class="left file">
								<a href="javascript:void(0)" onclick="file_form_add('member', '<?=$file_multi_size;?>', '')" class="file_plus" title="파일추가">추가</a>
							</div>
						</td>
					</tr>
				</tbody>
				</table>
				<div class="section">
					<div class="fr">
				<?
					if ($mem_idx == '') {
				?>
						<span class="btn_big_green"><input type="button" value="등록하기" onclick="check_form()" /></span>
						<span class="btn_big_green"><input type="button" value="등록취소" onclick="close_data_form()" /></span>
						<input type="hidden" name="sub_type" value="post" />
				<?
					} else {
				?>
						<span class="btn_big_blue"><input type="button" value="수정하기" onclick="check_form()" /></span>
						<span class="btn_big_blue"><input type="button" value="수정취소" onclick="close_data_form()" /></span>
						<input type="hidden" name="sub_type" value="modify" />
						<input type="hidden" name="mem_idx"  value="<?=$mem_idx;?>" />
				<?
					}
				?>

					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>
<? include $local_path . "/bizstory/include/find_address.php"; ?>

<script type="text/javascript">
//<![CDATA[
	$(".datepicker").datepicker();

	part_information('<?=$data["part_idx"];?>', 'part_duty', 'post_cpd_idx', '<?=$data['cpd_idx'];?>', '');
	part_information('<?=$data["part_idx"];?>', 'staff_group', 'post_csg_idx', '<?=$data['csg_idx'];?>', '');

	file_setting('file_fname1', 'member', '1', '<?=$file_multi_size;?>', '');
	file_setting('file_fname<?=$file_chk;?>', 'member', '<?=$file_chk;?>', '<?=$file_multi_size;?>', '');

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		chk_value = $('#post_part_idx').val(); // 지사
		chk_title = $('#post_part_idx').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_mem_name').val(); // 이름
		chk_title = $('#post_mem_name').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_msg = check_mem_email(); // 이메일
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		chk_msg = check_hp_num(); // 핸드폰번호
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

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
//]]>
</script>
<?
	}
?>