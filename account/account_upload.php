<?
/*
	수정 : 2012.11.30
	위치 : 회계업무 > 운영비관리 - 일괄등록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y') // 등록권한
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
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>">
			<?=$form_all;?>
			<input type="hidden" name="sub_type" id="post_sub_type" value="upload_file" />

		<fieldset>
			<legend class="blind">파일업로드</legend>
			<table class="tinytable write" summary="선택한 파일을 업로드합니다.">
				<caption>파일업로드</caption>
				<colgroup>
					<col width="150px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th><label for="file_fname1">업로드파일선택</label></th>
						<td>
							<div class="filewrap">
								<div class="file" id="file_fname1_view">
									<input type="file" name="file_fname1" id="file_fname1" class="type_text type_file type_multi" title="파일 선택하기" />
								</div>
								<span>* (.csv 만 가능) </span>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_file_name">샘플양식</label></th>
						<td>
							<div class="left">
								<span class="btn_big_violet"><input type="button" value="샘플양식" onclick="location.href='<?=$local_dir;?>/bizstory/account/account_sample.php'" /></span>
								* 샘플양식을 다운받아 양식에 맞게 올려주세요.
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="section">
				<div class="fr">
					<span class="btn_big_green"><input type="button" value="파일업로드" onclick="check_upload()" /></span>
					<span class="btn_big_violet"><input type="button" value="목록" onclick="close_data_form()" /></span>
				</div>
			</div>

			<div id="upload_file_list"></div>

		</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
	file_setting('file_fname1', 'account', '1', '<?=$file_multi_size;?>', '');

	function check_upload(str)
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_file_name').val(); // 파일
		chk_title = $('#post_file_name').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'html', url: '<?=$local_dir;?>/bizstory/account/account_upload_check.php',
				data: $('#postform').serialize(),
				success: function(msg) {
					$("#data_view").html(msg);
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