<?
/*
	생성 : 2013.01.17
	수정 : 2013.01.17
	위치 : 전문가코너 > 코드설정 > 상담분류
*/
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
	$link_list = $local_dir . "/bizstory/expert/consult_class_list.php"; // 목록
	$link_form = $local_dir . "/bizstory/expert/consult_class_form.php"; // 등록
	$link_ok   = $local_dir . "/bizstory/expert/consult_class_ok.php";   // 저장

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="popupform_open(\'\')" class="btn_big_green"><span>등록</span></a>';
	}
?>
	<div class="tablewrapper">
		<div id="tableheader">
			<div class="etc_bottom">
				<?=$btn_write;?>
			</div>
		</div>

		<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
			<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
			<input type="hidden" id="list_sub_action" name="sub_action" value="" />
			<input type="hidden" id="list_idx"        name="idx"        value="" />
			<input type="hidden" id="list_post_value" name="post_value" value="" />
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
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
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