<?
/*
	생성 : 2012.07.02
	위치 : 설정관리 > 접수관리 > 에이전트관리 > 공지관리
*/
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
	$link_list         = $local_dir . "/bizstory/comp_set/agent_notice_list.php";      // 목록
	$link_form         = $local_dir . "/bizstory/comp_set/agent_notice_form.php";      // 등록
	$link_ok           = $local_dir . "/bizstory/comp_set/agent_notice_ok.php";        // 저장
	$link_excel        = $local_dir . "/bizstory/comp_set/agent_notice_excel.php";     // 액셀
	$link_print        = $local_dir . "/bizstory/comp_set/agent_notice_print.php";     // 인쇄
	$link_print_detail = $local_dir . "/bizstory/comp_set/agent_notice_print_sel.php"; // 상세인쇄

	if ($auth_menu['down'] == "Y") // 다운로드버튼
	{
		//$btn_down = '<a href="javascript:void(0);" class="btn_sml" onclick="list_excel()"><span>엑셀</span></a>';
	}
	if ($auth_menu['print'] == "Y") // 인쇄버튼
	{
		//$btn_print     = '<a href="javascript:void(0);" class="btn_sml" onclick="list_print()"><span><em class="print"></em>인쇄</span></a>';
		//$btn_print_sel = '<a href="javascript:void(0);" class="btn_sml" onclick="list_print_detail()"><span><em class="print"></em>상세인쇄</span></a>';
	}
?>
<div class="tablewrapper">
	<div id="tableheader">
		<? include $local_path . "/bizstory/comp_set/part_menu_inc.php"; ?>
	</div>

	<div id="data_view" title="상세보기 / 등록, 수정폼"></div>

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
	var link_list         = '<?=$link_list;?>';
	var link_form         = '<?=$link_form;?>';
	var link_ok           = '<?=$link_ok;?>';
	var link_excel        = '<?=$link_excel;?>';
	var link_print        = '<?=$link_print;?>';
	var link_print_detail = '<?=$link_print_detail;?>';

//------------------------------------ 등록
	function check_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		chk_value = $('#post_content').val();
		chk_title = $('#post_content').attr('title');
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
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						popupform_close();
						list_data();
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

	list_data();
//]]>
</script>