<?
/*
	생성 : 2013.04.02
	수정 : 2013.04.02
	위치 : 파일센터 > 파일관리 - 이력보기 - 비고 등록, 수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

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

// 파일정보
	$chk_where = " and fi.fi_idx = '" . $fi_idx . "'";
	$chk_data = filecenter_info_data('view', $chk_where);

	$file_name = $chk_data['file_name'];
	$up_fi_idx = $chk_data['up_fi_idx'];
	$up_fi_arr = explode(',', $up_fi_idx);
	$up_fi_num = count($up_fi_arr) - 1;
	$up_idx    = $up_fi_arr[$up_fi_num];

	$dir_auth = filecenter_folder_auth($up_idx, $idx); // 권한 - 현 위치 폴더에 대한 권한 - up_idx

	$form_chk = 'N';
	if ($dir_auth['dir_read_auth'] == 'Y' || $dir_auth['dir_write_auth'] == 'Y') // 읽기, 쓰기일 경우 가능함.
	{
		$form_chk = 'Y';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
				file_history("' . $fi_idx . '");
			//]]>
			</script>
		';
	}

// 해당폴더에 대한 권한을 가지고 처리한다.
	if ($form_chk == 'Y')
	{
		$where = " and fh.fh_idx = '" . $fh_idx . "'";
		$data = filecenter_history_data('view', $where);

		if ($data['contents'] != '')
		{
			$form_title = '수정';
			$btn_class  = 'btn_big_blue';
			$btn_name   = '수정';
		}
		else
		{
			$form_title = '등록';
			$btn_class  = 'btn_big_green';
			$btn_name   = '등록';
		}
?>
<div class="ajax_write">
	<div class="upload_title">
		<strong><?=$file_name;?></strong> 비고 <?=$form_title;?>
		<img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_close.png" onclick="popupform_close(); file_history('<?=$fi_idx;?>');" alt="닫기" />
	</div>

	<div class="ajax_frame">

		<form id="contform" name="contform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_cont()">
			<?=$form_all;?>
			<input type="hidden" id="cont_fi_idx" name="fi_idx" value="<?=$fi_idx;?>" />
			<input type="hidden" id="cont_fh_idx" name="fh_idx" value="<?=$fh_idx;?>" />

			<fieldset>
				<legend class="blind">이력 비고 <?=$form_title;?></legend>
				<table class="tinytable write" summary="이력 비고 <?=$form_title;?>합니다.">
				<caption>이력 비고 <?=$form_title;?></caption>
				<colgroup>
					<col width="120px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th><label for="cont_contents">비고</label></th>
						<td>
							<div class="left">
								<input type="text" name="param[contents]" id="cont_contents" value="<?=$data['contents'];?>" size="50" title="비고를 입력하세요." class="type_text" />
							</div>
						</td>
					</tr>
				</tbody>
				</table>

				<div class="section">
					<div class="fr">
						<span class="<?=$btn_class;?>"><input type="submit" value="<?=$btn_name;?>하기" /></span>
						<span class="<?=$btn_class;?>"><input type="button" value="<?=$btn_name;?>취소" onclick="popupform_close(); file_history('<?=$fi_idx;?>');" /></span>

						<input type="hidden" name="sub_type" value="history_modify" />
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 비고
	function check_cont()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		chk_value = $('#cont_contents').val();
		chk_title = $('#cont_contents').attr('title');

		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#contform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						file_history('<?=$fi_idx;?>');
					}
					else
					{
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