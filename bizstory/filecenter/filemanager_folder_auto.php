<?
/*
	생성 : 2013.02.04
	수정 : 2013.05.08
	위치 : 파일센터 > 파일관리 - 자동생성
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

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

	$path_data = filecenter_folder_path($up_idx); // 현위치
	$dir_auth  = filecenter_folder_auth($up_idx); // 권한확인

	$form_chk = 'N';
	if ($dir_auth['dir_write_auth'] == 'Y') // 등록권한
	{
		$form_chk = 'Y';
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

	$form_chk = 'Y';
	if ($form_chk == 'Y')
	{
	// 타입정보
		$type_where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "' and code.menu_depth = '1' and code.view_yn = '1'";
		$type_list = filecenter_code_type_data('list', $type_where, '', '', '');

	// 상위정보
		$up_where = " and fi.fi_idx = '" . $up_idx . "'";
		$up_data = filecenter_info_data('view', $up_where);

		$up_path     = $up_data['file_path'] . '/' . $up_data['file_name'];
		$up_path_arr = explode('/', $up_path);
		$add_name    = substr($up_path_arr[1], 0, 1);
?>
<div class="ajax_write">
	<div class="upload_title">
		<strong>폴더생성</strong>
		<img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_close.png" onclick="popupform_close(); list_data();" alt="닫기" />
	</div>

	<div class="info_text">
		<ul>
			<li>타입을 선택하면 설정한 값으로 폴더가 생성됩니다.</li>
			<li>폴더에 해당되는 직원별 권한도 설정이 됩니다.</li>
		</ul>
	</div>
	<div class="ajax_frame">

		<div class="upload_l">
			<p>현위치 <span><?=$path_data['navi_path'];?></span></p>
		</div>

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_folder()">
			<?=$form_all;?>
			<input type="hidden" id="post_code_part" name="code_part" value="<?=$code_part;?>" />
			<input type="hidden" id="post_up_fi_idx" name="up_fi_idx" value="<?=$up_idx;?>" />
			<input type="hidden" id="post_dir_depth" name="dir_depth" value="<?=$up_level;?>" />
			<input type="hidden" id="post_add_name"  name="add_name"  value="<?=$add_name;?>" />

			<fieldset>
				<legend class="blind">폴더자동생성 폼</legend>
				<table class="tinytable write" summary="폴더자동생성을 합니다.">
				<caption>폴더자동생성</caption>
				<colgroup>
					<col width="120px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th><label for="post_folder_type">폴더자동생성</label></th>
						<td>
							<div class="left">
								<select name="folder_type" id="post_folder_type" title="타입을 선택하세요.">
									<option value="">타입을 선택하세요.</option>
							<?
								foreach ($type_list as $type_k => $type_data)
								{
									if (is_array($type_data))
									{
							?>
									<option value="<?=$type_data['code_idx'];?>"><?=$type_data['code_name'];?></option>
							<?
									}
								}
							?>
								</select>
							</div>
						</td>
					</tr>
				</tbody>
				</table>

				<div class="section">
					<div class="fr">
						<span class="btn_big_green"><input type="submit" value="등록" /></span>
						<span class="btn_big_green"><input type="button" value="취소" onclick="popupform_close()" /></span>

						<input type="hidden" name="sub_type" value="folder_post_auto" />
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 등록, 수정
	function check_folder()
	{
		var action_num = 0, chk_total = '';
		var chk_value = '', chk_title = '';

		var add_name = $('#post_add_name').val();

		var chk_value = $('#post_folder_type').val(); // 폴더타입
		var chk_title = $('#post_folder_type').attr('title');

		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'get', dataType: 'jsonp', url: '<?=$set_filecneter_url;?>/folder_ok.php', jsonp : 'callback',
				data: {
					'sub_type' : 'folder_post_auto', 'up_idx' : '<?=$up_idx;?>',
					'file_type' : chk_value, 'add_name' : add_name, 'mem_idx' : '<?=$code_mem;?>' },
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
		}
		else check_auth_popup(chk_total);
		return false;
	}
//]]>
</script>
<?
	}
?>