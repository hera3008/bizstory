
<?
/*
	수정 : 2012.09.25
	위치 : 업무폴더 > 나의업무 > 자기정보 > 자기정보
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

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
// 링크, 버튼
	$link_ok = $local_dir . "/bizstory/myinfo/myinfo_ok.php"; // 저장

	$where = " and mem.mem_idx = '" . $code_mem . "'";
	$data  = member_info_data('view', $where);

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

// 사진이미지
	$file_where = " and mf.mem_idx = '" . $code_mem . "' and mf.sort = '1'";
	$photo_data = member_file_data('view', $file_where);

	$file_upload_num = 1;
?>
<div class="info_text">
	<ul>
		<li>비밀번호는 변경할 경우만 입력하세요.</li>
		<li>사진은 80*80 크기로 해주세요.</li>
	</ul>
</div>

<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>
			<input type="hidden" name="sub_type" value="modify" />
			<input type="hidden" name="file_upload_num" id="file_upload_num" value="<?=$file_upload_num?>" />

		<fieldset>
			<legend class="blind">자기정보 폼</legend>
			<table class="tinytable write" summary="자기정보를 수정합니다.">
			<caption>자기정보</caption>
			<colgroup>
				<col width="100px" />
				<col />
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_mem_name">이름</label></th>
					<td colspan="3">
						<div class="left"><?=$data['mem_name'];?> - <?=$data['mem_id'];?></div>
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
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_mem_pwd">비밀번호</label></th>
					<td colspan="3">
						<div class="left">
							<input type="password" name="param[mem_pwd]" id="post_mem_pwd" class="type_text" title="비밀번호를 입력하세요." size="20" value="" />
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
							<strong class="btn_sml" onclick="execDaumPostcode('zip');"><span>우편번호찾기</span></strong>
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
							<?=code_select($set_telephone, 'param[tel_num1]', 'post_tel_num1', $data['tel_num1'], '전화번호 앞자리를 선택하세요.', '선택', '', '');?>
							-
							<input type="text" name="param[tel_num2]" id="post_tel_num2" class="type_text" title="전화번호를 모두 입력하세요." size="4" value="<?=$data['tel_num2'];?>" />
							-
							<input type="text" name="param[tel_num3]" id="post_tel_num3" class="type_text" title="전화번호를 모두 입력하세요." size="4" value="<?=$data['tel_num3'];?>" />
						</div>
					</td>
					<th><label for="post_hp_num1">핸드폰 번호</label></th>
					<td>
						<div class="left">
							<?=code_select($set_cellular, 'param[hp_num1]', 'post_hp_num1', $data['hp_num1'], '핸드폰 번호 앞자리를 선택하세요.', '선택', '', '');?>
							-
							<input type="text" name="param[hp_num2]" id="post_hp_num2" class="type_text" title="핸드폰 번호를 모두 입력하세요." size="4" value="<?=$data['hp_num2'];?>" />
							-
							<input type="text" name="param[hp_num3]" id="post_hp_num3" class="type_text" title="핸드폰 번호를 모두 입력하세요." size="4" value="<?=$data['hp_num3'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="file_fname1">사진</label></th>
					<td colspan="3">
			<?
				if ($photo_data['total_num'] > 0)
				{
					$img_size = $photo_data['img_size'];
					if ($img_size > 0) $img_size = number_format($img_size/1024);
					else $img_size = 0;

					$photo_img = '<img src="' . $comp_member_dir . '/' . $photo_data['mem_idx'] . '/' . $photo_data['img_sname'] . '" width="80px" alt="' . $data['mem_name'] . '" />';
			?>
						<div class="filewrap">
							<div class="file" id="file_fname1_view">
								<?=$photo_img;?>
								<a href="javascript:void(0);" class="btn_con" onclick="file_delete_chk('<?=$photo_data['mf_idx'];?>', '1')"><span>삭제</span></a>
							</div>
						</div>
			<?
				}
				else 
				{
				
			?>
						<div class="filewrap" style="<?=$photo_data['total_num'] > 0?'display:none':''?>">
							<span>* 사진크기는 80*80 </span>
							<div class="file" id="file_fname1_view">
								<input type="file" name="file_fname1" id="file_fname1" class="type_text type_file type_multi" title="파일 선택하기" />
							</div>
						</div>
			<?
				}
			?>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
					<span class="btn_big_blue"><input type="submit" value="수정" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="location.href='<?=$this_page;?>?<?=$f_all;?>'"/></span>
				</div>
			</div>

		</fieldset>
		</form>
	</div>
</div>
<?
	include $local_path . "/bizstory/include/find_address_daum.php";
?>
<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/script_member.js" charset="utf-8"></script>
<script type="text/javascript">
//<![CDATA[
	var link_ok = '<?=$link_ok;?>';

//------------------------------------ 수정
	function check_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '', chk_msg = '';

		chk_msg = check_mem_email(); // 이메일
		if (chk_msg == 'No') action_num++;

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				beforeSubmit: function(){ $("#loading").fadeIn('slow').fadeOut('slow'); },
				success : function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#popup_result_msg').dialog({autoOpen: true, title: '자기정보 처리결과'});
						$('#popup_result_msg').html('정상적으로 처리되었습니다.');
						document.location.reload();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);

		return false;
	}

	file_setting('file_fname1', 'member', '1', '<?=$file_multi_size;?>', '');

//------------------------------------ 폼에서 파일삭제
	function file_delete_chk(idx, sort)
	{
		$("#popup_notice_view").hide();
		file_form_delete(idx, sort);
		document.location.reload();
		
		/*
		if (confirm("선택한 파일을 삭제하시겠습니까?"))
		{
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: {'sub_type':'file_delete', 'idx':idx, 'sort':sort},
				beforeSubmit: function(){ $("#loading").fadeIn('slow').fadeOut('slow'); },
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#file_fname' + sort + '_view').html('<input type="file" name="file_fname' + sort + '" id="file_fname' + sort + '" class="type_text type_file type_multi" title="파일 선택하기" />');
						file_setting('file_fname' + sort, 'member', sort, '<?=$file_multi_size;?>', '');
					}
					else check_auth_popup('정상적으로 처리가 되지 않았습니다.');
				}
			});
			
		}
		*/
		return false;
	}
//]]>
</script>