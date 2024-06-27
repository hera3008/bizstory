<?
/*
	생성 : 2013.01.29
	수정 : 2013.05.08
	위치 : 파일센터 > 파일관리 - 폴더
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];
	$fi_idx    = $idx;

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
	if ($auth_menu['int'] == 'Y' && $fi_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $fi_idx != '') // 수정권한
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

// 해당폴더에 대한 권한을 가지고 처리한다.
	if ($form_chk == 'Y')
	{
		$where = " and fi.fi_idx = '" . $fi_idx . "'";
		$data = filecenter_info_data('view', $where);

		if ($data['up_fi_idx'] == '') $data['up_fi_idx'] = $up_idx;
		if ($data['dir_depth'] == '') $data['dir_depth'] = $up_level;

	// 상위정보
		$up_where = " and fi.fi_idx = '" . $data['up_fi_idx'] . "'";
		$up_data = filecenter_info_data('view', $up_where);

		$up_path     = $up_data['file_path'] . '/' . $up_data['file_name'];
		$up_path_arr = explode('/', $up_path);
		$add_name    = substr($up_path_arr[1], 0, 1);
?>
<div class="ajax_write">
	<div class="upload_title">
		<strong>풀더</strong> <?=$form_title;?>
		<img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_close.png" onclick="popupform_close();" alt="닫기" />
	</div>

	<div class="info_text">
		<ul>
			<li>띄어쓰기 없이 입력하세요.</li>
		</ul>
	</div>

	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_folder()">
			<?=$form_all;?>
			<input type="hidden" id="post_code_part" name="code_part" value="<?=$code_part;?>" />
			<input type="hidden" id="post_up_fi_idx" name="up_fi_idx" value="<?=$data['up_fi_idx'];?>" />
			<input type="hidden" id="post_dir_depth" name="dir_depth" value="<?=$data['dir_depth'];?>" />
			<input type="hidden" id="post_add_name"  name="add_name"  value="<?=$add_name;?>" />

			<fieldset>
				<legend class="blind">폴더관리 폼</legend>
				<table class="tinytable write" summary="폴더를 생성하거나 폴더명을 수정합니다.">
				<caption>폴더관리</caption>
				<colgroup>
					<col width="120px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th><label for="post_file_name">폴더명</label></th>
						<td>
							<div class="left">
								<input type="text" name="param[file_name]" id="post_file_name" value="<?=$data['file_name'];?>" size="20" title="폴더명을 입력하세요." class="type_text" />
							</div>
						</td>
					</tr>
				</tbody>
				</table>

				<div class="section">
					<div class="fr">
				<?
					if ($fi_idx == "") {
				?>
						<span class="btn_big_green"><input type="submit" value="등록" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="popupform_close()" /></span>

						<input type="hidden" name="sub_type" value="folder_post" />
				<?
					} else {
				?>
						<span class="btn_big_blue"><input type="submit" value="수정" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="popupform_close()" /></span>

						<input type="hidden" name="sub_type" value="folder_modify" />
						<input type="hidden" name="fi_idx"   value="<?=$fi_idx;?>" />
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
//------------------------------------ 등록
	function check_folder()
	{
		var action_num = 0, chk_total = '';
		var chk_value, chk_title, chk_emp, chk_spe;

		var add_name = $('#post_add_name').val();

		chk_value = $('#post_file_name').val();
		chk_title = $('#post_file_name').attr('title');
		chk_emp   = check_empty_value(chk_value); // 공백
		chk_spe   = special_char2(chk_value); // 특수문자

		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (chk_emp == 'No')
		{
			chk_total = chk_total + '공백없이 입력하세요.<br />';
			action_num++;
		}

		if (chk_spe == 'No')
		{
			chk_total = chk_total + '특수문자를 입력할 수 없습니다.<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
<?
	if ($fi_idx == '') // 생성일 경우
	{
?>
			$.ajax({
				type: 'get', dataType: 'jsonp', url: '<?=$set_filecneter_url;?>/folder_ok.php', jsonp : 'callback',
				data: {
					'sub_type' : 'folder_post', 'up_idx' : '<?=$data['up_fi_idx'];?>',
					'file_name' : chk_value, 'add_name' : add_name, 'mem_idx' : '<?=$code_mem;?>' },
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						popupform_close();
						list_left_data();
						list_data();
					}
					else
					{
						$("#loading").fadeOut('slow');
						$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
						check_auth_popup(msg.error_string);
					}
				}
			});
<?
	}
	else // 수정일 경우
	{
?>
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						popupform_close();
						list_left_data();
						list_data();
					}
					else
					{
						$("#loading").fadeOut('slow');
						$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
						check_auth_popup(msg.error_string);
					}
				}
			});
<?
	}
?>
		}
		else check_auth_popup(chk_total);
		return false;
	}
//]]>
</script>
<?
	}
?>