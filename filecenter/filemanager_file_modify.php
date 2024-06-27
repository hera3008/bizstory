<?
/*
	생성 : 2013.02.19
	수정 : 2013.02.19
	위치 : 파일센터 > 파일관리 - 파일명수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
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
	if ($auth_menu['mod'] == 'Y' && $fi_idx != '') // 수정권한
	{
		$form_chk = 'Y';
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

// 해당폴더에 대한 권한을 가지고 처리한다.
	if ($form_chk == 'Y')
	{
		$where = " and fi.fi_idx = '" . $fi_idx . "'";
		$data = filecenter_info_data('view', $where);

		$file_name = $data['file_name'];
		$file_ex   = explode('.', $file_name);
		$last_num  = sizeof($file_ex) - 1;
		$ex_name   = strtolower($file_ex[$last_num]);

		foreach ($file_ex as $k => $v)
		{
			if ($k == 0)
			{
				$total_file_name = $v;
			}
			else if ($k < $last_num)
			{
				$total_file_name .= '.' . $v;
			}
		}
		$file_name = $total_file_name;
?>
<div class="ajax_write">
	<div class="upload_title">
		<strong>파일명</strong> <?=$form_title;?>
		<img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_close.png" onclick="popupform_close();" alt="닫기" />
	</div>

	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_folder()">
			<?=$form_all;?>
			<input type="hidden" id="post_code_part" name="code_part" value="<?=$code_part;?>" />

			<fieldset>
				<legend class="blind">파일명 수정폼</legend>
				<table class="tinytable write" summary="파일명을 수정합니다.">
				<caption>파일명</caption>
				<colgroup>
					<col width="120px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th><label for="post_file_name">파일명</label></th>
						<td>
							<div class="left">
								<input type="text" name="param[file_name]" id="post_file_name" value="<?=$file_name;?>" size="50" title="파일명을 입력하세요." class="type_text" />
								<input type="hidden" name="file_ex_name" id="post_file_ex_name" value="<?=$ex_name;?>" />
							</div>
						</td>
					</tr>
				</tbody>
				</table>

				<div class="section">
					<div class="fr">
						<span class="btn_big_blue"><input type="submit" value="수정" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="popupform_close()" /></span>

						<input type="hidden" name="sub_type" value="file_modify" />
						<input type="hidden" name="fi_idx"   value="<?=$fi_idx;?>" />
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 수정
	function check_folder()
	{
		var action_num = 0, chk_total = '';
		var chk_value, chk_title, chk_emp;

		chk_value = $('#post_file_name').val();
		chk_title = $('#post_file_name').attr('title');
		chk_emp   = check_empty_value(chk_value);

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

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
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
						check_auth_popup(msg.error_string);
					}
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