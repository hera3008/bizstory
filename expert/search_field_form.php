<?
/*
	생성 : 2013.01.15
	수정 : 2013.01.16
	위치 : 전문가코너 > 코드설정 > 거래처검색분류 - 등록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$ecsf_idx = $idx;

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
	if (($auth_menu['int'] == 'Y' && $ecsf_idx == '') || ($auth_menu['mod'] == 'Y' && $ecsf_idx != '')) // 등록권한
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
		$where = " and ecsf.ecsf_idx = '" . $ecsf_idx . "'";
		$data = expert_client_search_field_data("view", $where);

		if ($data['view_yn'] == '') $data['view_yn'] = 'Y';
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>
		<fieldset>
			<legend class="blind">거래처검색조건 폼</legend>
			<table class="tinytable write" summary="거래처검색조건 필드를 등록/수정합니다.">
			<caption>거래처검색조건</caption>
			<colgroup>
				<col width="120px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_field_subject">검색조건명</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[field_subject]" id="post_field_subject" value="<?=$data['field_subject'];?>" size="25" title="검색조건명을 입력하세요." class="type_text" />
							<label for="post_view_yn"><input type="checkbox" name="param[view_yn]" id="view_yn" value="Y" <?=checked($data['view_yn'], 'Y');?> />사용</label>
							<label for="post_essential_yn"><input type="checkbox" name="param[essential_yn]" id="essential_yn" value="Y" <?=checked($data['essential_yn'], 'Y');?> />필수</label>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_field_name">필드명</label></th>
					<td>
						<div class="left">
				<?
					if ($ecsf_idx == '')
					{
				?>
							<input type="text" name="param[field_name]" id="post_field_name" value="<?=$data['field_name'];?>" size="20" title="필드명을 입력하세요." class="type_text" />
							영문으로 시작하세요. 영문 숫자 함께 사용하세요.
				<?
					}
					else
					{
						echo $data['field_name'];
				?>
							<input type="hidden" name="field_name" id="post_field_name" value="<?=$data['field_name'];?>" />
				<?
					}
				?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_field_type">필드형식</label></th>
					<td>
						<div class="left">
							<?=code_select($set_field_type, "param[field_type]", "post_field_type", $data["field_type"], '필드형식을 선택하세요.', '', '');?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_field_length">필드길이</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[field_length]" id="post_field_length" alt="필드길이" value="<?=$data['field_length'];?>" size="5" maxlength="3" class="type_text" /> 1~255까지
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
			<?
				if ($ecsf_idx == "") {
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
					<input type="hidden" name="ecsf_idx" value="<?=$ecsf_idx;?>" />
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
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_field_subject').val(); // 검색조건명
		chk_title = $('#post_field_subject').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_field_name').val(); // 필드명
		chk_title = $('#post_field_name').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_field_type').val(); // 필드형식
		chk_title = $('#post_field_type').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_field_length').val(); // 필드길이
		chk_title = $('#post_field_length').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success : function(msg) {
					if (msg.success_chk == "Y")
					{
						close_data_form();
					}
					else check_auth_popup(msg.error_string);
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