<?
/*
	생성 : 2012.09.27
	위치 : 코드관리 > 에이전트관리 > 상담게시판 > 상담분류
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
	$link_list = $local_dir . "/bizstory/consult/consult_class_list.php"; // 목록
	$link_form = $local_dir . "/bizstory/consult/consult_class_form.php"; // 등록
	$link_ok   = $local_dir . "/bizstory/consult/consult_class_ok.php";   // 저장
	$link_up   = $local_dir . "/bizstory/consult/consult_class_up.php";   // 상위
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
	var link_up   = '<?=$link_up;?>';

//------------------------------------ 등록
	function check_form()
	{
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
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success : function(msg) {
					if (msg.success_chk == "Y")
					{
						popupform_close();
						list_data();
					}
					else check_auth_popup(msg.error_string);
				},
				complete: function(){
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else
		{
			check_auth_popup(chk_total);
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		}
		return false;
	}

//------------------------------------ 단계변경
	function up_menu_change(menu_depth, idx)
	{
		if (menu_depth == "") menu_depth = $('#post_menu_depth').val();

		$.ajax({
			type: 'post', dataType: 'html', url: link_up,
			data : {"menu_depth" : menu_depth, "code_idx" : idx, "code_part" : $('#post_part_idx').val()},
			success : function(msg) {
				$('#up_menu_list').html(msg);
			}
		});
	}

//------------------------------------ 선택된 하위메뉴
	function down_menu_change(menu_depth, sel_depth, idx)
	{
		if (menu_depth == "") menu_depth = document.postform.menu_depth.value;
		$.ajax({
			type: 'post', dataType: 'html', url: link_up,
			data : {"menu_depth" : menu_depth, "sel_depth" : sel_depth, "code_idx" : idx},
			success : function(msg) {
				$('#up_menu_list').html(msg);
			}
		});
	}

	list_data();
//]]>
</script>