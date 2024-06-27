<?
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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_list = $local_dir . "/bizstory/comp_set/dili_set_form.php"; // 목록
	$link_form = $local_dir . "/bizstory/comp_set/dili_set_form.php"; // 등록
	$link_ok   = $local_dir . "/bizstory/comp_set/dili_set_ok.php";   // 저장
?>
<div class="tablewrapper">
	<div id="tableheader">
		<? include $local_path . "/bizstory/comp_set/part_menu_inc.php"; ?>
	</div>

	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
		<input type="hidden" id="list_sub_action" name="sub_action" value="" />
		<input type="hidden" id="list_idx"        name="idx"        value="" />
		<input type="hidden" id="list_post_value" name="post_value" value="" />
		<input type="hidden" id="list_code_part"  name="code_part"  value="<?=$code_part;?>" />
		<?=$form_page;?>

		<div id="data_list"></div>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
	var link_list = '<?=$link_list;?>';
	var link_form = '<?=$link_form;?>';
	var link_ok   = '<?=$link_ok;?>';

//------------------------------------ 등록
	function check_form()
	{
		$("#popup_notice_view").hide();
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_part_idx').val();
		chk_title = $('#post_part_idx').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_code_name').val();
		chk_title = $('#post_code_name').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				url     : link_ok,
				data    : $('#postform').serialize(),
				success : function(msg) {
					if (msg.success_chk == "Y")
					{
						popupform_close();
						list_data();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);

		return false;
	}

	list_data();
//]]>
</script>
<script type="text/javascript">
//<![CDATA[
//------------------------------------ Save
	function check_form1()
	{
		$("#popup_notice_view").hide();

		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

	// 상호명
		chk_msg = check_comp_name();
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

	// 대표자명
		chk_msg = check_boss_name();
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

	// 사업자등록번호
		chk_msg = check_comp_num();
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

	// 이메일
		chk_msg = check_comp_email();
		if (chk_msg == 'No')
		{
			action_num++;
			return false;
		}

		if (action_num == 0)
		{
			$.ajax({
				type   : 'post', dataType: 'json', url: "<?=$link_ok;?>",
				data   : $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y") check_auth('정상적으로 처리되었습니다.');
					else check_auth(msg.error_string);
				}
			});
		}
		return false;
	}
//]]>
</script>